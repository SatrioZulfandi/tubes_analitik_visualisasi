<?php

namespace App\Http\Controllers;

use App\Models\ClusterMaster;
use App\Models\TransjakartaTrip;

abstract class Controller
{
    /**
     * Mengambil opsi filter untuk Global Dashboard Filter.
     */
    protected function getFilterOptions()
    {
        return [
            'clusters' => ClusterMaster::orderBy('cluster_code')->get(),
            'day_types' => TransjakartaTrip::select('day_type')->whereNotNull('day_type')->distinct()->orderBy('day_type')->pluck('day_type'),
            'peak_hours' => [1 => 'Peak Hour', 0 => 'Non Peak Hour'],
            'corridors' => TransjakartaTrip::select('corridor_name')->whereNotNull('corridor_name')->distinct()->orderBy('corridor_name')->pluck('corridor_name'),
            'time_categories' => TransjakartaTrip::select('time_category')->whereNotNull('time_category')->distinct()->orderBy('time_category')->pluck('time_category'),
            'age_groups' => TransjakartaTrip::select('age_group')->whereNotNull('age_group')->distinct()->orderBy('age_group')->pluck('age_group'),
            'travel_types' => TransjakartaTrip::select('travel_type')->whereNotNull('travel_type')->distinct()->orderBy('travel_type')->pluck('travel_type'),
        ];
    }

    /**
     * Menerapkan Global Dashboard Filter ke kueri Eloquent.
     */
    protected function applyGlobalFilter($query)
    {
        $request = request();
        
        return $query
            ->when($request->filled('cluster_id'), fn($q) => $q->where('cluster_id', $request->cluster_id))
            ->when($request->filled('day_type'), fn($q) => $q->where('day_type', $request->day_type))
            ->when($request->filled('peak_hour'), fn($q) => $q->where('peak_hour', $request->peak_hour))
            ->when($request->filled('corridor_name'), fn($q) => $q->where('corridor_name', $request->corridor_name))
            ->when($request->filled('time_category'), fn($q) => $q->where('time_category', $request->time_category))
            ->when($request->filled('age_group'), fn($q) => $q->where('age_group', $request->age_group))
            ->when($request->filled('travel_type'), fn($q) => $q->where('travel_type', $request->travel_type));
    }
}
