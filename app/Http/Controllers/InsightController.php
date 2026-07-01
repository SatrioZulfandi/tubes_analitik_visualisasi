<?php

namespace App\Http\Controllers;

use App\Models\Insight;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Dashboard BI Transjakarta
|--------------------------------------------------------------------------
| Controller : InsightController
|
| Bertugas mengambil data dari database
| dan mengirimkannya ke Blade View.
|
| Tidak mengandung Business Logic Machine Learning.
|
*/

class InsightController extends Controller
{
    public function index()
    {
        // Mengambil wawasan yang berstatus aktif dan diurutkan
        $insights = Insight::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('insights.index', compact('insights'));
    }
}
