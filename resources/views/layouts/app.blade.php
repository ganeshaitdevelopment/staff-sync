<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StaffSync - HR Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style> body { font-family: 'Inter', sans-serif; } [x-cloak] { display: none !important; } </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl z-30 transition-transform transform md:translate-x-0 fixed md:relative h-full"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="h-16 flex items-center px-6 border-b border-slate-800">
                <span class="text-xl font-bold tracking-wider text-indigo-400">STAFF<span class="text-white">SYNC</span></span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @php
                    // Ambil menu Parent saja (yang parent_id NULL), urutkan, dan bawa data 'children'
                    $menus = \App\Models\Menu::whereNull('parent_id')
                                ->with('children')
                                ->orderBy('order', 'asc')
                                ->get();
                @endphp

                @foreach($menus as $menu)
                    @if(in_array(auth()->user()->role, $menu->allowed_roles))
                        
                        @if($menu->children->isEmpty())
                            <a href="{{ $menu->route ? route($menu->route) : '#' }}" 
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors
                               {{ request()->routeIs($menu->route) ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $menu->icon_svg !!}</svg>
                                {{ $menu->name }}
                            </a>

                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = !open" 
                                    class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $menu->icon_svg !!}</svg>
                                        {{ $menu->name }}
                                    </div>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                
                                <div x-show="open" x-cloak class="pl-11 mt-1 space-y-1">
                                    @foreach($menu->children as $child)
                                        @if(in_array(auth()->user()->role, $child->allowed_roles))
                                            <a href="{{ $child->route ? route($child->route) : '#' }}" 
                                               class="block px-4 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-md transition-colors
                                               {{ request()->routeIs($child->route) ? 'text-white font-semibold' : '' }}">
                                                {{ $child->name }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @endif
                @endforeach
            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if (auth()->user()->avatar)
                            <img class="w-8 h-8 rounded-full object-cover border border-slate-600" 
                                src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                alt="{{ auth()->user()->name }}">
                        @else
                            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-white truncate w-24">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors" title="Sign Out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-y-auto">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 md:hidden">
                <span class="font-bold text-lg">StaffSync</span>
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </header>

            <main class="p-6 md:p-10">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>