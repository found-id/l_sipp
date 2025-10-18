<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the root route redirect based on authentication status
     */
    public function index()
    {
        if (Auth::check()) {
            // User is authenticated, redirect to dashboard
            return redirect()->route('dashboard');
        } else {
            // User is not authenticated, redirect to login
            return redirect()->route('login');
        }
    }
}
