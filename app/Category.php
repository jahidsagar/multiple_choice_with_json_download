<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\App as apps;
use App\Question;
class Category extends Model
{
    /*
    we need constructor for hold the value of edit category id
    */
    private $editId;
    function __construct($editId = 0) {
        $this->editId = $editId;
    }

    //find all users category according 
    //to apps which he made
    public function findUserCategories()
    {
    	$x = new apps();
        $appUser = $x->UsersApps();

        $Cate = array();

        foreach ($appUser as $value) {
            //it can return 1 or more object coz we have more 
            //than one element so we need another foreach inside this
            $CategoryArry= DB::table('categories')
                        ->where('apps_id',$value->id)
                        ->get();
            foreach ($CategoryArry as $single) {
                $Cate[] = $single;
            }
        }
        return $Cate;
    }


    //find all user made category with package name and parent cateogry name
    public function UserCategoryWithPackageandCategoryName()
    {
        $x = new apps();
        $appUser = $x->UsersApps();

        $Cate = array();

        foreach ($appUser as $value) {
            //it can return 1 or more object coz we have more 
            //than one element so we need another foreach inside this
            $CategoryArry= DB::table('categories')
                        ->where('apps_id',$value->id)
                        ->get();
            $categoryCopy = $CategoryArry;
            foreach ($CategoryArry as $single) {

                //set all the necessary value
                $id = $single->id;
                $name = $single->name;
                $parent_catid = $single->parent_catid;
                $apps_id = $single->apps_id;
                $appsName = $value->package_name;
                $weight = $single->weight;
                //find parent cate name
                $parentCategoryName = "no Parent";
                if($single->parent_catid != null && $single->parent_catid != -1){
                    foreach ($categoryCopy as $nameOfCategory) {
                        if($nameOfCategory->id == $single->parent_catid){
                            $parentCategoryName = $nameOfCategory->name;
                        }
                    }
                }

                $Cate[] =[
                    'id'=>$id,
                    'weight'=>$weight,
                    'name'=>$name,
                    'parent_catid'=>$parent_catid,
                    'apps_id'=>$apps_id,
                    'appsName'=>$appsName , 
                    'parentCategoryName'=>$parentCategoryName
                ];
            }
        }

        return $Cate;
    }

    //find all category related to app id specially used for api
    function FindAllCategories($appsId , $parentsId = -1)
    {
        $Categories= DB::table('categories')
                    ->where([
                        ['apps_id',$appsId],
                        ['parent_catid', $parentsId]
                    ])
                    ->get();

        if(count($Categories) != 0 ){

            foreach ($Categories as $Category) {
                    
                $all[] = [
                    'id'=>$Category->id,
                    'name'=>$Category->name,
                    'parent_catid'=>$Category->parent_catid,
                    'apps_id'=>$Category->apps_id,
                    'sub_category'=>(new Category())->FindAllCategories($appsId, $Category->id)
                ];
            }
            return $all;
            
        }else{
            return null ;
        }
    }

    //this function used for on category edit
    //where we dont show the users the the edited
    //category and its children
    function FindAllEditableCategory($appsId ,$editableId, $parentsId = -1)
    {
        $Categories= DB::table('categories')
                    ->where('apps_id',$appsId)
                    ->where('parent_catid', $parentsId)
                    ->get();

        if(count($Categories) != 0 ){

            foreach ($Categories as $Category) {
                if ($editableId == $Category->id) {
                    continue;
                }else{
                    $all[] = [
                        'id'            => $Category->id,
                        'name'          => $Category->name,
                        // 'parent_catid'  => $Category->parent_catid,
                        // 'apps_id'       => $Category->apps_id,
                        'sub_category'       => (new Category())->FindAllEditableCategory($appsId,$editableId, $Category->id)
                    ];
                    
                }
            }
            if (isset($all)) {
                return $all;
            }else{
                return null;
            }
            
        }else{
            return null ;
        }
    }

    //find all category and related questions
    function FindAllCategoriesWithQuestions($appsId , $parentsId = -1)
    {
        $Categories= DB::table('categories')
                    ->where([
                        ['apps_id',$appsId],
                        ['parent_catid', $parentsId]
                    ])
                    ->get();

        if(count($Categories) != 0 ){

            foreach ($Categories as $Category) {
                // $questions = DB::table('questions')
                //                 ->where('categories_id',$Category->id)
                //                 ->get();   

                $questions = Question::with("tags")->where('categories_id',$Category->id)->get();
                $all[] = [
                    'id'=>$Category->id,
                    'name'=>$Category->name,
                    'parent_catid'=>$Category->parent_catid,
                    'apps_id'=>$Category->apps_id,
                    'weight'=>$Category->weight,
                    'questions'=>$questions,
                    'sub_category'=>(new Category())->FindAllCategoriesWithQuestions($appsId, $Category->id)
                ];
            }
            return $all;
            
        }else{
            return array() ;
        }
    }

    //find all category and related questions with date
    function FindAllCategoriesWithQuestionsAndDates($appsId , $date, $parentsId = -1)
    {
        $Categories= DB::table('categories')
                    ->where([
                        ['apps_id',$appsId],
                        ['parent_catid', $parentsId]
                    ])
                    ->get();

        if(count($Categories) != 0 ){

            foreach ($Categories as $Category) {
                // $questions = DB::table('questions')
                //                 ->where('categories_id',$Category->id)
                //                 ->where('created_at','>=',$date)
                //                 ->get();   
                $questions = Question::with("tags")->where('categories_id',$Category->id)->get();
                $all[] = [
                    'id'=>$Category->id,
                    'name'=>$Category->name,
                    'parent_catid'=>$Category->parent_catid,
                    'apps_id'=>$Category->apps_id,
                    'weight'=>$Category->weight,
                    'questions'=>$questions,
                    'sub_category'=>(new Category())->FindAllCategoriesWithQuestionsAndDates($appsId, $date, $Category->id)
                ];
            }
            return $all;
            
        }else{
            return array() ;
        }
    }

    //change the package name to all children 
    //if user chage the package on edit
    function FindAllEditableCategoryandChangeThePackageName($AppId,$ParentCategoryId)
    {
        $Categories= DB::table('categories')
                    ->where([
                        ['parent_catid', $ParentCategoryId]
                    ])
                    ->get();

        if(count($Categories) != 0){
            foreach ($Categories as $category) {
                $val = Category::findorfail($category->id);
                $val->apps_id = $AppId;
                $val->save();

                (new Category())->FindAllEditableCategoryandChangeThePackageName($AppId , $category->id);
            }
        }else return ;
    }

    //take all category related to app id
    public function CategoriesRelatedtoApp($appsId)
    {
        $Categories= DB::table('categories')
                    ->where([
                        ['apps_id',$appsId],
                    ])
                    ->get();

        if(count($Categories) != 0 ){

            foreach ($Categories as $Category) {
                $Parent_Name = 'no parent';
                if ($Category->parent_catid != -1) {
                    $parent_category = DB::table('categories')->where('id',$Category->parent_catid)->select('categories.name')->first();
                    $Parent_Name = $parent_category->name;
                }else{
                    $Parent_Name = 'no parent';
                }
                

                $all[] = [
                    'id'=>$Category->id,
                    'name'=>$Category->name,
                    'parent_catid'=>$Category->parent_catid,
                    'parent_category_name'=>$Parent_Name,
                    'apps_id'=>$Category->apps_id,
                    'weight'=>$Category->weight
                ];
            }
            return $all;
            
        }else{
            return null ;
        }
    }
}