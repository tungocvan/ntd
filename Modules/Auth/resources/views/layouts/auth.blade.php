<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        use Modules\Admin\Models\Setting;
        $favicon = Setting::getValue('site_favicon');
        $siteName = \Modules\Website\Models\Setting::getValue('site_name', 'TRƯỜNG TIỂU HỌC NGUYỄN THỊ ĐỊNH');
    @endphp
    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="icon" href="/favicon.ico" />
    @endif
    <title>@yield('title', $siteName)</title>
    @yield('css')
    <script>
        window.CHAT_CONFIG_HOST = "{{ env('NODEJS_SERVER_URL') }}";
        window.CHAT_CONFIG_PORT = "{{ env('NODEJS_SERVER_PORT') ?? 6001 }}";
    </script>
    @vite(['resources/css/tailwind.css', 'resources/js/tailwind.js'])
    @stack('styles')
    @livewireStyles
</head>


<body class="bg-gray-200 h-screen flex items-center justify-center">
    <div class="flex h-screen overflow-hidden">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </div>
    @yield('js')
    @stack('scripts')
    @livewireScripts
</body>

</html>
