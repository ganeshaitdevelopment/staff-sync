<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm">Welcome back, <span class="font-semibold text-indigo-600">{{ auth()->user()->name }}</span>.</p>
        </div>
        <div class="bg-white border border-slate-200 text-slate-600 text-sm font-medium px-4 py-2 rounded-lg shadow-sm flex items-center w-fit">
            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ \Carbon\Carbon::now()->format('l, d F Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-indigo-500 group-hover:w-full transition-all duration-300 opacity-10"></div>
            <div>
                <p class="text-slate-500 text-sm font-medium mb-1">Total Employees</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalEmployees }}</h3>
                <p class="text-emerald-600 text-xs font-medium mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Active Staff
                </p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-2 bg-blue-500 group-hover:w-full transition-all duration-300 opacity-10"></div>
            <div>
                <p class="text-slate-500 text-sm font-medium mb-1">Job Positions</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalPositions }}</h3>
                <p class="text-slate-400 text-xs mt-2">Departments</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg text-blue-600 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Daily Attendance</p>
                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                </div>
                @if($todayAttendance)
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-full">Present</span>
                @else
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-1 rounded-full animate-pulse">Not Yet</span>
                @endif
            </div>

<div class="mt-4">
    
    @if(!$todayAttendance)
        <button wire:click="checkIn" wire:loading.attr="disabled" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow transition flex items-center justify-center gap-2 text-sm">
            <svg wire:loading wire:target="checkIn" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg wire:loading.remove wire:target="checkIn" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            
            <span wire:loading.remove wire:target="checkIn">Check In Now</span>
            <span wire:loading wire:target="checkIn">Processing...</span>
        </button>

    @elseif(!$todayAttendance->check_out_time)
        <div class="mb-4 text-center">
            <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Clocked In At</span>
            <div class="text-2xl font-bold text-slate-800">{{ $todayAttendance->check_in_time }}</div>
        </div>

        <button wire:click="checkOut" wire:loading.attr="disabled" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg shadow transition flex items-center justify-center gap-2 text-sm">
            <svg wire:loading wire:target="checkOut" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg wire:loading.remove wire:target="checkOut" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>

            <span wire:loading.remove wire:target="checkOut">Check Out (Pulang)</span>
            <span wire:loading wire:target="checkOut">Processing...</span>
        </button>

    @else
        <div class="flex items-center gap-2 text-sm">
            <div class="bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100 flex-1 text-center">
                <span class="block text-xs text-slate-400">In</span>
                <span class="font-bold text-emerald-800">{{ $todayAttendance->check_in_time }}</span>
            </div>
            <div class="bg-red-50 px-3 py-2 rounded-lg border border-red-100 flex-1 text-center">
                <span class="block text-xs text-slate-400">Out</span>
                <span class="font-bold text-red-800">{{ $todayAttendance->check_out_time }}</span>
            </div>
        </div>
        <div class="mt-3 text-center">
            <span class="bg-slate-100 text-slate-500 text-xs py-1 px-3 rounded-full">See you tomorrow! 👋</span>
        </div>
    @endif

</div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Weekly Attendance Trend</h3>
        <div id="attendanceChart" class="w-full"></div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700">Recent Activities</h3>
            <a href="{{ route('logs.index') }}" class="text-xs text-indigo-600 hover:underline">View All</a>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="px-6 py-4 flex items-start gap-3">
                <div class="bg-emerald-100 text-emerald-600 rounded-full p-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-800"><span class="font-bold">{{ auth()->user()->name }}</span> logged in.</p>
                    <p class="text-xs text-slate-400 mt-1">Activity recorded.</p>
                </div>
                <span class="ml-auto text-xs text-slate-400">Just now</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        var options = {
            series: [{
                name: 'Employees Present',
                data: @json($chartData) 
            }],
            chart: {
                height: 350, // Tinggi grafik pas
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#6366f1'], // Warna Indigo-500
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: @json($chartLabels), 
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#64748b' } }
            },
            yaxis: {
                show: true,
                tickAmount: 4, 
                labels: { 
                    formatter: (val) => val.toFixed(0),
                    style: { colors: '#64748b' }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
            },
            tooltip: { theme: 'light' }
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>