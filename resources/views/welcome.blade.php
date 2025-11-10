<?php $title = __('Welcome'); // <-- 1. TITLE YAHAN SET KIYA HAI ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title> <!-- 2. TITLE YAHAN CALL KIYA HAI -->

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen bg-dots-darker bg-center bg-gray-100">

            <!-- Top Right Links (Login/Dashboard) -->
            @if (Route::has('login'))
                <div class="p-6 text-right">
                    @auth
                        {{-- Agar user login hai, toh Dashboard link dikhayein --}}
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Dashboard</a>
                    @else
                        {{-- Agar login nahi hai, toh Login link dikhayein --}}
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Log in</a>
                    @endauth
                </div>
            @endif

            <!-- Main Hero Content -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                {{-- Hum content ko vertically center kar rahe hain --}}
                <div class="flex flex-col items-center justify-center" style="min-height: 80vh;">

                    <!-- Logo -->
                    <div class="mb-8">
                        <a href="/">
                            <x-application-mark class="h-24 w-24 text-indigo-600" />
                        </a>
                    </div>

                    <!-- Main Heading -->
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                            The <span class="text-indigo-600">Innovation Pipeline</span>
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600 max-w-2xl">
                            This is the central hub for our team to collect, review, price, and manage all new feature ideas and innovations for our projects.
                        </p>
                    </div>

                    <!-- === 3. CALL TO ACTION BUTTONS (UPDATED) === -->
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        {{-- "Submit Idea" button hata diya gaya hai --}}

                        {{-- Sirf "Login" button reh gaya hai --}}
                        <a href="{{ route('login') }}"
                           class="rounded-md bg-indigo-600 px-5 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                           Team Member Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
