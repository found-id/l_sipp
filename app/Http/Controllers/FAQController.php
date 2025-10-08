<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display the FAQ page.
     */
    public function index()
    {
        return view('faq');
    }
}

