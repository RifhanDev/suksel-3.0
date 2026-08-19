-- =============================================================================
-- Batch 3 — Register Laravel migrations (tables already exist in DB)
-- =============================================================================
--
-- Problem:
--   Tables/columns were created manually or partially migrated, but rows are
--   missing from the `migrations` table. Running `php artisan migrate` then
--   fails with "Table already exists" / "Duplicate column".
--
-- Solution:
--   Mark these migrations as already applied (batch = 3) so Artisan skips them.
--
-- Run on staging DB (phpMyAdmin / mysql CLI), then:
--   php artisan migrate
--
-- Safe to re-run: only inserts rows that do not already exist.
-- =============================================================================

SET @batch := 3;

-- -----------------------------------------------------------------------------
-- Technical specification & checklist
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000001_create_standard_checklist_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000001_create_standard_checklist_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000002_create_technical_specification_documents_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000002_create_technical_specification_documents_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000003_create_technical_specification_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000003_create_technical_specification_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000004_create_technical_specification_details_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000004_create_technical_specification_details_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000005_create_technical_specification_score_rules_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000005_create_technical_specification_score_rules_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000006_create_technical_checklist_headers_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000006_create_technical_checklist_headers_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000007_create_technical_checklist_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000007_create_technical_checklist_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_27_000008_create_technical_checklist_files_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_27_000008_create_technical_checklist_files_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_28_000001_make_technical_specifications_library_based', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_28_000001_make_technical_specifications_library_based');

-- -----------------------------------------------------------------------------
-- Pengalaman kerja & kerja dalam tangan
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_30_075903_create_tender_pengalaman_kerjas_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_30_075903_create_tender_pengalaman_kerjas_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_30_075904_create_tender_pengalaman_kerja_dokumens_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_30_075904_create_tender_pengalaman_kerja_dokumens_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_30_075905_create_tender_kerja_dalam_tangans_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_30_075905_create_tender_kerja_dalam_tangans_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_04_30_075906_create_tender_kerja_dalam_tangan_dokumens_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_04_30_075906_create_tender_kerja_dalam_tangan_dokumens_table');

-- -----------------------------------------------------------------------------
-- Financial checklist
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_02_092858_create_financial_checklist_headers_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_02_092858_create_financial_checklist_headers_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_02_092900_create_financial_checklist_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_02_092900_create_financial_checklist_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_02_092901_create_financial_checklist_files_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_02_092901_create_financial_checklist_files_table');

-- -----------------------------------------------------------------------------
-- Specification pricing, profil petender, penyata bank
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002722_create_specification_pricings_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002722_create_specification_pricings_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002723_create_specification_pricing_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002723_create_specification_pricing_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002724_create_profil_petenders_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002724_create_profil_petenders_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002725_create_profil_petender_projects_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002725_create_profil_petender_projects_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002727_create_profil_petender_scoring_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002727_create_profil_petender_scoring_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002728_create_penyata_banks_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002728_create_penyata_banks_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002729_create_penyata_bank_bulans_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002729_create_penyata_bank_bulans_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002731_create_penyata_bank_scoring_items_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002731_create_penyata_bank_scoring_items_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_03_002732_create_penyata_bank_files_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_03_002732_create_penyata_bank_files_table');

-- -----------------------------------------------------------------------------
-- Tender peringkat, penyediaan iklan, mesyuarat, spesifikasi & kewangan kerja
-- -----------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_04_000001_add_tender_peringkat_to_tenders_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_04_000001_add_tender_peringkat_to_tenders_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_05_21_000001_create_penyediaan_iklans_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_05_21_000001_create_penyediaan_iklans_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_06_01_132511_create_penyediaan_mesyuarat_table', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_06_01_132511_create_penyediaan_mesyuarat_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_06_02_000001_create_spesifikasi_kerja_tables', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_06_02_000001_create_spesifikasi_kerja_tables');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2027_06_03_000001_create_kewangan_kerja_tables', @batch
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2027_06_03_000001_create_kewangan_kerja_tables');

-- -----------------------------------------------------------------------------
-- Verify
-- -----------------------------------------------------------------------------
SELECT migration, batch
FROM migrations
WHERE batch = 3
ORDER BY migration;
