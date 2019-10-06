<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Storage; //for file 

use Illuminate\Http\Request;
use App\CheckRole;
use App\App as app;
class DashboradController extends Controller
{
    //
    public function index()
    {
    	if (\Auth::check()) {
    		if(CheckRole::hasRole(\Auth::user()->id) == ('admin'||'user') ){
	    		$var = new app();
		    	return view('admin.includes.adminfront',[
		    		'Apps' => $var->UsersApps()
		    	]);
		    }
    	}else{
	    	return view('welcome');
	    }
    }
}

