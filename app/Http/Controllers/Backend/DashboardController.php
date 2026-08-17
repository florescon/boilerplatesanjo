<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Domains\Auth\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use App\Models\Order;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }

    public function kpis()
    {
        return view('backend.dashboard.kpis');
    }

    public function graph_list()
    {
        return view('backend.dashboard.graph_list');
    }

    public function graph_bom()
    {
        return view('backend.dashboard.graph_bom');
    }

    public function graph_print()
    {
        $components = [];

        // $components[] = renderLivewireForPrint('orders-chart', [
        //     'periodo' => $periodo,
        // ]);

        // $components[] = renderLivewireForPrint('sales-kpi', [
        //     'periodo' => $periodo,
        // ]);

        $components[] = renderLivewireForPrint('backend.charts.graph-flagship-product', [
            // 'periodo' => $periodo,
        ]);

        // dd($components);

        return view('backend.dashboard.graph_print', compact('components'));
    }


public function graph_production()
{
    return view('backend.dashboard.graph_production');
}

public function graph_efficiency()
{
    return view('backend.dashboard.graph_efficiency');
}

public function graph_material()
{
    return view('backend.dashboard.graph_material');
}

public function graph_historic()
{
    return view('backend.dashboard.graph_historic');
}

public function graph_by_product()
{
    return view('backend.dashboard.graph_by_product');
}

public function graph_flagship_product()
{
    return view('backend.dashboard.graph_flagship_product');
}

public function graph_comparative()
{
    return view('backend.dashboard.graph_comparative');
}

public function graph_projection()
{
    return view('backend.dashboard.graph_projection');
}

public function graph_bottlenecks()
{
    return view('backend.dashboard.graph_bottlenecks');
}

public function graph_quality()
{
    return view('backend.dashboard.graph_quality');
}

public function graph_observations()
{
    return view('backend.dashboard.graph_observations');
}

public function graph_costs()
{
    return view('backend.dashboard.graph_costs');
}

public function graph_labour()
{
    return view('backend.dashboard.graph_labour');
}

public function graph_profitability()
{
    return view('backend.dashboard.graph_profitability');
}

public function graph_by_order()
{
    return view('backend.dashboard.graph_by_order');
}

public function graph_oee()
{
    return view('backend.dashboard.graph_oee');
}

public function graph_tendencies()
{
    return view('backend.dashboard.graph_tendencies');
}

public function graph_ranking()
{
    return view('backend.dashboard.graph_ranking');
}


    public function index_old()
    {
        return view('backend.dashboard_old');
    }

}