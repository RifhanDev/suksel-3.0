#!/usr/bin/env bash
#
# Rollback Batch 3 migrations (reverse order)
#
# Usage (from project root):
#   bash database/scripts/batch_3_rollback.sh
#

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

MIGRATIONS_DIR="database/migrations"

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

echo "=== Rollback batch 3 migrations (reverse order) ==="

for (( idx=${#MIGRATIONS[@]}-1; idx>=0; idx-- )); do
    file="${MIGRATIONS[$idx]}"
    path="${MIGRATIONS_DIR}/${file}"
    echo ">> php artisan migrate:rollback --path=${path} --force"
    php artisan migrate:rollback --path="${path}" --force || true
    echo ""
done

echo "=== Rollback complete ==="
