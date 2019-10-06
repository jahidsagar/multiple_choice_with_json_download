<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\App as app;
use App\Category;
use App\CheckRole;
use DB;
class CategoryController extends Controller
{
    // static url = '';
    //
    public function index()
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            
            $appUser = new app();
            $Cate = new Category();
            // return $Cate->UserCategoryWithPackageandCategoryName();
    	   return view('admin.includes.categories.category',
            [
                'App'=> $appUser->UsersApps() , 
                'Cat'=> $Cate->UserCategoryWithPackageandCategoryName()
            ]);
        }
    }

    public function store(Request $request)
    {
        // return $request->all();
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){

            //validate request
            $validatedData = $request->validate([
                'name' =>'required',
                'weight'=>'numeric',
                'parent_catid' => 'numeric',
                'app_id' => 'required|numeric',
            ]);
            //again validate for unique as db table has
            //also do this on edit method

        	$val = new Category();
        	$val->name = $request->name;
            $val->weight = $request->weight;
        	$val->parent_catid = $request->parent_catid;
        	$val->apps_id = $request->app_id;
        	if(! $val->save()){
        		return redirect($request->previous_url)->with('msg','may be some errors');
        	}
        	return redirect($request->previous_url)->with('msg','create successful');
        }
    }

    public function edit($id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
        	return view('admin.includes.categories.categoryedit',
                [
                    'Cat'=>Category::all(),
                    'App'=>App::all(),
                    'eCat'=>Category::findorfail($id)
                ]
            );
        }
    }

    public function editStore(Request $request ,$id)
    {
        // return $request->all();
        
         if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
        	$validatedData = $request->validate([
                    'name' =>'required',
                    'parent_catid' => 'numeric',
                    'app_id' => 'required|numeric',
                    'weight'=>'numeric',
                ]);
            $val = Category::findorfail($id);
            $val->name = $request->name;
            $val->weight = $request->weight;
            $val->parent_catid = $request->parent_catid;
            //take the previous id
            $PrevAppId = $val->apps_id;
            if ($PrevAppId != $request->app_id) {
                $ChangePackage = new Category();
                $ChangePackage->FindAllEditableCategoryandChangeThePackageName($request->app_id,$val->id);
            }
            $val->apps_id = $request->app_id;
            if(! $val->save()){
                return redirect($request->previous_url)->with('msg','may be some errors');
            }
            return redirect($request->previous_url)->with('msg','edit successful');
        }
    }

    public function delete($id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
        	if (questions::findorfail($id)->delete()) {
                return redirect('/dashboard/questions')->with('msg','delete successful');
            }
            return redirect('/dashboard/questions')->with('msg','may be some errors');
        }
    }


    /*
    this method is used for ajax request
    to filter the category with app_id
    */

    public function getRelatedCategory($id)
    {
        // if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            $selectedCategory = DB::table('categories')
                ->where('apps_id', $id)
                ->select('categories.id','categories.name')
                ->get();
            return $selectedCategory;
        // }
    }

    public function getEditableCategory($appsId , $editCategoryId)
    {
        $var = new Category();
        return $var->FindAllEditableCategory($appsId,$editCategoryId);
    }
}
