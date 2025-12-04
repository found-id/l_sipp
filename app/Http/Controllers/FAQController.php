<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class FAQController extends Controller
{
    /**
     * Display the FAQ page.
     */
    public function index()
    {
        $fontConfig = SystemSetting::getFontConfig();
        return view('faq', compact('fontConfig'));
    }
}
