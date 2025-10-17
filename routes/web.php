<?php

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
use App\Models\SmtpMails;
use Illuminate\Support\Facades\Route;

// Basic routes to get the application running
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('prices', [HomeController::class, 'prices']);
Route::get('results', [HomeController::class, 'results']);
Route::get('privacy', [HomeController::class, 'privacy']);

// Public resources
Route::resource('comments', CommentsController::class);
Route::get('/agencies/{id}/prices', [OrganizationUnitsController::class, 'prices']);
Route::get('/agencies/{id}/results', [OrganizationUnitsController::class, 'results']);
Route::get('/agencies/{id}/news', [OrganizationUnitsController::class, 'news']);
Route::get('/agencies/{id}/report/{tender}', [OrganizationUnitsController::class, 'report']);
Route::resource('agencies', OrganizationUnitsController::class);
Route::get('helps/search', [HelpsController::class, 'search']);
Route::resource('helps', HelpsController::class);
Route::get('manuals/{manual}', [HomeController::class, 'manualShow'])->name('manuals.show');

// Company search
Route::get('company_search', [HomeController::class, 'companySearch']);
Route::post('company_search', [HomeController::class, 'doCompanySearch']);

// Email change
Route::get('change_email', [HomeController::class, 'changeEmail']);
Route::post('change_email', [HomeController::class, 'doChangeEmail']);
Route::get('change_email/{token}', [HomeController::class, 'verifyChangeEmail'])->name('verify_change_email');

// Registration routes
Route::get('register', [RegistrationController::class, 'register'])->name('registration');
Route::post('register', [RegistrationController::class, 'storeRegister']);
Route::get('register-user', [RegistrationController::class, 'registerUser'])->name('registration-user');
Route::post('register-user', [RegistrationController::class, 'storeRegisterUser']);

// Auth routes
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

// Contact
Route::get('contact', [HomeController::class, 'contact']);

// Circulars
Route::get('circulars', [HomeController::class, 'circulars'])->name('circulars.public');

// Aduan (Complaints)
Route::get('aduan/create', [HomeController::class, 'aduanCreate'])->name('aduan.create');

// Chat Widget
Route::get('chat_widget', [HomeController::class, 'chatWidget'])->name('chat_widget');

// Payment
Route::post('payment/fpx/listen', [FpxController::class, 'listen'])->name('fpx.listen');

// Transactions
Route::post('transactions/{id}/ebpg_requery', [TransactionsController::class, 'ebpg_requery'])->name('transactions.ebpg_requery');

