<?php

namespace App\Traits;

trait EntrustCompatTrait
{
    /**
     * Compatibility wrapper for Entrust's ability method.
     * Accepts string or array for roles and permissions. Returns boolean.
     *
     * @param array|string $roles
     * @param array|string $permissions
     * @param array $options (ignored)
     * @return bool
     */
    public function ability($roles = [], $permissions = [], $options = [])
    {
        // Normalize to arrays
        $roles = is_array($roles) ? $roles : (empty($roles) ? [] : array_map('trim', explode('|', $roles)));
        $permissions = is_array($permissions) ? $permissions : (empty($permissions) ? [] : array_map('trim', explode('|', $permissions)));

        $roleCheck = null;
        $permCheck = null;

        // Use Spatie-provided helpers if available
        if (!empty($roles)) {
            if (method_exists($this, 'hasAnyRole')) {
                $roleCheck = $this->hasAnyRole($roles);
            } else {
                // fall back: check single role
                $found = false;
                foreach ($roles as $r) {
                    if (method_exists($this, 'hasRole') && $this->hasRole($r)) {
                        $found = true;
                        break;
                    }
                }
                $roleCheck = $found;
            }
        }

        if (!empty($permissions)) {
            if (method_exists($this, 'hasAnyPermission')) {
                $permCheck = $this->hasAnyPermission($permissions);
            } else {
                $found = false;
                foreach ($permissions as $p) {
                    if (method_exists($this, 'hasPermissionTo') && $this->hasPermissionTo($p)) {
                        $found = true;
                        break;
                    }
                }
                $permCheck = $found;
            }
        }

        // Decide result: if both checks present require both true, else return whichever is present
        if (is_null($roleCheck) && is_null($permCheck)) {
            return false;
        }

        if (is_null($roleCheck)) {
            return (bool) $permCheck;
        }

        if (is_null($permCheck)) {
            return (bool) $roleCheck;
        }

        return (bool) ($roleCheck && $permCheck);
    }
}
