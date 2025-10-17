<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-[#f8f6f0] antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="w-full h-full flex justify-center items-center ">
        <div class="w-full h-full">
            {{ $slot }}
        </div>
    </div>
    @fluxScripts
</body>

</html>