<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\App;
use App\Tag;
use App\Category;
use App\Question;
class ShowController extends Controller
{
    //for the category 
    function category($id , $name)
    {
    	$questions = Question::with('tags')->where('categories_id', '=', $id)->get();
    	// return $questions;
    	return view('admin.includes.generic.show',
            [
                'questions'=> $questions,
                'title'=>'category related questions',
                'id'=>$id
            ]);

    	
    }

//aitake bad dite hobe coz aita akhon r lagbe na, coz new ui ja bolse
    function app($id , $name)
    {
    	$app = new App();
        // return $app->AppRelated_Questions($id);

    	return view('admin.includes.generic.show',
            [
                'questions'=> $app->AppRelated_Questions($id),
                'title'=>'tag related questions'
            ]);
    }
    //=============================================================

//it has major problem have to filter all questions which made by users so it has to be modified
    //and thats why it off
    function tag($id , $name)
    {
        $tag = new Tag();
        // return $tag->TagRelated_Questions($id);
        return view('admin.includes.generic.show',
            [
                'questions'=> $tag->TagRelated_Questions($id),
                'title'=>'tag related questions'
            ]);
    }
    //=================================================================

    function something($appId , $parentId = -1)
    {
        $var = new Category();
        return $var->FindAllCategories($appId , $parentId);
    }

    function editsomething($appId , $editCategoryId)
    {
        $var = new Category($editCategoryId);
        return $var->FindAllEditableCategory($appId);
    }
}
