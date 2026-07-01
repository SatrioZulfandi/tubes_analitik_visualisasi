<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Dashboard BI Transjakarta
|--------------------------------------------------------------------------
| Controller : AboutController
|
| Bertugas mengambil data statis
| dan mengirimkannya ke Blade View.
|
| Tidak mengandung Business Logic Machine Learning.
|
*/

class AboutController extends Controller
{
    public function index()
    {
        // Data statis untuk disajikan di halaman about
        $projectData = [
            'Project Name'  => 'Dashboard BI Transjakarta',
            'Dataset'       => 'dfTransjakarta_cluster.csv',
            'Method'        => 'K-Means Clustering',
            'Total Rows'    => 31730,
            'Total Cluster' => 4,
            'Tech Stack'    => [
                'Python',
                'Laravel 12',
                'MySQL',
                'ApexCharts',
                'Bootstrap 5'
            ]
        ];

        return view('about.index', compact('projectData'));
    }
}
