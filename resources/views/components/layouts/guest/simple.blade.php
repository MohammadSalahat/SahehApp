<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white w-full">
    <div class="flex flex-col gap-6">
        {{ $slot }}
    </div>
    @fluxScripts
</body>

</html>