<?php

namespace App\Http\Controllers;

use App\Models\TransjakartaTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationalController extends Controller
{
    public function index()
    {
        $filterOptions = $this->getFilterOptions();

        $dayTypeDist = $this->applyGlobalFilter(TransjakartaTrip::query())->select('day_type', DB::raw('count(*) as total'))->groupBy('day_type')->get();
        $timeCategoryDist = $this->applyGlobalFilter(TransjakartaTrip::query())->select('time_category', DB::raw('count(*) as total'))->groupBy('time_category')->get();
        $travelTypeDist = $this->applyGlobalFilter(TransjakartaTrip::query())->select('travel_type', DB::raw('count(*) as total'))->groupBy('travel_type')->get();
        $ageGroupDist = $this->applyGlobalFilter(TransjakartaTrip::query())->select('age_group', DB::raw('count(*) as total'))->groupBy('age_group')->get();
        $peakHourDist = $this->applyGlobalFilter(TransjakartaTrip::query())->select('peak_hour', DB::raw('count(*) as total'))->groupBy('peak_hour')->get();
        
        $topCorridor = $this->applyGlobalFilter(TransjakartaTrip::query())->select('corridor_name', DB::raw('count(*) as total'))
            ->groupBy('corridor_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $stopsDist = $this->applyGlobalFilter(TransjakartaTrip::query())->select('stops_passed', DB::raw('count(*) as total'))
            ->groupBy('stops_passed')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('operational.index', compact(
            'filterOptions',
            'dayTypeDist',
            'timeCategoryDist',
            'travelTypeDist',
            'ageGroupDist',
            'peakHourDist',
            'topCorridor',
            'stopsDist'
        ));
    }
}
