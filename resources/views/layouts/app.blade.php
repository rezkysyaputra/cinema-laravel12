<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CINETIX') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @php
    $isProduction = app()->environment('production');
    $manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
    @endphp

    @if ($isProduction && file_exists($manifestPath))
    @php
    $manifest = json_decode(file_get_contents($manifestPath), true);
    @endphp
    <link rel="stylesheet" href="{{ config('app.url') }}/build/{{ $manifest['resources/css/app.css']['file'] }}">
    <script type="module" src="{{ config('app.url') }}/build/{{ $manifest['resources/js/app.js']['file'] }}"></script>
    @else
    @viteReactRefresh
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @endif


    @livewireStyles
</head>
<body class="bg-dark-bg text-white min-h-screen">
    @include('layouts.navigation')

    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '', type: 'success', timeout: null }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" x-init="window.addEventListener('toast', e => {
            message = e.detail.message;
            type = e.detail.type || 'success';
            show = true;
            clearTimeout(timeout);
            timeout = setTimeout(() => show = false, 3000);
         })" class="fixed top-6 right-6 z-50 min-w-[220px] max-w-xs px-4 py-3 rounded-lg shadow-lg flex items-center gap-3" :class="{
            'bg-green-600 text-white': type === 'success',
            'bg-red-600 text-white': type === 'error',
            'bg-yellow-500 text-black': type === 'warning',
            'bg-blue-600 text-white': type === 'info',
         }" style="display: none;">
        <template x-if="type === 'success'"><i class="fas fa-check-circle"></i></template>
        <template x-if="type === 'error'"><i class="fas fa-times-circle"></i></template>
        <template x-if="type === 'warning'"><i class="fas fa-exclamation-triangle"></i></template>
        <template x-if="type === 'info'"><i class="fas fa-info-circle"></i></template>
        <span x-text="message"></span>
        <button @click="show = false" class="ml-auto text-lg focus:outline-none">&times;</button>
    </div>
    <!-- End Toast Notification -->

    <!-- Page Heading -->
    @isset($header)
    <header class="">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endisset

    <!-- Page Content -->
    <main class="px-4 sm:px-6 lg:px-8 pb-20 lg:pb-8">
        {{ $slot }}
    </main>

    @stack('scripts')
    @livewireScripts

    {{-- Toast auto trigger from session flash --}}
    @if(session('status'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: @json(session('status'))
                    , type: 'success'
                }
            }));
        });

    </script>
    @endif
    @if(session('error'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: @json(session('error'))
                    , type: 'error'
                }
            }));
        });

    </script>
    @endif
    @if(session('toast'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: @json(session('toast.message'))
                    , type: @json(session('toast.type', 'success'))
                }
            }));
        });

    </script>
    @endif
</body>
</html>
