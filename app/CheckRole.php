<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;

class CheckRole extends Model
{
    //
    public static function hasRole($id)
    {
    	$user = User::findorfail($id);
        if ($user->roles_id == null) {
            return "null";
        }
        $role = Role::findorfail($user->roles_id);
        $name = $role->name ;
        
        return $name;
    }
}
