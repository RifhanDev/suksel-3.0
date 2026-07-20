<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable FK checks for safe truncation if your DB contains FKs referencing this table.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = [
            ['id' => 1, 'group_name' => 'Role', 'name' => 'Role:list', 'display_name' => 'List Roles', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 2, 'group_name' => 'Role', 'name' => 'Role:show', 'display_name' => 'Show Role', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 3, 'group_name' => 'Role', 'name' => 'Role:create', 'display_name' => 'Create New Role', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 4, 'group_name' => 'Role', 'name' => 'Role:edit', 'display_name' => 'Edit Existing Role', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 5, 'group_name' => 'Role', 'name' => 'Role:delete', 'display_name' => 'Delete Existing Role', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 6, 'group_name' => 'Permission', 'name' => 'Permission:list', 'display_name' => 'List Permissions', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 7, 'group_name' => 'Permission', 'name' => 'Permission:show', 'display_name' => 'Show Permission', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 8, 'group_name' => 'Permission', 'name' => 'Permission:create', 'display_name' => 'Create New Permission', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 9, 'group_name' => 'Permission', 'name' => 'Permission:edit', 'display_name' => 'Edit Existing Permission', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 10, 'group_name' => 'Permission', 'name' => 'Permission:delete', 'display_name' => 'Delete Existing Permission', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 11, 'group_name' => 'Organization Unit', 'name' => 'OrganizationUnit:list', 'display_name' => 'List Organization Units', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 12, 'group_name' => 'Organization Unit', 'name' => 'OrganizationUnit:show', 'display_name' => 'Show Organization Unit', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 13, 'group_name' => 'Organization Unit', 'name' => 'OrganizationUnit:create', 'display_name' => 'Create New Organization Unit', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 14, 'group_name' => 'Organization Unit', 'name' => 'OrganizationUnit:edit', 'display_name' => 'Edit Existing Organization Unit', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 15, 'group_name' => 'Organization Unit', 'name' => 'OrganizationUnit:delete', 'display_name' => 'Delete Existing Organization Unit', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 16, 'group_name' => 'User', 'name' => 'User:list', 'display_name' => 'List Users', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 17, 'group_name' => 'User', 'name' => 'User:show', 'display_name' => 'Show User', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 18, 'group_name' => 'User', 'name' => 'User:create', 'display_name' => 'Create New User', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 19, 'group_name' => 'User', 'name' => 'User:edit', 'display_name' => 'Edit Existing User', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 20, 'group_name' => 'User', 'name' => 'User:delete', 'display_name' => 'Delete Existing User', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 21, 'group_name' => 'User', 'name' => 'User:set_confirmation', 'display_name' => "Set Existing User's Confirmed Status", 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 22, 'group_name' => 'User', 'name' => 'User:set_password', 'display_name' => "Set Existing User's Password", 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 23, 'group_name' => 'Vendor', 'name' => 'Vendor:list', 'display_name' => 'List Vendors', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 24, 'group_name' => 'Vendor', 'name' => 'Vendor:show', 'display_name' => 'Show Vendor', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 25, 'group_name' => 'Vendor', 'name' => 'Vendor:create', 'display_name' => 'Create vendor', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 26, 'group_name' => 'Vendor', 'name' => 'Vendor:edit', 'display_name' => 'Edit Existing Vendor', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 27, 'group_name' => 'Vendor', 'name' => 'Vendor:delete', 'display_name' => 'Delete Existing Vendor', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 28, 'group_name' => 'Vendor', 'name' => 'Vendor:approve', 'display_name' => 'Approve new vendor or change request', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 29, 'group_name' => 'Vendor', 'name' => 'Vendor:reject', 'display_name' => 'Reject new vendor or change request', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 30, 'group_name' => 'Vendor', 'name' => 'Vendor:blacklist', 'display_name' => 'Blacklist vendor', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 31, 'group_name' => 'Tender', 'name' => 'Tender:list', 'display_name' => 'List Tenders', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 32, 'group_name' => 'Tender', 'name' => 'Tender:show', 'display_name' => 'Show Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 33, 'group_name' => 'Tender', 'name' => 'Tender:create', 'display_name' => 'Create Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 34, 'group_name' => 'Tender', 'name' => 'Tender:edit', 'display_name' => 'Edit Existing Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 35, 'group_name' => 'Tender', 'name' => 'Tender:delete', 'display_name' => 'Delete Existing Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 36, 'group_name' => 'Tender', 'name' => 'Tender:approve', 'display_name' => 'Approve new Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 37, 'group_name' => 'Tender', 'name' => 'Tender:reject', 'display_name' => 'Reject new Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 38, 'group_name' => 'Tender', 'name' => 'Tender:publish', 'display_name' => 'Publish Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],
            ['id' => 39, 'group_name' => 'Tender', 'name' => 'Tender:cancel', 'display_name' => 'Cancel Tender', 'created_at' => '2015-05-26 09:03:13', 'updated_at' => '2015-05-26 09:03:13'],

            ['id' => 40, 'group_name' => 'Report', 'name' => 'Report:view', 'display_name' => 'View Reports', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 41, 'group_name' => 'Report', 'name' => 'Report:view:revenue_yearly', 'display_name' => 'View yearly report', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 42, 'group_name' => 'Report', 'name' => 'Report:view:agency_active', 'display_name' => 'View 10 active agency report', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 43, 'group_name' => 'Report', 'name' => 'Report:view:agency_transaction', 'display_name' => 'View all agency transactions', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 44, 'group_name' => 'Report', 'name' => 'Report:view:agency_type', 'display_name' => 'View transactions by agency type', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 45, 'group_name' => 'Report', 'name' => 'Report:view:agency_tender', 'display_name' => 'View agency transactions by tender', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 46, 'group_name' => 'Report', 'name' => 'Report:view:agency_tender:organization_unit_id', 'display_name' => 'View agency transactions by tender (For Agency)', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 47, 'group_name' => 'Report', 'name' => 'Report:view:agency_daily', 'display_name' => 'View agency daily transactions', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 48, 'group_name' => 'Report', 'name' => 'Report:view:agency_daily:organization_unit_id', 'display_name' => 'View agency daily transactions (For Agency)', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 49, 'group_name' => 'Report', 'name' => 'Report:view:gateway_daily', 'display_name' => 'View gateway daily transactions', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 50, 'group_name' => 'Report', 'name' => 'Report:view:gateway_daily:organization_unit_id', 'display_name' => 'View gateway daily transactions (For Agency)', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 51, 'group_name' => 'Report', 'name' => 'Report:view:vendor_status', 'display_name' => 'View vendors summary by status', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 52, 'group_name' => 'Report', 'name' => 'Report:view:vendor_code', 'display_name' => 'View vendors summary by qualification code', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 53, 'group_name' => 'Report', 'name' => 'Report:view:vendor_district', 'display_name' => 'View vendors by district', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 54, 'group_name' => 'Report', 'name' => 'Report:view:user_agency', 'display_name' => 'View users by agency', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],
            ['id' => 55, 'group_name' => 'Report', 'name' => 'Report:view:user_agency:organization_unit_id', 'display_name' => 'View users by agency (For Agency)', 'created_at' => '2016-05-19 08:03:49', 'updated_at' => '2016-05-19 08:03:49'],

            ['id' => 56, 'group_name' => 'Transaction', 'name' => 'Transaction:edit', 'display_name' => 'Update transaction information', 'created_at' => '2016-05-19 08:51:12', 'updated_at' => '2016-05-19 08:51:12'],
            ['id' => 57, 'group_name' => 'Transaction', 'name' => 'Transaction:edit:organization_unit_id', 'display_name' => 'Update transaction information (For Agency)', 'created_at' => '2016-05-19 08:51:34', 'updated_at' => '2016-05-19 08:51:34'],
            ['id' => 58, 'group_name' => 'Transaction', 'name' => 'Transaction:list', 'display_name' => 'List all transactions', 'created_at' => '2016-08-02 02:50:45', 'updated_at' => '2016-08-02 02:50:45'],
            ['id' => 59, 'group_name' => 'Transaction', 'name' => 'Transaction:show', 'display_name' => 'View a transaction', 'created_at' => '2016-08-02 02:51:31', 'updated_at' => '2016-08-02 02:51:31'],

            ['id' => 60, 'group_name' => 'Vendor', 'name' => 'CodeRequest:list', 'display_name' => 'List change requests', 'created_at' => '2016-08-02 03:00:41', 'updated_at' => '2016-08-02 03:01:02'],
            ['id' => 61, 'group_name' => 'Vendor', 'name' => 'CodeRequest:show', 'display_name' => 'Show change request', 'created_at' => '2016-08-02 03:00:54', 'updated_at' => '2016-08-02 03:00:54'],

            ['id' => 62, 'group_name' => 'Report', 'name' => 'Report:view:user_activity', 'display_name' => 'Lihat laporan aktiviti staf', 'created_at' => '2016-08-11 15:27:05', 'updated_at' => '2016-08-11 15:27:05'],
            ['id' => 63, 'group_name' => 'Report', 'name' => 'Report:view:user_login', 'display_name' => 'View user login report', 'created_at' => '2016-11-01 09:54:33', 'updated_at' => '2016-11-01 09:54:33'],

            ['id' => 64, 'group_name' => 'Vendor', 'name' => 'Vendor:certificate', 'display_name' => 'Display vendor certificate', 'created_at' => '2017-09-05 01:23:41', 'updated_at' => '2017-09-05 01:23:41'],

            ['id' => 65, 'group_name' => 'Blacklist', 'name' => 'Blacklist:index', 'display_name' => 'List all blacklist', 'created_at' => '2017-08-02 02:13:36', 'updated_at' => '2017-08-02 02:13:36'],
            ['id' => 66, 'group_name' => 'Blacklist', 'name' => 'Blacklist:create', 'display_name' => 'Create new blacklist', 'created_at' => '2017-08-02 02:14:50', 'updated_at' => '2017-08-02 02:14:50'],
            ['id' => 67, 'group_name' => 'Blacklist', 'name' => 'Blacklist:update', 'display_name' => 'Update existing blacklist', 'created_at' => '2017-08-02 02:15:08', 'updated_at' => '2017-08-02 02:15:08'],
            ['id' => 68, 'group_name' => 'Blacklist', 'name' => 'Blacklist:delete', 'display_name' => 'Delete existing blacklist', 'created_at' => '2017-08-02 02:16:15', 'updated_at' => '2017-08-02 02:16:15'],
            ['id' => 69, 'group_name' => 'Blacklist', 'name' => 'Blacklist:cancel', 'display_name' => 'Cancel existing blacklist', 'created_at' => '2017-08-02 02:16:37', 'updated_at' => '2017-08-02 02:16:37'],

            ['id' => 70, 'group_name' => 'Transaction', 'name' => 'Transaction:all', 'display_name' => 'List all transaction', 'created_at' => '2017-08-02 02:52:51', 'updated_at' => '2017-08-02 02:52:51'],
            ['id' => 71, 'group_name' => 'Vendor', 'name' => 'Vendor:histories', 'display_name' => 'List vendor histories', 'created_at' => '2017-08-02 03:20:08', 'updated_at' => '2017-08-02 03:20:08'],
            ['id' => 72, 'group_name' => 'User', 'name' => 'User:login', 'display_name' => 'Login sebagai pengguna', 'created_at' => '2017-09-12 02:28:14', 'updated_at' => '2017-09-12 02:28:14'],
            ['id' => 73, 'group_name' => 'Vendor', 'name' => 'Vendor:override', 'display_name' => 'Override vendor email and registration no.', 'created_at' => '2017-09-14 05:12:06', 'updated_at' => '2017-09-14 05:12:06'],

            ['id' => 74, 'group_name' => 'Tender', 'name' => 'Tender:vendors:all', 'display_name' => "View vendors under tender (Allow for all organization)", 'created_at' => '2017-10-17 02:16:23', 'updated_at' => '2017-10-17 02:16:23'],
            ['id' => 75, 'group_name' => 'Tender', 'name' => 'Tender:vendors', 'display_name' => "Allow to view tender's vendors", 'created_at' => '2017-10-17 02:16:42', 'updated_at' => '2017-10-17 02:16:42'],
            ['id' => 76, 'group_name' => 'Tender', 'name' => 'Tender:exception', 'display_name' => 'Allow user to add vendor exception to tender', 'created_at' => '2017-10-17 02:45:28', 'updated_at' => '2017-10-17 02:45:28'],

            ['id' => 77, 'group_name' => 'System', 'name' => 'System:histories', 'display_name' => 'View version histories', 'created_at' => '2017-10-25 05:08:28', 'updated_at' => '2017-10-25 05:08:28'],

            ['id' => 78, 'group_name' => 'Report', 'name' => 'Report:view:code_request', 'display_name' => 'View code requests report', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 79, 'group_name' => 'Report', 'name' => 'Report:view:vendor_registration', 'display_name' => 'View vendors registration summary', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 80, 'group_name' => 'Report', 'name' => 'Report:view:vendor_registration_list', 'display_name' => 'View vendors registration list', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 81, 'group_name' => 'Report', 'name' => 'Report:view:staff_activity', 'display_name' => 'View staff activity ', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 82, 'group_name' => 'Report', 'name' => 'Report:view:code_district', 'display_name' => "View vendor's code district total", 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 83, 'group_name' => 'Report', 'name' => 'Report:view:vendor_transaction', 'display_name' => 'View vendor transaction list', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 84, 'group_name' => 'Report', 'name' => 'Report:view:transaction_hasil', 'display_name' => 'View transaction by hasil', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],

            ['id' => 85, 'group_name' => 'Api', 'name' => 'Api:list', 'display_name' => "VIew list of api's token registered", 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 86, 'group_name' => 'Api', 'name' => 'Api:create', 'display_name' => "create api's token for agency", 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],

            ['id' => 87, 'group_name' => 'SmtpMails', 'name' => 'SmtpMails:list', 'display_name' => 'View list mail smtp', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 88, 'group_name' => 'SmtpMails', 'name' => 'SmtpMails:show', 'display_name' => 'Show mail smtp', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 89, 'group_name' => 'SmtpMails', 'name' => 'SmtpMails:create', 'display_name' => 'Create new mail smtp', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 90, 'group_name' => 'SmtpMails', 'name' => 'SmtpMails:edit', 'display_name' => 'Edit existing smtp mail', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
            ['id' => 91, 'group_name' => 'SmtpMails', 'name' => 'SmtpMails:delete', 'display_name' => 'Delete existing smtp mail', 'created_at' => '2023-04-29 03:46:14', 'updated_at' => null],
        ];

        // Insert all rows in a single query
        DB::table('permissions')->insert($rows);
    }
}