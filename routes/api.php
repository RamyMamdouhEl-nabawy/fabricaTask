<?php

Route::group([

   'prefix' => 'auth'

], function () {
Route::post('login',['as' => 'login','uses' => 'AuthController@login']);
Route::post('logout',['as' => 'logout','uses' =>  'AuthController@logout']);
Route::post('refresh',['as' => 'refresh','uses' => 'AuthController@refresh']);
Route::post('me',['as' => 'me','uses' =>  'AuthController@me']);

Route::post('register',['as' => 'register','uses' => 'AuthController@register']);
Route::any('userData',['as' => 'userData','uses' => 'AuthController@userData']);
Route::get('courses',['as' => 'courses','uses' => 'AuthController@getCoursesData']);
Route::post('AddCourses',['as' => 'AddCourses','uses' => 'AuthController@newCourse']);
Route::post('CourseReservation', 'AuthController@reservation');

Route::post('login', 'UsersController@login');
Route::get('profile', 'UsersController@userProfile');
Route::post('MyCourses', 'UsersController@myCourses');

});