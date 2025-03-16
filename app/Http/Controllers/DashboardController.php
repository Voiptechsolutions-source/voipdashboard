<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Return a view for the dashboard
        return view('dashboard'); // Make sure the 'dashboard' view exists
    }
}
