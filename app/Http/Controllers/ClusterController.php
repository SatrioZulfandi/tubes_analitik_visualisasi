<?php

namespace App\Http\Controllers;

use App\Models\ClusterMaster;
use App\Models\TransjakartaTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusterController extends Controller
{
    public function index()
    {
        $filterOptions = $this->getFilterOptions();

        $clusterSummary = $this->applyGlobalFilter(TransjakartaTrip::query())
            ->select(
                'cluster_id',
                DB::raw('count(*) as total_members'),
                DB::raw('avg(age) as avg_age'),
                DB::raw('avg(travel_duration) as avg_duration'),
                DB::raw('avg(stops_passed) as avg_stops'),
                DB::raw('avg(pay_amount) as avg_pay'),
                DB::raw('avg(tap_in_hour) as avg_hour'),
                DB::raw('(sum(peak_hour) / count(*)) * 100 as peak_hour_percentage')
            )
            ->with('cluster')
            ->groupBy('cluster_id')
            ->get();

        return view('cluster.index', compact('filterOptions', 'clusterSummary'));
    }
}
