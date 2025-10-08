<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function coba()
    {
        $labels1 = ['Design', 'Development', 'Testing', 'Marketing', 'Support'];
        $values1 = [22, 40, 15, 10, 13];
        return view('pages.keuangan', compact('labels1','values1'));
    }
}
