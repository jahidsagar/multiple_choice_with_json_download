<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Question;
class ApiController extends Controller
{
    //
    
    public function getLatestTimeStampsBetweenTwo($time1,$time2) {
        if($time1==null && $time2==null)
            return null;
        if($time1==null)
            return $time2;
        if($time2==null)
            return $time1;
        return strtotime($time1)>strtotime($time2)? $time1: $time2;
    }
    
    
    public function getLatestTimeStampsBetweenAllDates($time1,$time2,$time3,$time4) {
        
        $latest= $time1;
        
        $latest = $this->getLatestTimeStampsBetweenTwo($latest,$time2);
        
        $latest = $this->getLatestTimeStampsBetweenTwo($latest,$time3);
        
        $latest = $this->getLatestTimeStampsBetweenTwo($latest,$time4);
        
        return $latest;
    }
    
    function check_if_cached($key) {
        
        if (Cache::has($key)){
            return Cache::get($key);
        }
        return 0;
        
    }
    
    function save_data_to_cache($data,$key) {
        
        $minute = 60;
        Cache::put($key, $data, $minute*60);
        return;
        
    }
    
    
    
    public function getAllData($package){
        $key = 'getAllData.'.$package;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            //return $cached;
        }
        
            
        $app = \App\App::where('package_name',$package)->first();
        
        if($app == null) return array();
        if($app->count()==0)
            return array();
        
        //variable of builders, not collections
        $categories = $this->getCategories($app);
        $categories_timestamps = $this->getLatestGenericTimeStampFromCollection($categories);
        //return $categories->get(); 
        $category_ids_array = $categories->get()->pluck('id')->toArray();
       
        $questions = $this->getQuestions($category_ids_array);
        $questions_timestamps = $this->getLatestGenericTimeStampFromCollection($questions);
        
        
        $question_tag = $this->getQuestionTags($questions->get()->pluck('id')->toArray());
        $question_tag_timestamps = $this->getLatestGenericTimeStampFromCollection($question_tag);
        
        $tags = $this->getTags($question_tag->get()->pluck('tag_id')->toArray());
        $tag_timestamps = $this->getLatestGenericTimeStampFromCollection($tags);
        
        $latest_time_between_all_fields = 
                $this->getLatestTimeStampsBetweenAllDates(
                        $categories_timestamps, 
                        $questions_timestamps, 
                        $question_tag_timestamps, 
                        $tag_timestamps);
        
        
        $json= array(
            'categories_timestamps' => $categories_timestamps."",
            'questions_timestamps' => $questions_timestamps."",
            'question_tag_timestamps' => $question_tag_timestamps."",
            'tag_timestamps' => $tag_timestamps."",
            'latest_time_between_all_fields' => $latest_time_between_all_fields."",
            'categories' => $categories->get(),
            'questions' => $questions->get(),
            'tags' => $tags->get(),
            'question_tags' => $question_tag->get()
        );
        
        $this->save_data_to_cache($json, $key);
        
