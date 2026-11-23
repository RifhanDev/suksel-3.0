<?php

namespace App\Console\Commands;

use App\Transaction;
use App\TenderVendor;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AuditPurchaseUnlocks extends Command
{
	protected $signature = 'purchase:audit-unlocks
                            {--date= : Single calendar day (Y-m-d); ignored if --from is set}
                            {--from=2026-11-17 : Range start (Y-m-d)}
                            {--to= : Range end (Y-m-d), default today}
                            {--repair : Re-run generateTenderVendor() for incomplete success carts}
                            {--vendor= : Limit to one vendor_id}';

	protected $description = 'Find successful purchase carts where some tenders were never unlocked (participate=1) so vendor cannot see dokumen/resit';

	public function handle(): int
	{
		[$start, $end] = $this->resolveRange();
		$vendorId = $this->option('vendor');
		$repair = (bool) $this->option('repair');

		$query = Transaction::query()
			->where('type', 'purchase')
			->where('status', 'success')
			->whereBetween('created_at', [$start, $end])
			->orderBy('id');

		if ($vendorId !== null && $vendorId !== '') {
			$query->where('vendor_id', (int) $vendorId);
		}

		$txns = $query->get();
		$this->info(sprintf(
			'Success purchase txns from %s to %s: %d',
			$start->toDateString(),
			$end->toDateString(),
			$txns->count()
		));

		$problems = 0;
		$rows = [];

		foreach ($txns as $txn) {
			$tenderIds = method_exists($txn, 'cachedPurchaseTenderIds')
				? $txn->cachedPurchaseTenderIds()
				: $this->fallbackIds($txn->cached_data);

			if (count($tenderIds) < 1) {
				continue;
			}

			$unlocked = TenderVendor::query()
				->where('vendor_id', $txn->vendor_id)
				->whereIn('tender_id', $tenderIds)
				->where('participate', 1)
				->pluck('tender_id')
				->map(fn ($id) => (int) $id)
				->all();

			$missing = array_values(array_diff($tenderIds, $unlocked));
			if ($missing === []) {
				continue;
			}

			$problems++;
			$vendor = Vendor::find($txn->vendor_id);
			$rows[] = [
				$txn->id,
				$txn->number,
				$txn->vendor_id,
				trim(($vendor->registration ?? '') . ' ' . ($vendor->name ?? '')) ?: '-',
				implode(',', $tenderIds),
				implode(',', $unlocked),
				implode(',', $missing),
				number_format((float) $txn->amount, 2),
				(string) $txn->created_at,
			];

			$this->warn(sprintf(
				'TXN #%s (id=%d) vendor=%d missing unlock for tender(s): %s (cart had %d)',
				$txn->number,
				$txn->id,
				$txn->vendor_id,
				implode(',', $missing),
				count($tenderIds)
			));

			if ($repair) {
				$txn->generateTenderVendor();
				$after = TenderVendor::query()
					->where('vendor_id', $txn->vendor_id)
					->whereIn('tender_id', $tenderIds)
					->where('participate', 1)
					->pluck('tender_id')
					->map(fn ($id) => (int) $id)
					->all();
				$still = array_values(array_diff($tenderIds, $after));
				if ($still === []) {
					$this->info("  repaired txn {$txn->id}");
				} else {
					$this->error('  still missing after repair: ' . implode(',', $still));
				}
			}
		}

		if ($rows === []) {
			$this->info('No incomplete unlocks found in that range.');
			return self::SUCCESS;
		}

		$this->table(
			['txn_id', 'number', 'vendor_id', 'vendor', 'cart_tenders', 'unlocked', 'missing', 'amount', 'created_at'],
			$rows
		);
		$this->line("Problem carts: {$problems}");
		if (! $repair) {
			$this->comment('Re-run with --repair to backfill missing tender_vendors unlocks (dokumen + resit).');
		}

		return self::SUCCESS;
	}

	/**
	 * @return array{0: Carbon, 1: Carbon}
	 */
	protected function resolveRange(): array
	{
		// --date wins for a single-day check; otherwise scan --from..--to.
		if ($this->option('date')) {
			$date = Carbon::parse($this->option('date'))->startOfDay();

			return [$date, $date->copy()->endOfDay()];
		}

		$start = Carbon::parse($this->option('from') ?: '2026-11-17')->startOfDay();
		$end = Carbon::parse($this->option('to') ?: now()->toDateString())->endOfDay();

		return [$start, $end];
	}

	/**
	 * @return list<int>
	 */
	protected function fallbackIds($cached): array
	{
		$raw = @unserialize($cached);
		if ($raw instanceof \Illuminate\Support\Collection) {
			$raw = $raw->all();
		}
		if (! is_array($raw)) {
			return [];
		}

		return array_values(array_unique(array_filter(array_map('intval', $raw))));
	}
}
