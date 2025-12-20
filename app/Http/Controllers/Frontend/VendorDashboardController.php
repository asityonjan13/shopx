<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View; // <-- correct View import
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    function index(): View
    {
        return view('vendorend.dashboard.index');
    }
}
