<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\PetenderPerformanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\OrganizationUnitsController;
use App\Http\Controllers\HelpsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TendersController;
use App\Http\Controllers\FpxController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\VendorBlacklistsController;
use App\Http\Controllers\CodeRequestsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmtpMailsController;
use App\Http\Controllers\TenderCategoriesController;
use App\Http\Controllers\TenderTypesController;
use App\Http\Controllers\OrganizationUnitTypesController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\TenderStatusesController;
use App\Http\Controllers\TenderSubmissionsController;
use App\Http\Controllers\TenderVendorsController;
use App\Http\Controllers\TenderInvitesController;
use App\Http\Controllers\TenderFilesController;
use App\Http\Controllers\TenderCodesController;
use App\Http\Controllers\TenderExceptionsController;
use App\Http\Controllers\TenderPricesController;
use App\Http\Controllers\TenderWinnersController;
use App\Http\Controllers\TenderCommentsController;
use App\Http\Controllers\TenderReportsController;
use App\Http\Controllers\TenderAnalyticsController;
use App\Http\Controllers\TenderNotificationsController;
use App\Http\Controllers\TenderAuditController;
use App\Http\Controllers\TenderExportController;
use App\Http\Controllers\TenderImportController;
use App\Http\Controllers\TenderBackupController;
use App\Http\Controllers\TenderRestoreController;
use App\Http\Controllers\TenderArchiveController;
use App\Http\Controllers\TenderUnarchiveController;
use App\Http\Controllers\TenderCloneController;
use App\Http\Controllers\TenderTemplateController;
use App\Http\Controllers\TenderWorkflowController;
use App\Http\Controllers\TenderApprovalController;
use App\Http\Controllers\TenderRejectionController;
use App\Http\Controllers\TenderRevisionController;
use App\Http\Controllers\TenderVersionController;
use App\Http\Controllers\TenderHistoryController;
use App\Http\Controllers\TenderLogController;
use App\Http\Controllers\TenderActivityController;
use App\Http\Controllers\TenderEventController;
use App\Http\Controllers\TenderReminderController;
use App\Http\Controllers\TenderAlertController;
use App\Http\Controllers\TenderNotificationController;
use App\Http\Controllers\TenderEmailController;
use App\Http\Controllers\TenderSmsController;
use App\Http\Controllers\TenderPushController;
use App\Http\Controllers\TenderWebhookController;
use App\Http\Controllers\TenderApiController;
use App\Http\Controllers\TenderIntegrationController;
use App\Http\Controllers\TenderSyncController;
use App\Http\Controllers\TenderMigrationController;
use App\Http\Controllers\TenderUpgradeController;
use App\Http\Controllers\TenderMaintenanceController;
use App\Http\Controllers\TenderHealthController;
use App\Http\Controllers\TenderStatusController;
use App\Http\Controllers\TenderMonitorController;
use App\Http\Controllers\TenderDashboardController;
use App\Http\Controllers\TenderAnalyticsController;
use App\Http\Controllers\TenderReportController;
use App\Http\Controllers\TenderChartController;
use App\Http\Controllers\TenderGraphController;
use App\Http\Controllers\TenderMetricController;
use App\Http\Controllers\TenderKpiController;
use App\Http\Controllers\TenderPerformanceController;
use App\Http\Controllers\TenderBenchmarkController;
use App\Http\Controllers\TenderComparisonController;
use App\Http\Controllers\TenderTrendController;
use App\Http\Controllers\TenderForecastController;
use App\Http\Controllers\TenderPredictionController;
use App\Http\Controllers\TenderRecommendationController;
use App\Http\Controllers\TenderSuggestionController;
use App\Http\Controllers\TenderAdviceController;
use App\Http\Controllers\TenderTipController;
use App\Http\Controllers\TenderGuideController;
use App\Http\Controllers\TenderTutorialController;
use App\Http\Controllers\TenderHelpController;
use App\Http\Controllers\TenderFaqController;
use App\Http\Controllers\TenderSupportController;
use App\Http\Controllers\TenderContactController;
use App\Http\Controllers\TenderFeedbackController;
use App\Http\Controllers\TenderReviewController;
use App\Http\Controllers\TenderRatingController;
use App\Http\Controllers\TenderCommentController;
use App\Http\Controllers\TenderLikeController;
use App\Http\Controllers\TenderShareController;
use App\Http\Controllers\TenderBookmarkController;
use App\Http\Controllers\TenderFavoriteController;
use App\Http\Controllers\TenderWatchController;
use App\Http\Controllers\TenderSubscribeController;
use App\Http\Controllers\TenderFollowController;
use App\Http\Controllers\TenderTrackController;
use App\Http\Controllers\TenderMonitorController;
use App\Http\Controllers\TenderAlertController;
use App\Http\Controllers\TenderNotificationController;
use App\Http\Controllers\TenderReminderController;
use App\Http\Controllers\TenderEventController;
use App\Http\Controllers\TenderActivityController;
use App\Http\Controllers\TenderLogController;
use App\Http\Controllers\TenderHistoryController;
use App\Http\Controllers\TenderVersionController;
use App\Http\Controllers\TenderRevisionController;
use App\Http\Controllers\TenderRejectionController;
use App\Http\Controllers\TenderApprovalController;
use App\Http\Controllers\TenderWorkflowController;
use App\Http\Controllers\TenderTemplateController;
use App\Http\Controllers\TenderCloneController;
use App\Http\Controllers\TenderUnarchiveController;
use App\Http\Controllers\TenderArchiveController;
use App\Http\Controllers\TenderRestoreController;
use App\Http\Controllers\TenderBackupController;
use App\Http\Controllers\TenderImportController;
use App\Http\Controllers\TenderExportController;
use App\Http\Controllers\TenderAuditController;
use App\Http\Controllers\TenderNotificationsController;
use App\Http\Controllers\TenderAnalyticsController;
use App\Http\Controllers\TenderReportsController;
use App\Http\Controllers\TenderCommentsController;
use App\Http\Controllers\TenderWinnersController;
use App\Http\Controllers\TenderPricesController;
use App\Http\Controllers\TenderExceptionsController;
use App\Http\Controllers\TenderCodesController;
use App\Http\Controllers\TenderFilesController;
use App\Http\Controllers\TenderInvitesController;
use App\Http\Controllers\TenderVendorsController;
use App\Http\Controllers\TenderSubmissionsController;
use App\Http\Controllers\TenderStatusesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\OrganizationUnitTypesController;
use App\Http\Controllers\TenderTypesController;
use App\Http\Controllers\TenderCategoriesController;
use App\Http\Controllers\SmtpMailsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CodeRequestsController;
use App\Http\Controllers\VendorBlacklistsController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\FpxController;
use App\Http\Controllers\TendersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\HelpsController;
use App\Http\Controllers\OrganizationUnitsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Models\SmtpMails;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('prices', [App\Http\Controllers\HomeController::class, 'prices']);
Route::get('results', [App\Http\Controllers\HomeController::class, 'results']);
Route::get('privacy', [App\Http\Controllers\HomeController::class, 'privacy']);

