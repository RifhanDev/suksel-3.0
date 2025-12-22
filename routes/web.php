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


// Basic routes to get the application running
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('prices', [HomeController::class, 'prices']);
Route::get('results', [HomeController::class, 'results']);
Route::get('privacy', [HomeController::class, 'privacy']);

// Place 3.0 Modules Routes Temporarily Here
Route::view('/cipta-tender', 'newModule.cipta_tender')->name('ciptaTender');
Route::view('/pelantikan-jawatankuasa', 'newModule.pelantikan_jawatankuasa')->name('pelantikanJawatankuasa');
Route::view('/jawatankuasa-spesifikasi/senarai-teknikal', 'newModule.jawatankuasaSpesifikasi.senarai_teknikal')->name('jawatankuasaSpesifikasi.teknikal');
Route::view('/jawatankuasa-spesifikasi/senarai-kewangan', 'newModule.jawatankuasaSpesifikasi.senarai_kewangan')->name('jawatankuasaSpesifikasi.kewangan');

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
Route::get('auth/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('auth/confirm/{registration_code}', [AuthController::class, 'confirm']);
Route::get('auth/forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('auth/forgot_password', [AuthController::class, 'doForgotPassword']);
Route::get('auth/reset/{token}', [AuthController::class, 'resetPassword']);
Route::post('auth/reset', [AuthController::class, 'doResetPassword']);

// Tenders
Route::get('tenders/select', [TendersController::class, 'select']);
Route::resource('tenders', TendersController::class);
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
Route::view('/pelantikan-jawatankuasa', 'newModule.pelantikan_jawatankuasa')->name('pelantikanJawatankuasa');
Route::view('/senarai-teknikal', 'newModule.jawatankuasaSpesifikasi.senarai_teknikal')->name('senaraiTeknikal');
Route::view('/senarai-kewangan', 'newModule.jawatankuasaSpesifikasi.senarai_kewangan')->name('senaraiKewangan');
Route::view('/jawatankuasa-pembuka', 'newModule.jawatankuasa_pembuka')->name('jawatankuasaPembuka');
Route::view('/penilaian-teknikal', 'newModule.penilaian.teknikal')->name('penilaianTeknikal');
Route::view('/penilaian-kewangan', 'newModule.penilaian.kewangan')->name('penilaianKewangan');


// Protected routes
Route::middleware(['auth'])->group(function () {
	// Route::get('tender/select', [TendersController::class, 'select']);
	Route::resource('tender', TendersController::class);
	// Route::get('tender/{id}/prices', [TendersController::class, 'prices'])->name('tenders.prices');
	// Route::get('tender/{tender_id}/files/{id}', [TendersController::class, 'file'])->name('tenders.files');
	// Route::get('tender/{id}/vendors', [TendersController::class, 'vendors'])->name('tenders.vendors');
	// Route::post('tender/{id}/exception', [TendersController::class, 'exception'])->name('tenders.exception');

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
	Route::get('profile/release', [ProfileController::class, 'releaseUser'])->name('release_user');

	// Dashboard fetch routes
	Route::post('dashboard/fetch/tender', [HomeController::class, 'tender_dashboard'])->name('dashboard.tender');
	Route::post('dashboard/fetch/tender-summary', [HomeController::class, 'tender_summary_dashboard'])->name('dashboard.tender.summary');
	Route::post('dashboard/fetch/transaction', [HomeController::class, 'transaction_dashboard'])->name('dashboard.transaction');
	Route::post('dashboard/fetch/transaction-value', [HomeController::class, 'transaction_value_dashboard'])->name('dashboard.transaction-value');
	Route::post('dashboard/fetch/transaction-summary', [HomeController::class, 'transaction_summary_dashboard'])->name('dashboard.transaction.summary');
	Route::post('dashboard/fetch/transaction-value-summary', [HomeController::class, 'transaction_value_summary_dashboard'])->name('dashboard.transaction-value.summary');

	// Version histories
	Route::get('version-histories', [HomeController::class, 'versionHistories'])->name('version-histories');

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
