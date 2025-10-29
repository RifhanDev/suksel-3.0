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

        // Check roles using hasRole method
        if (!empty($roles)) {
            $found = false;
            foreach ($roles as $r) {
                if (method_exists($this, 'hasRole') && $this->hasRole($r)) {
                    $found = true;
                    break;
                }
            }
            $roleCheck = $found;
        }

        // Check permissions through roles (Entrust style)
        if (!empty($permissions)) {
            // Filter out empty permission strings
            $permissions = array_filter($permissions, function ($p) {
                return !empty(trim($p));
            });

            if (!empty($permissions)) {
                $found = false;
                if (method_exists($this, 'roles')) {
                    foreach ($this->roles as $role) {
                        foreach ($permissions as $permission) {
                            if ($role->permissions()->where('name', $permission)->exists()) {
                                $found = true;
                                break 2; // Break out of both loops
                            }
                        }
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

    /**
     * Laravel's can() method compatibility for permission checking.
     * Checks if user has a specific permission through their roles.
     *
     * @param string $permission
     * @return bool
     */
    public function can($permission, $arguments = [])
    {
        // Check if this is a built-in Laravel authorization (like policies)
        // If parent class has can() method, defer to it for non-permission strings
        if (method_exists(get_parent_class($this), 'can') && !str_contains($permission, ':')) {
            return parent::can($permission, $arguments);
        }

        // Check if user has this permission through their roles
        if (method_exists($this, 'roles')) {
            foreach ($this->roles as $role) {
                if ($role->permissions()->where('name', $permission)->exists()) {
                    return true;
                }
            }
        }

        return false;
    }
}
