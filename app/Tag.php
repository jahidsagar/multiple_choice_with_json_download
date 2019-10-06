<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tag extends Model
{
    //take all question related to the tag
    function TagRelated_Questions($value)
    {
    	$questions = DB::table('question_tag')
    					->where('tag_id', '=', $value)
    					->select('question_id')
    					->get();
    	foreach ($questions as $value) {
    		$kochu = DB::table('questions')->where('id',$value->question_id)->get();
    		foreach ($kochu as $k) {
    			$all[] = $k;
    		}
    	}

        if (isset($all)) {
            return $all;
        }return null;
    }
}
