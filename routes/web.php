<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\PetenderPerformanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\OrganizationUnitsController;
use App\Http\Controllers\HelpsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\TendersController;
use App\Http\Controllers\LantikanTerusController;
use App\Http\Controllers\FpxController;
use App\Http\Controllers\EbpgController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\VendorBlacklistsController;
use App\Http\Controllers\CodeRequestsController;
use App\Http\Controllers\SmtpMailController; // Note: SmtpMailController (not SmtpMailsController)
use App\Http\Controllers\StatesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ManualsController;
use App\Http\Controllers\CircularController;
use App\Http\Controllers\VersionHistoryController;
use App\Http\Controllers\RefKategoriJenisPerolehanController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\RejectTemplateController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\MailManagerController;
use App\Http\Controllers\MailQueueController;
use App\Http\Controllers\FaqCategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FaqLogController;
use App\Http\Controllers\CustomerQuestionController;
use App\Http\Controllers\ShareholdersController;
use App\Http\Controllers\DirectorsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\AwardsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\RemarksController;
use App\Http\Controllers\OrganizationTypesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\TwoFactorAdminController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\CertificationCodesController;
use App\Http\Controllers\GatewaysController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\HelpCategoriesController;
use App\Http\Controllers\PembelianTerusController;
use App\Http\Controllers\SuratNiatController;
use App\Http\Controllers\ReportRevenueController;
use App\Http\Controllers\ReportAgencyActiveController;
use App\Http\Controllers\ReportAgencyAllController;
use App\Http\Controllers\ReportAgencyTypeController;
use App\Http\Controllers\ReportAgencyDailyController;
use App\Http\Controllers\ReportAgencyTransactionController;
use App\Http\Controllers\ReportGatewayDailyController;
use App\Http\Controllers\ReportVendorStatusController;
use App\Http\Controllers\ReportVendorSummaryController;
use App\Http\Controllers\ReportCodeRequestController;
use App\Http\Controllers\ReportVendorRegistrationController;
use App\Http\Controllers\ReportVendorRegistrationListController;
use App\Http\Controllers\ReportStaffActivityController;
use App\Http\Controllers\ReportCodeDistrictController;
use App\Http\Controllers\ReportVendorTransactionController;
use App\Http\Controllers\ReportTransactionByHasilController;
use App\Http\Controllers\ReportVendorCodeController;
use App\Http\Controllers\ReportUserAgencyController;
use App\Http\Controllers\ReportUserActiveController;
use App\Http\Controllers\ReportVendorDistrictController;
use App\Http\Controllers\ReportUserActivityController;
use App\Http\Controllers\ReportUserLoginController;
use App\Http\Controllers\DummyController;
use App\Http\Controllers\JawatankuasaController;
use App\Http\Controllers\TechnicalChecklistController;
use App\Http\Controllers\StandardChecklistItemController;
use App\Http\Controllers\TechnicalSpecificationController;
use App\Http\Controllers\KerjaDalamTanganController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\FinancialChecklistController;
use App\Http\Controllers\SpecificationPricingController;
use App\Http\Controllers\ProfilPetenderController;
use App\Http\Controllers\PenyataBankController;
use App\Http\Controllers\LembaranImbanganController;
use App\Http\Controllers\BonSahamController;
use App\Http\Controllers\TenderPrestasiKerjaController;
use App\Http\Controllers\CutOffController;
use App\Http\Controllers\JawatankuasaPembukaController;
use App\Http\Controllers\VendorSubmissionsDemoController;
use App\Http\Controllers\PenyediaanSuratNiatController;
use App\Http\Controllers\PenyediaanSuratSstController;
use App\Http\Controllers\JawatankuasaPerolehanController;
use App\Http\Controllers\PenyediaanMesyuaratController;
use App\Http\Controllers\PenilaianKewanganController;
use App\Http\Controllers\PenilaianKewanganKerjaController;
use App\Http\Controllers\PenilaianTeknikalController;
use App\Http\Controllers\PerakuanJabatanController;
use App\Http\Controllers\EbiddingController;
use App\Http\Controllers\SpesifikasiTenderKerjaController;
use App\Http\Controllers\SenaraiKewanganKerjaController;
use App\Http\Controllers\TenderVisitRepresentativeController;
use App\Http\Controllers\LawatanTapakUrusetiaController;

// Basic routes to get the application running
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('prices', [HomeController::class, 'prices']);
Route::get('results', [HomeController::class, 'results']);
Route::get('privacy', [HomeController::class, 'privacy']);
/////////////////by edry///////////////////////////////////
// Place 3.0 Modules Routes Temporarily Here
Route::view('/jawatankuasa-spesifikasi/senarai-teknikal', 'newModule.jawatankuasaSpesifikasi.senarai_teknikal')->name('jawatankuasaSpesifikasi.teknikal');
Route::view('/jawatankuasa-spesifikasi/senarai-kewangan', 'newModule.jawatankuasaSpesifikasi.senarai_kewangan')->name('jawatankuasaSpesifikasi.kewangan');

Route::get('/cut-off', [CutOffController::class, 'index'])->middleware(['auth'])->name('cutOff.index');
Route::get('/cut-off/{uuid}', [CutOffController::class, 'show'])->middleware(['auth'])->name('cutOff.show');
Route::post('/cut-off/simpan', [CutOffController::class, 'simpan'])->middleware(['auth'])->name('cutOff.simpan');
Route::post('/cut-off/hantar', [CutOffController::class, 'hantar'])->middleware(['auth'])->name('cutOff.hantar');

Route::get('/perakuan-jabatan', [PerakuanJabatanController::class, 'index'])->middleware(['auth'])->name('perakuanjabatan.index');
// Route::get('/perakuan-jabatan/{tender_no}', [PerakuanJabatanController::class, 'show'])->middleware(['auth'])->name('perakuanjabatan.show');
Route::view('/perakuan-jabatan/form', 'newModule.perakuanJabatan.form')->name('perakuanJabatan.form');
Route::view('/perakuan-jabatan/kertas-taklimat', 'newModule.perakuanJabatan.kertas_taklimat')->name('perakuanJabatan.kertasTaklimat');
Route::view('/perakuan-jabatan/pengesyoran-pembekal', 'newModule.perakuanJabatan.pengesyoran_pembekal')->name('perakuanJabatan.pengesyoranPembekal');

Route::get('/eBidding/index', [EbiddingController::class, 'index'])->middleware(['auth'])->name('eBidding.index');
Route::view('/keputusan-mesyuarat', 'newModule.eBidding.keptusan_mesyuarat')->name('keputusanMesyuarat');

Route::get('/pembelian-terus/maklumat-projek/{id}', [PembelianTerusController::class, 'detailProject'])
	->name('pembelianTerus.detailProject');

Route::prefix('pembelian-terus')->controller(PembelianTerusController::class)->middleware(['auth'])->group(function () {
	Route::get('/cipta-projek', 'index')->name('pembelianTerus.createProject');
	Route::get('/cipta-projek/baru', 'create')->name('pembelianTerus.create');
	Route::post('/cipta-projek', 'store')->name('pembelianTerus.store');
	Route::get('/cipta-projek/{id}/kemaskini', 'edit')->name('pembelianTerus.edit');
	Route::put('/cipta-projek/{id}', 'update')->name('pembelianTerus.update');
	Route::get('/sebut-harga', 'quoteProject')->name('pembelianTerus.quoteProject');
	Route::post('/maklumat-projek/{id}/tawaran', 'submitOffer')->name('pembelianTerus.submitOffer');
	Route::get('/cut-off-projek', 'cutOffProject')->name('pembelianTerus.cutOffProject');
	Route::get('/cut-off-details/{id}', 'cutOffDetails')->name('pembelianTerus.cutOffDetails');
	Route::post('/cut-off-details/{id}', 'storeCutoff')->name('pembelianTerus.storeCutoff');
	Route::get('/pemilihan-syarikat', 'pemilihanSyarikat')->name('pembelianTerus.pemilihanSyarikat');
	Route::get('/pemilihan-syarikat-details/{id}', 'pemilihanSyarikatDetails')->name('pembelianTerus.pemilihanSyarikatDetails');
	Route::post('/pemilihan-syarikat-details/{id}', 'storePemilihan')->name('pembelianTerus.storePemilihan');
	Route::get('/keputusan-syarikat', 'keputusanSyarikat')->name('pembelianTerus.keputusanSyarikat');
	Route::get('/keputusan-syarikat-details/{id}', 'keputusanSyarikatDetails')->name('pembelianTerus.keputusanSyarikatDetails');
	Route::post('/keputusan-syarikat-details/{id}', 'storeKeputusan')->name('pembelianTerus.storeKeputusan');
	Route::get('/surat-setuju-terima/{id}', 'downloadSuratSetujuTerima')->name('pembelianTerus.downloadSuratSetujuTerima');
});
/////////////////////////////////////////////////////////////

// Public resources
Route::resource('comments', CommentsController::class);
Route::get('/agencies/{id}/prices', [OrganizationUnitsController::class, 'prices']);
Route::get('/agencies/{id}/results', [OrganizationUnitsController::class, 'results']);
Route::get('/agencies/{id}/news', [OrganizationUnitsController::class, 'news']);
Route::get('/agencies/{id}/report/{tender}', [OrganizationUnitsController::class, 'report']);
Route::resource('agencies', OrganizationUnitsController::class);
Route::get('helps/search', [HelpsController::class, 'search']);
Route::resource('helps', HelpsController::class);
Route::resource('manuals', ManualsController::class);

// Company search
Route::get('company_search', [HomeController::class, 'companySearch']);
Route::post('company_search', [HomeController::class, 'doCompanySearch']);

// Email change
Route::get('change_email', [HomeController::class, 'changeEmail']);
Route::post('change_email', [HomeController::class, 'doChangeEmail']);
Route::get('change_email/{token}', [HomeController::class, 'verifyChangeEmail'])->name('verify_change_email');

// Account review (public - accessible from email link)
Route::get('users/{user}/account-review', [UsersController::class, 'accountReview'])->name('users.account-review');

// Registration routes
Route::get('register', [RegistrationController::class, 'register'])->name('registration');
Route::post('register', [RegistrationController::class, 'storeRegister']);
Route::get('register-user', [RegistrationController::class, 'registerUser'])->name('registration-user');
Route::post('register-user', [RegistrationController::class, 'storeRegisterUser']);

