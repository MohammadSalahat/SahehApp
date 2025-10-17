<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

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