<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class checkAcces
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userid = Auth::id();
        $component = $request->path();
        $hasacces = DB::table('users_acces')
                    ->join('menus', function ($join) use ($userid, $component) {
                                $join->on('users_acces.id_menus', '=', 'menus.id')
                                    ->where('users_acces.id_users', '=',  $userid)
                                    ->where('menus.route', '=',  $component);
                    })
                    ->select('users_acces.id')
                    ->count(); 
        if( ! $hasacces > 0)
        {
           return response()->json([                
                'hasacces' => false,
                'message' => 'No cuentas con el permiso para realizar esta acción'            
            ]);
        }
        else
        {
            return $next($request);
        }
    }
}
