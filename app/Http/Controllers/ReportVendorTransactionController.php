<?php

namespace App\Http\Controllers;

use App\Traits\Helper;
use Illuminate\Http\Request;
use App\Models\OrganizationUnit;
use Illuminate\Support\Facades\DB;

class ReportVendorTransactionController extends Controller
{
    use Helper;

    /** @var array<string, true>|null */
    private static $b2bLookup = null;

    /** @var array<string, true>|null */
    private static $b2cLookup = null;

    public function index()
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:vendor_transaction'])) {
            return $this->_access_denied();
        }
        $agencies = OrganizationUnit::get(['id', 'name']);

        return view('reports.vendor.transaction.index', compact('agencies'));
    }

    public function view(Request $request)
    {
        if (!auth()->check() || !auth()->user()->ability(['Admin'], ['Report:view:vendor_transaction'])) {
            return $this->_access_denied();
        }
        set_time_limit(120);

        $agency_id = (int) $request->agency;
        $type = $request->type;
        $year = $request->year;
        $week = $request->week;
        $monthInput = $request->month;
        $month = $monthInput ? date('F Y', strtotime($monthInput)) : null;

        [$from, $to, $weekLabel] = $this->resolveDateRange($type, $year, $monthInput, $week);
        if ($weekLabel !== null) {
            $week = $weekLabel;
        }

        // Single pass on base tables — avoids view_tender_vendor_report (full txn left-join)
        // and the old O(n×m) Collection::where attachment loop.
        $rowsQuery = DB::table('transactions as t')
            ->leftJoin('vendors as v', 'v.id', '=', 't.vendor_id')
            ->leftJoin('tender_vendors as tv', 'tv.transaction_id', '=', 't.id')
            ->leftJoin('tenders as tn', 'tn.id', '=', 'tv.tender_id')
            ->select([
                't.id as transaction_id',
                't.vendor_id',
                'v.name as vendor_name',
                't.number',
                't.type as transaction_type',
                't.gateway_reference',
                't.created_at',
                't.method',
                't.status',
                't.gateway_message',
                'tv.id as tender_vendor_id',
                'tv.amount',
                'tn.ref_number',
            ])
            ->where('t.organization_unit_id', $agency_id)
            ->orderBy('t.created_at')
            ->orderBy('t.id');

        if ($type === 'week') {
            $rowsQuery->whereRaw('YEARWEEK(t.created_at, 7) = ?', [$from]);
        } else {
            $rowsQuery->where('t.created_at', '>=', $from)
                ->where('t.created_at', '<', $to);
        }

        $summary = [
            'success_b2c' => 0,
            'success_b2b' => 0,
            'success_count' => 0,
            'success_amount' => 0.0,
            'failed_b2c' => 0,
            'failed_b2b' => 0,
            'failed_count' => 0,
            'failed_amount' => 0.0,
            'pending_b2c' => 0,
            'pending_b2b' => 0,
            'pending_count' => 0,
            'pending_amount' => 0.0,
        ];

        $grouped = [];
        $order = [];

        foreach ($rowsQuery->cursor() as $row) {
            $txnId = (int) $row->transaction_id;

            if (! isset($grouped[$txnId])) {
                $createdAt = $row->created_at;
                $grouped[$txnId] = (object) [
                    'transaction_id' => $txnId,
                    'vendor_id' => $row->vendor_id,
                    'name' => $row->vendor_name,
                    'number' => $row->number,
                    'hasil_code' => ($row->transaction_type === 'purchase') ? '73105' : '71399',
                    'gateway_reference' => $row->gateway_reference,
                    'created_at' => $createdAt,
                    'receipt' => $this->receiptNumGenerator(
                        $row->number,
                        date('d-m-Y', strtotime((string) $createdAt))
                    ),
                    'details' => collect(),
                    'total_amount' => 0.0,
                ];
                $order[] = $txnId;
            }

            // No tender_vendors row (e.g. subscription) — keep txn visible with empty details ("Tiada").
            if ($row->tender_vendor_id === null) {
                continue;
            }

            $bank = $this->resolveFpxBuyerBankType($row->gateway_message);
            $amount = (float) ($row->amount ?? 0);
            $status = (string) ($row->status ?? '');

            $grouped[$txnId]->details->push((object) [
                'ref_number' => $row->ref_number,
                'method' => $row->method,
                'fpx_buyerBankId' => $bank,
                'status' => $status,
                'amount' => $amount,
            ]);
            $grouped[$txnId]->total_amount += $amount;
            $this->accumulateSummary($summary, $status, $bank, $amount);
        }

        $responses = collect();
        foreach ($order as $txnId) {
            $responses->push($grouped[$txnId]);
        }

        $agencyName = OrganizationUnit::select('name')->where('id', $agency_id)->value('name');

        return view('reports.vendor.transaction.view', compact(
            'responses',
            'agencyName',
            'type',
            'year',
            'month',
            'week',
            'summary'
        ));
    }

    /**
     * @return array{0: string|int, 1: string|null, 2: string|null}
     */
    private function resolveDateRange(?string $type, $year, $monthInput, $week): array
    {
        if ($type === 'month') {
            $ts = strtotime((string) $monthInput . '-01');
            $from = date('Y-m-01 00:00:00', $ts);
            $to = date('Y-m-01 00:00:00', strtotime('+1 month', $ts));

            return [$from, $to, null];
        }

        if ($type === 'week') {
            $newWeek = date('YW', strtotime((string) $week));
            $weekLabel = date('W, Y', strtotime((string) $week));

            return [$newWeek, null, $weekLabel];
        }

        $y = (int) $year;
        $from = sprintf('%04d-01-01 00:00:00', $y);
        $to = sprintf('%04d-01-01 00:00:00', $y + 1);

        return [$from, $to, null];
    }

    private function resolveFpxBuyerBankType(?string $gatewayMessage): string
    {
        if ($gatewayMessage === null || $gatewayMessage === '') {
            return 'null';
        }

        $pos = strpos($gatewayMessage, 'fpx_buyerBankId:');
        if ($pos === false) {
            return 'null';
        }

        $start = $pos + strlen('fpx_buyerBankId:');
        $rest = substr($gatewayMessage, $start);
        $pipe = strpos($rest, '|');
        $buyerBankId = trim($pipe === false ? $rest : substr($rest, 0, $pipe));

        if ($buyerBankId === '') {
            return 'null';
        }

        return $this->fpxBuyerBankIdDictionary($buyerBankId);
    }

    private function accumulateSummary(array &$summary, string $status, string $bank, float $amount): void
    {
        if ($status === 'success') {
            $summary['success_count']++;
            $summary['success_amount'] += $amount;
            if ($bank === 'B2C') {
                $summary['success_b2c']++;
            } elseif ($bank === 'B2B') {
                $summary['success_b2b']++;
            }
        } elseif ($status === 'failed') {
            $summary['failed_count']++;
            $summary['failed_amount'] += $amount;
            if ($bank === 'B2C') {
                $summary['failed_b2c']++;
            } elseif ($bank === 'B2B') {
                $summary['failed_b2b']++;
            }
        } elseif ($status === 'pending') {
            $summary['pending_count']++;
            $summary['pending_amount'] += $amount;
            if ($bank === 'B2C') {
                $summary['pending_b2c']++;
            } elseif ($bank === 'B2B') {
                $summary['pending_b2b']++;
            }
        }
    }

    public function fpxBuyerBankIdDictionary($fpx_buyerBankId)
    {
        if (self::$b2bLookup === null) {
            self::$b2bLookup = array_fill_keys([
                'ABB0235', 'ABMB0213', 'AGRO02', 'AMBB0208', 'BIMB0340', 'BMMB0342',
                'BNP003', 'BCBB0235', 'CIT0218', 'DBB0199', 'HLB0224', 'HSBC0223',
                'BKRM0602', 'KFH0346', 'MBB0228', 'OCBC0229', 'PBB0233', 'PBB0234',
                'RHB0218', 'SCB0215', 'UOB0228',
            ], true);

            self::$b2cLookup = array_fill_keys([
                'ABB0233', 'ABMB0212', 'AGRO01', 'AMBB0209', 'BIMB0340', 'BMMB0341',
                'BKRM0602', 'BOCM01', 'BSN0601', 'BCBB0235', 'HLB0224', 'HSBC0223',
                'KFH0346', 'MBB0228', 'MB2U0227', 'OCBC0229', 'PBB0233', 'RHB0218',
                'SCB0216', 'UOB0226',
            ], true);
        }

        if (isset(self::$b2bLookup[$fpx_buyerBankId])) {
            return 'B2B';
        }
        if (isset(self::$b2cLookup[$fpx_buyerBankId])) {
            return 'B2C';
        }

        return $fpx_buyerBankId;
    }
}
