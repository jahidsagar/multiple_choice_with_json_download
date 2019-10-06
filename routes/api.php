<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//=============this api used for ajax request in category
Route::get('/category/{id}','CategoryController@getRelatedCategory');
Route::get('/category/editable/{appsId}/{editCategoryId}','CategoryController@getEditableCategory');
//================end 

//all these used for finding questions in v1
//show all questions
Route::get('v1/questionsall/{package}','QuestionsController@apiIndex');
//show questions with date
Route::get('v1/questionsall/{package}/{date}','QuestionsController@apiIndexDate');
//update quesitons
Route::put('v1/questionsupdate/{package}/{id}','QuestionsController@update');

//=========these used finding with category
//show all questions
Route::get('v2/questionsall/{package}','QuestionsController@apiIndex2');
//show questions with date
Route::get('v2/questionsall/{package}/{date}','QuestionsController@apiIndexDate2');


//*******version 3***********

Route::get('v3/{package}/all_data/','ApiController@getAllData');
Route::get('v3/{package}/all_data/{date}','ApiController@getAllDataWithDate');

Route::get('v3/{package}/categories/','ApiController@getCategoriesAll');
Route::get('v3/{package}/categories/{date}','ApiController@getCategoriesAllwithDate');

Route::get('v3/{package}/questions/','ApiController@getQuestionsAll');
Route::get('v3/{package}/questions/{date}','ApiController@getQuestionsAllwithDate');

Route::get('v3/{package}/tags/','ApiController@getTagsAll');
Route::get('v3/{package}/tags/{date}','ApiController@getTagsAllwithDate');

Route::get('v3/{package}/question_tags/','ApiController@getQuestionTagAll');
Route::get('v3/{package}/question_tags/{date}','ApiController@getQuestionTagAllwithDate');

//================== version 4 encrypted ===================== lets fight :) 
Route::get('v4/{package}/all_data/','ApiController@getAll_Encrypted_Data');
Route::get('v4/{package}/all_data/{date}','ApiController@getAll_Encrypted_DataWithDate');

Route::get('v4/{package}/categories/','ApiController@getCategoriesAll_Encrypted');
Route::get('v4/{package}/categories/{date}','ApiController@getCategoriesAllwithDate_Encrypted');

Route::get('v4/{package}/questions/','ApiController@getQuestionsAll_Encrypted');
Route::get('v4/{package}/questions/{date}','ApiController@getQuestionsAllwithDate_Encrypted');

Route::get('v4/{package}/tags/','ApiController@getTagsAll_Encrypted');
Route::get('v4/{package}/tags/{date}','ApiController@getTagsAllwithDate_Encrypted');

Route::get('v4/{package}/question_tags/','ApiController@getQuestionTagAll_Encrypted');
Route::get('v4/{package}/question_tags/{date}','ApiController@getQuestionTagAllwithDate_Encrypted');