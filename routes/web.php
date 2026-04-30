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
use App\Http\Controllers\FpxController;
use App\Http\Controllers\EbpgController;
use App\Http\Controllers\DuitNowController;
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
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\CertificationCodesController;
use App\Http\Controllers\GatewaysController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\HelpCategoriesController;
use App\Http\Controllers\PembelianTerusController;
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
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\CutOffController;
use App\Http\Controllers\JawatankuasaPerolehanController;
use App\Http\Controllers\PenilaianKewanganController;
use App\Http\Controllers\PenilaianTeknikalController;
use App\Http\Controllers\PerakuanJabatanController;
use App\Http\Controllers\EbiddingController;

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
Route::get('/cut-off/{tender_no}', [CutOffController::class, 'show'])->middleware(['auth'])->name('cutOff.show');

Route::get('/perakuan-jabatan', [PerakuanJabatanController::class, 'index'])->middleware(['auth'])->name('perakuanjabatan.index');
Route::get('/perakuan-jabatan/{tender_no}', [PerakuanJabatanController::class, 'show'])->middleware(['auth'])->name('perakuanjabatan.show');
Route::view('/perakuan-jabatan/form', 'newModule.perakuanJabatan.form')->name('perakuanJabatan.form');
Route::view('/perakuan-jabatan/kertas-taklimat', 'newModule.perakuanJabatan.kertas_taklimat')->name('perakuanJabatan.kertasTaklimat');
Route::view('/perakuan-jabatan/pengesyoran-pembekal', 'newModule.perakuanJabatan.pengesyoran_pembekal')->name('perakuanJabatan.pengesyoranPembekal');

Route::get('/eBidding/index', [EbiddingController::class, 'index'])->middleware(['auth'])->name('eBidding.index');
Route::view('/keputusan-mesyuarat', 'newModule.eBidding.keptusan_mesyuarat')->name('keputusanMesyuarat');

