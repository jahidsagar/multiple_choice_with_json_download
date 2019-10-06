<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CheckRole;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //for simplicity i can make middleware
        //but for this simple purpose i didnt.
        //next time better luck



        //check if user null or block
        if(CheckRole::hasRole(\Auth::user()->id) == ('block') || CheckRole::hasRole(\Auth::user()->id) == ('null')){
            \Auth::logout();
            return redirect()->to('/')->with('msg', 'admin not approved you yet !!');
        }else{
            // return redirect()->route('root');
            return redirect()->to('/');
        }
        //if so then logout and rediect to landing page
        //else redirect to dashboard



        // return view('home');
    }
}