// Auth routes
Route::get('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'doLogin']);
Route::get('auth/2fa/verify', [AuthController::class, 'show2FA'])->name('2fa.verify');
Route::post('auth/2fa/verify', [AuthController::class, 'verify2FA']);
Route::get('auth/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('auth/confirm/{registration_code}', [AuthController::class, 'confirm']);
Route::get('auth/forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('auth/forgot_password', [AuthController::class, 'doForgotPassword']);
Route::get('auth/reset/{token}', [AuthController::class, 'resetPassword']);
Route::post('auth/reset', [AuthController::class, 'doResetPassword']);

// Tenders
Route::get('tenders/select', [TendersController::class, 'select']);
// Route::resource('tenders', TendersController::class);
Route::get('tenders', [TendersController::class, 'index'])->name('tenders.index');
Route::get('tenders/{id}', [TendersController::class, 'show'])->name('tenders.show');
Route::get('tenders/{id}/prices', [TendersController::class, 'prices'])->name('tenders.prices');
Route::get('tenders/{tender_id}/files/{id}', [TendersController::class, 'file'])->name('tenders.files');
Route::get('tenders/{id}/vendors', [TendersController::class, 'vendors'])->name('tenders.vendors');
Route::post('tenders/{id}/exception', [TendersController::class, 'exception'])->name('tenders.exception');

Route::middleware(['auth'])->group(function () {
	Route::get('tenders/{tender}/vendor-submission/readiness', [\App\Http\Controllers\VendorTenderSubmissionController::class, 'readiness'])->name('tenders.vendorSubmission.readiness');
	Route::post('tenders/{tender}/vendor-submission', [\App\Http\Controllers\VendorTenderSubmissionController::class, 'submit'])->name('tenders.vendorSubmission.submit');
	Route::get('tenders/{tender}/dokumen/{itemUuid}/specification', [\App\Http\Controllers\VendorTenderDokumenController::class, 'specificationForm'])->name('tenderDokumen.specificationForm');
	Route::get('tenders/{tender}/dokumen/pengalaman-kerja-review', [\App\Http\Controllers\PengalamanKerjaController::class, 'review'])->name('tenderDokumen.pengalamanKerjaReview');
	Route::get('tenders/{tender}/dokumen/kerja-dalam-tangan-review', [\App\Http\Controllers\KerjaDalamTanganController::class, 'review'])->name('tenderDokumen.kerjaDalamTanganReview');
	Route::get('tenders/{tender}/spesifikasi-kerja-files/{fileUuid}/download', [\App\Http\Controllers\VendorTenderDokumenController::class, 'downloadSpesifikasiKerjaFile'])->name('tenderDokumen.spesifikasiKerjaFile');
	Route::post('tenders/{tender}/dokumen/{itemUuid}/upload', [\App\Http\Controllers\VendorTenderDokumenController::class, 'upload'])->name('tenderDokumen.upload');
	Route::get('tenders/{tender}/checklist-files/{section}/{fileUuid}/download', [\App\Http\Controllers\TenderChecklistFileController::class, 'download'])->name('tenderChecklist.download');
	Route::get('tenders/{tender}/stos-form-files/{type}/{fileUuid}/download', [\App\Http\Controllers\StosFormFileController::class, 'download'])->name('stosFormFile.download');
	Route::get('tenders/dokumen-files/{fileUuid}/download', [\App\Http\Controllers\VendorTenderDokumenController::class, 'download'])->name('tenderDokumen.download');
	Route::delete('tenders/dokumen-files/{fileUuid}', [\App\Http\Controllers\VendorTenderDokumenController::class, 'deleteFile'])->name('tenderDokumen.deleteFile');
	Route::post('tenders/{tender}/dokumen/{itemUuid}/key-in', [\App\Http\Controllers\VendorTenderDokumenController::class, 'saveKeyIn'])->name('tenderDokumen.saveKeyIn');
	Route::post('tenders/{tender}/dokumen/{itemUuid}/specification', [\App\Http\Controllers\VendorTenderDokumenController::class, 'saveSpecification'])->name('tenderDokumen.saveSpecification');
});

// Petender Performance
Route::prefix('petenders')->controller(PetenderPerformanceController::class)->group(function () {
	Route::post('petender-performance/{tender}/{vendor}', 'store')->name('store.PetenderPerformance');
	Route::get('{tender}', 'vendorPetender')->name('index.TenderVendor');
});

// News
Route::resource('news', NewsController::class);

// Contact
Route::get('contact', [HomeController::class, 'contact']);

// Payment — respond/listen are landing points PayNet (or the bank) posts back
// to directly. They must stay outside 'auth': the caller has no session/login
// of ours, and even the user's own browser can arrive here with no valid
// session cookie after a cross-site POST from the bank's domain (SameSite
// cookie policy). respond() already handles both a present and an absent
// session (see its $returning fallback lookup by transaction number).
Route::post('payment/fpx/listen', [FpxController::class, 'listen'])->name('fpx.listen');
Route::post('payment/fpx/respond', [FpxController::class, 'respond'])->name('fpx.respond');
Route::post('payment/ebpg/respond', [EbpgController::class, 'respond'])->name('ebpg.respond');

// Transactions
Route::post('transactions/{id}/ebpg_requery', [TransactionsController::class, 'ebpg_requery'])->name('transactions.ebpg_requery');

// Circular
Route::resource('circulars', CircularController::class)->except(['show', 'destroy']);
Route::get('circulars/{id}/publish', [CircularController::class, 'publish'])->name('circulars.publish');
Route::get('circulars/list', [CircularController::class, 'public'])->name('circulars.public');
Route::get('circulars/sort', [CircularController::class, 'sortPosition'])->name('circulars.position');
Route::post('circulars/sort', [CircularController::class, 'updatePosition'])->name('circulars.update.position');

// Complaint/Aduan
Route::get('aduan', [ComplaintController::class, 'create'])->name('aduan.create');
Route::post('aduan', [ComplaintController::class, 'store'])->name('aduan.store');
Route::get('aduan/list', [ComplaintController::class, 'index'])->name('aduan.index');
Route::get('aduan/{id}', [ComplaintController::class, 'show'])->name('aduan.show');
Route::get('aduan/{id}/{status}', [ComplaintController::class, 'updateStatus'])->name('aduan.update.status');

// BotMan
Route::match(['get', 'post'], 'botman', [BotManController::class, 'handle'])->name('botman');
Route::get('chat-widget/{chat_id}', [BotManController::class, 'chatWidget'])->withoutMiddleware(['auth'])->name('chat_widget');


// Place 3.0 Modules Routes Temporarily Here
Route::view('/semak-tender', 'newModule.semak_tender')->name('semakPenciptaanTender');
Route::get('/penyediaan-iklan', [\App\Http\Controllers\PenyediaanIklanController::class, 'index'])->name('penyediaanIklan.index');
Route::get('/penyediaan-iklan/{tender}', [\App\Http\Controllers\PenyediaanIklanController::class, 'show'])->name('penyediaanIklan.show');
Route::post('/penyediaan-iklan/{tender}/simpan', [\App\Http\Controllers\PenyediaanIklanController::class, 'simpan'])->name('penyediaanIklan.simpan');
Route::post('/penyediaan-iklan/{tender}/hantar', [\App\Http\Controllers\PenyediaanIklanController::class, 'hantar'])->name('penyediaanIklan.hantar');

Route::get('/pelantikan-jawatankuasa', [JawatankuasaController::class, 'create'])->middleware(['auth'])->name('pelantikanJawatankuasa');
Route::get('/pelantikan-jawatankuasa-1-peringkat', [JawatankuasaController::class, 'create1Peringkat'])->middleware(['auth'])->name('pelantikanJawatankuasaSatuPeringkat');
Route::get('/pelantikan-jawatankuasa/laporan', [JawatankuasaController::class, 'laporan'])->middleware(['auth'])->name('jawatankuasa.laporan');
Route::post('/pelantikan-jawatankuasa/hantar-pemakluman', [JawatankuasaController::class, 'hantarPemakluman'])->middleware(['auth'])->name('jawatankuasa.hantarPemakluman');
Route::view('/senarai-semak', 'newModule.jawatankuasaSpesifikasi.index')->name('senaraiSemak');

Route::get('/lantikan-terus/cipta-projek', [LantikanTerusController::class, 'index'])->middleware(['auth'])->name('lantikan.index');
Route::get('/lantikan-terus/cipta-projek/baru', [LantikanTerusController::class, 'create'])->middleware(['auth'])->name('lantikan.create');
Route::post('/lantikan-terus/cipta-projek', [LantikanTerusController::class, 'store'])->middleware(['auth'])->name('lantikan.store');
Route::get('/lantikan-terus/cipta-projek/{id}/kemaskini', [LantikanTerusController::class, 'edit'])->middleware(['auth'])->name('lantikan.edit');
Route::put('/lantikan-terus/cipta-projek/{id}', [LantikanTerusController::class, 'update'])->middleware(['auth'])->name('lantikan.update');
Route::get('/sebut-harga-terus', [LantikanTerusController::class, 'sebutHargaIndex'])->name('sebutHargaTerus.index');
Route::get('/sebut-harga-terus/{id}/lihat', [LantikanTerusController::class, 'sebutHargaShow'])->name('sebutHargaTerus.show');
Route::post('/sebut-harga-terus/{id}/tawaran', [LantikanTerusController::class, 'submitOffer'])->middleware(['auth'])->name('sebutHargaTerus.submitOffer');
Route::get('/cut-off-terus', [LantikanTerusController::class, 'cutOffIndex'])->middleware(['auth'])->name('cutOffTerus.index');
Route::get('/cut-off-terus/{id}/lihat', [LantikanTerusController::class, 'cutOffShow'])->middleware(['auth'])->name('cutOffTerus.show');
Route::post('/cut-off-terus/{id}', [LantikanTerusController::class, 'storeCutoff'])->middleware(['auth'])->name('cutOffTerus.store');
Route::get('/pemilihan-syarikat-terus', [LantikanTerusController::class, 'pemilihanIndex'])->middleware(['auth'])->name('pemilihanTerus.index');
Route::get('/pemilihan-syarikat-terus/{id}/lihat', [LantikanTerusController::class, 'pemilihanShow'])->middleware(['auth'])->name('pemilihanTerus.show');
Route::post('/pemilihan-syarikat-terus/{id}', [LantikanTerusController::class, 'storePemilihan'])->middleware(['auth'])->name('pemilihanTerus.store');
Route::get('/keputusan-syarikat-terus', [LantikanTerusController::class, 'keputusanIndex'])->middleware(['auth'])->name('keputusanTerus.index');
Route::get('/keputusan-syarikat-terus/{id}/lihat', [LantikanTerusController::class, 'keputusanShow'])->middleware(['auth'])->name('keputusanTerus.show');
Route::post('/keputusan-syarikat-terus/{id}', [LantikanTerusController::class, 'storeKeputusan'])->middleware(['auth'])->name('keputusanTerus.store');
Route::get('/lantikan-terus/surat-setuju-terima/{id}', [LantikanTerusController::class, 'downloadSuratSetujuTerima'])->middleware(['auth'])->name('lantikan.downloadSuratSetujuTerima');

// senaraiKewanganKerja routes are auth-protected — defined inside middleware group below
// penyediaanSpekTender routes are auth-protected — defined inside middleware group below
// Lembaran Imbangan routes are registered inside auth group below
// Route::view('/bon-atau-saham/{tenderUuid?}', 'newModule.jawatankuasaSpesifikasi.form_bon_saham')->name('bonAtauSaham');
// Route::post('/bon-atau-saham/submit', [JawatankuasaController::class, 'storeBonSaham'])->middleware(['auth'])->name('jawatankuasa.hantarBonSaham');
// Route::view('/prestasi-kerja-semasa-petender/{tenderUuid?}', 'newModule.jawatankuasaSpesifikasi.form_prestasi_kerja_semasa_petender')->name('prestasiKerjaSemasa');
// Route::post('/prestasi-kerja-semasa-petender/submit', [JawatankuasaController::class, 'storePrestasiKerjaSemasa'])->middleware(['auth'])->name('jawatankuasa.hantarPrestasiKerjaSemasa');
Route::view('/templat-spesifikasi', 'newModule.jawatankuasaSpesifikasi.form_cipta_spesifikasi')->name('spesifikasiForm.legacy');
Route::view('/pengalaman-kerja', 'newModule.jawatankuasaSpesifikasi.form_pengalaman_kerja')->name('pgmnKerjaForm');
Route::post('/pengalaman-kerja/simpan', [JawatankuasaController::class, 'simpanPengalamanKerja'])->name('jawatankuasa.simpanPengalamanKerja');
Route::view('/kerja-dalam-tangan', 'newModule.jawatankuasaSpesifikasi.form_kerja_dalam_tangan')->name('kjDlmTanganForm');
Route::post('/kerja-dalam-tangan/simpan', [JawatankuasaController::class, 'simpanKerjaDalamTangan'])->name('jawatankuasa.simpanKerjaDalamTangan');
Route::get('/spesifikasi-kewangan', [JawatankuasaController::class, 'spesifikasiKewanganBekalan'])->name('spesifikasiFormKewanganBekalan.legacy');
Route::view('/profil-petender', 'newModule.jawatankuasaSpesifikasi.form_profil_petender')->name('prflPetender');
Route::post('/profile-petender/submit', [JawatankuasaController::class, 'storeProfilPetender'])->middleware(['auth'])->name('jawatankuasa.hantarProfilPetender');
Route::view('/penyata-bank', 'newModule.jawatankuasaSpesifikasi.form_penyata_bank')->name('pnytBank');
Route::post('/penyata-bank/submit', [JawatankuasaController::class, 'storePenyataKewangan'])->middleware(['auth'])->name('jawatankuasa.hantarPenyataKewangan');
Route::middleware(['auth'])->group(function () {
	Route::get('/index-perincian', [PenyediaanMesyuaratController::class, 'index'])->name('perincianMesyuarat');
	Route::get('/perincian-page', [PenyediaanMesyuaratController::class, 'show'])->name('perincianPage');
	Route::post('/penyediaan-mesyuarat/simpan', [PenyediaanMesyuaratController::class, 'simpan'])->name('penyediaanMesyuarat.simpan');
	Route::post('/penyediaan-mesyuarat/hantar', [PenyediaanMesyuaratController::class, 'hantar'])->name('penyediaanMesyuarat.hantar');

	Route::get('/index-jawatankuasa', [PenyediaanMesyuaratController::class, 'indexKehadiran'])->name('jawatankuasaMesyuarat');
	Route::get('/jawatankuasa-page', [PenyediaanMesyuaratController::class, 'showKehadiran'])->name('jawatankuasaPage');
	Route::post('/kehadiran-mesyuarat/simpan', [PenyediaanMesyuaratController::class, 'simpanKehadiran'])->name('kehadiranMesyuarat.simpan');
});
Route::middleware('auth')->group(function () {
	Route::get('/lawatan-tapak-urusetia', [LawatanTapakUrusetiaController::class, 'index'])->name('lawatanTapakUrusetia');
	Route::get('/pengesahan-lawatan-tapak-urusetia/{tender}', [LawatanTapakUrusetiaController::class, 'pengesahan'])->name('pengesahanLawatanTapak');
	Route::post('/pengesahan-lawatan-tapak-urusetia/{tender}', [LawatanTapakUrusetiaController::class, 'updatePengesahan'])->name('pengesahanLawatanTapak.update');
	Route::get('/kelulusan-lawatan-tapak-urusetia/{tender}', [LawatanTapakUrusetiaController::class, 'kelulusan'])->name('kelulusanLawatanTapak');
});
Route::middleware(['auth'])->group(function () {
	// Surat Niat: senarai sahaja di sini. Halaman butiran + semua tindakan surat
	// dikendalikan SuratNiatController (kumpulan di bawah blok ini) — dua-dua
	// branch membina modul ini secara berasingan, dan versi itu yang lengkap.
	Route::get('/index-penyediaan-surat-niat', [PenyediaanSuratNiatController::class, 'index'])->name('indexPenyediaanSuratNiat');
	Route::get('/index-penyediaan-sst', [PenyediaanSuratSstController::class, 'index'])->name('indexPenyediaanSST');
	Route::get('/penyediaan-sst', [PenyediaanSuratSstController::class, 'show'])->name('penyediaanSST');
	Route::post('/penyediaan-sst/hantar', [PenyediaanSuratSstController::class, 'hantar'])->name('penyediaanSST.hantar');
	Route::post('/penyediaan-sst/simpan', [PenyediaanSuratSstController::class, 'simpan'])->name('penyediaanSST.simpan');
	Route::post('/penyediaan-sst/jana', [PenyediaanSuratSstController::class, 'jana'])->name('penyediaanSST.jana');
	Route::get('/penyediaan-sst/{tender}/pembekal', [PenyediaanSuratSstController::class, 'pembekal'])->name('penyediaanSST.pembekal');
	Route::get('/penyediaan-sst/{tender}/surat', [PenyediaanSuratSstController::class, 'senaraiSurat'])->name('penyediaanSST.senaraiSurat');
	Route::get('/penyediaan-sst/{tender}/vendor/{vendorId}', [PenyediaanSuratSstController::class, 'sst'])->name('penyediaanSST.sst');
	Route::get('/penyediaan-sst/{tender}/vendor/{vendorId}/surat-setuju-terima', [PenyediaanSuratSstController::class, 'suratSetujuTerima'])->name('penyediaanSST.suratSetujuTerima');
	Route::get('/penyediaan-sst/{tender}/vendor/{vendorId}/surat-akuan-pembida-berjaya', [PenyediaanSuratSstController::class, 'suratAkuanPembidaBerjaya'])->name('penyediaanSST.suratAkuanPembidaBerjaya');
	Route::get('/penyediaan-sst/{tender}/vendor/{vendorId}/surat-akuan-sumpah-syarikat', [PenyediaanSuratSstController::class, 'suratAkuanSumpahSyarikat'])->name('penyediaanSST.suratAkuanSumpahSyarikat');
	Route::post('/penyediaan-sst/{tender}/dokumen', [PenyediaanSuratSstController::class, 'muatNaikDokumen'])->name('penyediaanSST.muatNaikDokumen');
	Route::get('/index-jawatankuasa-pembuka', [JawatankuasaPembukaController::class, 'index'])->name('indexJawatankuasaPembuka');
	Route::get('/jawatankuasa-pembuka', [JawatankuasaPembukaController::class, 'show'])->name('jawatankuasaPembuka');
	Route::post('/jawatankuasa-pembuka/simpan-pematuhan', [JawatankuasaPembukaController::class, 'simpanPematuhan'])->name('jawatankuasaPembuka.simpanPematuhan');
	Route::get('/jawatankuasa-pembuka/rumusan-data', [JawatankuasaPembukaController::class, 'getRumusanData'])->name('jawatankuasaPembuka.rumusanData');

	Route::post('/jawatankuasa-pembuka/hantar', [JawatankuasaPembukaController::class, 'hantar'])
		->middleware('committee.pengerusi:open')
		->name('jawatankuasaPembuka.hantar');
	Route::get('/jawatankuasa-pembuka/penilaian-semasa', [\App\Http\Controllers\JawatankuasaPembukaSesiController::class, 'evaluations'])
		->name('jawatankuasaPembuka.penilaianSemasa');
	Route::get('/jawatankuasa-pembuka/status-penilaian', [\App\Http\Controllers\JawatankuasaPembukaSesiController::class, 'itemStatuses'])
		->name('jawatankuasaPembuka.statusPenilaian');
	Route::get('/jawatankuasa-pembuka/taraf-bumiputera', [\App\Http\Controllers\JawatankuasaPembukaSesiController::class, 'bumiputeraStatuses'])
		->name('jawatankuasaPembuka.tarafBumiputera');
	// {jenis}: open = Pembuka, tech = Teknikal, fin = Kewangan.
	Route::prefix('/penilaian-sesi/{jenis}')->name('penilaianSesi.')->group(function () {
		Route::get('/session', [\App\Http\Controllers\EvaluationSessionController::class, 'session'])->name('session');
		Route::post('/declaration', [\App\Http\Controllers\EvaluationSessionController::class, 'storeDeclaration'])->name('declaration');
		Route::post('/lock', [\App\Http\Controllers\EvaluationSessionController::class, 'acquireLock'])->name('lock');
		Route::post('/lock/release', [\App\Http\Controllers\EvaluationSessionController::class, 'releaseLock'])->name('lock.release');
		Route::post('/rows/complete', [\App\Http\Controllers\EvaluationSessionController::class, 'completeRows'])->name('rows.complete');
		Route::get('/locks', [\App\Http\Controllers\EvaluationSessionController::class, 'locks'])->name('locks');
		Route::post('/log', [\App\Http\Controllers\EvaluationSessionController::class, 'storeLog'])->name('log');
	});

	// Demo: reference page for loading vendor checklist submissions
	Route::get('/demo/penyerahan-petender', [VendorSubmissionsDemoController::class, 'index'])->name('demo.vendorSubmissions.index');
	Route::get('/demo/penyerahan-petender/{tender}', [VendorSubmissionsDemoController::class, 'show'])->name('demo.vendorSubmissions.show');
});

// Surat Niat — butiran & tindakan. Sengaja di luar kumpulan di atas: laluan ini
// menerima id tender (int), bukan uuid, jadi PenyediaanSuratNiatController::index()
// menjana show_url dengan $tender->id supaya padan.
Route::prefix('penyediaan-surat-niat')->controller(SuratNiatController::class)->middleware(['auth'])->group(function () {
	Route::get('/{tender}', 'show')->name('penyediaanSuratNiat');
	Route::get('/{tender}/pembekals', 'pembekals')->name('suratNiat.pembekals');
	Route::post('/{tender}/pembekals', 'savePembekals')->name('suratNiat.savePembekals');
	Route::get('/{tender}/surat', 'suratList')->name('suratNiat.suratList');
	Route::post('/{tender}/surat', 'generateSurat')->name('suratNiat.generateSurat');
	Route::post('/{tender}/hantar', 'hantar')->name('suratNiat.hantar');
});
Route::middleware(['auth'])->controller(SuratNiatController::class)->group(function () {
	Route::put('/surat-niat/surat/{id}', 'updateSurat')->name('suratNiat.updateSurat');
	Route::delete('/surat-niat/surat/{id}', 'deleteSurat')->name('suratNiat.deleteSurat');
	Route::get('/surat-niat/surat/{id}/download', 'download')->name('suratNiat.download');
});
Route::view('/show-soalan-lazim', 'helps.show')->name('showSoalanLazim');

// penilaian teknikal
Route::middleware(['auth'])->group(function () {
	Route::get('/penilaian-teknikal', [PenilaianTeknikalController::class, 'indexDatatable'])->name('penilaianTeknikal');
	Route::get('/penilaian-teknikal/{uuid}', [PenilaianTeknikalController::class, 'show'])->name('penilaianTeknikal.show');
	Route::get('/penilaian-teknikal-kerja/{uuid}', [PenilaianTeknikalController::class, 'showTeknikalKerja'])->name('penilaianTeknikalKerja.show');
	Route::post('/penilaian-teknikal/hantar', [PenilaianTeknikalController::class, 'hantar'])->name('penilaianTeknikal.hantar');
	Route::post('/penilaian-teknikal/pematuhan/simpan', [PenilaianTeknikalController::class, 'simpanPematuhan'])->name('penilaianTeknikal.simpanPematuhan');
	Route::get('/penilaian-teknikal/{tender}/rumusan-pematuhan', [PenilaianTeknikalController::class, 'rumusanPematuhan'])->name('penilaianTeknikal.rumusanPematuhan');
	Route::post('/penilaian-teknikal/{tender}/hantar-pematuhan', [PenilaianTeknikalController::class, 'hantarPematuhan'])->name('penilaianTeknikal.hantarPematuhan');
	Route::post('/penilaian-teknikal/spesifikasi/sahkan', [PenilaianTeknikalController::class, 'confirmSpesifikasi'])->name('penilaianTeknikal.confirmSpesifikasi');
	Route::get('/penilaian-teknikal/{tender}/spesifikasi/{checklistItemUuid}/rollup', [PenilaianTeknikalController::class, 'spesifikasiRollup'])->name('penilaianTeknikal.spesifikasiRollup');
	Route::get('/penilaian-teknikal/{tender}/spesifikasi/{checklistItemUuid}/vendor/{vendorId}', [PenilaianTeknikalController::class, 'spesifikasiDetail'])->name('penilaianTeknikal.spesifikasiDetail');
	Route::post('/penilaian-teknikal/spesifikasi/simpan', [PenilaianTeknikalController::class, 'simpanSpesifikasi'])->name('penilaianTeknikal.simpanSpesifikasi');
	Route::get('/penilaian-teknikal/{tender}/borang/{checklistItemUuid}/rows', [PenilaianTeknikalController::class, 'borangRows'])->name('penilaianTeknikal.borangRows');
	Route::post('/penilaian-teknikal/borang/simpan', [PenilaianTeknikalController::class, 'simpanBorang'])->name('penilaianTeknikal.simpanBorang');
	Route::get('/penilaian-teknikal/{tender}/rumusan-penilaian-teknikal', [PenilaianTeknikalController::class, 'rumusanPenilaianTeknikal'])->name('penilaianTeknikal.rumusanPenilaianTeknikal');
	Route::get('/penilaian-teknikal/{tender}/laporan', [PenilaianTeknikalController::class, 'laporanPenilaianTeknikal'])->name('penilaianTeknikal.laporanPenilaianTeknikal');
	Route::get('/penilaian-teknikal/{tender}/laporan/cetak', [PenilaianTeknikalController::class, 'cetakLaporan'])->name('penilaianTeknikal.cetakLaporan');
	Route::post('/penilaian-teknikal/laporan/simpan-draf', [PenilaianTeknikalController::class, 'simpanDrafLaporan'])->name('penilaianTeknikal.simpanDrafLaporan');
	Route::post('/penilaian-teknikal-kerja/hantar', [PenilaianTeknikalController::class, 'hantarTeknikalKerja'])->name('penilaianTeknikalKerja.hantar');
	Route::post('/penilaian-teknikal-kerja/{tender}/lampiran/upload', [PenilaianTeknikalController::class, 'uploadLampiranKerja'])->name('penilaianTeknikalKerja.lampiran.upload');
	Route::post('/penilaian-teknikal-kerja/lampiran/{lampiran}/rename', [PenilaianTeknikalController::class, 'renameLampiranKerja'])->name('penilaianTeknikalKerja.lampiran.rename');
	Route::delete('/penilaian-teknikal-kerja/lampiran/{lampiran}', [PenilaianTeknikalController::class, 'deleteLampiranKerja'])->name('penilaianTeknikalKerja.lampiran.delete');
	Route::get('/penilaian-teknikal-kerja/lampiran/{lampiran}/download', [PenilaianTeknikalController::class, 'downloadLampiranKerja'])->name('penilaianTeknikalKerja.lampiran.download');

	// new penilaian kewangan
	Route::get('/penilaian-kewangan', [PenilaianKewanganController::class, 'index'])->name('penilaianKewangan');
	Route::get('/penilaian-kewangan/{tender_no}', [PenilaianKewanganController::class, 'show'])->name('penilaianKewangan.show')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan/simpan-pematuhan', [PenilaianKewanganController::class, 'simpanPematuhan'])->name('penilaianKewangan.simpanPematuhan');
	Route::post('/penilaian-kewangan/kemaskini-langkah', [PenilaianKewanganController::class, 'kemaskiniLangkah'])->name('penilaianKewangan.kemaskiniLangkah');
	Route::post('/penilaian-kewangan/simpan-laporan', [PenilaianKewanganController::class, 'simpanLaporanDraft'])->name('penilaianKewangan.simpanLaporan');
	Route::post('/penilaian-kewangan/hantar', [PenilaianKewanganController::class, 'hantar'])->name('penilaianKewangan.hantar');

	// Penilaian Kewangan (Kerja)
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang1/simpan-kriteria', [PenilaianKewanganKerjaController::class, 'simpanBorang1Kriteria'])->name('penilaianKewanganKerja.borang1.simpanKriteria')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang1/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang1Muktamad'])->name('penilaianKewanganKerja.borang1.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang2/simpan-kriteria', [PenilaianKewanganKerjaController::class, 'simpanBorang2Kriteria'])->name('penilaianKewanganKerja.borang2.simpanKriteria')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang2/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang2Muktamad'])->name('penilaianKewanganKerja.borang2.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang3/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang3Muktamad'])->name('penilaianKewanganKerja.borang3.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang4/simpan-vendor', [PenilaianKewanganKerjaController::class, 'simpanBorang4PenilaianVendor'])->name('penilaianKewanganKerja.borang4.simpanVendor')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang4/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang4Muktamad'])->name('penilaianKewanganKerja.borang4.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang5/simpan-kriteria', [PenilaianKewanganKerjaController::class, 'simpanBorang5Kriteria'])->name('penilaianKewanganKerja.borang5.simpanKriteria')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang5/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang5Muktamad'])->name('penilaianKewanganKerja.borang5.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang6/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang6Muktamad'])->name('penilaianKewanganKerja.borang6.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang7/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang7Muktamad'])->name('penilaianKewanganKerja.borang7.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang8/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang8Muktamad'])->name('penilaianKewanganKerja.borang8.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang14/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang14Muktamad'])->name('penilaianKewanganKerja.borang14.simpanMuktamad')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/borang15/simpan-muktamad', [PenilaianKewanganKerjaController::class, 'simpanBorang15Muktamad'])->name('penilaianKewanganKerja.borang15.simpanMuktamad')->where('tender_no', '.*');
	Route::get('/penilaian-kewangan-kerja/{tender_no}/borang/{borang_code}', [PenilaianKewanganKerjaController::class, 'getBorang'])->name('penilaianKewanganKerja.borang.show')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/borang/{borang_code}/simpan', [PenilaianKewanganKerjaController::class, 'simpanBorang'])->name('penilaianKewanganKerja.borang.simpan')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/pengesahan-peringkat', [PenilaianKewanganKerjaController::class, 'sahkanPeringkat'])->name('penilaianKewanganKerja.sahkanPeringkat')->where('tender_no', '.*');
	Route::post('/penilaian-kewangan-kerja/{tender_no}/hantar', [PenilaianKewanganKerjaController::class, 'hantar'])->name('penilaianKewanganKerja.hantar')->where('tender_no', '.*');
	Route::get('/penilaian-kewangan-kerja/{tender_no}', [PenilaianKewanganKerjaController::class, 'show'])->name('penilaianKewanganKerja.show')->where('tender_no', '.*');
});


