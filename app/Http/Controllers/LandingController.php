<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesantren;
use App\Models\Santri;

class LandingController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index()
    {
        // Data Real from Database (Data2)
        $stats = [
            'pesantren' => Pesantren::count(),
            'santri' => Santri::count(),
            'users' => \App\Models\User::count(),
        ];

        // Plans from Config (Source of Truth)
        $plans = config('subscription.plans');

        return view('welcome', compact('stats', 'plans'));
    }
}
