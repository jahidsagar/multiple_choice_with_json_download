<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
// use App\question_tag as QmapTag;
use App\QuestionTag;
use DB;
class TagsController extends Controller
{
    //
    public function insert($tags,$qId)
    {
		foreach ($tags as $tag) {
            //if $tag empty than continue
            // if(empty(trim($tag))) continue;
			//find tag id
			$tagId = $this->findTag($tag);
			//save tagid and qid
            // $id = DB::table('question_tag')->insert(
            //         ['question_id' => $qId, 'tag_id' => $tagId]
            //     );

            $ques_tag = new QuestionTag();
            $ques_tag->question_id = $qId;
            $ques_tag->tag_id = $tagId;
            $ques_tag->save();
		}
    }

    public function findTag($tag)
    {
    	$var =0;
    	$var = DB::table('tags')->where('name',$tag)->get();

    	if(count($var) == 0){
    		$var = new Tag();
    		$var->name = str_replace(' ','_',$tag);
    		$var->save();
    		return $var->id;
    	}else{
    		return $var[0]->id;
    	}

    }
    //save tags which comes from ajax
    public function SaveAjaxTag($tag)
    {
        $var =0;
        $var = DB::table('tags')->where('name',$tag)->get();

        if(count($var) == 0){
            $var = new Tag();
            $var->name = str_replace(' ','_',$tag);
            $var->save();
            return $var->name;
        }else{
            return null;
        }

    }
    //add new tags by ajax request
    function tags_ajax(Request $request)
    {
        $explodeTags = explode(",", $request->tags);
        // return $explodeTags;
        foreach ($explodeTags as $tag) {
            $tagobj = new TagsController();
            /*
            we have no need this id , because we need only tag string 
            in the $explodeTags. after that we are done to append the select
            box.
            */
            $arr[]=$tagobj->SaveAjaxTag($tag);
        }
        return $arr;
    }

    //add json import tag
    public function add($tags,$qId)
    {
        foreach ($tags as $tag) {
            //if $tag empty than continue
            // if(empty(trim($tag))) continue;
            //find tag id
            $tagId = $this->findTag($tag->name);
            //save tagid and qid
            // $id = DB::table('question_tag')->insert(
            //         ['question_id' => $qId, 'tag_id' => $tagId]
            //     );

            $ques_tag = new QuestionTag();
            $ques_tag->question_id = $qId;
            $ques_tag->tag_id = $tagId;
            $ques_tag->save();
        }
    }
}
