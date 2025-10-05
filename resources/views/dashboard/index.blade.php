@extends('layouts.app')

@section('title', 'Dashboard - SIPP PKL')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Selamat datang, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Anda login sebagai {{ ucfirst(auth()->user()->role) }}
            </p>
        </div>
    </div>

    <!-- Role-based Dashboard Content -->
    @if(auth()->user()->role === 'mahasiswa')
        @include('dashboard.mahasiswa', ['stats' => $stats ?? []])
    @elseif(auth()->user()->role === 'dospem')
        @include('dashboard.dospem', ['stats' => $stats ?? []])
    @elseif(auth()->user()->role === 'admin')
        @include('dashboard.admin', ['stats' => $stats ?? []])
    @endif
</div>
@endsection