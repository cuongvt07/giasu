<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Gia Sư</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar for desktop -->
        <div class="hidden md:flex md:flex-col md:fixed md:inset-y-0 md:w-64 bg-white shadow-lg z-10">
            <div class="flex items-center justify-center h-16 bg-gradient-to-r from-indigo-600 to-purple-600">
                <span class="text-white text-lg font-bold tracking-wide">Trang Gia Sư</span>
            </div>
            
            <div class="flex-1 flex flex-col overflow-y-auto">
                <!-- Avatar & user info -->
                <div class="flex flex-col items-center py-6 px-4 border-b border-gray-100">
                    <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold mb-3 overflow-hidden">
                        @if(Auth::user()->tutor && Auth::user()->tutor->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->tutor->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-gray-500 mt-1">Gia sư</p>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-6 px-4 space-y-1.5">
                    <a href="{{ route('tutor.dashboard') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.dashboard') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('tutor.bookings.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.bookings.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.bookings.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Lịch Dạy
                    </a>

                    <a href="{{ route('tutor.schedule.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.schedule.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.schedule.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lịch Rảnh
                    </a>

                    <a href="{{ route('tutor.jobs.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.schedule.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.schedule.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Quản lý tin tuyển dụng 
                    </a>

                    <a href="{{ route('tutor.earnings.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.earnings.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.earnings.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Thu Nhập
                    </a>

                    <a href="{{ route('tutor.profile.edit') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.profile.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.profile.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Hồ Sơ
                    </a>
                </nav>
                
                <!-- Footer links -->
                <div class="mt-auto p-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center py-2 px-4 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                            <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Đăng Xuất
                        </button>
                    </form>
                    
                    <a href="{{ route('home') }}" class="mt-2 w-full flex items-center py-2 px-4 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                        <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Trang Chủ
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar & overlay -->
        <div class="md:hidden" x-data="{ open: false }">
            <!-- Sidebar overlay -->
            <div x-show="open" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75" @click="open = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
            
            <!-- Sidebar -->
            <div x-show="open" class="fixed inset-y-0 left-0 z-40 w-full max-w-xs bg-white" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-2 pr-2">
                    <button @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile sidebar content -->
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-center h-16 bg-gradient-to-r from-indigo-600 to-purple-600">
                        <span class="text-white text-lg font-bold tracking-wide">Trang Gia Sư</span>
                    </div>
                    
                    <div class="flex-1 flex flex-col overflow-y-auto">
                        <!-- Avatar & user info -->
                        <div class="flex flex-col items-center py-6 px-4 border-b border-gray-100">
                            <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold mb-3 overflow-hidden">
                                @if(Auth::user()->tutor && Auth::user()->tutor->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->tutor->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <h2 class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</h2>
                            <p class="text-xs text-gray-500 mt-1">Gia sư</p>
                        </div>
                        
                        <!-- Navigation -->
                        <nav class="mt-6 px-4 space-y-1.5">
                            <a href="{{ route('tutor.dashboard') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.dashboard') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('tutor.bookings.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.bookings.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.bookings.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Lịch Dạy
                            </a>

                            <a href="{{ route('tutor.schedule.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.schedule.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.schedule.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Lịch Rảnh
                            </a>

                            <a href="{{ route('tutor.earnings.index') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.earnings.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.earnings.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Thu Nhập
                            </a>

                            <a href="{{ route('tutor.profile.edit') }}" class="group flex items-center py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('tutor.profile.*') ? 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 shadow-md' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('tutor.profile.*') ? 'text-white' : 'text-gray-500 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Hồ Sơ
                            </a>
                        </nav>
                        
                        <!-- Footer links -->
                        <div class="mt-auto p-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center py-2 px-4 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Đăng Xuất
                                </button>
                            </form>
                            
                            <a href="{{ route('home') }}" class="mt-2 w-full flex items-center py-2 px-4 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out">
                                <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Trang Chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top bar -->
            <div class="sticky top-0 z-10 bg-white md:flex md:items-center md:justify-between shadow-sm px-4 sm:px-6 lg:px-8 h-16">
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = true" type="button" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                
                <!-- Page title: Hide on mobile, show on desktop -->
                <div class="hidden md:block">
                    <div class="flex items-center">
                        @if(request()->routeIs('tutor.dashboard'))
                            <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
                        @elseif(request()->routeIs('tutor.bookings.*'))
                            <h1 class="text-lg font-semibold text-gray-800">Lịch Dạy</h1>
                        @elseif(request()->routeIs('tutor.schedule.*'))
                            <h1 class="text-lg font-semibold text-gray-800">Lịch Rảnh</h1>
                        @elseif(request()->routeIs('tutor.earnings.*'))
                            <h1 class="text-lg font-semibold text-gray-800">Thu Nhập</h1>
                        @elseif(request()->routeIs('tutor.profile.*'))
                            <h1 class="text-lg font-semibold text-gray-800">Hồ Sơ</h1>
                        @endif
                    </div>
                </div>
                
                <!-- Right side user dropdown -->
                <div class="ml-auto flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 overflow-hidden">
                                    @if(Auth::user()->tutor && Auth::user()->tutor->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->tutor->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-1 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <!-- <x-dropdown-link :href="route('tutor.profile.edit')">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Hồ Sơ Gia Sư
                                </div>
                            </x-dropdown-link> -->
                            
                            <x-dropdown-link :href="route('profile.edit')">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Thông Tin Tài Khoản
                                </div>
                            </x-dropdown-link>
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <x-dropdown-link :href="route('home')">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Trang Chủ
                                </div>
                            </x-dropdown-link>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    <div class="flex items-center">
                                        <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Đăng Xuất
                                    </div>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html> 