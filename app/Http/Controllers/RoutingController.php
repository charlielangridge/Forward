<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Visit;
use Request;

class RoutingController extends Controller
{
    public function route(Request $request)
    {
    	$domain = Request::server('HTTP_HOST');
    	$route = Route::where('in','like', '%'.$domain.'%')->first();

    	if ($route != NULL)
    	{
    		$visit = new Visit;
    		$visit->ip = Request::ip();
    		$visit->http_user_agent = Request::server('HTTP_USER_AGENT');
    		$visit->http_referer = Request::server('HTTP_REFERER');
            $visit->http_headers = Request::server();
    		$visit->route_id = $route->id;
    		$visit->save();
    		
	    	return redirect($route->out);
    	}
    	else
    	{
    		return redirect(url('admin'));
    	}
    }

    public function test(Request $request)
    {
    	dd(Request::server());
    }
}