Route::view('/penilaian-kewangan-kerja', 'newModule.penilaian_kewangan.penilaian.borang1')->name('borang1');
Route::view('/penilaian-kewangan-kerja-borang2', 'newModule.penilaian_kewangan.penilaian.borang2')->name('borang2');
Route::view('/penilaian-kewangan-kerja-borang3', 'newModule.penilaian_kewangan.penilaian.borang3')->name('borang3');
Route::view('/penilaian-kewangan-kerja-lembaran', 'newModule.penilaian_kewangan.penilaian.lembaran')->name('lembaran');
Route::view('/penilaian-kewangan-kerja-akaun-bank', 'newModule.penilaian_kewangan.penilaian.akaun_bank')->name('akaunBank');
Route::view('/penilaian-kewangan-kerja-bon-saham', 'newModule.penilaian_kewangan.penilaian.bon_saham')->name('bonSaham');
Route::view('/penilaian-kewangan-kerja-borang4', 'newModule.penilaian_kewangan.penilaian.borang4')->name('borang4');
Route::view('/penilaian-kewangan-kerja-borang5', 'newModule.penilaian_kewangan.penilaian.borang5')->name('borang5');
Route::view('/penilaian-kewangan-kerja-borang6', 'newModule.penilaian_kewangan.penilaian.borang6')->name('borang6');
Route::view('/penilaian-kewangan-kerja-borang7', 'newModule.penilaian_kewangan.penilaian.borang7')->name('borang7');
Route::view('/penilaian-kewangan-kerja-borang7-serupa', 'newModule.penilaian_kewangan.penilaian.borang7')->name('serupa');
Route::view('/penilaian-kewangan-kerja-borang7-sebanding', 'newModule.penilaian_kewangan.penilaian.borang7')->name('sebanding');
Route::view('/penilaian-kewangan-kerja-borang8', 'newModule.penilaian_kewangan.penilaian.borang8')->name('borang8');
Route::view('/penilaian-kewangan-kerja-borang9', 'newModule.penilaian_kewangan.penilaian.borang9')->name('borang9');
Route::view('/penilaian-kewangan-kerja-borang9-serupa', 'newModule.penilaian_kewangan.penilaian.borang9_serupa')->name('kerjaSerupa');
Route::view('/penilaian-kewangan-kerja-borang9-sebanding', 'newModule.penilaian_kewangan.penilaian.borang9_sebanding')->name('kerjaSebanding');
Route::view('/penilaian-kewangan-kerja-borang10', 'newModule.penilaian_kewangan.penilaian.borang10')->name('borang10');
Route::view('/penilaian-kewangan-kerja-borang11', 'newModule.penilaian_kewangan.penilaian.borang11')->name('borang11');
Route::view('/penilaian-kewangan-kerja-borang12', 'newModule.penilaian_kewangan.penilaian.borang12')->name('borang12');
Route::view('/penilaian-kewangan-kerja-borang13', 'newModule.penilaian_kewangan.penilaian.borang13')->name('borang13');
Route::view('/penilaian-kewangan-kerja-borang14', 'newModule.penilaian_kewangan.penilaian.borang14')->name('borang14');
Route::view('/penilaian-kewangan-kerja-borang15', 'newModule.penilaian_kewangan.penilaian.borang15')->name('borang15');


