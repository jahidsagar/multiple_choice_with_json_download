<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class App extends Model
{
    //this a one to many relation but in db some mistakes according
    //eloquent thats why i paused it may be some day :) 

    public function categories()
    {
    	return $this->hasMany('App\Category');
    }

// take all apps name which made by user
    public function UsersApps()
    {
    	return DB::table('apps')
                    ->where('users_id',\Auth::user()->id)
                    ->orderBy('updated_at', 'desc')
                    ->get();
    }

    //take all questions according to category id
    function AppRelated_Questions($value)
    {
        $categories = DB::table('categories')
                        ->where('apps_id', '=', $value)
                        ->select('categories.id')
                        ->get();


        foreach ($categories as $category) {
            $questions = DB::table('questions')->where('categories_id', '=', $category->id)->get();
            foreach ($questions as $question) {
                $all[] = (object)$question;
            }
        }
        if (isset($all)) {
            return $all;
        }return null;
        
    }
}
