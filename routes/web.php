<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
// 	if (\Auth::check()) {
// 		return view('admin.includes.adminfront');
// 	}
//     return view('welcome');
// });
Route::get('/','DashboradController@index')->name('root');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//dashborad
Route::get('/dashboard','DashboradController@index')->name('dashboard')->middleware('auth');


//set roles
Route::get('/dashboard/setrole','setRoleController@index')->middleware('auth', App\Http\Middleware\Admin::class);
Route::post('/dashboard/setrole/{id}','setRoleController@setRole')->middleware('auth', App\Http\Middleware\Admin::class);;

//set Questions 
Route::get('/dashboard/questions/add/{category_id}','QuestionsController@index')->middleware('auth');
Route::get('/dashboard/questions/seeallquestion','QuestionsController@seeAll')->middleware('auth');//see all Questions
Route::post('/dashboard/questions','QuestionsController@store')->middleware('auth');//add Questions
Route::get('/dashboard/questions/edit/{id}','QuestionsController@edit')->middleware('auth');//edit Questions
Route::post('/dashboard/questions/edit/{id}','QuestionsController@editStore')->middleware('auth');
Route::get('/dashboard/questions/{id}','QuestionsController@delete')->middleware('auth');//delete Questions

//import json file
Route::post('/import','QuestionsController@take_json')->middleware('auth');
//import json file v3
Route::post('/importv3','QuestionsController@take_json_v3')->middleware('auth');


//set catgory
Route::get('/dashboard/category','CategoryController@index')->middleware('auth');
Route::post('/dashboard/category','CategoryController@store')->middleware('auth');
Route::get('/dashboard/category/edit/{id}','CategoryController@edit')->middleware('auth');
Route::post('/dashboard/category/edit/{id}','CategoryController@editStore')->middleware('auth');
//set App
Route::get('/dashboard/app','AppController@index')->middleware('auth');
Route::post('/dashboard/app','AppController@storeApp')->middleware('auth');
Route::get('/dashboard/app/edit/{id}','AppController@edit')->middleware('auth');
Route::post('/dashboard/app/edit/{id}','AppController@editStore')->middleware('auth');

//ajax request from tags
Route::post('/dashboard/tags/save',"TagsController@tags_ajax");

//show generic data like category , package , tag related questions
Route::get('/dashboard/category_show/{id}/{name}','ShowController@category')->middleware('auth');
Route::get('/dashboard/app_show/{id}/{name}','ShowController@app')->middleware('auth');
Route::get('/dashboard/tag_show/{id}/{name}','ShowController@tag')->middleware('auth');
//download questions as json
Route::get('/download/{package_name}','AppController@download');
Route::get('/edownload/{package_name}','AppController@enrypted_odwnload');



//package related category
Route::get('/package/{id}/{name}','AppController@allCategory');
//testing url no need in live server 
// Route::get('/kisuekta/{appId}/{parentid?}','ShowController@something');
// Route::get('/kisuektaedit/{appId}/{editCategoryId}','ShowController@editsomething');

View::composer('*', function ($view) {
    //
    if(\Auth::check()){
        $roleforview = App\CheckRole::hasRole(\Auth::user()->id);
        $view->with('roleforview',$roleforview);
    }
});