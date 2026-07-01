<?php

namespace App\Http\Controllers;

use App\Models\TransjakartaTrip;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Dashboard BI Transjakarta
|--------------------------------------------------------------------------
| Controller : DatasetController
|
| Bertugas mengambil data dari database
| dan mengirimkannya ke Blade View.
|
| Tidak mengandung Business Logic Machine Learning.
|
*/

class DatasetController extends Controller
{
    public function index(Request $request)
    {
        // Pengambilan data menggunakan eloquent query builder, filtering dinamis dengan when()
        // dan menghindari N+1 problem menggunakan eager loading 'cluster'
        $dataset = TransjakartaTrip::with('cluster')
            ->when($request->filled('cluster_id'), function ($query) use ($request) {
                // Menggunakan scope ByCluster dari model
                $query->byCluster($request->cluster_id);
            })
            ->when($request->filled('day_type'), function ($query) use ($request) {
                $query->where('day_type', $request->day_type);
            })
            ->when($request->filled('peak_hour'), function ($query) use ($request) {
                $query->where('peak_hour', $request->peak_hour);
            })
            ->when($request->filled('corridor_name'), function ($query) use ($request) {
                $query->where('corridor_name', 'like', '%' . $request->corridor_name . '%');
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                // Global search bar functionality
                $query->where(function($subQuery) use ($request) {
                    $subQuery->where('pay_card_bank', 'like', '%' . $request->search . '%')
                             ->orWhere('corridor_name', 'like', '%' . $request->search . '%')
                             ->orWhere('time_category', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(20);

        return view('dataset.index', compact('dataset'));
    }
}