// Protected routes
Route::middleware(['auth'])->group(function () {
	Route::get('txn_status/{id}', [HomeController::class, 'txnStatus'])->name('txn_status');
	Route::get('register/company', [RegistrationController::class, 'company'])->name('company_registration');
	Route::put('register/company', [RegistrationController::class, 'storeCompany']);
	Route::get('register/payment', [RegistrationController::class, 'payment'])->name('payment_registration');
	Route::post('register/payment', [RegistrationController::class, 'storePayment']);
	Route::get('register/payment_callback/{transaction_id}', [RegistrationController::class, 'callbackPayment']);

	Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
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

	// Admin routes
	Route::middleware(['role:admin'])->group(function () {
		Route::get('users/pending-approval', [UsersController::class, 'pendingApproval'])->name('users.pending-approval');
		Route::get('users/{user}/approval', [UsersController::class, 'approval'])->name('users.approval');
		Route::put('users/{user}/approval', [UsersController::class, 'storeApproval'])->name('users.store-approval');
		Route::get('users/{user}/account-review', [UsersController::class, 'accountReview'])->name('users.account-review');
		Route::get('users/{user}/histories', [UsersController::class, 'histories'])->name('users.histories');
		Route::get('users/{user}/login', [UsersController::class, 'doLogin'])->name('users.login');
		Route::put('users/{user}/confirm', [UsersController::class, 'confirm']);
		Route::get('users/{user}/reset_password', [UsersController::class, 'getSetPassword'])->name('user.reset-password');
		Route::put('users/{user}/reset_password', [UsersController::class, 'putSetPassword']);
		Route::put('users/{user}/confirm', [UsersController::class, 'putSetConfirmation']);
		Route::get('users/{user}/resend_confirmation', [UsersController::class, 'resendConfirmation']);

		Route::get('vendors/select', [VendorsController::class, 'select']);
		Route::get('vendors/new', [VendorsController::class, 'pendingRegistrationIndex']);
		Route::get('vendors/approval', [VendorsController::class, 'approvalNew1Index']);
		Route::get('vendors/changes', [VendorsController::class, 'approvalEdit1Index']);
		Route::get('vendors/emails', [VendorsController::class, 'emails']);

		Route::get('vendor/{vendor_id}/approve', [VendorsController::class, 'approve']);
		Route::post('vendor/{vendor_id}/reject', [VendorsController::class, 'reject']);
		Route::get('vendor/{vendor_id}/blacklist', [VendorsController::class, 'blacklist']);
		Route::put('vendor/{vendor_id}/blacklist', [VendorsController::class, 'doBlacklist']);
		Route::get('vendor/{vendor_id}/cancelBlacklist', [VendorsController::class, 'cancelBlacklist']);

		Route::get('vendors/{vendor}/subscriptions/{id}/receipt', [SubscriptionsController::class, 'receipt'])->name('vendors.subscriptions.receipt');
		Route::get('vendors/{user}/edit_email', [VendorsController::class, 'editEmail']);
		Route::put('vendors/{user}/edit_email', [VendorsController::class, 'updateEmail']);
		Route::get('vendors/{user}/histories', [VendorsController::class, 'histories']);
		Route::get('vendors/{user}/certificate', [VendorsController::class, 'certificate']);

		Route::get('vendor/{vendor}/blacklists', [VendorBlacklistsController::class, 'index'])->name('vendor.blacklists');
		Route::get('vendor/{vendor}/blacklists/create', [VendorBlacklistsController::class, 'create'])->name('vendor.blacklists.create');
		Route::post('vendor/{vendor}/blacklists', [VendorBlacklistsController::class, 'store'])->name('vendor.blacklists.store');
		Route::get('vendor/{vendor}/blacklists/{blacklists}', [VendorBlacklistsController::class, 'show'])->name('vendor.blacklists.show');
		Route::get('vendor/{vendor}/blacklists/{blacklists}/edit', [VendorBlacklistsController::class, 'edit'])->name('vendor.blacklists.edit');
		Route::put('vendor/{vendor}/blacklists/{blacklists}', [VendorBlacklistsController::class, 'update'])->name('vendor.blacklists.update');
		Route::delete('vendor/{vendor}/blacklists/{blacklists}', [VendorBlacklistsController::class, 'destroy'])->name('vendor.blacklists.destroy');
		Route::get('vendor/{vendor}/blacklists/{blacklists}/file', [VendorBlacklistsController::class, 'file'])->name('vendor.blacklists.file');
		Route::put('vendor/{vendor}/blacklists/{blacklists}/cancel', [VendorBlacklistsController::class, 'cancel'])->name('vendor.blacklists.cancel');

		Route::get('vendor/{vendor}/requests', [CodeRequestsController::class, 'index'])->name('vendor.requests');
		Route::get('vendor/{vendor}/requests/create', [CodeRequestsController::class, 'create'])->name('vendor.requests.create');
		Route::post('vendor/{vendor}/requests', [CodeRequestsController::class, 'store'])->name('vendor.requests.store');
		Route::get('vendor/{vendor}/requests/{requests}', [CodeRequestsController::class, 'show'])->name('vendor.requests.show');
		Route::get('vendor/{vendor}/requests/{requests}/edit', [CodeRequestsController::class, 'edit'])->name('vendor.requests.edit');
		Route::put('vendor/{vendor}/requests/{requests}', [CodeRequestsController::class, 'update'])->name('vendor.requests.update');
		Route::delete('vendor/{vendor}/requests/{requests}', [CodeRequestsController::class, 'destroy'])->name('vendor.requests.destroy');
		Route::put('vendor/{vendor}/requests/{requests}/approve', [CodeRequestsController::class, 'approve_vendor'])->name('vendor.requests.approve');
		Route::post('vendor/{vendor}/requests/{requests}/reject', [CodeRequestsController::class, 'reject_vendor'])->name('vendor.requests.reject');

		Route::get('requests/show/{request_id}', [CodeRequestsController::class, 'showAll'])->name('requests.showAll');
		Route::put('requests/{requests}/approve', [CodeRequestsController::class, 'approve'])->name('requests.approve');
		Route::post('requests/{requests}/reject', [CodeRequestsController::class, 'reject'])->name('requests.reject');

		Route::post('transactions/ajax', [TransactionsController::class, 'updateFpxCount'])->name('updateFpxCount');
		Route::get('transactions/subscription', [TransactionsController::class, 'subscriptionIndex']);
		Route::get('transactions/purchase', [TransactionsController::class, 'purchaseIndex']);
		Route::get('transactions/success', [TransactionsController::class, 'successTransIndex']);
		Route::get('transactions/pending', [TransactionsController::class, 'pendingTransIndex']);
		Route::get('transactions/declined', [TransactionsController::class, 'declinedTransIndex']);

		Route::get('reports', [ReportsController::class, 'index'])->name('reports');
		Route::get('reports/export', [ReportsController::class, 'export'])->name('reports.export');

		Route::get('settings', [SettingsController::class, 'index'])->name('settings');
		Route::put('settings', [SettingsController::class, 'update']);

		Route::get('smtp_mails', [SmtpMailsController::class, 'index'])->name('smtp_mails');
		Route::get('smtp_mails/create', [SmtpMailsController::class, 'create'])->name('smtp_mails.create');
		Route::post('smtp_mails', [SmtpMailsController::class, 'store'])->name('smtp_mails.store');
		Route::get('smtp_mails/{smtp_mail}', [SmtpMailsController::class, 'show'])->name('smtp_mails.show');
		Route::get('smtp_mails/{smtp_mail}/edit', [SmtpMailsController::class, 'edit'])->name('smtp_mails.edit');
		Route::put('smtp_mails/{smtp_mail}', [SmtpMailsController::class, 'update'])->name('smtp_mails.update');
		Route::delete('smtp_mails/{smtp_mail}', [SmtpMailsController::class, 'destroy'])->name('smtp_mails.destroy');

		Route::resource('tender_categories', TenderCategoriesController::class);
		Route::resource('tender_types', TenderTypesController::class);
		Route::resource('organization_unit_types', OrganizationUnitTypesController::class);
		Route::resource('states', StatesController::class);
		Route::resource('countries', CountriesController::class);
		Route::resource('tender_statuses', TenderStatusesController::class);
		Route::resource('tender_submissions', TenderSubmissionsController::class);
		Route::resource('tender_vendors', TenderVendorsController::class);
		Route::resource('tender_invites', TenderInvitesController::class);
		Route::resource('tender_files', TenderFilesController::class);
		Route::resource('tender_codes', TenderCodesController::class);
		Route::resource('tender_exceptions', TenderExceptionsController::class);
		Route::resource('tender_prices', TenderPricesController::class);
		Route::resource('tender_winners', TenderWinnersController::class);
		Route::resource('tender_comments', TenderCommentsController::class);
		Route::resource('tender_reports', TenderReportsController::class);
		Route::resource('tender_analytics', TenderAnalyticsController::class);
		Route::resource('tender_notifications', TenderNotificationsController::class);
		Route::resource('tender_audit', TenderAuditController::class);
		Route::resource('tender_export', TenderExportController::class);
		Route::resource('tender_import', TenderImportController::class);
		Route::resource('tender_backup', TenderBackupController::class);
		Route::resource('tender_restore', TenderRestoreController::class);
		Route::resource('tender_archive', TenderArchiveController::class);
		Route::resource('tender_unarchive', TenderUnarchiveController::class);
		Route::resource('tender_clone', TenderCloneController::class);
		Route::resource('tender_template', TenderTemplateController::class);
		Route::resource('tender_workflow', TenderWorkflowController::class);
		Route::resource('tender_approval', TenderApprovalController::class);
		Route::resource('tender_rejection', TenderRejectionController::class);
		Route::resource('tender_revision', TenderRevisionController::class);
		Route::resource('tender_version', TenderVersionController::class);
		Route::resource('tender_history', TenderHistoryController::class);
		Route::resource('tender_log', TenderLogController::class);
		Route::resource('tender_activity', TenderActivityController::class);
		Route::resource('tender_event', TenderEventController::class);
		Route::resource('tender_reminder', TenderReminderController::class);
		Route::resource('tender_alert', TenderAlertController::class);
		Route::resource('tender_notification', TenderNotificationController::class);
		Route::resource('tender_email', TenderEmailController::class);
		Route::resource('tender_sms', TenderSmsController::class);
		Route::resource('tender_push', TenderPushController::class);
		Route::resource('tender_webhook', TenderWebhookController::class);
		Route::resource('tender_api', TenderApiController::class);
		Route::resource('tender_integration', TenderIntegrationController::class);
		Route::resource('tender_sync', TenderSyncController::class);
		Route::resource('tender_migration', TenderMigrationController::class);
		Route::resource('tender_upgrade', TenderUpgradeController::class);
		Route::resource('tender_maintenance', TenderMaintenanceController::class);
		Route::resource('tender_health', TenderHealthController::class);
		Route::resource('tender_status', TenderStatusController::class);
		Route::resource('tender_monitor', TenderMonitorController::class);
		Route::resource('tender_dashboard', TenderDashboardController::class);
		Route::resource('tender_report', TenderReportController::class);
		Route::resource('tender_chart', TenderChartController::class);
		Route::resource('tender_graph', TenderGraphController::class);
		Route::resource('tender_metric', TenderMetricController::class);
		Route::resource('tender_kpi', TenderKpiController::class);
		Route::resource('tender_performance', TenderPerformanceController::class);
		Route::resource('tender_benchmark', TenderBenchmarkController::class);
		Route::resource('tender_comparison', TenderComparisonController::class);
		Route::resource('tender_trend', TenderTrendController::class);
		Route::resource('tender_forecast', TenderForecastController::class);
		Route::resource('tender_prediction', TenderPredictionController::class);
		Route::resource('tender_recommendation', TenderRecommendationController::class);
		Route::resource('tender_suggestion', TenderSuggestionController::class);
		Route::resource('tender_advice', TenderAdviceController::class);
		Route::resource('tender_tip', TenderTipController::class);
		Route::resource('tender_guide', TenderGuideController::class);
		Route::resource('tender_tutorial', TenderTutorialController::class);
		Route::resource('tender_help', TenderHelpController::class);
		Route::resource('tender_faq', TenderFaqController::class);
		Route::resource('tender_support', TenderSupportController::class);
		Route::resource('tender_contact', TenderContactController::class);
		Route::resource('tender_feedback', TenderFeedbackController::class);
		Route::resource('tender_review', TenderReviewController::class);
		Route::resource('tender_rating', TenderRatingController::class);
		Route::resource('tender_comment', TenderCommentController::class);
		Route::resource('tender_like', TenderLikeController::class);
		Route::resource('tender_share', TenderShareController::class);
		Route::resource('tender_bookmark', TenderBookmarkController::class);
		Route::resource('tender_favorite', TenderFavoriteController::class);
		Route::resource('tender_watch', TenderWatchController::class);
		Route::resource('tender_subscribe', TenderSubscribeController::class);
		Route::resource('tender_follow', TenderFollowController::class);
		Route::resource('tender_track', TenderTrackController::class);
	});
});
