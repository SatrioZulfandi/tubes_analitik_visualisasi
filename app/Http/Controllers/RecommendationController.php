<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Dashboard BI Transjakarta
|--------------------------------------------------------------------------
| Controller : RecommendationController
|
| Bertugas mengambil data dari database
| dan mengirimkannya ke Blade View.
|
| Tidak mengandung Business Logic Machine Learning.
|
*/

class RecommendationController extends Controller
{
    public function index()
    {
        // Mengambil rekomendasi aktif, urutkan, dan kelompokkan per prioritas
        $recommendations = Recommendation::where('is_active', true)
            ->orderBy('display_order')
            ->get()
            ->groupBy('priority'); 

        return view('recommendations.index', compact('recommendations'));
    }
}
