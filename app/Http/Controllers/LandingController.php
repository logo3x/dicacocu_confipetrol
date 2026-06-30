<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function home(): View
    {
        return view('landing.home');
    }

    public function sgd(): View
    {
        return view('landing.sgd');
    }

    public function dicacocu(): View
    {
        return view('landing.dicacocu');
    }

    public function contacto(): View
    {
        return view('landing.contacto');
    }
}
