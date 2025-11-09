<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white flex flex-col justify-between">
    @include('partials.navbar')

    <div>
        {{ $slot }}
    </div>

    @include('partials.footer')
    @fluxScripts
</body>

</html>