<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::orderBy('nama')->get();
        return view('mitra.index', compact('mitra'));
    }
}