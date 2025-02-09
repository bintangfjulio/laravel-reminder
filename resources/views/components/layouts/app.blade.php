<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <div class="navbar bg-base-100 shadow-md">
        <div class="flex-none">
            <div class="drawer-content">
                <label for="my-drawer" class="drawer-button btn btn-square btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block h-5 w-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg></label>
            </div>
        </div>
        <div class="flex-1">
            <a class="btn btn-ghost text-xl">Lend</a>
        </div>
        <div class="flex-none mr-3">
            <h1 class="mr-5">{{ Auth::user()->name }}</h1>
            <livewire:logout />
        </div>
    </div>

    {{ $slot }}

    <div class="drawer z-10 sticky top-0">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-side">
            <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-white text-base-content min-h-full w-80 px-4">
                <div class="flex justify-start items-center mb-4">
                    <a class="btn btn-ghost text-xl">Lend</a>
                </div>

                <div class="flex justify-center mb-5">
                    <div class="bg-black text white text-white capitalize py-1 px-4 rounded-xl text-[13px]">
                        {{ Auth::user()->role }}
                    </div>
                </div>

                <li class="mb-1 text-sm">
                    <a wire:navigate href="/lend" wire:current='bg-gray-200 text-black'>
                        Lend
                    </a>
                </li>

                @if (Auth::user()->role == 'admin')
                    <li class="mb-1 text-sm">
                        <a wire:navigate href="/room" wire:current='bg-gray-200 text-black'>
                            Room
                        </a>
                    </li>

                    <li class="mb-1 text-sm">
                        <a wire:navigate href="/user" wire:current='bg-gray-200 text-black'>
                            User
                        </a>
                    </li>
                @endif
        </div>
    </div>
    @livewireScripts
</body>

</html>
