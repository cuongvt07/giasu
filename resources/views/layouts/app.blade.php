<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('head')
</head>
<body x-data="{ openPost:false }" class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">
                            Gia Sư Online
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Tìm Gia Sư
                        </a>
                        <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Môn Học
                        </a>
                        @php
                            // Kiểm tra user có phải là gia sư hay không
                            $isTutor = false;
                            if (auth()->check()) {
                                try {
                                    $isTutor = auth()->user()->tutor()->exists();
                                } catch(\Exception $e) {
                                    // Không làm gì nếu có lỗi
                                }
                            }
                        @endphp
                        @if(!$isTutor)
                        <a href="{{ route('ai-advisor') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Tư Vấn AI
                        </a>
                        @endif

                        <a href="{{ route('tutors.jobs.post') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Tin Tuyển Gia Sư
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @php
                                        // Kiểm tra user có phải là gia sư hay không một cách an toàn
                                        $isTutor = false;
                                        $user = auth()->user();
                                        if ($user) {
                                            try {
                                                $isTutor = $user->tutor()->exists();
                                            } catch(\Exception $e) {
                                                // Không làm gì nếu có lỗi
                                            }
                                        }
                                    @endphp
                                    
                                    @if(auth()->user()->is_admin)
                                        <x-dropdown-link :href="route('admin.dashboard')">
                                            {{ __('Admin Dashboard') }}
                                        </x-dropdown-link>
                                    @elseif($isTutor)
                                        <x-dropdown-link :href="route('tutor.dashboard')">
                                            {{ __('Bảng Điều Khiển') }}
                                        </x-dropdown-link>
                                    @else
                                        <x-dropdown-link :href="route('student.bookings.index')">
                                            {{ __('Buổi Học Của Tôi') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('student.bookings.tutors')">
                                            {{ __('Gia Sư Của Tôi') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('student.manager.jobs')">
                                            {{ __('Quản lý tin đăng tuyển') }}
                                        </x-dropdown-link>
                                    @endif
                                    
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Hồ Sơ') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                            {{ __('Đăng Xuất') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('tutors.register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                                Đăng ký trở thành gia sư
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-md ml-3">Đăng Ký</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Về Chúng Tôi</h3>
                        <ul role="list" class="mt-4 space-y-4">
                            <li>
                                <a href="{{ route('about-us') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Giới Thiệu
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Liên Hệ
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Dịch Vụ</h3>
                        <ul role="list" class="mt-4 space-y-4">
                            <li>
                                <a href="{{ route('tutors.index') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Tìm Gia Sư
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tutors.register') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Trở Thành Gia Sư
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Hỗ Trợ</h3>
                        <ul role="list" class="mt-4 space-y-4">
                            <li>
                                <a href="{{ route('faq') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    FAQ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('guide') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Hướng Dẫn
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Pháp Lý</h3>
                        <ul role="list" class="mt-4 space-y-4">
                            <li>
                                <a href="{{ route('terms') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Điều Khoản
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('privacy-policy') }}" class="text-base text-gray-500 hover:text-gray-900">
                                    Bảo Mật
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <x-tutor-post-modal />

    @livewireScripts
    @stack('scripts')
</body>
</html> 