Route::view('/lawatan-tapak', 'newModule.syarikatPembekal.lawatan_tapak')->name('lawatanTapak');

Route::middleware('auth')->group(function () {
	Route::get('/visits/{visit}/representatives', [TenderVisitRepresentativeController::class, 'index'])
		->name('visits.representatives.index');
	Route::post('/visits/{visit}/representatives', [TenderVisitRepresentativeController::class, 'store'])
		->name('visits.representatives.store');
});
Route::view('/syarat-tender', 'newModule.syarikatPembekal.syarat_tender')->name('syaratTender');
Route::view('/kod-bidang', 'newModule.syarikatPembekal.kod_bidang')->name('kodBidang');

// Dummy Controller 
Route::get('/tender/cipta', [DummyController::class, 'create'])->name('tender.create');
Route::post('/tender/store', [DummyController::class, 'store'])->name('tender.store');
Route::get('/tender/bekalan', [DummyController::class, 'bekalan'])->name('tender.bekalan');
Route::get('/tender/kerja', [DummyController::class, 'kerja'])->name('tender.kerja');
Route::get('/tender/status/{status}', [DummyController::class, 'viewByStatus'])->name('tender.status');
// Protected routes
Route::middleware(['auth'])->group(function () {
	Route::get('/senarai-teknikal/{tenderUuid}', [TechnicalChecklistController::class, 'index'])->name('senaraiTeknikal');
	Route::post('/senarai-teknikal/{tenderUuid}', [TechnicalChecklistController::class, 'store'])->name('senaraiTeknikal.store');
	Route::post('/senarai-teknikal/{tenderUuid}/hantar', [TechnicalChecklistController::class, 'submit'])->name('senaraiTeknikal.submit');
	Route::post('/senarai-teknikal/{tenderUuid}/fail', [TechnicalChecklistController::class, 'uploadFile'])->name('senaraiTeknikal.uploadFile');
	Route::delete('/senarai-teknikal/fail/{fileUuid}', [TechnicalChecklistController::class, 'deleteFile'])->name('senaraiTeknikal.deleteFile');
	Route::get('/senarai-teknikal/templat-spesifikasi/{tenderUuid}/{documentUuid?}', [TechnicalSpecificationController::class, 'create'])->name('spesifikasiForm');
	Route::get('/standard-checklist-items', [StandardChecklistItemController::class, 'index'])->name('standardChecklistItems.index');
	Route::get('/spesifikasi-teknikal', [TechnicalSpecificationController::class, 'index'])->name('spesifikasiTeknikal.index');
	Route::post('/spesifikasi-teknikal', [TechnicalSpecificationController::class, 'store'])->name('spesifikasiTeknikal.store');
	Route::get('/spesifikasi-teknikal/{uuid}', [TechnicalSpecificationController::class, 'show'])->name('spesifikasiTeknikal.show');
	Route::put('/spesifikasi-teknikal/{uuid}', [TechnicalSpecificationController::class, 'update'])->name('spesifikasiTeknikal.update');
	Route::post('/spesifikasi-teknikal/{uuid}/lengkap', [TechnicalSpecificationController::class, 'complete'])->name('spesifikasiTeknikal.complete');
	Route::delete('/spesifikasi-teknikal/{uuid}', [TechnicalSpecificationController::class, 'destroy'])->name('spesifikasiTeknikal.destroy');
	Route::get('/senarai-teknikal/{tenderUuid}/pengalaman-kerja', [PengalamanKerjaController::class, 'create'])->name('senaraiTeknikal.pengalamanKerja.tender');
	Route::post('/senarai-teknikal/{tenderUuid}/pengalaman-kerja', [PengalamanKerjaController::class, 'store'])->name('senaraiTeknikal.pengalamanKerja.store');

	//check blade location///
	Route::delete('/pengalaman-kerja-files/{fileUuid}', [PengalamanKerjaController::class, 'deleteFile'])->name('pengalamanKerja.deleteFile');
	Route::get('/senarai-teknikal/{tenderUuid}/kerja-dalam-tangan', [KerjaDalamTanganController::class, 'create'])->name('senaraiTeknikal.kerjaDalamTangan');
	Route::post('/senarai-teknikal/{tenderUuid}/kerja-dalam-tangan', [KerjaDalamTanganController::class, 'store'])->name('senaraiTeknikal.kerjaDalamTangan.store');
	Route::delete('/kerja-dalam-tangan-files/{fileUuid}', [KerjaDalamTanganController::class, 'deleteFile'])->name('kerjaDalamTangan.deleteFile');
	Route::get('/senarai-kewangan-bekalan/{tenderUuid}', [FinancialChecklistController::class, 'index'])->name('senaraiKewanganBekalan');
	Route::post('/senarai-kewangan-bekalan/{tenderUuid}', [FinancialChecklistController::class, 'store'])->name('senaraiKewanganBekalan.store');
	Route::post('/senarai-kewangan-bekalan/{tenderUuid}/hantar', [FinancialChecklistController::class, 'submit'])->name('senaraiKewanganBekalan.submit');
	Route::post('/senarai-kewangan-bekalan/{tenderUuid}/fail', [FinancialChecklistController::class, 'uploadFile'])->name('senaraiKewanganBekalan.uploadFile');
	Route::delete('/senarai-kewangan-bekalan/fail/{fileUuid}', [FinancialChecklistController::class, 'deleteFile'])->name('senaraiKewanganBekalan.deleteFile');
	Route::get('/profil-petender/{tenderUuid}', [ProfilPetenderController::class, 'index'])->name('profilPetender');
	Route::post('/profil-petender/{tenderUuid}', [ProfilPetenderController::class, 'store'])->name('profilPetender.store');
	Route::post('/profil-petender/{tenderUuid}/selesai', [ProfilPetenderController::class, 'submit'])->name('profilPetender.submit');
	Route::get('/spesifikasi-kewangan/{spesifikasiUuid}', [SpecificationPricingController::class, 'index'])->name('spesifikasiFormKewanganBekalan');
	Route::post('/spesifikasi-kewangan/{spesifikasiUuid}', [SpecificationPricingController::class, 'store'])->name('spesifikasiKewangan.store');
	Route::post('/spesifikasi-kewangan/{spesifikasiUuid}/selesai', [SpecificationPricingController::class, 'submit'])->name('spesifikasiKewangan.submit');
	Route::get('/penyata-bank/{tenderUuid}', [PenyataBankController::class, 'index'])->name('penyataBank')->middleware(['auth']);
	Route::post('/penyata-bank/{tenderUuid}', [PenyataBankController::class, 'store'])->name('penyataBank.store')->middleware(['auth']);
	Route::post('/penyata-bank/{tenderUuid}/selesai', [PenyataBankController::class, 'submit'])->name('penyataBank.submit')->middleware(['auth']);
	Route::post('/penyata-bank/{tenderUuid}/fail', [PenyataBankController::class, 'uploadFile'])->name('penyataBank.uploadFile')->middleware(['auth']);
	Route::delete('/penyata-bank-fail/{fileUuid}', [PenyataBankController::class, 'deleteFile'])->name('penyataBank.deleteFile')->middleware(['auth']);

	Route::get('/lembaran-imbangan/{tenderUuid}', [LembaranImbanganController::class, 'index'])->name('lembaranImbangan');
	Route::post('/lembaran-imbangan/{tenderUuid}', [LembaranImbanganController::class, 'store'])->name('lembaranImbangan.store');
	Route::post('/lembaran-imbangan/{tenderUuid}/selesai', [LembaranImbanganController::class, 'submit'])->name('lembaranImbangan.submit');

	Route::get('/bon-atau-saham/{tenderUuid}', [BonSahamController::class, 'index'])->name('bonAtauSaham');
	Route::post('/bon-atau-saham/{tenderUuid}', [BonSahamController::class, 'store'])->name('bonAtauSaham.store');

	Route::get('/prestasi-kerja-semasa-petender/{tenderUuid}', [TenderPrestasiKerjaController::class, 'index'])->name('prestasiKerjaSemasa');
	Route::post('/prestasi-kerja-semasa-petender/{tenderUuid}', [TenderPrestasiKerjaController::class, 'store'])->name('prestasiKerjaSemasa.store');
	Route::delete('/prestasi-kerja-semasa-petender/fail/{fileUuid}', [TenderPrestasiKerjaController::class, 'deleteFile'])->name('prestasiKerjaSemasa.deleteFile');
	//////////////////////////

	// Route::get('tender/select', [TendersController::class, 'select']);
	Route::resource('tender', TendersController::class);
	Route::resource('jawatankuasa', JawatankuasaController::class);
	// Route::get('tender/{id}/prices', [TendersController::class, 'prices'])->name('tenders.prices');
	// Route::get('tender/{tender_id}/files/{id}', [TendersController::class, 'file'])->name('tenders.files');
	// Route::get('tender/{id}/vendors', [TendersController::class, 'vendors'])->name('tenders.vendors');
	// Route::post('tender/{id}/exception', [TendersController::class, 'exception'])->name('tenders.exception');

	Route::get('/pengurusan-spesifikasi', [TendersController::class, 'manageSpecification'])->name('pengurusanSpesifikasi');

	// Penyediaan Spesifikasi Tender (Kerja)
	Route::get('/penyediaan-spesifikasi-tender/{tenderUuid}', [SpesifikasiTenderKerjaController::class, 'index'])->name('penyediaanSpekTender');
	Route::post('/penyediaan-spesifikasi-tender/{tenderUuid}', [SpesifikasiTenderKerjaController::class, 'store'])->name('penyediaanSpekTender.store');
	Route::post('/penyediaan-spesifikasi-tender/{tenderUuid}/hantar', [SpesifikasiTenderKerjaController::class, 'submit'])->name('penyediaanSpekTender.submit');
	Route::post('/penyediaan-spesifikasi-tender/{tenderUuid}/fail', [SpesifikasiTenderKerjaController::class, 'uploadFile'])->name('penyediaanSpekTender.uploadFile');
	Route::delete('/penyediaan-spesifikasi-tender/fail/{fileUuid}', [SpesifikasiTenderKerjaController::class, 'deleteFile'])->name('penyediaanSpekTender.deleteFile');

	// Senarai Kewangan Kerja
	Route::get('/senarai-kewangan-kerja/{tenderUuid}', [SenaraiKewanganKerjaController::class, 'index'])->name('senaraiKewanganKerja');
	Route::post('/senarai-kewangan-kerja/{tenderUuid}', [SenaraiKewanganKerjaController::class, 'store'])->name('senaraiKewanganKerja.store');
	Route::post('/senarai-kewangan-kerja/{tenderUuid}/hantar', [SenaraiKewanganKerjaController::class, 'submit'])->name('senaraiKewanganKerja.submit');
	Route::post('/senarai-kewangan-kerja/{tenderUuid}/fail', [SenaraiKewanganKerjaController::class, 'uploadFile'])->name('senaraiKewanganKerja.uploadFile');
	Route::delete('/senarai-kewangan-kerja/fail/{fileUuid}', [SenaraiKewanganKerjaController::class, 'deleteFile'])->name('senaraiKewanganKerja.deleteFile');

	// Perakuan Jabatan
	Route::get('/perakuan-jabatan', [PerakuanJabatanController::class, 'index'])->name('perakuanjabatan.index');
	Route::post('/perakuan-jabatan/{tender}/kertas-taklimat/simpan', [PerakuanJabatanController::class, 'kertasTaklimatSimpan'])->name('perakuanjabatan.kertasTaklimat.simpan');
	Route::post('/perakuan-jabatan/{tender}/kertas-taklimat/hantar', [PerakuanJabatanController::class, 'kertasTaklimatHantar'])->name('perakuanjabatan.kertasTaklimat.hantar');
	Route::get('/perakuan-jabatan/kertas-taklimat/file/{file}', [PerakuanJabatanController::class, 'kertasTaklimatDownload'])->name('perakuanjabatan.kertasTaklimat.download');
	Route::post('/perakuan-jabatan/{tender}/pengesyoran-pembekal/simpan', [PerakuanJabatanController::class, 'pengesyoranPembekalSimpan'])->name('perakuanjabatan.pengesyoranPembekal.simpan');
	Route::post('/perakuan-jabatan/{tender}/pengesyoran-pembekal/hantar', [PerakuanJabatanController::class, 'pengesyoranPembekalHantar'])->name('perakuanjabatan.pengesyoranPembekal.hantar');
	Route::get('/perakuan-jabatan/{id}', [PerakuanJabatanController::class, 'show'])->middleware(['auth'])->name('perakuanjabatan.show');
	Route::get('/perakuan-jabatan/form', [PerakuanJabatanController::class, 'form'])->middleware(['auth'])->name('perakuanJabatan.form');
	Route::get('/perakuan-jabatan/kertas-taklimat', [PerakuanJabatanController::class, 'kertasTaklimat'])->middleware(['auth'])->name('perakuanJabatan.kertasTaklimat');
	Route::get('/perakuan-jabatan/pengesyoran-pembekal', [PerakuanJabatanController::class, 'pengesyoranPembekal'])->middleware(['auth'])->name('perakuanJabatan.pengesyoranPembekal');

	// Jawatankuasa Perolehan
	Route::get('jawatankuasa-perolehan', [JawatankuasaPerolehanController::class, 'index'])->name('jawatankuasa.perolehan.index');
	Route::get('jawatankuasa-perolehan/form', [JawatankuasaPerolehanController::class, 'form'])->name('jawatankuasa.perolehan.form');
	Route::post('jawatankuasa-perolehan/mesyuarat/simpan', [JawatankuasaPerolehanController::class, 'store'])->name('jawatankuasa.perolehan.mesyuarat.simpan');
	Route::post('jawatankuasa-perolehan/mesyuarat/hantar', [JawatankuasaPerolehanController::class, 'hantar'])->name('jawatankuasa.perolehan.mesyuarat.hantar');
	Route::post('jawatankuasa-perolehan/kertas-keputusan/simpan', [JawatankuasaPerolehanController::class, 'simpanKertasKeputusan'])->name('jawatankuasa.perolehan.kertas_keputusan.simpan');
	Route::post('jawatankuasa-perolehan/kertas-keputusan/hantar', [JawatankuasaPerolehanController::class, 'hantarKertasKeputusan'])->name('jawatankuasa.perolehan.kertas_keputusan.hantar');
	Route::post('jawatankuasa-perolehan/pemilihan-pembekal/simpan', [JawatankuasaPerolehanController::class, 'simpanPemilihanPembekal'])->name('jawatankuasa.perolehan.pemilihan_pembekal.simpan');
	Route::post('jawatankuasa-perolehan/pemilihan-pembekal/hantar', [JawatankuasaPerolehanController::class, 'hantarPemilihanPembekal'])->name('jawatankuasa.perolehan.pemilihan_pembekal.hantar');
	// Route::get('/jawatankuasa-perolehan/index', 'newModule.jawatankuasaPerolehan.index')->name('jawatankuasaPerolehan.index');
	// Route::view('/jawatankuasa-perolehan/form', 'newModule.jawatankuasaPerolehan.form')->name('jawatankuasaPerolehan.form');

	// ebidding
	Route::get('/eBidding/index', [EbiddingController::class, 'index'])->middleware(['auth'])->name('eBidding.index');
	Route::get('/eBidding/{id}', [EbiddingController::class, 'show'])->middleware(['auth'])->name('eBidding.show');
	Route::post('/eBidding/{id}/kertas-taklimat/simpan', [EbiddingController::class, 'simpanKertasTaklimat'])->middleware(['auth'])->name('eBidding.kertasTaklimat.simpan');
	Route::post('/eBidding/{id}/kertas-taklimat/hantar', [EbiddingController::class, 'hantarKertasTaklimat'])->middleware(['auth'])->name('eBidding.kertasTaklimat.hantar');
	Route::post('/eBidding/{id}/pengesyoran/simpan', [EbiddingController::class, 'simpanPengesyoranPembekal'])->middleware(['auth'])->name('eBidding.pengesyoran.simpan');
	Route::post('/eBidding/{id}/pengesyoran/hantar', [EbiddingController::class, 'hantarPengesyoranPembekal'])->middleware(['auth'])->name('eBidding.pengesyoran.hantar');
	Route::post('/eBidding/{id}/jadual-bidaan/simpan', [EbiddingController::class, 'simpanJadualBidaan'])->middleware(['auth'])->name('eBidding.jadualBidaan.simpan');
	Route::post('/eBidding/{id}/jadual-bidaan/mula', [EbiddingController::class, 'mulaBidaan'])->middleware(['auth'])->name('eBidding.jadualBidaan.mula');
	Route::post('/eBidding/{id}/vendor-bidaan/hantar', [EbiddingController::class, 'hantarVendorBidaan'])->middleware(['auth'])->name('eBidding.vendorBidaan.hantar');
	Route::post('/eBidding/{id}/advance-stage', [EbiddingController::class, 'advanceStage'])->middleware(['auth'])->name('eBidding.advanceStage');

	// Version 3.0 tender creation routes
	Route::get('/cipta-tender', [TendersController::class, 'createNew'])->name('ciptaTender');
	Route::post('/cipta-tender', [TendersController::class, 'storeNew'])->name('storeCiptaTender');

	// Get type of perolehan by kategori jenis perolehan
	Route::get('/ref/type-of-perolehan-by-kategori', [RefKategoriJenisPerolehanController::class, 'getTypeOfPerolehanByKategori'])->name('getTypeOfPerolehanByKategori');

	Route::resource('vendors', VendorsController::class);
	Route::get('vendors/select', [VendorsController::class, 'select']);
	Route::get('vendors/new', [VendorsController::class, 'pendingRegistrationIndex']);
	Route::get('vendors/approval', [VendorsController::class, 'approvalNew1Index']);
	Route::get('vendors/changes', [VendorsController::class, 'approvalEdit1Index']);
	Route::get('vendors/emails', [VendorsController::class, 'emails']);
	Route::get('vendor/{vendor_id}/approve', [VendorsController::class, 'approve']);
	Route::post('vendor/{vendor_id}/reject', [VendorsController::class, 'reject']);
	Route::get('vendors/{vendor}/subscriptions/{id}/receipt', [SubscriptionsController::class, 'receipt'])->name('vendors.subscriptions.receipt');
	Route::get('vendors/{user}/edit_email', [VendorsController::class, 'editEmail']);
	Route::put('vendors/{user}/edit_email', [VendorsController::class, 'updateEmail']);
	Route::get('vendors/{user}/histories', [VendorsController::class, 'histories']);
	Route::get('vendors/{user}/certificate', [VendorsController::class, 'certificate']);
	Route::post('vendors/{id}/cidb/integrate', [VendorsController::class, 'integrateCidb'])->name('vendors.cidb.integrate');
	Route::resource('vendor.blacklists', VendorBlacklistsController::class);
	Route::get('vendor/{vendor}/blacklists/{blacklists}/file', [VendorBlacklistsController::class, 'file'])->name('vendor.blacklists.file');
	Route::put('vendor/{vendor}/blacklists/{blacklists}/cancel', [VendorBlacklistsController::class, 'cancel'])->name('vendor.blacklists.cancel');
	Route::get('requests', [CodeRequestsController::class, 'index'])->name('requests.index');
	Route::get('vendor/{vendor}/requests/{requests}/edit', [CodeRequestsController::class, 'edit'])->name('vendor.requests.edit');
	Route::put('vendor/{vendor}/requests/{requests}', [CodeRequestsController::class, 'update'])->name('vendor.requests.update');
	Route::put('vendor/{vendor}/requests/{requests}/approve', [CodeRequestsController::class, 'approve_vendor'])->name('vendor.requests.approve');
	Route::post('vendor/{vendor}/requests/{requests}/reject', [CodeRequestsController::class, 'reject_vendor'])->name('vendor.requests.reject');
	Route::get('vendor/{vendor_id}/blacklist', [VendorsController::class, 'blacklist']);
	Route::put('vendor/{vendor_id}/blacklist', [VendorsController::class, 'doBlacklist']);
	Route::get('vendor/{vendor_id}/cancelBlacklist', [VendorsController::class, 'cancelBlacklist']);

	Route::get('/agency/{id}', [OrganizationUnitsController::class, 'agency']);
	Route::get('/agency/{id}/prices', [OrganizationUnitsController::class, 'agencyPrices']);
	Route::get('/agency/{id}/results', [OrganizationUnitsController::class, 'agencyResults']);
	Route::get('/agency/{id}/news', [OrganizationUnitsController::class, 'agencyNews']);
	Route::get('/agency/{id}/report/{tender}', [OrganizationUnitsController::class, 'agencyReport']);
	Route::resource('agency', OrganizationUnitsController::class);

	Route::get('txn_status/{id}', [HomeController::class, 'txnStatus'])->name('txn_status');
	Route::get('register/company', [RegistrationController::class, 'company'])->name('company_registration');
	Route::put('register/company', [RegistrationController::class, 'storeCompany']);
	Route::get('register/payment', [RegistrationController::class, 'payment'])->name('payment_registration');
	Route::post('register/payment', [RegistrationController::class, 'storePayment']);
	Route::get('register/payment_callback/{transaction_id}', [RegistrationController::class, 'callbackPayment']);

	// Admin dashboard must come before general dashboard route to avoid route conflict
	Route::get('dashboard/hq', [HomeController::class, 'managementDashboard'])->name('dashboard.hq')->middleware(['role:Admin']);

	Route::get('dashboard/{id?}', [HomeController::class, 'dashboard'])->name('dashboard'); //for vendor
	Route::get('vendor', [HomeController::class, 'vendor'])->name('vendor');
	Route::get('renewal', [HomeController::class, 'renewal'])->name('renewal');
	Route::post('renewal', [HomeController::class, 'storeRenewal']);
	Route::get('renewal_callback/{transaction_id}', [HomeController::class, 'callbackRenewal']);

	Route::get('tenders/{id}/buy', [TendersController::class, 'buy'])->name('tenders.buy');
	Route::get('tenders/{tender}/receipt/{id}', [TendersController::class, 'receipt'])->name('tenders.receipt');
	Route::get('tenders/{tender}/document/{id}', [TendersController::class, 'document'])->name('tenders.document');
	Route::post('tenders/{id}/vendors', [TendersController::class, 'updateVendors']);
	Route::post('tenders/{id}/invites', [TendersController::class, 'updateInvites']);
	Route::post('tenders/{id}/vendor', [TendersController::class, 'addVendor'])->name('tenders.addVendor');
	Route::get('tenders/{id}/publish', [TendersController::class, 'publish'])->name('tenders.publish');
	Route::get('tenders/{id}/cancel', [TendersController::class, 'cancel'])->name('tenders.cancel');
	Route::get('tenders/{id}/publishPrices', [TendersController::class, 'publishPrices'])->name('tenders.publishPrices');
	Route::get('tenders/{id}/publishWinner', [TendersController::class, 'publishWinner'])->name('tenders.publishWinner');
	Route::get('tenders/{tender_id}/vendor/{id}', [TendersController::class, 'vendor'])->name('tenders.vendor');
	Route::get('tenders/{id}/vendors/print', [TendersController::class, 'printVendors'])->name('tenders.vendors.print');
	Route::get('tenders/{id}/vendors/template', [TendersController::class, 'template'])->name('tenders.template');
	Route::post('tenders/bulkUpdate', [TendersController::class, 'bulkUpdate'])->name('tenders.bulkUpdate');
	Route::get('tenders/{id}/eligibles', [TendersController::class, 'eligibles'])->name('tenders.eligibles');
	Route::post('tenders/exception/store', [TendersController::class, 'storeException'])->name('tender.store.exception');
	Route::get('tenders/{id}/exceptions', [TendersController::class, 'exceptions'])->name('tender.exceptions');
	Route::get('tenders/{id}/approve', [TendersController::class, 'approve_exception'])->name('tender.approve.exception');
	Route::post('tenders/{id}/reject/{exception_id}', [TendersController::class, 'reject_exception'])->name('tender.reject.exception');
	Route::get('tenders/{id}/publishPrice', [TendersController::class, 'publishPrice']);

	Route::get('cart', [CartController::class, 'index'])->name('cart');
	Route::get('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
	Route::get('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
	Route::get('cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
	Route::post('cart', [CartController::class, 'process'])->name('cart.process');
	Route::get('cart/callback/{transaction_id}', [CartController::class, 'callback']);
	Route::get('cart/receipt/{transaction_id}', [CartController::class, 'callback'])->name('cart.receipt');

	Route::get('profile', [ProfileController::class, 'show'])->name('profile');
	Route::get('profile/change_password', [ProfileController::class, 'changePassword'])->name('change_password');
	Route::put('profile/change_password', [ProfileController::class, 'doChangePassword']);
	Route::get('profile/force_password_change', [ProfileController::class, 'forceChangePassword'])->name('profile.force-password-change');
	Route::put('profile/force_password_change', [ProfileController::class, 'doForceChangePassword']);
	Route::get('profile/release', [ProfileController::class, 'releaseUser'])->name('release_user');

	// Two-factor authentication (user's own enrolment / self-service)
	Route::get('profile/2fa', [TwoFactorController::class, 'manage'])->name('2fa.manage');
	Route::get('profile/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
	Route::post('profile/2fa/confirm', [TwoFactorController::class, 'confirm'])->name('2fa.confirm');
	Route::post('profile/2fa/regenerate', [TwoFactorController::class, 'regenerate'])->name('2fa.regenerate');
	Route::delete('profile/2fa', [TwoFactorController::class, 'disable'])->name('2fa.disable');

	// User's own complaints (Aduan)
	Route::get('my-aduan', [ComplaintController::class, 'myComplaints'])->name('my.aduan.index');
	Route::get('my-aduan/{id}', [ComplaintController::class, 'myComplaintShow'])->name('my.aduan.show');

	// Dashboard fetch routes
	Route::post('dashboard/fetch/tender', [HomeController::class, 'tender_dashboard'])->name('dashboard.tender');
	Route::post('dashboard/fetch/tender-summary', [HomeController::class, 'tender_summary_dashboard'])->name('dashboard.tender.summary');
	Route::post('dashboard/fetch/transaction', [HomeController::class, 'transaction_dashboard'])->name('dashboard.transaction');
	Route::post('dashboard/fetch/transaction-value', [HomeController::class, 'transaction_value_dashboard'])->name('dashboard.transaction-value');
	Route::post('dashboard/fetch/transaction-summary', [HomeController::class, 'transaction_summary_dashboard'])->name('dashboard.transaction.summary');
	Route::post('dashboard/fetch/transaction-value-summary', [HomeController::class, 'transaction_value_summary_dashboard'])->name('dashboard.transaction-value.summary');

	// Version histories
	// Route::get('version-histories', [HomeController::class, 'versionHistories'])->name('version-histories');

	// Test routes (keep for now)
	// Route::get('tenders/send-eligible', [TendersController::class, 'sendEligible']);
	Route::get('hanif', [TendersController::class, 'sendEligible']);
	// Iskandar Hantar e-mail Account Review Request kepada User Sistem 
	Route::get('iskandar', [UsersController::class, 'sendArr']);

	// Vendor resource routes
	Route::resource('vendor.shareholders', ShareholdersController::class);
	Route::resource('vendor.directors', DirectorsController::class);
	Route::resource('vendor.contacts', ContactsController::class);
	Route::resource('vendor.awards', AwardsController::class);
	Route::resource('vendor.projects', ProjectsController::class);
	Route::resource('vendor.products', ProductsController::class);
	Route::resource('vendor.assets', AssetsController::class);
	Route::resource('vendor.remarks', RemarksController::class);
	Route::resource('vendor.subscriptions', SubscriptionsController::class);

	Route::get('vendor/{vendor}/requests', [CodeRequestsController::class, 'index'])->name('vendor.requests.index');
	Route::get('vendor/{vendor}/requests/create', [CodeRequestsController::class, 'create'])->name('vendor.requests.create');
	Route::post('vendor/{vendor}/requests', [CodeRequestsController::class, 'store'])->name('vendor.requests.store');
	Route::get('vendor/{vendor}/requests/{requests}', [CodeRequestsController::class, 'show'])->name('vendor.requests.show');
	Route::delete('vendor/{vendor}/requests/{requests}', [CodeRequestsController::class, 'destroy'])->name('vendor.requests.destroy');

	// Payment routes — vendor-initiated (buying tender docs, renewing subscription),
	// must NOT be role:Admin-only. Moved out of that group: it was blocking every
	// vendor from ever reaching FPX/eBPG connect/bank-list with a 403.
	// (fpx.respond/ebpg.respond live at the top of this file, outside auth —
	// see the comment there for why.)
	Route::get('payment/fpx/connect', [FpxController::class, 'connect'])->name('fpx.connect');
	Route::get('payment/fpx/bank-list', [FpxController::class, 'bankList'])->name('fpx.bank-list');
	Route::get('payment/ebpg/connect', [EbpgController::class, 'connect'])->name('ebpg.connect');

	// Admin routes
	Route::middleware(['role:Admin'])->group(function () {
		Route::get('dashboard/hq', [HomeController::class, 'managementDashboard'])->name('dashboard.hq');

		// Two-factor authentication management
		Route::prefix('two-factor')->name('two-factor.')->group(function () {
			Route::get('/', [TwoFactorAdminController::class, 'index'])->name('index');
			Route::get('audit', [TwoFactorAdminController::class, 'audit'])->name('audit');
			Route::put('roles', [TwoFactorAdminController::class, 'updateRoleSettings'])->name('roles.update');
			Route::put('settings', [TwoFactorAdminController::class, 'updateSettings'])->name('settings.update');
			Route::put('users/{user}/reset', [TwoFactorAdminController::class, 'resetUser'])->name('users.reset');
		});

		Route::get('users/pending-approval', [UsersController::class, 'pendingApproval'])->name('users.pending-approval');
		Route::get('users/{user}/approval', [UsersController::class, 'approval'])->name('users.approval');
		Route::put('users/{user}/approval', [UsersController::class, 'storeApproval'])->name('users.store-approval');
		Route::get('users/{user}/histories', [UsersController::class, 'histories'])->name('users.histories');
		Route::get('users/{user}/login', [UsersController::class, 'doLogin'])->name('users.login');
		Route::put('users/{user}/confirm', [UsersController::class, 'confirm']);
		Route::get('users/{user}/reset_password', [UsersController::class, 'getSetPassword'])->name('user.reset-password');
		Route::put('users/{user}/reset_password', [UsersController::class, 'putSetPassword']);
		Route::get('users/{user}/send_reset_password', [UsersController::class, 'sendResetPasswordEmail'])->name('user.send-reset-password');
		Route::put('users/{user}/confirm', [UsersController::class, 'putSetConfirmation']);
		Route::get('users/{user}/resend_confirmation', [UsersController::class, 'resendConfirmation']);
		Route::resource('users', UsersController::class);

		Route::post('user/byagency', [UsersController::class, 'getUserByAgencies'])->name('user.by.agency');
		Route::post('user/byid', [UsersController::class, 'getUserById'])->name('user.by.id');



		Route::get('requests/show/{request_id}', [CodeRequestsController::class, 'showAll'])->name('requests.showAll');
		Route::put('requests/{requests}/approve', [CodeRequestsController::class, 'approve'])->name('requests.approve');
		Route::post('requests/{requests}/reject', [CodeRequestsController::class, 'reject'])->name('requests.reject');



		Route::post('transactions/ajax', [TransactionsController::class, 'updateFpxCount'])->name('updateFpxCount');
		Route::get('transactions/subscription', [TransactionsController::class, 'subscriptionIndex']);
		Route::get('transactions/purchase', [TransactionsController::class, 'purchaseIndex']);
		Route::get('transactions/success', [TransactionsController::class, 'successTransIndex']);
		Route::get('transactions/pending', [TransactionsController::class, 'pendingTransIndex']);
		Route::get('transactions/declined', [TransactionsController::class, 'declinedTransIndex']);
		Route::get('transactions/failed', [TransactionsController::class, 'failedTransIndex']);
		Route::get('transactions/pending_authorization', [TransactionsController::class, 'pendingAuthTransIndex']);

		Route::resource('transactions', TransactionsController::class);
		Route::get('transactions/{id}/receipt', [TransactionsController::class, 'receipt'])->name('transactions.receipt');
		Route::get('transactions/{id}/fpx_query', [TransactionsController::class, 'fpx_query'])->name('transactions.fpx_query');
		Route::get('transactions/{id}/fpx_requery', [TransactionsController::class, 'fpx_requery'])->name('transactions.fpx_requery');
		Route::get('transactions/{id}/temp_receipt', [TransactionsController::class, 'temp_receipt'])->name('transactions.temp_receipt');

		Route::resource('blacklists', VendorBlacklistsController::class);
		Route::prefix('vendor/{vendor}/blacklists/{blacklists}')->name('vendor.blacklists.')->controller(VendorBlacklistsController::class)->group(function () {
			Route::put('cancel', 'cancel')->name('cancel');
			Route::get('unblacklist', 'unblacklist')->name('unblacklist');
		});

		// Settings - Controller doesn't exist, commented out
		// Route::get('settings', [SettingsController::class, 'index'])->name('settings');
		// Route::put('settings', [SettingsController::class, 'update']);

		// Reports - Controller doesn't exist, commented out
		// Route::get('reports', [ReportsController::class, 'index'])->name('reports');
		// Route::get('reports/export', [ReportsController::class, 'export'])->name('reports.export');

		// SMTP Mails (Note: SmtpMailController, not SmtpMailsController)
		Route::get('smtp_mails', [SmtpMailController::class, 'index'])->name('smtp_mails');
		Route::get('smtp_mails/create', [SmtpMailController::class, 'create'])->name('smtp_mails.create');
		Route::post('smtp_mails', [SmtpMailController::class, 'store'])->name('smtp_mails.store');
		Route::get('smtp_mails/{smtp_mail}', [SmtpMailController::class, 'show'])->name('smtp_mails.show');
		Route::get('smtp_mails/{smtp_mail}/edit', [SmtpMailController::class, 'edit'])->name('smtp_mails.edit');
		Route::put('smtp_mails/{smtp_mail}', [SmtpMailController::class, 'update'])->name('smtp_mails.update');
		Route::delete('smtp_mails/{smtp_mail}', [SmtpMailController::class, 'destroy'])->name('smtp_mails.destroy');

		Route::resource('banners', BannersController::class);
		Route::get('banners/{id}/publish', [BannersController::class, 'publish'])->name('banners.publish');
		// Route::resource('vendors', VendorsController::class);

		// Organization Types
		Route::resource('organizationtypes', OrganizationTypesController::class);
		Route::post('organizationtypes/custom', [OrganizationTypesController::class, 'customSave'])->name('org_type_custom_save');

		// Roles and Permissions
		Route::resource('roles', RolesController::class);
		Route::resource('permissions', PermissionsController::class);

		// Codes and Gateways
		Route::resource('codes', CertificationCodesController::class);
		Route::resource('gateways', GatewaysController::class);
		Route::resource('payments', PaymentsController::class);
		Route::resource('helpcategories', HelpCategoriesController::class);

		// News
		Route::get('news/{id}/publish', [NewsController::class, 'publish'])->name('news.publish');

		// Reports - Individual Report Controllers
		Route::get('reports/revenue', [ReportRevenueController::class, 'index']);
		Route::post('reports/revenue', [ReportRevenueController::class, 'view']);
		Route::get('reports/revenue/excel', [ReportRevenueController::class, 'excel']);

		Route::get('reports/agency/active', [ReportAgencyActiveController::class, 'index']);
		Route::post('reports/agency/active', [ReportAgencyActiveController::class, 'view']);
		Route::get('reports/agency/active/excel', [ReportAgencyActiveController::class, 'excel']);

		Route::get('reports/agency/all', [ReportAgencyAllController::class, 'index']);
		Route::post('reports/agency/all', [ReportAgencyAllController::class, 'view']);
		Route::get('reports/agency/all/excel', [ReportAgencyAllController::class, 'excel']);

		Route::get('reports/agency/type', [ReportAgencyTypeController::class, 'index']);
		Route::post('reports/agency/type', [ReportAgencyTypeController::class, 'view']);
		Route::get('reports/agency/type/excel', [ReportAgencyTypeController::class, 'excel']);

		Route::get('reports/agency/daily', [ReportAgencyDailyController::class, 'index']);
		Route::post('reports/agency/daily', [ReportAgencyDailyController::class, 'view']);
		Route::get('reports/agency/daily/excel', [ReportAgencyDailyController::class, 'excel']);

		Route::get('reports/agency/transaction', [ReportAgencyTransactionController::class, 'index']);
		Route::post('reports/agency/transaction', [ReportAgencyTransactionController::class, 'view']);
		Route::get('reports/agency/transaction/receipts', [ReportAgencyTransactionController::class, 'receipts']);
		Route::get('reports/agency/transaction/excel', [ReportAgencyTransactionController::class, 'excel']);

		Route::get('reports/gateway/daily', [ReportGatewayDailyController::class, 'index']);
		Route::post('reports/gateway/daily', [ReportGatewayDailyController::class, 'view']);
		Route::get('reports/gateway/daily/excel', [ReportGatewayDailyController::class, 'excel']);

		Route::get('reports/vendor/status', [ReportVendorStatusController::class, 'index']);
		Route::get('reports/vendor/status/view/{view}', [ReportVendorStatusController::class, 'view']);
		Route::get('reports/vendor/status/csv/{view}', [ReportVendorStatusController::class, 'csv']);
		Route::get('reports/vendor/status/excel/{view}', [ReportVendorStatusController::class, 'excel']);

		/* add by zayid 4-jan-23 */
		Route::get('reports/vendor/summary/{year}/{vendor_id}', [ReportVendorSummaryController::class, 'index'])->name('report.vendor.summary');

		Route::get('reports/vendor/request', [ReportCodeRequestController::class, 'index']);
		Route::post('reports/vendor/request', [ReportCodeRequestController::class, 'view']);

		Route::get('reports/vendor/registration', [ReportVendorRegistrationController::class, 'index']);
		Route::post('reports/vendor/registration', [ReportVendorRegistrationController::class, 'view']);

		Route::get('reports/vendor/registration-list', [ReportVendorRegistrationListController::class, 'index']);
		Route::post('reports/vendor/registration-list', [ReportVendorRegistrationListController::class, 'view']);

		Route::get('reports/staff/activity', [ReportStaffActivityController::class, 'index']);
		Route::post('reports/staff/activity', [ReportStaffActivityController::class, 'view']);

		Route::get('reports/code/district', [ReportCodeDistrictController::class, 'index']);
		Route::post('reports/code/district', [ReportCodeDistrictController::class, 'view']);

		Route::get('reports/vendor/transaction', [ReportVendorTransactionController::class, 'index']);
		Route::post('reports/vendor/transaction', [ReportVendorTransactionController::class, 'view']);

		Route::get('reports/transaction/hasil', [ReportTransactionByHasilController::class, 'index']);
		Route::post('reports/transaction/hasil', [ReportTransactionByHasilController::class, 'view']);

		/* end by zayid */

		Route::get('reports/vendor/codes', [ReportVendorCodeController::class, 'index']);
		Route::post('reports/vendor/codes', [ReportVendorCodeController::class, 'view']);
		Route::get('reports/vendor/codes/excel', [ReportVendorCodeController::class, 'excel']);

		Route::get('reports/user/agency', [ReportUserAgencyController::class, 'index']);
		Route::post('reports/user/agency', [ReportUserAgencyController::class, 'view']);
		Route::get('reports/user/agency/excel', [ReportUserAgencyController::class, 'excel']);

		Route::get('reports/user/active', [ReportUserActiveController::class, 'index']);
		Route::post('reports/user/active', [ReportUserActiveController::class, 'view']);
		Route::get('reports/user/active/excel', [ReportUserActiveController::class, 'excel']);

		Route::get('reports/vendor/district', [ReportVendorDistrictController::class, 'index']);
		Route::get('reports/vendor/district/view', [ReportVendorDistrictController::class, 'view']);
		Route::get('reports/vendor/district/excel', [ReportVendorDistrictController::class, 'excel']);

		Route::get('reports/user/activity', [ReportUserActivityController::class, 'index']);
		Route::post('reports/user/activity', [ReportUserActivityController::class, 'view']);
		Route::get('reports/user/activity/excel', [ReportUserActivityController::class, 'excel']);

		Route::get('reports/user/login', [ReportUserLoginController::class, 'index']);
		Route::post('reports/user/login', [ReportUserLoginController::class, 'view']);
		Route::get('reports/user/login/excel', [ReportUserLoginController::class, 'excel']);

		// Circular
		Route::resource('circulars', CircularController::class)->except(['show', 'destroy']);
		Route::get('circulars/{id}/publish', [CircularController::class, 'publish'])->name('circulars.publish');
		Route::get('circulars/list', [CircularController::class, 'public'])->name('circulars.public');
		Route::get('circulars/sort', [CircularController::class, 'sortPosition'])->name('circulars.position');
		Route::post('circulars/sort', [CircularController::class, 'updatePosition'])->name('circulars.update.position');

		// Version histories (CRUD – admin)
		Route::resource('version-histories', VersionHistoryController::class)->names([
			'index' => 'version-histories.index',
			'create' => 'version-histories.create',
			'store' => 'version-histories.store',
			'show' => 'version-histories.show',
			'edit' => 'version-histories.edit',
			'update' => 'version-histories.update',
			'destroy' => 'version-histories.destroy',
		]);

		// Refunds
		Route::prefix('refunds')->group(function () {
			Route::post('get-transaction', [RefundController::class, 'fetch_transactions'])->name('get_transaction');
			Route::post('get-refund', [RefundController::class, 'get_refund_details'])->name('get_refund_details');
			// Route::get('create', [RefundController::class, 'create'])->name('refunds.create');
			// Route::post('store', [RefundController::class, 'store'])->name('refunds.store');
			// Route::get('{refund}/show', [RefundController::class, 'show'])->name('refunds.show');
			// Route::get('{refund}/edit', [RefundController::class, 'edit'])->name('refunds.edit');
			// Route::post('{refund}/update', [RefundController::class, 'update'])->name('refunds.update');

			Route::prefix('request')->group(function () {
				Route::get('/', [RefundController::class, 'index_request'])->name('refunds.request.index');
				Route::get('new', [RefundController::class, 'pendingRefundRequestIndex']);
				Route::get('process', [RefundController::class, 'processRefundRequestIndex']);
				Route::get('reject', [RefundController::class, 'rejectRefundRequestIndex']);
				Route::get('{refund}/show', [RefundController::class, 'show_request'])->name('refunds.request.show');
				Route::post('{refund}/reject', [RefundController::class, 'reject_request'])->name('refunds.request.reject');
				Route::get('{refund}/approve', [RefundController::class, 'approve_request'])->name('refunds.request.approve');
			});

			Route::prefix('complaint')->group(function () {
				Route::get('/', [RefundController::class, 'index_complaint'])->name('refunds.complaint.index');
				Route::get('new', [RefundController::class, 'pendingRefundComplaintIndex']);
				Route::get('reject', [RefundController::class, 'rejectRefundComplaintIndex']);
				Route::get('{refund}/show', [RefundController::class, 'show_complaint'])->name('refunds.complaint.show');
				Route::post('{refund}/reject', [RefundController::class, 'reject_complaint'])->name('refunds.complaint.reject');
				Route::get('{refund}/approve', [RefundController::class, 'approve_complaint'])->name('refunds.complaint.approve');
			});
		});

		// Complaint/Aduan
		Route::get('aduan', [ComplaintController::class, 'create'])->name('aduan.create');
		Route::post('aduan', [ComplaintController::class, 'store'])->name('aduan.store');
		Route::get('aduan/list', [ComplaintController::class, 'index'])->name('aduan.index');
		Route::get('aduan/{id}', [ComplaintController::class, 'show'])->name('aduan.show');
		Route::post('aduan/{id}/reply', [ComplaintController::class, 'reply'])->name('aduan.reply');
		Route::get('aduan/{id}/{status}', [ComplaintController::class, 'updateStatus'])->name('aduan.update.status');

		// BotMan
		Route::match(['get', 'post'], 'botman', [BotManController::class, 'handle'])->withoutMiddleware(['auth'])->name('botman');
		Route::get('chat-widget/{chat_id}', [BotManController::class, 'chatWidget'])->withoutMiddleware(['auth'])->name('chat_widget');

		// API Token
		Route::get('apitoken', [ApiTokenController::class, 'index'])->name('apitoken.index');
		Route::get('apitoken/create', [ApiTokenController::class, 'create'])->name('apitoken.create');
		Route::post('apitoken/store', [ApiTokenController::class, 'store'])->name('apitoken.store');
		Route::post('apitoken/generate', [ApiTokenController::class, 'generateToken'])->name('apitoken.generate');
		Route::post('apitoken/{client}/regenerate', [ApiTokenController::class, 'regenerate'])->name('apitoken.regenerate');
		Route::post('apitoken/{client}/revoke', [ApiTokenController::class, 'revoke'])->name('apitoken.revoke');

		// Email SMTP Manager
		Route::group(['as' => 'mail-manager.', 'prefix' => 'mail-manager'], function () {
			Route::resource('smtp-setting', SmtpMailController::class);
			Route::resource('mail-queue', MailQueueController::class);
		});

		// Chatbot Manager
		Route::group(['as' => 'chatbot-manager.', 'prefix' => 'chatbot-manager'], function () {
			Route::resource('category', FaqCategoryController::class);
			Route::resource('question', FaqController::class);
			Route::resource('chatlog', FaqLogController::class);
			Route::resource('newquestion', CustomerQuestionController::class);
		});

		// Reject Template
		Route::resource('reject-template', RejectTemplateController::class);
	});

	// Laporan Transaksi Syarikat (vendor-accessible — duplicated outside Admin group)
	Route::get('reports/vendor/summary/{year}/{vendor_id}', [ReportVendorSummaryController::class, 'index'])->name('vendor.report.vendor.summary');

	// Refunds (vendor-accessible — duplicated outside Admin group)
	Route::prefix('refunds')->group(function () {
		Route::get('create', [RefundController::class, 'create'])->name('refunds.create');
		Route::post('store', [RefundController::class, 'store'])->name('refunds.store');
		Route::get('{refund}/show', [RefundController::class, 'show'])->name('refunds.show');
		Route::get('{refund}/edit', [RefundController::class, 'edit'])->name('refunds.edit');
		Route::post('{refund}/update', [RefundController::class, 'update'])->name('refunds.update');
	});
});
