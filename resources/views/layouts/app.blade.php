<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-800 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Maruwa</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('properties.index') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('properties.*') ? 'bg-indigo-700' : '' }}">
                    Propriedades
                </a>
                <a href="{{ route('owners.index') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('owners.*') ? 'bg-indigo-700' : '' }}">
                    Proprietários
                </a>
                <a href="{{ route('guests.index') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('guests.*') ? 'bg-indigo-700' : '' }}">
                    Hóspedes
                </a>
                <a href="{{ route('reservations.index') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('reservations.*') ? 'bg-indigo-700' : '' }}">
                    Reservas
                </a>
                <a href="{{ route('financial.index') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('financial.*') ? 'bg-indigo-700' : '' }}">
                    Financeiro
                </a>
                <a href="{{ route('reports.owners') }}" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('reports.*') ? 'bg-indigo-700' : '' }}">
                    Relatórios
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
