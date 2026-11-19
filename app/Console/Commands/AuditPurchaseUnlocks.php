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
                            {--date=2026-11-17 : Calendar day to audit (Y-m-d)}
                            {--repair : Re-run generateTenderVendor() for incomplete success carts}
                            {--vendor= : Limit to one vendor_id}';

	protected $description = 'Find successful multi-tender carts where some tenders were never unlocked (participate=1)';

	public function handle(): int
	{
		$date = Carbon::parse($this->option('date'))->startOfDay();
		$end = $date->copy()->endOfDay();
		$vendorId = $this->option('vendor');
		$repair = (bool) $this->option('repair');

		$query = Transaction::query()
			->where('type', 'purchase')
			->where('status', 'success')
			->whereBetween('created_at', [$date, $end])
			->orderBy('id');

		if ($vendorId !== null && $vendorId !== '') {
			$query->where('vendor_id', (int) $vendorId);
		}

		$txns = $query->get();
		$this->info(sprintf(
			'Success purchase txns on %s: %d',
			$date->toDateString(),
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
				$txn->created_at,
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
			$this->info('No incomplete unlocks found for that day.');
			return self::SUCCESS;
		}

		$this->table(
			['txn_id', 'number', 'vendor_id', 'vendor', 'cart_tenders', 'unlocked', 'missing', 'amount', 'created_at'],
			$rows
		);
		$this->line("Problem carts: {$problems}");
		if (! $repair) {
			$this->comment('Re-run with --repair to backfill missing tender_vendors unlocks.');
		}

		return self::SUCCESS;
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
