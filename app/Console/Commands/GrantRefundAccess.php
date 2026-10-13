<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\DB;

class GrantRefundAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:grant-access {email? : Email of the user to grant access}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant refund management access to a user (creates Refund Admin role and Refund:list permission if needed)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        // If no email provided, use current logged in user or ask
        if (!$email) {
            if (auth()->check()) {
                $user = auth()->user();
                $this->info("Using currently logged in user: {$user->email}");
            } else {
                $email = $this->ask('Enter user email');
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $this->error("User with email '{$email}' not found!");
                    return 1;
                }
            }
        } else {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("User with email '{$email}' not found!");
                return 1;
            }
        }

        // Create or get Refund Admin role
        $refundAdminRole = Role::firstOrCreate(
            ['name' => 'Refund Admin'],
            [
                'display_name' => 'Refund Administrator',
                'description' => 'Administrator with access to refund management'
            ]
        );
        $this->info("✓ Refund Admin role exists or created");

        // Create or get Refund:list permission
        $refundListPermission = Permission::firstOrCreate(
            ['name' => 'Refund:list'],
            [
                'display_name' => 'List Refunds',
                'description' => 'Permission to view refund list'
            ]
        );
        $this->info("✓ Refund:list permission exists or created");

        // Assign permission to role
        if (!$refundAdminRole->perms()->where('permissions.id', $refundListPermission->id)->exists()) {
            $refundAdminRole->perms()->attach($refundListPermission->id);
            $this->info("✓ Refund:list permission assigned to Refund Admin role");
        } else {
            $this->info("✓ Refund:list permission already assigned to Refund Admin role");
        }

        // Assign role to user using Spatie
        if (method_exists($user, 'assignRole')) {
            try {
                $user->assignRole('Refund Admin');
                $this->info("✓ Refund Admin role assigned to user using Spatie");
            } catch (\Exception $e) {
                // Role might already be assigned
                if (str_contains($e->getMessage(), 'already exists')) {
                    $this->info("✓ Refund Admin role already assigned to user");
                } else {
                    $this->warn("Could not assign role via Spatie: " . $e->getMessage());
                }
            }
        }

        // Also assign using role_user table (for compatibility)
        $roleUserExists = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('role_id', $refundAdminRole->id)
            ->exists();

        if (!$roleUserExists) {
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $refundAdminRole->id
            ]);
            $this->info("✓ Refund Admin role assigned to user via role_user table");
        } else {
            $this->info("✓ Refund Admin role already assigned to user via role_user table");
        }

        // Verify access
        $user->refresh();
        $hasAccess = $user->ability(['Admin', 'Refund Admin'], ['Refund:list']);

        if ($hasAccess) {
            $this->info("\n✅ SUCCESS! User '{$user->email}' now has refund management access!");
            $this->info("   - Role: Refund Admin");
            $this->info("   - Permission: Refund:list");
        } else {
            $this->warn("\n⚠️  Warning: Access check returned false. Please verify manually.");
        }

        return 0;
    }
}