        return $json;
    }
    
    public function getAllDataWithDate($package,$date){
        
        $key = 'getAllData'.$package.$date;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        
            
        $app = \App\App::where('package_name',$package)->first();
        
        if($app->count()==0)
            return array();
        
        $categories = $this->getCategories($app);
        $categories_with_date = $this->getCategoriesWithDate($app, $date);
        $categories_timestamps = $this->getLatestGenericTimeStampFromCollection($categories_with_date);
        //return $categories->get(); 
        $category_ids_array = $categories->get()->pluck('id')->toArray();
       
        $questions = $this->getQuestions($category_ids_array);
        $questions_with_date = $this->getQuestionsWithDate($category_ids_array, $date);
        $questions_timestamps = $this->getLatestGenericTimeStampFromCollection($questions_with_date);
        
        $question_id_array = $questions->get()->pluck('id')->toArray();
        
        $question_tag = $this->getQuestionTags($question_id_array);
        $question_tag_with_date = $this->getQuestionTagsWithDate($question_id_array,$date);
        $question_tag_timestamps = $this->getLatestGenericTimeStampFromCollection($question_tag_with_date);
        
        $tags_with_date = $this->getTagsWithDate($question_tag->get()->pluck('tag_id')->toArray(),$date);
        $tag_timestamps = $this->getLatestGenericTimeStampFromCollection($tags_with_date);
        
        $latest_time_between_all_fields = 
                $this->getLatestTimeStampsBetweenAllDates(
                        $categories_timestamps, 
                        $questions_timestamps, 
                        $question_tag_timestamps, 
                        $tag_timestamps);
        
        
        $json= array(
            'categories_timestamps' => $categories_timestamps."",
            'questions_timestamps' => $questions_timestamps."",
            'question_tag_timestamps' => $question_tag_timestamps."",
            'tag_timestamps' => $tag_timestamps."",
            'latest_time_between_all_fields' => $latest_time_between_all_fields."",
            'categories' => $categories_with_date->get(),
            'questions' => $questions_with_date->get(),
            'tags' => $tags_with_date->get(),
            'question_tags' => $question_tag_with_date->get()
        );
        
        $this->save_data_to_cache($json, $key);
        
        return $json;
        
        
        
    }
    
    
 public function getLatestGenericTimeStampFromCollection($question1){
     
     //$question = clone $question1;
     $question_c = clone $question1;
     $question_create = clone $question1;
     $question_update = clone $question1;

     
     if($question_c->get()->count()==0)
         return null;
     
     //$name = $question->orderBy('created_at','desc')->first()->id;
     $sorted_via_createdAt = $question_create->orderBy('created_at','desc')->first()->created_at;
     $sorted_via_updatedAt = $question_update->orderBy('updated_at','desc')->first()->updated_at;
     
     
    
     return $this->getLatestTimeStampsBetweenTwo($sorted_via_createdAt, $sorted_via_updatedAt);
     
 }

  public function getQuestionTags($questions){
        
        return \App\QuestionTag::whereIn('question_id',$questions);

    }
    
    public function getQuestionTagsWithDate($questions,$date){
        
        return \App\QuestionTag::
                whereIn('question_id', $questions)
                ->where(function ($query) use ($date) {
                    
                    $query->where('created_at', '>', $date)
                    ->orWhere('updated_at', '>', $date);
                    
                    });

        //  ->where('created_at','>=',$date)->get();

    }
    
    public function getTags($tags){
        
        return \App\Tag::whereIn('id',$tags);

    }
    
    public function getTagsWithDate($tags,$date){
        
        return \App\Tag::whereIn('id',$tags)
                ->where(function ($query) use ($date) {
                    
                    $query->where('created_at', '>', $date)
                    ->orWhere('updated_at', '>', $date);
                    
                    });

    }
    
    
    /*public function getLatestQuestionTags($question){
     
     $sorted_via_createdAt = $question::orderBy('created_at','desc')->first();
     $sorted_via_updatedAt = $question::orderBy('updated_at','desc')->first();
     
     return $this->getLatestTimeStamps($sorted_via_createdAt, $sorted_via_updatedAt);
     
 }*/
 
    
    public function getCategories($app){
        
        return \App\Category::where('apps_id',$app->id);

    }
    
    public function getCategoriesWithDate($app,$date){
        
        return \App\Category::where('apps_id',$app->id)
                ->where(function ($query) use ($date) {
                    
                    $query->where('created_at', '>', $date)
                    ->orWhere('updated_at', '>', $date);
                    
                    });

    }
    
    /*public function getCategoriesID($app){
        
        return \App\Category::where('apps_id',$app->id)->pluck('id')->toArray();

    }*/

    public function getQuestions($category){
        
        return Question::whereIn('categories_id',$category);

    }
    
    public function getQuestionsWithDate($category,$date){
        
        return Question::whereIn('categories_id',$category)
                ->where(function ($query) use ($date) {
                    
                    $query->where('created_at', '>', $date)
                    ->orWhere('updated_at', '>', $date);
                    
                    });

    }

