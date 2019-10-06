<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage; //for file 

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\App as apps;
use App\Category;
use App\CheckRole;
use DB;
class AppController extends Controller
{
    //
    public function index()
    {
        // return CheckRole::hasRole(\Auth::user()->id);

        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            $appUser = new apps();
            return view('admin.includes.apps.apps',['App' => $appUser->UsersApps()]); 
        }
    }
    public function storeApp(Request $request)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            // Storage::disk('local')->put('file.txt', 'change content');
            $validatedData = $request->validate([
                'package_name' => 'required|unique:apps,package_name'
            ]);
            if ( \Auth::check()) {
                $val = new apps();
                $val->title = $request->title;
                $val->users_id = \Auth::user()->id;
                $val->package_name = $request->package_name;
                if(!$val->save()){
                    return redirect('/dashboard/app')->with('msg','may be some error');
                }
                return redirect('/')->with('msg','save successful');
            }
            return redirect('/')->with('msg','may be some error');
        }
    	
    }

    public function edit($id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user')){
        	if (apps::findorfail($id)) {
                return view('admin.includes.apps.appsedit',['App' => apps::findorfail($id)]);
            }
            return redirect('/dashboard/app')->with('msg','may be some errors');
        }
    }

    public function editStore(Request $request,$id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user')){
             $validatedData = $request->validate([
                'package_name' => [
                    'required',
                    Rule::unique('apps')->ignore($id,'id'),
                ],
            ]);
        	if (apps::findorfail($id)) {
    	        $val = apps::findorfail($id);
    	        $val->title = $request->title;
    	        $val->package_name = $request->package_name;
    	        $val->save();
    	        return redirect('/')->with('msg','edit successful');
            }
            return redirect('/')->with('msg','may be some errors');
        }
    }

    public function allCategory($id,$name)
    {
        // return (new Category())->CategoriesRelatedtoApp($id);
        $var = (new Category())->CategoriesRelatedtoApp($id);
        $package = DB::table('apps')->where('package_name',$name)->get();
        return view('admin.includes.apps.relatedCategory', 
            [
                'packageName'=>$package,
                'categories'=> isset($var) ? $var : null,
            ]);
    }

    public function download($package_name = null)
    {
        if($package_name == null){
            return null;
        }
        $filename = str_replace('.','_',$package_name).'.'.'json';
        $data = (new ApiController())->getAllData($package_name);

         $headers = [
        'Content-type'        => 'application/json',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];
        return \Response::make($data, 200, $headers);
        //Storage::disk('local')->put($filename, ($data));
        //return Storage::download($filename);
    }

    public function encrypted_download($package_name = null)
    {
        if($package_name == null){
            return null;
        }
        $filename = str_replace('.','_',$package_name).'.'.'json';
        $data = (new ApiController())->getAll_Encrypted_Data($package_name);
        
        $data = (new ApiController())->getAllData($package_name);

        $headers = [
        'Content-type'        => 'application/json',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];
        return \Response::make($data, 200, $headers);
        //Storage::disk('local')->put($filename, ($data));
       // return Storage::download($filename);
    }
}