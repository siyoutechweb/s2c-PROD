<?php namespace App\Models\Traits;
use App\Models\User;
trait Roles
{


public function hasRole($role)
{
    
    if ($this->role()->where('name', $role)->first()) {
        return true;
    }
    return false;
}

public function hasAnyRole($roles)
{
    if (is_array($roles)) {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
    } else {
        if ($this->hasRole($roles)) {
            return true;
        }
    }
    return false;
}
}