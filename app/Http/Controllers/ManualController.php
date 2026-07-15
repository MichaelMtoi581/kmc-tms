<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function userManual()
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('manual.user-manual');
        return $pdf->download('TMIS-User-Manual.pdf');
    }
}
