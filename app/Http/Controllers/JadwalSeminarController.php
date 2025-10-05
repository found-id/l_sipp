<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSeminar;

class JadwalSeminarController extends Controller
{
    public function index()
    {
        $jadwal = JadwalSeminar::with(['pembuat'])
            ->orderBy('tanggal_dibuat', 'asc')
            ->get();
        return view('jadwal-seminar.index', compact('jadwal'));
    }
}