// Public resources
Route::resource('comments', [CommentsController::class]);
Route::get('/agencies/{id}/prices', [OrganizationUnitsController::class, 'prices']);
Route::get('/agencies/{id}/results', [OrganizationUnitsController::class, 'results']);
Route::get('/agencies/{id}/news', [OrganizationUnitsController::class, 'news']);
Route::get('/agencies/{id}/report/{tender}', [OrganizationUnitsController::class, 'report']);
Route::resource('agencies', [OrganizationUnitsController::class]);
Route::get('helps/search', [HelpsController::class, 'search']);
Route::resource('helps', [HelpsController::class]);
Route::resource('manuals', [ManualsController::class]);

Route::get('company_search', [HomeController::class, 'companySearch']);
Route::post('company_search', [HomeController::class, 'doCompanySearch']);

Route::get('change_email', [HomeController::class, 'changeEmail']);
Route::post('change_email', [HomeController::class, 'doChangeEmail']);
Route::get('change_email/{token}', ['uses' => [HomeController::class, 'verifyChangeEmail'], 'as' => 'verify_change_email']);

// Registration routes
Route::get('register', ['as' => 'registration', 'uses' => [RegistrationController::class, 'register']]);
Route::post('register', [RegistrationController::class, 'storeRegister']);
Route::get('register-user', ['as' => 'registration-user', 'uses' => [RegistrationController::class, 'registerUser']]);
Route::post('register-user', [RegistrationController::class, 'storeRegisterUser']);

