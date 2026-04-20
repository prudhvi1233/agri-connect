<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Contract;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'farmer') {
            $listings = Listing::where('farmer_id', $user->id)->get();
            $contracts = Contract::where('farmer_id', $user->id)->with('buyer', 'listing')->get();
            return view('dashboard.farmer', compact('listings', 'contracts'));
        }

        if ($user->role === 'buyer') {
            $contracts = Contract::where('buyer_id', $user->id)->with('farmer', 'listing')->get();
            return view('dashboard.buyer', compact('contracts'));
        }

        return view('dashboard');
    }
}
