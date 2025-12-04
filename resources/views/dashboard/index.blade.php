@extends('layouts.app')

@section('title', 'Dashboard - SIP PKL')

@section('content')
<div class="space-y-4 md:space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-3 py-3 md:px-4 md:py-5 sm:p-6">
            <h1 class="text-lg md:text-2xl font-bold text-gray-900">
                Halo, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-0.5 md:mt-1 text-xs md:text-sm text-gray-600">
                Login sebagai {{ ucfirst(auth()->user()->role) }}
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