<?php

namespace App\Http\Middleware\WL;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth, Response;

class WLStaffLoginMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('wl_staff')->check()) // 未登录
        {
            return redirect('/login');

//            $return["status"] = false;
//            $return["log"] = "admin-no-login";
//            $return["msg"] = "请先登录";
//            return Response::json($return);
        }
        else
        {
            $me_admin = Auth::guard('wl_staff')->user();
            // 判断用户是否被封禁
            if($me_admin->item_status != 1)
            {
                Auth::guard('wl_staff')->logout();
                return redirect('/login');
            }
            view()->share('me_admin', $me_admin);
        }
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $me_admin = Auth::guard('wl_staff')->user();
        view()->share('me', $me_admin);
    }
}
