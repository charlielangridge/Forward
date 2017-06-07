<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Visit;
use Charts;
use Jenssegers\Agent\Agent;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title

        // Get visits data
        $visits = Visit::all();

        $mobileTotal = $tabletTotal = $iOSTotal = $androidOSTotal = 0;
        $referers = [];
        $referers['direct'] = 0;
        $routeVisits = [];

        foreach ($visits as $visit) {
            
            if (array_key_exists($visit->route_id, $routeVisits))
            {
                $routeVisits[$visit->route_id] += 1;
            }
            else
            {
                $routeVisits[$visit->route_id] = 1;
            }

            foreach ($routeVisits as $key => $value) {
                $routeNames[$key] = Route::find($key)->in;
            }

            $agent = new Agent();
            $agent->setUserAgent($visit->http_user_agent);
            $agent->setHttpHeaders($visit->http_headers);
            if ($agent->isMobile())
            {
                $mobileTotal += 1;
            }
            elseif ($agent->isTablet())
            {
                $tabletTotal += 1;
            }

            if ($agent->isiOS())
            {
                $iOSTotal += 1;
            }
            elseif ($agent->isAndroidOS())
            {
                $androidOSTotal += 1;
            }

            
            
            if($visit->http_referer == NULL)
            {
                $referers['direct'] += 1;
            }
            else
            {
                if(array_key_exists($visit->http_referer,$referers))
                {
                    $referers[$visit->http_referer] += 1;
                }
                else
                {
                    $referers[$visit->http_referer] = 1;   
                }
            }

        }

        $rSites = [];
        $rVisits = [];
        foreach ($referers as $key => $referer) {
            array_push($rSites, $key);
            array_push($rVisits, $referer);
        }
     

        $desktopTotal = count($visits) - $mobileTotal - $tabletTotal;


         $this->data['chartPlatform'] = 
         Charts::create('pie', 'chartjs')
            ->title('Platform')
            ->labels(['Mobile', 'Tablet', 'Desktop'])
            ->values([$mobileTotal,$tabletTotal,$desktopTotal])
            ->dimensions(0,0);

        $this->data['chartMobileOS'] = 
         Charts::create('pie', 'chartjs')
            ->title('Mobile OS')
            ->labels(['iOS', 'Android'])
            ->values([$iOSTotal,$androidOSTotal])
            ->dimensions(0,0);

        $this->data['chartReferer'] = 
         Charts::create('bar', 'highcharts')
            ->title('Referers')
            ->labels($rSites)
            ->values($rVisits)
            ->dimensions(0,0)
            ->elementLabel('Visits');

        $this->data['chartRoutes'] = 
         Charts::create('bar', 'highcharts')
            ->title('Routes')
            ->labels($routeNames)
            ->values($routeVisits)
            ->dimensions(0,0)
            ->elementLabel('Visits');

        return view('backpack::dashboard', $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(config('backpack.base.route_prefix').'/dashboard');
    }
}
