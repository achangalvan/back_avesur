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

Route::get('/test', function (Request $request) {
    return response()->json([    	
    	'user' => [
    		'first_name' => 'Alan',
    		'last_name' => 'Chan'
    	]
    ]);
})->middleware('auth:api','checkacces');

Route::get('acces_component/{component}', function ($component) {
    $menu = array();
    $userid = Auth::id();
    $hasacces = DB::table('users_acces')
                ->join('menus', function ($join) use ($userid, $component) {
                    $join->on('users_acces.id_menus', '=', 'menus.id')
                        ->where('users_acces.id_users', '=',  $userid)
                        ->where('menus.route', '=', $component);
                })
                ->select('users_acces.id')
                ->count();
    if($hasacces > 0){
        $menu = DB::table('menus')->select('id','route')->get();
    }
	return response()->json([    	
    	'acces' =>  $hasacces,
        'menu' => $menu     
    ]);
})->middleware('auth:api');