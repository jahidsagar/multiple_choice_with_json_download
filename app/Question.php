<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Category;
use App\Http\Controllers\TagsController;
class Question extends Model
{
    //
    public function tags()
    {
    	return $this->belongstoMany('App\Tag');
    }

    //category goes to category model , question and tag goes to belongs model
    //funny things category dosent go 
    public function import($category, $apps_id , $parent_catid = -1)
    {
    	//check first if category or subcategory exist or not
    	//if not than return but null provided
    	if(empty($category)) return false;
    	$find_category_if_exist = Category::all()->where('name',$category->name)->where('apps_id',$apps_id)->first();
    	if (isset($find_category_if_exist)) {
    		if(!empty( $category->questions)){
                foreach ($category->questions as $single_question) {
                    (new Question())->questionsEntry($single_question , $find_category_if_exist->id);
                }
            }
            if(! empty($category->sub_category)){
                foreach ($category->sub_category as $sc) {
                    (new Question())->import($sc, $apps_id , $find_category_if_exist->id);
                }
            }
    	}else{
    		$cat = new Category();
	    	$cat->name = $category->name;
	    	$cat->weight = $category->weight;
	    	$cat->parent_catid = $parent_catid;
	    	$cat->apps_id = $apps_id;
	    	
	    	if($cat->save()){
	    		if(!empty( $category->questions)){
	    			foreach ($category->questions as $single_question) {
	    				(new Question())->questionsEntry($single_question , $cat->id);
	    			}
	    		}
	    		if(! empty($category->sub_category)){
		    		foreach ($category->sub_category as $sc) {
		    			(new Question())->import($sc, $apps_id , $cat->id);
		    		}
		    	}
	    	}
    	}

    	return true;
    }

    //from json file
    public function questionsEntry($allQuestions , $cat_id)
    {
    	$val = new Question();
        $val->question = $allQuestions->question ;
        $val->op1 = $allQuestions->op1;
        $val->op2 = $allQuestions->op2;
        $val->op3 = $allQuestions->op3;
        $val->op4 = $allQuestions->op4;
        $val->ans = $allQuestions->ans;
        $val->difficulty = $allQuestions->difficulty;
        $val->categories_id = $cat_id;
        if($val->save()){
            if (! empty($allQuestions->tags)) {
                $tagControl = new TagsController();
                $tagControl->add($allQuestions->tags, $val->id);

            }
        }

    }
}
