#!/usr/bin/env bash
#
# Batch 3 — Suksel 3.0 module migrations (staging / production)
#
# Runs pending migrations in dependency order. When batches 1 & 2 already
# exist in the migrations table, Laravel records these as batch 3.
#
# Usage (from project root):
#   bash database/scripts/batch_3_migrate.sh
#
# Dry-run (show pending only):
#   bash database/scripts/batch_3_migrate.sh --dry-run
#

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

DRY_RUN=false
if [[ "${1:-}" == "--dry-run" ]]; then
    DRY_RUN=true
fi

BATCH_NAME="batch_3"
MIGRATIONS_DIR="database/migrations"

# Ordered list — do not reorder (foreign keys / table dependencies)
MIGRATIONS=(
    "2027_04_27_000001_create_standard_checklist_items_table.php"
    "2027_04_27_000002_create_technical_specification_documents_table.php"
    "2027_04_27_000003_create_technical_specification_items_table.php"
    "2027_04_27_000004_create_technical_specification_details_table.php"
    "2027_04_27_000005_create_technical_specification_score_rules_table.php"
    "2027_04_27_000006_create_technical_checklist_headers_table.php"
    "2027_04_27_000007_create_technical_checklist_items_table.php"
    "2027_04_27_000008_create_technical_checklist_files_table.php"
    "2027_04_28_000001_make_technical_specifications_library_based.php"
    "2027_04_30_075903_create_tender_pengalaman_kerjas_table.php"
    "2027_04_30_075904_create_tender_pengalaman_kerja_dokumens_table.php"
    "2027_04_30_075905_create_tender_kerja_dalam_tangans_table.php"
    "2027_04_30_075906_create_tender_kerja_dalam_tangan_dokumens_table.php"
    "2027_05_02_092858_create_financial_checklist_headers_table.php"
    "2027_05_02_092900_create_financial_checklist_items_table.php"
    "2027_05_02_092901_create_financial_checklist_files_table.php"
    "2027_05_03_002722_create_specification_pricings_table.php"
    "2027_05_03_002723_create_specification_pricing_items_table.php"
    "2027_05_03_002724_create_profil_petenders_table.php"
    "2027_05_03_002725_create_profil_petender_projects_table.php"
    "2027_05_03_002727_create_profil_petender_scoring_items_table.php"
    "2027_05_03_002728_create_penyata_banks_table.php"
    "2027_05_03_002729_create_penyata_bank_bulans_table.php"
    "2027_05_03_002731_create_penyata_bank_scoring_items_table.php"
    "2027_05_03_002732_create_penyata_bank_files_table.php"
    "2027_05_04_000001_add_tender_peringkat_to_tenders_table.php"
    "2027_05_21_000001_create_penyediaan_iklans_table.php"
    "2027_06_01_132511_create_penyediaan_mesyuarat_table.php"
    "2027_06_02_000001_create_spesifikasi_kerja_tables.php"
    "2027_06_03_000001_create_kewangan_kerja_tables.php"
)

echo "=== Suksel 3.0 — ${BATCH_NAME} migrations ==="
echo "Project: $ROOT_DIR"
echo "Total files: ${#MIGRATIONS[@]}"
echo ""

missing=0
for file in "${MIGRATIONS[@]}"; do
    if [[ ! -f "${MIGRATIONS_DIR}/${file}" ]]; then
        echo "MISSING: ${MIGRATIONS_DIR}/${file}"
        missing=$((missing + 1))
    fi
done

if [[ $missing -gt 0 ]]; then
    echo ""
    echo "Abort: ${missing} migration file(s) not found."
    exit 1
fi

if $DRY_RUN; then
    echo "Dry-run — would run these migrations in order:"
    for file in "${MIGRATIONS[@]}"; do
        echo "  - ${file}"
    done
    exit 0
fi

ran=0
skipped=0

for file in "${MIGRATIONS[@]}"; do
    path="${MIGRATIONS_DIR}/${file}"
    echo ">> php artisan migrate --path=${path} --force"
    if php artisan migrate --path="${path}" --force; then
        ran=$((ran + 1))
    else
        echo "WARN: migrate returned non-zero for ${file} (may already be applied)"
        skipped=$((skipped + 1))
    fi
    echo ""
done

echo "=== Done ==="
echo "Processed: ${#MIGRATIONS[@]} file(s)"
echo ""
echo "Verify batch in DB:"
echo "  SELECT batch, migration FROM migrations WHERE batch = (SELECT MAX(batch) FROM migrations) ORDER BY migration;"