Route::prefix('pembelian-terus')->controller(PembelianTerusController::class)->group(function () {
	Route::get('/cipta-projek', 'createProject')->name('pembelianTerus.createProject');
	Route::get('/sebut-harga', 'quoteProject')->name('pembelianTerus.quoteProject');
	Route::get('/maklumat-projek/{tender_no}', 'detailProject')->name('pembelianTerus.detailProject');
	Route::get('/cut-off-projek', 'cutOffProject')->name('pembelianTerus.cutOffProject');
	Route::get('/cut-off-details/{tender_no}', 'cutOffDetails')->name('pembelianTerus.cutOffDetails');
	Route::get('/pemilihan-syarikat', 'pemilihanSyarikat')->name('pembelianTerus.pemilihanSyarikat');
	Route::get('/pemilihan-syarikat-details/{tender_no}', 'pemilihanSyarikatDetails')->name('pembelianTerus.pemilihanSyarikatDetails');
	Route::get('/keputusan-syarikat', 'keputusanSyarikat')->name('pembelianTerus.keputusanSyarikat');
	Route::get('/keputusan-syarikat-details/{tender_no}', 'keputusanSyarikatDetails')->name('pembelianTerus.keputusanSyarikatDetails');
	Route::get('/surat-setuju-terima/{tender_no}', 'downloadSuratSetujuTerima')->name('pembelianTerus.downloadSuratSetujuTerima');
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
Route::get('tenders/{id}/prices', [TendersController::class, 'prices'])->name('tenders.prices');
Route::get('tenders/{tender_id}/files/{id}', [TendersController::class, 'file'])->name('tenders.files');
Route::get('tenders/{id}/vendors', [TendersController::class, 'vendors'])->name('tenders.vendors');
Route::post('tenders/{id}/exception', [TendersController::class, 'exception'])->name('tenders.exception');

// Petender Performance
Route::prefix('petenders')->controller(PetenderPerformanceController::class)->group(function () {
	Route::post('petender-performance/{tender}/{vendor}', 'store')->name('store.PetenderPerformance');
	Route::get('{tender}', 'vendorPetender')->name('index.TenderVendor');
});

// News
Route::resource('news', NewsController::class);

// Contact
Route::get('contact', [HomeController::class, 'contact']);

// Payment
Route::post('payment/fpx/listen', [FpxController::class, 'listen'])->name('fpx.listen');

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

Route::get('/pelantikan-jawatankuasa', [JawatankuasaController::class, 'create'])->middleware(['auth'])->name('pelantikanJawatankuasa');
Route::get('/pelantikan-jawatankuasa/laporan', [JawatankuasaController::class, 'laporan'])->middleware(['auth'])->name('jawatankuasa.laporan');
Route::post('/pelantikan-jawatankuasa/hantar-pemakluman', [JawatankuasaController::class, 'hantarPemakluman'])->middleware(['auth'])->name('jawatankuasa.hantarPemakluman');
Route::view('/senarai-semak', 'newModule.jawatankuasaSpesifikasi.index')->name('senaraiSemak');

Route::view('/senarai-kewangan-bekalan', 'newModule.jawatankuasaSpesifikasi.senarai_kewangan_bekalan')->name('senaraiKewanganBekalan');
Route::post('/senarai-kewangan-bekalan/hantar', [JawatankuasaController::class, 'simpanSenaraiKewanganBekalan'])->name('jawatankuasa.simpanSenaraiKewanganBekalan');
Route::view('/senarai-kewangan-kerja', 'newModule.jawatankuasaSpesifikasi.senarai_kewangan_kerja')->name('senaraiKewanganKerja');
Route::post('/senarai-kewangan-kerja/hantar', [JawatankuasaController::class, 'simpanSenaraiKewanganKerja'])->name('jawatankuasa.simpanSenaraiKewanganKerja');
Route::view('/penyediaan-spesifikasi-tender', 'newModule.jawatankuasaSpesifikasi.form_penyediaan_spesifikasi_tender')->name('penyediaanSpekTender');
Route::view('/lembaran-imbangan', 'newModule.jawatankuasaSpesifikasi.form_lembaran_imbangan')->name('lembaranImbangan');
Route::view('/bon-atau-saham', 'newModule.jawatankuasaSpesifikasi.form_bon_saham')->name('bonAtauSaham');
Route::post('/bon-atau-saham/submit', [JawatankuasaController::class, 'storeBonSaham'])->middleware(['auth'])->name('jawatankuasa.hantarBonSaham');
Route::view('/prestasi-kerja-semasa-petender', 'newModule.jawatankuasaSpesifikasi.form_prestasi_kerja_semasa_petender')->name('prestasiKerjaSemasa');
Route::post('/prestasi-kerja-semasa-petender/submit', [JawatankuasaController::class, 'storePrestasiKerjaSemasa'])->middleware(['auth'])->name('jawatankuasa.hantarPrestasiKerjaSemasa');
Route::post('/pengalaman-kerja/simpan', [JawatankuasaController::class, 'simpanPengalamanKerja'])->name('jawatankuasa.simpanPengalamanKerja');
Route::view('/kerja-dalam-tangan', 'newModule.jawatankuasaSpesifikasi.form_kerja_dalam_tangan')->name('kjDlmTanganForm');
Route::post('/kerja-dalam-tangan/simpan', [JawatankuasaController::class, 'simpanKerjaDalamTangan'])->name('jawatankuasa.simpanKerjaDalamTangan');
Route::get('/spesifikasi-kewangan', [JawatankuasaController::class, 'spesifikasiKewanganBekalan'])->name('spesifikasiFormKewanganBekalan');
Route::view('/profil-petender', 'newModule.jawatankuasaSpesifikasi.form_profil_petender')->name('prflPetender');
Route::post('/profile-petender/submit', [JawatankuasaController::class, 'storeProfilPetender'])->middleware(['auth'])->name('jawatankuasa.hantarProfilPetender');
Route::view('/penyata-bank', 'newModule.jawatankuasaSpesifikasi.form_penyata_bank')->name('pnytBank');
Route::post('/penyata-bank/submit', [JawatankuasaController::class, 'storePenyataKewangan'])->middleware(['auth'])->name('jawatankuasa.hantarPenyataKewangan');
Route::view('/jawatankuasa-pembuka', 'newModule.jawatankuasa_pembuka')->name('jawatankuasaPembuka');

// penilaian teknikal
Route::get('/penilaian-teknikal', [PenilaianTeknikalController::class, 'index'])->name('penilaianTeknikal');
Route::get('/penilaian-teknikal/{tender_no}', [PenilaianTeknikalController::class, 'show'])->name('penilaianTeknikal.show');

// new penilaian kewangan
Route::get('/penilaian-kewangan', [PenilaianKewanganController::class, 'index'])->name('penilaianKewangan');
Route::get('/penilaian-kewangan/{tender_no}', [PenilaianKewanganController::class, 'show'])->name('penilaianKewangan.show');


Route::view('/penilaian-kewangan-kerja', 'newModule.penilaian.borang1')->name('borang1');
Route::view('/penilaian-kewangan-kerja-borang2', 'newModule.penilaian.borang2')->name('borang2');
Route::view('/penilaian-kewangan-kerja-borang3', 'newModule.penilaian.borang3')->name('borang3');
Route::view('/penilaian-kewangan-kerja-lembaran', 'newModule.penilaian.lembaran')->name('lembaran');
Route::view('/penilaian-kewangan-kerja-akaun-bank', 'newModule.penilaian.akaun_bank')->name('akaunBank');
Route::view('/penilaian-kewangan-kerja-bon-saham', 'newModule.penilaian.bon_saham')->name('bonSaham');
Route::view('/penilaian-kewangan-kerja-borang4', 'newModule.penilaian.borang4')->name('borang4');
Route::view('/penilaian-kewangan-kerja-borang5', 'newModule.penilaian.borang5')->name('borang5');
Route::view('/penilaian-kewangan-kerja-borang6', 'newModule.penilaian.borang6')->name('borang6');
Route::view('/penilaian-kewangan-kerja-borang7-serupa', 'newModule.penilaian.borang7_serupa')->name('serupa');
Route::view('/penilaian-kewangan-kerja-borang7-sebanding', 'newModule.penilaian.borang7_sebanding')->name('sebanding');
Route::view('/penilaian-kewangan-kerja-borang8', 'newModule.penilaian.borang8')->name('borang8');
Route::view('/penilaian-kewangan-kerja-borang9', 'newModule.penilaian.borang9')->name('borang9');
Route::view('/penilaian-kewangan-kerja-borang9-serupa', 'newModule.penilaian.borang9_serupa')->name('kerjaSerupa');
Route::view('/penilaian-kewangan-kerja-borang9-sebanding', 'newModule.penilaian.borang9_sebanding')->name('kerjaSebanding');
Route::view('/penilaian-kewangan-kerja-borang10', 'newModule.penilaian.borang10')->name('borang10');
Route::view('/penilaian-kewangan-kerja-borang11', 'newModule.penilaian.borang11')->name('borang11');
Route::view('/penilaian-kewangan-kerja-borang12', 'newModule.penilaian.borang12')->name('borang12');
Route::view('/penilaian-kewangan-kerja-borang13', 'newModule.penilaian.borang13')->name('borang13');
Route::view('/penilaian-kewangan-kerja-borang14', 'newModule.penilaian.borang14')->name('borang14');
Route::view('/penilaian-kewangan-kerja-borang15', 'newModule.penilaian.borang15')->name('borang15');


Route::view('/lawatan-tapak', 'newModule.syarikatPembekal.lawatan_tapak')->name('lawatanTapak');

Route::middleware('auth')->group(function () {
	Route::get('/visits/{visit}/representatives', 'TenderVisitRepresentativeController@index')
		->name('visits.representatives.index');
	Route::post('/visits/{visit}/representatives', 'TenderVisitRepresentativeController@store')
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
Route::middleware(['auth'])->group(function () 
{
	Route::get('/senarai-teknikal/{tenderUuid}', [TechnicalChecklistController::class, 'index'])->name('senaraiTeknikal');
	Route::post('/senarai-teknikal/{tenderUuid}', [TechnicalChecklistController::class, 'store'])->name('senaraiTeknikal.store');
	Route::post('/senarai-teknikal/{tenderUuid}/hantar', [TechnicalChecklistController::class, 'submit'])->name('senaraiTeknikal.submit');
	Route::post('/senarai-teknikal/{tenderUuid}/fail', [TechnicalChecklistController::class, 'uploadFile'])->name('senaraiTeknikal.uploadFile');
	Route::delete('/senarai-teknikal/fail/{fileUuid}', [TechnicalChecklistController::class, 'deleteFile'])->name('senaraiTeknikal.deleteFile');
	Route::post('/senarai-teknikal/{tenderUuid}/pengalaman-kerja', [PengalamanKerjaController::class, 'store'])->name('senaraiTeknikal.pengalamanKerja.store');
	Route::delete('/pengalaman-kerja-files/{fileUuid}', [PengalamanKerjaController::class, 'deleteFile'])->name('pengalamanKerja.deleteFile');
	Route::get('/senarai-teknikal/templat-spesifikasi/{tenderUuid}/{documentUuid?}', [TechnicalSpecificationController::class, 'create'])->name('spesifikasiForm');
	Route::get('/standard-checklist-items', [StandardChecklistItemController::class, 'index'])->name('standardChecklistItems.index');
	Route::get('/spesifikasi-teknikal', [TechnicalSpecificationController::class, 'index'])->name('spesifikasiTeknikal.index');
	Route::post('/spesifikasi-teknikal', [TechnicalSpecificationController::class, 'store'])->name('spesifikasiTeknikal.store');
	Route::get('/spesifikasi-teknikal/{uuid}', [TechnicalSpecificationController::class, 'show'])->name('spesifikasiTeknikal.show');
	Route::put('/spesifikasi-teknikal/{uuid}', [TechnicalSpecificationController::class, 'update'])->name('spesifikasiTeknikal.update');
	Route::post('/spesifikasi-teknikal/{uuid}/lengkap', [TechnicalSpecificationController::class, 'complete'])->name('spesifikasiTeknikal.complete');
	Route::delete('/spesifikasi-teknikal/{uuid}', [TechnicalSpecificationController::class, 'destroy'])->name('spesifikasiTeknikal.destroy');
	Route::get('/senarai-teknikal/{tenderUuid}/pengalaman-kerja', [PengalamanKerjaController::class, 'create'])->name('senaraiTeknikal.pengalamanKerja.tender');
	Route::view('/senarai-teknikal/kerja-dalam-tangan', 'newModule.jawatankuasaSpesifikasi.form_kerja_dalam_tangan')->name('senaraiTeknikal.kerjaDalamTangan');

	// Route::get('tender/select', [TendersController::class, 'select']);
	Route::resource('tender', TendersController::class);
	Route::resource('jawatankuasa', JawatankuasaController::class);
	// Route::get('tender/{id}/prices', [TendersController::class, 'prices'])->name('tenders.prices');
	// Route::get('tender/{tender_id}/files/{id}', [TendersController::class, 'file'])->name('tenders.files');
	// Route::get('tender/{id}/vendors', [TendersController::class, 'vendors'])->name('tenders.vendors');
	// Route::post('tender/{id}/exception', [TendersController::class, 'exception'])->name('tenders.exception');

	Route::get('/pengurusan-spesifikasi', [TendersController::class, 'manageSpecification'])->name('pengurusanSpesifikasi');

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

	// Admin routes
	Route::middleware(['role:Admin'])->group(function () {
		Route::get('dashboard/hq', [HomeController::class, 'managementDashboard'])->name('dashboard.hq');

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

		// Payment routes
		Route::get('payment/fpx/connect', [FpxController::class, 'connect'])->name('fpx.connect');
		Route::post('payment/fpx/respond', [FpxController::class, 'respond'])->name('fpx.respond');
		Route::get('payment/fpx/bank-list', [FpxController::class, 'bankList'])->name('fpx.bank-list');
		Route::get('payment/ebpg/connect', [EbpgController::class, 'connect'])->name('ebpg.connect');
		Route::post('payment/ebpg/respond', [EbpgController::class, 'respond'])->name('ebpg.respond');
		Route::get('payment/duitnow/connect', [DuitNowController::class, 'connect'])->name('duitnow.connect');
		Route::post('payment/duitnow/respond', [DuitNowController::class, 'respond'])->name('duitnow.respond');
		Route::post('payment/duitnow/callback', [DuitNowController::class, 'callback'])->name('duitnow.callback');

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

		// Exception
		Route::post('tenders/exception/store', [TendersController::class, 'storeException'])->name('tender.store.exception');
		Route::get('tenders/{id}/exceptions', [TendersController::class, 'exceptions'])->name('tender.exceptions');
		Route::get('tenders/{id}/approve', [TendersController::class, 'approve_exception'])->name('tender.approve.exception');
		Route::post('tenders/{id}/reject/{exception_id}', [TendersController::class, 'reject_exception'])->name('tender.reject.exception');

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
			Route::get('create', [RefundController::class, 'create'])->name('refunds.create');
			Route::post('store', [RefundController::class, 'store'])->name('refunds.store');
			Route::get('{refund}/show', [RefundController::class, 'show'])->name('refunds.show');
			Route::get('{refund}/edit', [RefundController::class, 'edit'])->name('refunds.edit');
			Route::post('{refund}/update', [RefundController::class, 'update'])->name('refunds.update');

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
});
