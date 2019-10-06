<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
class setRoleController extends Controller
{
    //

    public function index()
    {
    	return view('admin.includes.roles.setrole',['users'=>User::all(),'roles'=>Role::all()]);
    }

    public function setRole(Request $request , $id)
    {
    	// return $request->all();
    	if (User::findorfail($id)) {
    		$val = User::findorfail($id);
    		$val->roles_id = $request->roles_id;
    		$val->save();
    		return redirect('/dashboard/setrole')->with('msg','set successful');
    	}
    	return redirect('/dashboard/setrole')->with('msg','may be some error');

    }
}
