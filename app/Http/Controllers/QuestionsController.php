<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\App as app;
use DB;
use App\Category;
use App\CheckRole;
use App\Tag;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\CategoryController;
use App\QuestionTag;
use Illuminate\Validation\Rule;
class QuestionsController extends Controller
{
    //
    public function index($category_id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            // $cat = new Category();
            // $appName = new app();
            $category = Category::findorfail($category_id);
            $appName = app::findorfail($category->apps_id);
            $allTags = DB::table('tags')
                    ->select('name')
                    ->get();
    	   return view('admin.includes.questions.questions',
            [
                'App' => $appName,
                'Category'=>$category,
                'Alltags'=>$allTags,
            ]);
        }
    }

    public function seeAll()
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            //many question may have same category
            //so we have to find all questions with that 
            //category.

            //grap all category
            $cat = new Category();
            $x = $cat->findUserCategories();
            $y = array();
            //take all questions
            foreach ($x as $category) {

                $k = Question::with('tags')->where('categories_id',$category->id)->get();
                foreach ($k as $single) {
                    $y[] = $single;
                }
            }
        	return view('admin.includes.questions.questionsall',
                [
                    'Questions'=>$y, 
                ]);
        }
    }

    public function store(Request $request)
    {
        // return $request->all();
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            //validate request
            $validatedData = $request->validate([
                'question' => [
                    'required',
                    Rule::unique('questions')->where(function ($query) use ($request){
                        return $query->where('categories_id', $request->categories_id);
                    })
                ],
                'option1' => 'required',
                'option2' => 'required',
                'option3' => 'required',
                'option4' => 'required',
                'ans' => 'required|numeric',
                'difficulty' => 'required|numeric',
                'categories_id' => 'required|numeric',
            ]);



            $val = new Question();
            $val->question = $request->question ;
            $val->op1 = $request->option1;
            $val->op2 = $request->option2;
            $val->op3 = $request->option3;
            $val->op4 = $request->option4;
            $val->ans = $request->ans;
            $val->difficulty = $request->difficulty;
            $val->categories_id = $request->categories_id;
            if($val->save()){
                if (isset($request->tags)) {
                    $tagControl = new TagsController();
                    $tagControl->insert($request->tags, $val->id);

                }
                //explode string 
                // $explodeTags = explode(",", $request->tags);
                //find each and insert
                // $tagControl = new TagsController();
                //send tags with id
                // $tagControl->insert($request->tags, $val->id);
                return redirect($request->previous_url)->with('msg','save successful');
            }
            return redirect($request->previous_url)->with('msg','may be some errors');
        }
    }

    public function edit($id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            //grap all id from tags
        	if (Question::findorfail($id)) {
                $q = Question::find($id);
                $c = Category::find($q->categories_id);
                $allTags = DB::table('tags')
                    ->select('name')
                    ->get();
                return view('admin.includes.questions.questionedit',
                    [
                        'data'=>Question::with('tags')->find($id) , 
                        'App'=>app::all(), 
                        'Rcate'=> $c ,
                        'Alltags'=>$allTags
                    ]);
            }
        }
    }

    public function editStore(Request $request , $id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            //validate questions
            $validatedData = $request->validate([
                'question' => [
                    'required',
                ],
                'option1' => 'required',
                'option2' => 'required',
                'option3' => 'required',
                'option4' => 'required',
                'ans' => 'required|numeric',
                'difficulty' => 'required|numeric',
                'categories_id' => 'required|numeric',
            ]);



            $val = Question::findorfail($id);
            $val->question = $request->question ;
            $val->op1 = $request->option1;
            $val->op2 = $request->option2;
            $val->op3 = $request->option3;
            $val->op4 = $request->option4;
            $val->ans = $request->ans;
            $val->difficulty = $request->difficulty;
            $val->categories_id = $request->categories_id;
            if($val->save()){
                //first delete the question_tag
                DB::table('question_tag')->where('question_id', $id)->delete();
                //explode string 
                //$explodeTags = explode(",", $request->tags);
                //find each and insert
                if (isset($request->tags)) {
                    $tagControl = new TagsController();
                    $tagControl->insert($request->tags, $val->id);

                }
                // $tagControl = new TagsController();
                //send tags with id
                // $tagControl->insert($request->tags, $val->id);
                return redirect($request->previous_url)->with('msg','edit successful');
            }
            return redirect($request->previous_url)->with('msg','may be some errors');
        }
    }

    public function delete($id)
    {
        if(CheckRole::hasRole(\Auth::user()->id) == ('admin') || CheckRole::hasRole(\Auth::user()->id) ==('user') ){
            //delete all value form questions map tag
        	if (Question::findorfail($id)->delete()) {
                return redirect()->back()->with('msg','delete successful');
            }
            return redirect()->back()->with('msg','may be some errors');
        }
    }

    public function take_json(Request $request)
    {
        if($request->hasfile('json_file')){
            $arr = json_decode(file_get_contents($request->file('json_file')),false,100);
            $data = (object) $arr;
            foreach ($data as $value) {
                //first check if the category exist or not
                //if yes than use its id otherwise create one
                $find_category_if_exist = Category::all()->where('name',$value->name)->where('apps_id',$request->package_id)->first();
                if(isset($find_category_if_exist)){
                    if(!empty( $value->questions)){
                        foreach ($value->questions as $single_question) {
                            (new Question())->questionsEntry($single_question , $find_category_if_exist->id);
                        }
                    }
                    if(! empty($value->sub_category)){
                        foreach ($value->sub_category as $sc) {
                            (new Question())->import($sc, $request->package_id , $find_category_if_exist->id);
                        }
                    }
                }else {
                    $cat = new Category();
                    $cat->name = $value->name;
                    $cat->weight = $value->weight;
                    $cat->parent_catid = -1;
                    $cat->apps_id = $request->package_id;
                    if($cat->save()){
                        if(!empty( $value->questions)){
                            foreach ($value->questions as $single_question) {
                                (new Question())->questionsEntry($single_question , $cat->id);
                            }
                        }
                        if(! empty($value->sub_category)){
                            foreach ($value->sub_category as $sc) {
                                (new Question())->import($sc, $request->package_id , $cat->id);
                            }
                        }
                    }
                }
            }
            return back()->with('msg','all added successfully.');
        }
        return back()->with('msg','file not found!!!');
    }
    //for import json file v3
    public function take_json_v3(Request $request)
    {
        if ($request->has('json_file')) {
            $jsonfile = json_decode(file_get_contents($request->file('json_file')),true);

            $data = (object)$jsonfile;
            if(array_key_exists('questions', $data) && array_key_exists('categories', $data) && array_key_exists('tags', $data) && array_key_exists('question_tags', $data)){

            }
            else return back()->with('msg','sorry format is not correct !!!');
            foreach ($data->categories as $value) {
                //find if yes getid store as myid
                $categoryId = Category::where('name',$value['name'])->where('apps_id',$request->package_id)->first();
                if($categoryId != null){
                    $category_list[]=[
                        'myid'=>$categoryId->id,
                        'id'=>$value['id'],
                        'name'=>$value['name'],
                        'weight'=>$value['weight'],
                        'parent_catid'=>$categoryId->parent_catid
                    ];
                }else{
                //if no than store and take id as my id
                    $val = new Category();
                    $val->name = $value['name'];
                    $val->weight = $value['weight'];
                    $val->apps_id = $request->package_id;
                    $val->parent_catid = ($value['parent_catid'] == -1) ? -1 : $this->find_parent_category($value['parent_catid'],$category_list);

                    if($val->save()){
                        $category_list[]=[
                            'myid'=>$val->id,
                            'id'=>$value['id'],
                            'name'=>$val->name,
                            'weight'=>$val->weight,
                            'parent_catid'=>$val->parent_catid
                        ];
                    }
                }
            }
            // return $category_list; complete category
            foreach ($data->tags as $key) {
                $tagId = Tag::where('name',$key['name'])->first();
                if($tagId != null){
                    $tag_list[] = [
                        'myid'=>$tagId->id,
                        'id'=>$key['id'],
                        'name'=>$key['name']
                    ];
                }else{
                    $valTag = new Tag();
                    $valTag->name = $key['name'];

                    if($valTag->save()){
                        $tag_list[] = [
                            'myid'=>$valTag->id,
                            'id'=>$key['id'],
                            'name'=>$key['name']
                        ];
                    }
                }
            }
            // return $tag_list; complete tag
            foreach ($data->questions as $questionSingle) {
                $valQuestion = new Question();
                $valQuestion->question = $questionSingle['question'] ;
                $valQuestion->op1 = $questionSingle['op1'];
                $valQuestion->op2 = $questionSingle['op2'];
                $valQuestion->op3 = $questionSingle['op3'];
                $valQuestion->op4 = $questionSingle['op4'];
                $valQuestion->ans = $questionSingle['ans'];
                $valQuestion->difficulty = $questionSingle['difficulty'];
                $valQuestion->categories_id = $this->find_parent_category($questionSingle['categories_id'],$category_list);
                if($valQuestion->save()){
                    $question_list[] = [
                        'myid'=>$valQuestion->id,
                        'id'=>$questionSingle['id']
                    ];
                }
            }
            // return $question_list; complete questions
            foreach ($data->question_tags as $questionTag_single) {
                $qu_id = $this->question_id($questionTag_single['question_id'], $question_list);
                $ta_id = $this->tag_id($questionTag_single['tag_id'], $tag_list);

                $qu_ta = new QuestionTag();
                $qu_ta->question_id = $qu_id;
                $qu_ta->tag_id = $ta_id;
                $qu_ta->save();
            }
            return back()->with('msg','save successful');
        }
        return back()->with('msg','some error occured !!!');
    }
    //find the parent category from the json matching with my object
    //if not found it will be parent
    public function find_parent_category($a , $ca_li){
        foreach ($ca_li as $key) {
            if($key['id'] == $a) return $key['myid'];
        }
        return -1;
    }
    //find question id
    public function question_id($a,$ques_li)
    {
        foreach ($ques_li as $value) {
            if($value['id'] == $a) return $value['myid'];
        }
        return -1;
    }
    //find tag id
    public function tag_id($a,$tag_li)
    {
        foreach ($tag_li as $value) {
            if($value['id'] == $a) return $value['myid'];
        }
        return -1;
    }
    //this methods used
    //for api purpose and personally i make it as usual
    //=================================================================
    public function apiIndex($package){
        if ($packages = DB::table('apps')
                    ->where('package_name',$package)
                    ->get()) {
            $QuestionWithTag = array();
            $packageId = $packages[0]->id;
            $allQuestionsId = null;
            
            $allQuestionsId = DB::table('questions')
                    ->join('categories', 'categories.id','=','questions.categories_id')
                    ->where('categories.id',$packageId)
                    ->select('questions.id')
                    ->get();
            foreach ($allQuestionsId as $qId) {
                //find question id from category
               $QuestionWithTag[] = Question::with('tags')->find($qId->id);
            }

            return $QuestionWithTag;
        }
        return "error 404";
    }
    public function apiIndexDate($package,$date = null){
        if ($packages = DB::table('apps')
                    ->where('package_name',$package)
                    ->get()) {


                $QuestionWithTag = array();
                $packageId = $packages[0]->id;
                $allQuestionsId = null;

                if ($date != null) {
                    // $d = strstr($date,' ',true); //get the date
                    // $t = strstr($date,' '); //get the time

                    $allQuestionsId = DB::table('questions')
                            ->join('categories', 'categories.id','=','questions.categories_id')
                            ->where('categories.id',$packageId)
                            ->where('questions.updated_at','>=' ,$date)
                            ->where('questions.created_at','>=' ,$date)
                            ->select('questions.id')
                            ->get();
                    foreach ($allQuestionsId as $qId) {
                        //find question id from category
                       $QuestionWithTag[] = Question::with('tags')->find($qId->id);
                    }
            }

            return $QuestionWithTag;

        }return "error 404";
    }

    public function update(Request $request ,$package, $id){
        $val = Question::findorfail($id);
        $val->question = $request->question ;
        $val->op1 = $request->option1;
        $val->op2 = $request->option2;
        $val->op3 = $request->option3;
        $val->op4 = $request->option4;
        $val->ans = $request->ans;
        $val->difficulty = $request->difficulty;
        $val->categories_id = $request->package_name;
        if($val->save()){
            //first delete the question_tag
            DB::table('question_tag')->where('question_id', $id)->delete();
            //explode string 
            $explodeTags = explode(",", $request->tags);
            //find each and insert
            $tagControl = new TagsController();
            //send tags with id
            $tagControl->insert($explodeTags, $val->id);
            return $val;
        }
        return "error 404";
    }

    public function apiIndex2($package){
        if ($packages = DB::table('apps')
                    ->where('package_name',$package)
                    // ->select('id')
                    ->get()) {
            // return $packages[0]->id;
            $var = new Category();
            return $var->FindAllCategoriesWithQuestions($packages[0]->id);
        }
        return "error 404";
    }
    public function apiIndexDate2($package,$date){
        if ($packages = DB::table('apps')
                    ->where('package_name',$package)
                    ->get()) {

            $var = new Category();
            return $var->FindAllCategoriesWithQuestionsAndDates($packages[0]->id, $date);
                

        }return "error 404";
    }
}