// ******************** from here i have done , before towhid vaia *********************************
   //get all categories
    public function getCategoriesAll($app){
        $key = 'getAllCategories.'.$app;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }

        $categories = $this->getCategories(\App\App::where('package_name',$app)->first());
        $json= array(
            'categories_timestamps' => $this->getLatestGenericTimeStampFromCollection($categories)."",
            'categories' => $categories->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all categories with date
    public function getCategoriesAllwithDate($app,$date){
        $key = 'getAllCategories.'.$app.$date;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }

        $apps = \App\App::where('package_name',$app)->first();
        $categories = $this->getCategoriesWithDate($apps,$date);
        $json= array(
            'categories_timestamps' => $this->getLatestGenericTimeStampFromCollection($categories)."",
            'categories' => $categories->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all question
    public function getQuestionsAll($app){
        $key = 'getAllQuestions.'.$app;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        $categories = $this->getCategories(\App\App::where('package_name',$app)->first())->get()->pluck('id')->toArray();
        $questions = $this->getQuestions($categories);
        $json= array(
            'questions_timestamps' => $this->getLatestGenericTimeStampFromCollection($questions)."",
            'questions' => $questions->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all question with date
    public function getQuestionsAllwithDate($app,$date){
        $key = 'getAllQuestionswithDate.'.$app.$date;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        $categories = $this->getCategories(\App\App::where('package_name',$app)->first())->get()->pluck('id')->toArray();
        $questions = $this->getQuestionsWithDate($categories,$date);
        $json= array(
            'questions_timestamps' => $this->getLatestGenericTimeStampFromCollection($questions)."",
            'questions' => $questions->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all tags
    public function getTagsAll($app){
        $key = 'getAllTags.'.$app;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        $categories = $this->getCategories(\App\App::where('package_name',$app)->first())->get()->pluck('id')->toArray();
        $questions = $this->getQuestions($categories);
        $question_tag = $this->getQuestionTags($questions->get()->pluck('id')->toArray());
        $tags = $this->getTags($question_tag->get()->pluck('tag_id')->toArray());

        $json= array(
            'tags_timestamps' => $this->getLatestGenericTimeStampFromCollection($tags)."",
            'tags' => $tags->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all tags with date
    public function getTagsAllwithDate($app,$date){
        $key = 'getAllTagswithDate.'.$app.$date;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        $categories = $this->getCategories(\App\App::where('package_name',$app)->first())->get()->pluck('id')->toArray();
        $questions = $this->getQuestions($categories);
        $question_tag = $this->getQuestionTags($questions->get()->pluck('id')->toArray());
        $tags = $this->getTagsWithDate($question_tag->get()->pluck('tag_id')->toArray(),$date);

        $json= array(
            'tags_timestamps' => $this->getLatestGenericTimeStampFromCollection($tags)."",
            'tags' => $tags->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all question_tags
    public function getQuestionTagAll($app){
        $key = 'getAllQuestionTag.'.$app;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        $categories = $this->getCategories(\App\App::where('package_name',$app)->first())->get()->pluck('id')->toArray();
        $questions = $this->getQuestions($categories);
        $question_tag = $this->getQuestionTags($questions->get()->pluck('id')->toArray());

        $json= array(
            'question_tag_timestamps' => $this->getLatestGenericTimeStampFromCollection($question_tag)."",
            'question_tag' => $question_tag->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }

    //get all question_tags with date
    public function getQuestionTagAllwithDate($app,$date){
        $key = 'getAllQuestionTagwithDate.'.$app.$date;
        $cached = $this->check_if_cached($key);
        
        if($cached!=0)
        {
            return $cached;
        }
        $categories = $this->getCategories(\App\App::where('package_name',$app)->first())->get()->pluck('id')->toArray();
        $questions = $this->getQuestions($categories);
        $question_tag = $this->getQuestionTagsWithDate($questions->get()->pluck('id')->toArray(),$date);

        $json= array(
            'question_tag_timestamps' => $this->getLatestGenericTimeStampFromCollection($question_tag)."",
            'question_tag' => $question_tag->get(),
        );
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    

    // ========*************** version 4 Encrypted ********************=========
    public function getAll_Encrypted_Data($app)
    {
        $key = 'getAll_Encrypted_Data.'.$app;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $getData = $this->getAllData($app);//gettype = array
        $getEncryptData = $this->doEncrypt($getData, $app);

        $this->save_data_to_cache($getEncryptData, $key);
        return $getEncryptData;
    }
    public function getAll_Encrypted_DataWithDate($package , $date)
    {
        $key = 'getAll_Encrypted_DataWithDate.'.$package.$date;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $getData = $this->getAllDataWithDate($package , $date);
        $getEncryptData = $this->doEncrypt($getData, $package);
        $this->save_data_to_cache($getEncryptData, $key);
        return $getEncryptData;
    }

    //--------------  get categories and with date
    public function getCategoriesAll_Encrypted($package)
    {
        $key = 'getCategoriesAll_Encrypted.'.$package;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getCategoriesAll($package),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    public function getCategoriesAllwithDate_Encrypted($package , $date)
    {
        $key = 'getCategoriesAllwithDate_Encrypted.'.$package.$date;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json =  $this->doEncrypt($this->getCategoriesAllwithDate($package,$date),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    // ************** end ***************


    //--------------  get questions and with date
    public function getQuestionsAll_Encrypted($package)
    {
        $key = 'getQuestionsAll_Encrypted.'.$package;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getQuestionsAll($package),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    public function getQuestionsAllwithDate_Encrypted($package , $date)
    {
        $key = 'getQuestionsAllwithDate_Encrypted.'.$package.$date;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getQuestionsAllwithDate($package,$date),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    // ************** end ***************


    //--------------  get tags and with date
    public function getTagsAll_Encrypted($package)
    {
        $key = 'getTagsAll_Encrypted.'.$package;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getTagsAll($package),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    public function getTagsAllwithDate_Encrypted($package , $date)
    {
        $key = 'getTagsAllwithDate_Encrypted.'.$package.$date;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getTagsAllwithDate($package,$date),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    // ************** end ***************


    //--------------  get questiontags and with date
    public function getQuestionTagAll_Encrypted($package)
    {
        $key = 'getQuestionTagAll_Encrypted.'.$package;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getQuestionTagAll($package),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    public function getQuestionTagAllwithDate_Encrypted($package , $date)
    {
        $key = 'getQuestionTagAllwithDate_Encrypted.'.$package.$date;
        $cached = $this->check_if_cached($key);
        if($cached!=0)
        {
            return $cached;
        }
        $json = $this->doEncrypt($this->getQuestionTagAllwithDate($package,$date),$package);
        $this->save_data_to_cache($json, $key);
        return $json;
    }
    // ************** end ***************


// //////////////////////// encryption algorithm //////////////////////////////////
    public function doEncrypt($plain,$security_token) {
        
        $keypart_middle = $security_token;

        if(strlen($keypart_middle) < 10){
            $keypart_middle = str_pad($keypart_middle,10,"*");
        }else if(strlen($keypart_middle) > 10){
            $keypart_middle = substr($keypart_middle,0,10);
        }
        $key = 'Tnh87POvfT6'.$keypart_middle.'nhMKn689ghK';

        $iv  = 'mkYU90nhhwwq7ykO'; // 16 bytes
        $method = 'aes-256-cbc';
        //array to string = json_encode() or print_r($data , true)
        $result= base64_encode(openssl_encrypt (json_encode($plain), $method, $key, OPENSSL_RAW_DATA, $iv));
        
        return $result;

        //return openssl_encrypt ($plain, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

}
