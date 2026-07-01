<?php

namespace App\Http\Controllers;

use App\Models\TransjakartaTrip;
use App\Models\ClusterMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $filterOptions = $this->getFilterOptions();

        // Mengaplikasikan Filter Global (PowerBI style)
        $totalTrips = $this->applyGlobalFilter(TransjakartaTrip::query())->count();
        $totalClusters = ClusterMaster::count(); 
        
        $avgAge = $this->applyGlobalFilter(TransjakartaTrip::query())->avg('age');
        $avgTravelDuration = $this->applyGlobalFilter(TransjakartaTrip::query())->avg('travel_duration');
        $avgStopsPassed = $this->applyGlobalFilter(TransjakartaTrip::query())->avg('stops_passed');
        $totalRevenue = $this->applyGlobalFilter(TransjakartaTrip::query())->sum('pay_amount');

        $clusterDistribution = $this->applyGlobalFilter(TransjakartaTrip::query())
            ->select('cluster_id', DB::raw('count(*) as total'))
            ->with('cluster')
            ->groupBy('cluster_id')
            ->get();

        $peakHourDistribution = $this->applyGlobalFilter(TransjakartaTrip::query())
            ->select('peak_hour', DB::raw('count(*) as total'))
            ->groupBy('peak_hour')
            ->get();

        $dayTypeDistribution = $this->applyGlobalFilter(TransjakartaTrip::query())
            ->select('day_type', DB::raw('count(*) as total'))
            ->groupBy('day_type')
            ->get();

        $topCorridors = $this->applyGlobalFilter(TransjakartaTrip::query())
            ->select('corridor_name', DB::raw('count(*) as total'))
            ->groupBy('corridor_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'filterOptions',
            'totalTrips',
            'totalClusters',
            'avgAge',
            'avgTravelDuration',
            'avgStopsPassed',
            'totalRevenue',
            'clusterDistribution',
            'peakHourDistribution',
            'dayTypeDistribution',
            'topCorridors'
        ));
    }
}