// Confide routes
Route::post('auth/login', [AuthController::class, 'doLogin']);
Route::get('auth/logout', ['as' => 'logout', 'uses' => [AuthController::class, 'logout']]);
Route::get('auth/confirm/{registration_code}', [AuthController::class, 'confirm']);
Route::get('auth/forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('auth/forgot_password', [AuthController::class, 'doForgotPassword']);
Route::get('auth/reset/{token}', [AuthController::class, 'resetPassword']);
Route::post('auth/reset', [AuthController::class, 'doResetPassword']);

// Tenders
Route::get('tenders/select', [TendersController::class, 'select']);
Route::resource('tenders', [TendersController::class]);
Route::get('tenders/{id}/prices', ['as' => 'tenders.prices', 'uses' => [TendersController::class, 'prices']]);
Route::get('tenders/{tender_id}/files/{id}', ['as' => 'tenders.files', 'uses' => [TendersController::class, 'file']]);
Route::get('tenders/{id}/vendors', ['as' => 'tenders.vendors', 'uses' => [TendersController::class, 'vendors']]);
Route::post('tenders/{id}/exception', ['as' => 'tenders.exception', 'uses' => [TendersController::class, 'exception']]);

// Petender Performance
Route::prefix('/petenders']->controller(PetenderPerformanceController::class)->group(function () {
	Route::post('/petender-performance/{tender}/{vendor}', 'store']->name('store.PetenderPerformance'];
	Route::get('/{tender}', 'vendorPetender']->name('index.TenderVendor'];
});

// News
Route::resource('news', [NewsController::class]);
// Contact
Route::get('contact', [HomeController::class, 'contact'];
//Route::post('contact', [HomeController::class, 'doContact'];

Route::post('payment/fpx/listen', ['as' => 'fpx.listen', 'uses' => [FpxController::class, listen']);

Route::post('transactions/{id}/ebpg_requery', ['as' => 'transactions.ebpg_requery', 'uses' => [TransactionsController::class, ebpg_requery']);

// Authenticated routes
Route::group(['before' => 'auth'], function () {
	Route::get('txn_status/{id}', ['as' => 'txn_status', 'uses' => [HomeController::class, 'txnStatus']);
	Route::get('register/company', ['as' => 'company_registration', 'uses' => [RegistrationController::class, company']);
	Route::put('register/company', [RegistrationController::class, storeCompany'];
	Route::get('register/payment', ['as' => 'payment_registration', 'uses' => [RegistrationController::class, payment']);
	Route::post('register/payment', [RegistrationController::class, storePayment'];
	Route::get('register/payment_callback/{transaction_id}', [RegistrationController::class, callbackPayment'];

	Route::get('dashboard', ['as' => 'dashboard', 'uses' => [HomeController::class, 'dashboard']);
	Route::get('vendor', ['as' => 'vendor', 'uses' => [HomeController::class, 'vendor']);
	Route::get('renewal', ['as' => 'renewal', 'uses' => [HomeController::class, 'renewal']);
	Route::post('renewal', [HomeController::class, 'storeRenewal'];
	Route::get('renewal_callback/{transaction_id}', [HomeController::class, 'callbackRenewal'];

	Route::get('tenders/{id}/buy', ['as' => 'tenders.buy', 'uses' => [TendersController::class, buy']);
	Route::get('tenders/{tender}/receipt/{id}', ['as' => 'tenders.receipt', 'uses' => [TendersController::class, receipt']);
	Route::get('tenders/{tender}/document/{id}', ['as' => 'tenders.document', 'uses' => [TendersController::class, document']);
	Route::post('tenders/{id}/vendors', [TendersController::class, updateVendors'];
	Route::post('tenders/{id}/invites', [TendersController::class, updateInvites'];
	Route::post('tenders/{id}/vendor', ['as' => 'tenders.addVendor', 'uses' => [TendersController::class, addVendor']);
	Route::get('tenders/{id}/publish', ['as' => 'tenders.publish', 'uses' => [TendersController::class, publish']);
	Route::get('tenders/{id}/cancel', ['as' => 'tenders.cancel', 'uses' => [TendersController::class, cancel']);
	Route::get('tenders/{id}/publishPrices', ['as' => 'tenders.publishPrices', 'uses' => [TendersController::class, publishPrices']);
	Route::get('tenders/{id}/publishWinner', ['as' => 'tenders.publishWinner', 'uses' => [TendersController::class, publishWinner']);
	Route::get('tenders/{tender_id}/vendor/{id}', ['as' => 'tenders.vendor', 'uses' => [TendersController::class, vendor']);
	Route::get(
		'tenders/{id}/vendors/print',
		['as' => 'tenders.vendors.print', 'uses' => [TendersController::class, printVendors']
	);
	Route::get('tenders/{id}/vendors/template', ['as' => 'tenders.template', 'uses' => [TendersController::class, template']);
	Route::post(
		'tenders/{id}/vendors/bulkUpdate',
		['as' => 'tenders.bulkUpdate', 'uses' => [TendersController::class, bulkUpdate']
	);
	Route::get('tenders/{id}/eligibles', ['as' => 'tenders.eligibles', 'uses' => [TendersController::class, eligibles']);

	Route::get('cart', ['as' => 'cart', 'uses' => [CartController::class, index']);
	Route::get('cart/clear', ['as' => 'cart.clear', 'uses' => [CartController::class, clear']);
	Route::get('cart/checkout', ['as' => 'cart.checkout', 'uses' => [CartController::class, checkout']);
	Route::get('cart/delete/{id}', ['as' => 'cart.delete', 'uses' => [CartController::class, delete']);
	Route::post('cart', ['as' => 'cart.process', 'uses' => [CartController::class, process']);
	Route::get('cart/callback/{transaction_id}', [CartController::class, callback'];
	Route::get('cart/receipt/{transaction_id}', ['as' => 'cart.receipt', 'uses' => [CartController::class, callback']);

	Route::get('profile', ['as' => 'profile', 'uses' => [ProfileController::class, show']);
	Route::get('profile/change_password', ['as' => 'change_password', 'uses' => [ProfileController::class, changePassword']);
	Route::put('profile/change_password', [ProfileController::class, doChangePassword'];
	Route::get('profile/release', ['uses' => [ProfileController::class, releaseUser', 'as' => 'release_user']);

	// User Approval
	Route::get('users/pending-approval', ['as' => 'users.pending-approval', 'uses' => [UsersController::class, pendingApproval']);
	Route::get('users/{user}/approval', ['as' => 'users.approval', 'uses' => [UsersController::class, approval']);
	Route::put('users/{user}/approval', ['as' => 'users.store-approval', 'uses' => [UsersController::class, storeApproval']);

	// User Account Review Request
	Route::get('users/{user}/account-review', ['as' => 'users.account-review', 'uses' => [UsersController::class, accountReview']);

	Route::resource('users', [UsersController::class]);
	Route::get('users/{user}/histories', ['as' => 'users.histories', 'uses' => [UsersController::class, histories']);
	Route::get('users/{user}/login', ['as' => 'users.login', 'uses' => [UsersController::class, doLogin']);
	Route::put('users/{user}/confirm', [UsersController::class, confirm'];
	Route::get('users/{user}/reset_password', ['as' => 'user.reset-password', 'uses' => [UsersController::class, getSetPassword']);
	Route::put('users/{user}/reset_password', [UsersController::class, putSetPassword'];
	Route::put('users/{user}/confirm', [UsersController::class, putSetConfirmation'];
	Route::get('users/{user}/resend_confirmation', [UsersController::class, resendConfirmation'];

	Route::get('vendors/select', [VendorsController::class, select'];
	Route::get('vendors/new', [VendorsController::class, pendingRegistrationIndex'];
	Route::get('vendors/approval', [VendorsController::class, approvalNew1Index'];
	Route::get('vendors/changes', [VendorsController::class, approvalEdit1Index'];

	Route::get('vendors/emails', [VendorsController::class, emails'];

	Route::resource('vendors', [VendorsController::class]);
	Route::get('vendor/{vendor_id}/approve', [VendorsController::class, approve'];
	Route::post('vendor/{vendor_id}/reject', [VendorsController::class, reject'];
	Route::get('vendor/{vendor_id}/blacklist', [VendorsController::class, blacklist'];
	Route::put('vendor/{vendor_id}/blacklist', [VendorsController::class, doBlacklist'];
	Route::get('vendor/{vendor_id}/cancelBlacklist', [VendorsController::class, cancelBlacklist'];

	Route::resource('vendors.subscriptions', [SubscriptionsController::class]);
	Route::get('vendors/{vendor}/subscriptions/{id}/receipt', ['as' => 'vendors.subscriptions.receipt', 'uses' => [SubscriptionsController::class, receipt']);
	Route::get('vendors/{user}/edit_email', [VendorsController::class, editEmail'];
	Route::put('vendors/{user}/edit_email', [VendorsController::class, updateEmail'];
	Route::get('vendors/{user}/histories', [VendorsController::class, histories'];
	Route::get('vendors/{user}/certificate', [VendorsController::class, certificate'];

	Route::resource('vendor.shareholders', [ShareholdersController::class]);
	Route::resource('vendor.directors', [DirectorsController::class]);
	Route::resource('vendor.contacts', [ContactsController::class]);
	Route::resource('vendor.awards', [AwardsController::class]);
	Route::resource('vendor.projects', [ProjectsController::class]);
	Route::resource('vendor.products', [ProductsController::class]);
	Route::resource('vendor.assets', [AssetsController::class]);
	Route::resource('vendor.remarks', [RemarksController::class]);
	Route::resource('vendor.subscriptions', [SubscriptionsController::class]);

	Route::resource('vendor.blacklists', [VendorBlacklistsController::class]);
	Route::get('vendor/{vendor}/blacklists/{blacklists}/file', ['as' => 'vendor.blacklists.file', 'uses' => [VendorBlacklistsController::class, file']);
	Route::put('vendor/{vendor}/blacklists/{blacklists}/cancel', ['as' => 'vendor.blacklists.cancel', 'uses' => [VendorBlacklistsController::class, cancel']);

	Route::resource('vendor.requests', [CodeRequestsController::class]);
	Route::put('vendor/{vendor}/requests/{requests}/approve', ['as' => 'vendor.requests.approve', 'uses' => [CodeRequestsController::class, approve_vendor']);
	Route::post('vendor/{vendor}/requests/{requests}/reject', ['as' => 'vendor.requests.reject', 'uses' => [CodeRequestsController::class, reject_vendor']);
	Route::resource('requests', [CodeRequestsController::class]);
	Route::get('requests/show/{request_id}', ['as' => 'requests.showAll', 'uses' => [CodeRequestsController::class, showAll']);
	Route::put('requests/{requests}/approve', ['as' => 'requests.approve', 'uses' => [CodeRequestsController::class, approve']);
	Route::post('requests/{requests}/reject', ['as' => 'requests.reject', 'uses' => [CodeRequestsController::class, reject']);

	Route::post('transactions/ajax', [TransactionsController::class, updateFpxCount']->name('updateFpxCount'];
	Route::get('transactions/subscription', [TransactionsController::class, subscriptionIndex'];
	Route::get('transactions/purchase', [TransactionsController::class, purchaseIndex'];
	Route::get('transactions/success', [TransactionsController::class, successTransIndex'];
	Route::get('transactions/pending', [TransactionsController::class, pendingTransIndex'];
	Route::get('transactions/declined', [TransactionsController::class, declinedTransIndex'];
	Route::get('transactions/failed', [TransactionsController::class, failedTransIndex'];
	Route::get('transactions/pending_authorization', [TransactionsController::class, pendingAuthTransIndex'];

	Route::resource('transactions', [TransactionsController::class]);
	Route::get('transactions/{id}/receipt', ['as' => 'transactions.receipt', 'uses' => [TransactionsController::class, receipt']);

	Route::get('transactions/{id}/fpx_query', ['as' => 'transactions.fpx_query', 'uses' => [TransactionsController::class, fpx_query']);
	Route::get('transactions/{id}/fpx_requery', ['as' => 'transactions.fpx_requery', 'uses' => [TransactionsController::class, fpx_requery']);
	Route::get('transactions/{id}/temp_receipt', ['as' => 'transactions.temp_receipt', 'uses' => [TransactionsController::class, temp_receipt']);

	Route::resource('blacklists', [VendorBlacklistsController::class]);
	Route::put(
		'vendor/{vendor}/blacklists/{blacklists}/cancel',
		['as' => 'vendor.blacklists.cancel', 'uses' => [VendorBlacklistsController::class, cancel']
	);
	Route::get(
		'vendor/{vendor}/blacklists/{blacklists}/unblacklist',
		['as' => 'vendor.blacklists.unblacklist', 'uses' => [VendorBlacklistsController::class, unblacklist']
	);

	Route::resource('organizationtypes', [OrganizationTypesController::class]);
	Route::post('organizationtypes/custom', 'OrganizationTypesController@customSave']->name("org_type_custom_save");
	Route::resource('roles', [RolesController::class]);
	Route::resource('permissions', [PermissionsController::class]);
	Route::resource('codes', [CertificationCodesController::class]);
	Route::resource('gateways', [GatewaysController::class]);
	Route::resource('payments', [PaymentsController::class]);
	Route::resource('helpcategories', [HelpCategoriesController::class]);

	Route::resource('banners', [BannersController::class]);
	Route::get('banners/{id}/publish', ['as' => 'banners.publish', 'uses' => 'BannersController@publish']);

	Route::get('tenders/{id}/publish', [TendersController::class, publish'];
	Route::get('tenders/{id}/publishPrice', [TendersController::class, publishPrice'];
	// Route::get('tenders/{id}/publishWinner', [TendersController::class, publishWinner'];
	Route::get('tenders/{id}/publishWinner', ['as' => 'tenders.publishWinner', 'uses' => [TendersController::class, publishWinner']);

	Route::get('news/{id}/publish', ['as' => 'news.publish', 'uses' => 'NewsController@publish']);

	Route::get('payment/fpx/connect', ['as' => 'fpx.connect', 'uses' => [FpxController::class, connect']);
	Route::post('payment/fpx/respond', ['as' => 'fpx.respond', 'uses' => [FpxController::class, respond']);

	Route::get('payment/ebpg/connect', ['as' => 'ebpg.connect', 'uses' => 'EbpgController@connect']);
	Route::post('payment/ebpg/respond', ['as' => 'ebpg.respond', 'uses' => 'EbpgController@respond']);
	Route::get('payment/fpx/bank-list',   ['as' => 'fpx.bank-list',     'uses' => [FpxController::class, bankList']);

	Route::get('dashboard/hq', [HomeController::class, 'managementDashboard'];

	Route::get('reports/revenue', 'ReportRevenueController@index'];
	Route::post('reports/revenue', 'ReportRevenueController@view'];
	Route::get('reports/revenue/excel', 'ReportRevenueController@excel'];

	Route::get('reports/agency/active', 'ReportAgencyActiveController@index'];
	Route::post('reports/agency/active', 'ReportAgencyActiveController@view'];
	Route::get('reports/agency/active/excel', 'ReportAgencyActiveController@excel'];

	Route::get('reports/agency/all', 'ReportAgencyAllController@index'];
	Route::post('reports/agency/all', 'ReportAgencyAllController@view'];
	Route::get('reports/agency/all/excel', 'ReportAgencyAllController@excel'];

	Route::get('reports/agency/type', 'ReportAgencyTypeController@index'];
	Route::post('reports/agency/type', 'ReportAgencyTypeController@view'];
	Route::get('reports/agency/type/excel', 'ReportAgencyTypeController@excel'];

	Route::get('reports/agency/daily', 'ReportAgencyDailyController@index'];
	Route::post('reports/agency/daily', 'ReportAgencyDailyController@view'];
	Route::get('reports/agency/daily/excel', 'ReportAgencyDailyController@excel'];

	Route::get('reports/agency/transaction', 'ReportAgencyTransactionController@index'];
	Route::post('reports/agency/transaction', 'ReportAgencyTransactionController@view'];
	Route::get('reports/agency/transaction/receipts', 'ReportAgencyTransactionController@receipts'];
	Route::get('reports/agency/transaction/excel', 'ReportAgencyTransactionController@excel'];

	Route::get('reports/gateway/daily', 'ReportGatewayDailyController@index'];
	Route::post('reports/gateway/daily', 'ReportGatewayDailyController@view'];
	Route::get('reports/gateway/daily/excel', 'ReportGatewayDailyController@excel'];

	Route::get('reports/vendor/status', 'ReportVendorStatusController@index'];
	Route::get('reports/vendor/status/view/{view}', 'ReportVendorStatusController@view'];
	Route::get('reports/vendor/status/csv/{view}', 'ReportVendorStatusController@csv'];
	Route::get('reports/vendor/status/excel/{view}', 'ReportVendorStatusController@excel'];

	/* add by zayid 4-jan-23 */
	Route::get('reports/vendor/summary/{year}/{vendor_id}', 'ReportVendorSummaryController@index']->name('report.vendor.summary'];

	Route::get('reports/vendor/request', 'ReportCodeRequestController@index'];
	Route::post('reports/vendor/request', 'ReportCodeRequestController@view'];

	Route::get('reports/vendor/registration', 'ReportVendorRegistrationController@index'];
	Route::post('reports/vendor/registration', 'ReportVendorRegistrationController@view'];

	Route::get('reports/vendor/registration-list', 'ReportVendorRegistrationListController@index'];
	Route::post('reports/vendor/registration-list', 'ReportVendorRegistrationListController@view'];

	Route::get('reports/staff/activity', 'ReportStaffActivityController@index'];
	Route::post('reports/staff/activity', 'ReportStaffActivityController@view'];

	Route::get('reports/code/district', 'ReportCodeDistrictController@index'];
	Route::post('reports/code/district', 'ReportCodeDistrictController@view'];

	Route::get('reports/vendor/transaction', 'ReportVendorTransactionController@index'];
	Route::post('reports/vendor/transaction', 'ReportVendorTransactionController@view'];

	Route::get('reports/transaction/hasil', 'ReportTransactionByHasilController@index'];
	Route::post('reports/transaction/hasil', 'ReportTransactionByHasilController@view'];

	/* end by zayid */

	Route::get('reports/vendor/codes', 'ReportVendorCodeController@index'];
	Route::post('reports/vendor/codes', 'ReportVendorCodeController@view'];
	Route::get('reports/vendor/codes/excel', 'ReportVendorCodeController@excel'];

	Route::get('reports/user/agency', 'ReportUserAgencyController@index'];
	Route::post('reports/user/agency', 'ReportUserAgencyController@view'];
	Route::get('reports/user/agency/excel', 'ReportUserAgencyController@excel'];

	Route::get('reports/user/active', 'ReportUserActiveController@index'];
	Route::post('reports/user/active', 'ReportUserActiveController@view'];
	Route::get('reports/user/active/excel', 'ReportUserActiveController@excel'];

	Route::get('reports/vendor/district', 'ReportVendorDistrictController@index'];
	Route::get('reports/vendor/district/view', 'ReportVendorDistrictController@view'];
	// Route::post('reports/vendor/district',          'ReportVendorDistrictController@view'];
	Route::get('reports/vendor/district/excel', 'ReportVendorDistrictController@excel'];

	Route::get('reports/user/activity', 'ReportUserActivityController@index'];
	Route::post('reports/user/activity', 'ReportUserActivityController@view'];
	Route::get('reports/user/activity/excel', 'ReportUserActivityController@excel'];

	Route::get('reports/user/login', 'ReportUserLoginController@index'];
	Route::post('reports/user/login', 'ReportUserLoginController@view'];
	Route::get('reports/user/login/excel', 'ReportUserLoginController@excel'];

	Route::get('version-histories', ['as' => 'version-histories', 'uses' => [HomeController::class, 'versionHistories']);

	//Route::get('tenders/send-eligible', [TendersController::class, sendEligible'];
	Route::get('hanif', [TendersController::class, sendEligible'];
	// Iskandar Hantar e-mail Account Review Request kepada User Sistem 
	Route::get('iskandar', [UsersController::class, sendArr'];

	Route::resource('reject-template', [RejectTemplateController::class]);

	// Route::resource('refunds', [RefundController::class]);
	Route::prefix('refunds']->group(function () {
		Route::post('/get-transaction', 'RefundController@fetch_transactions']->name('get_transaction'];
		Route::post('/get-refund', 'RefundController@get_refund_details']->name('get_refund_details'];
		Route::get('/create', 'RefundController@create']->name('refunds.create'];
		Route::post('/store', 'RefundController@store']->name('refunds.store'];
		Route::get('/{refund}/show', 'RefundController@show']->name('refunds.show'];
		Route::get('/{refund}/edit', 'RefundController@edit']->name('refunds.edit'];
		Route::post('/{refund}/update', 'RefundController@update']->name('refunds.update'];

		Route::prefix('request']->group(function () {
			Route::get('/', 'RefundController@index_request']->name('refunds.request.index'];
			Route::get('/new', 'RefundController@pendingRefundRequestIndex'];
			Route::get('/process', 'RefundController@processRefundRequestIndex'];
			Route::get('/reject', 'RefundController@rejectRefundRequestIndex'];
			Route::get('/{refund}/show', 'RefundController@show_request']->name('refunds.request.show'];
			Route::post('/{refund}/reject', 'RefundController@reject_request']->name('refunds.request.reject'];
			Route::get('/{refund}/approve', 'RefundController@approve_request']->name('refunds.request.approve'];
		});

		Route::prefix('complaint']->group(function () {
			Route::get('/', 'RefundController@index_complaint']->name('refunds.complaint.index'];
			Route::get('/new', 'RefundController@pendingRefundComplaintIndex'];
			Route::get('/reject', 'RefundController@rejectRefundComplaintIndex'];
			Route::get('/{refund}/show', 'RefundController@show_complaint']->name('refunds.complaint.show'];
			Route::post('/{refund}/reject', 'RefundController@reject_complaint']->name('refunds.complaint.reject'];
			Route::get('/{refund}/approve', 'RefundController@approve_complaint']->name('refunds.complaint.approve'];
		});
	});

	// Exception
	Route::post('tenders/exception/store', ['as' => 'tender.store.exception', 'uses' => [TendersController::class, storeException']);
	Route::get('tenders/{id}/exceptions', ['as' => 'tender.exceptions', 'uses' => [TendersController::class, exceptions']);
	Route::get('tenders/{id}/approve', ['as' => 'tender.approve.exception', 'uses' => [TendersController::class, approve_exception']);
	Route::post('tenders/{id}/reject/{exception_id}', ['as' => 'tender.reject.exception', 'uses' => [TendersController::class, reject_exception']);

	// Circular
	Route::resource('circulars', 'CircularController']->except(['show', 'destroy']);
	Route::get('circulars/{id}/publish', ['as' => 'circulars.publish', 'uses' => 'CircularController@publish']);
	Route::get('circulars/list', ['as' => 'circulars.public', 'uses' => 'CircularController@public']);
	Route::get('circulars/sort', ['as' => 'circulars.position', 'uses' => 'CircularController@sortPosition']);
	Route::post('circulars/sort', ['as' => 'circulars.update.position', 'uses' => 'CircularController@updatePosition']);

	Route::post('user/byagency', ['as' => 'user.by.agency', 'uses' => [UsersController::class, getUserByAgencies']);
	Route::post('user/byid', ['as' => 'user.by.id', 'uses' => [UsersController::class, getUserById']);

	/* // Petender Performance
	Route::prefix('/tenders']->controller(PetenderPerformanceController::class)->group(function () {
		Route::post('/petender-performance/{tender}/{vendor}', 'store']->name('store.PetenderPerformance'];
		Route::get('/{tender}/{vendor}', 'vendorPetender']->name('index.TenderVendor'];
	}); */

	Route::post('dashboard/fetch/tender', ['as' => 'dashboard.tender', 'uses' => [HomeController::class, 'tender_dashboard']);
	Route::post('dashboard/fetch/tender-summary', ['as' => 'dashboard.tender.summary', 'uses' => [HomeController::class, 'tender_summary_dashboard']);
	Route::post('dashboard/fetch/transaction', ['as' => 'dashboard.transaction', 'uses' => [HomeController::class, 'transaction_dashboard']);
	Route::post('dashboard/fetch/transaction-value', ['as' => 'dashboard.transaction-value', 'uses' => [HomeController::class, 'transaction_value_dashboard']);
	Route::post('dashboard/fetch/transaction-summary', ['as' => 'dashboard.transaction.summary', 'uses' => [HomeController::class, 'transaction_summary_dashboard']);
	Route::post('dashboard/fetch/transaction-value-summary', ['as' => 'dashboard.transaction-value.summary', 'uses' => [HomeController::class, 'transaction_value_summary_dashboard']);

	// Complaint/Aduan
	Route::get('aduan', ['as' => 'aduan.create', 'uses' => 'ComplaintController@create']);
	Route::post('aduan', ['as' => 'aduan.store', 'uses' => 'ComplaintController@store']);
	Route::get('aduan/list', ['as' => 'aduan.index', 'uses' => 'ComplaintController@index']);
	Route::get('aduan/{id}', ['as' => 'aduan.show', 'uses' => 'ComplaintController@show']);
	Route::get('aduan/{id}/{status}', ['as' => 'aduan.update.status', 'uses' => 'ComplaintController@updateStatus']);

	Route::match(['get', 'post'], 'botman', [BotManController::class, 'handle'])->name('botman'];
	Route::get('chat-widget/{chat_id}', [BotManController::class, 'chatWidget'])->withoutMiddleware(['auth'])->name('chat_widget'];

	//Api Token
	Route::get('apitoken', ['as' => 'apitoken.index', 'uses' => 'ApiTokenController@index']);
	Route::get('apitoken/create', ['as' => 'apitoken.create', 'uses' => 'ApiTokenController@create']);
	Route::post('apitoken/store', ['as' => 'apitoken.store', 'uses' => 'ApiTokenController@store']);
	Route::post('apitoken/generate', ['as' => 'apitoken.generate', 'uses' => 'ApiTokenController@generateToken']);

	// Email SMTP Manager
	// Route::resource('mail-manager', [MailManagerController::class]);
	Route::group(['as'=>'mail-manager.'  ,'prefix'=>'mail-manager'],function(){
		Route::resource('smtp-setting', [SmtpMailController::class]);
		Route::resource('mail-queue', [MailQueueController::class]);
	});

	Route::group(['as'=>'chatbot-manager.'  ,'prefix'=>'chatbot-manager'],function(){
		Route::resource('category', [FaqCategoryController::class]);
		Route::resource('question', [FaqController::class]);
		Route::resource('chatlog', [FaqLogController::class]);
		Route::resource('newquestion', [CustomerQuestionController::class]);
	});
});

