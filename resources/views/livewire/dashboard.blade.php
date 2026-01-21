<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm">Welcome back, <span class="font-semibold text-indigo-600">{{ auth()->user()->name }}</span>.</p>
            <div class="mt-1 text-xs font-medium text-slate-400 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::now()->format('l, d F Y') }}
            </div>
        </div>

        <div class="w-full md:w-auto">
            @if(!$todayAttendance)
                <button wire:click="checkIn" wire:loading.attr="disabled" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                    <svg wire:loading wire:target="checkIn" class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span wire:loading.remove wire:target="checkIn">📍 Check In Now</span>
                    <span wire:loading wire:target="checkIn">Processing...</span>
                </button>
            @elseif(!$todayAttendance->check_out_time)
                <button wire:click="checkOut" wire:loading.attr="disabled" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                    <svg wire:loading wire:target="checkOut" class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span wire:loading.remove wire:target="checkOut">👋 Check Out</span>
                    <span wire:loading wire:target="checkOut">Processing...</span>
                </button>
            @else
                <button disabled class="w-full md:w-auto bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold py-2.5 px-6 rounded-lg cursor-not-allowed flex items-center justify-center gap-2 text-sm">
                    ✅ Completed Today
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1 bg-indigo-500 group-hover:w-full transition-all duration-500 opacity-10"></div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">{{ auth()->user()->role !== 'user' ? 'Total Employees' : 'Attendance This Month' }}</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ auth()->user()->role !== 'user' ? $totalEmployees : $presentToday }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1 bg-emerald-500 group-hover:w-full transition-all duration-500 opacity-10"></div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">{{ auth()->user()->role !== 'user' ? 'Present Today' : 'Days Present' }}</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $presentToday }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1 bg-amber-500 group-hover:w-full transition-all duration-500 opacity-10"></div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">{{ auth()->user()->role !== 'user' ? 'Late Today' : 'Late This Month' }}</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $lateToday }}</h3>
            </div>
            <div class="p-3 bg-amber-50 rounded-lg text-amber-600 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1 bg-purple-500 group-hover:w-full transition-all duration-500 opacity-10"></div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">{{ auth()->user()->role !== 'user' ? 'On Leave Today' : 'Pending Requests' }}</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $onLeaveToday }}</h3>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg text-purple-600 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Attendance Trend (7 Days)</h3>
            <div id="attendanceChart" class="w-full"></div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden h-fit">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-700">Recent Activities</h3>
                {{-- <a href="#" class="text-xs text-indigo-600 hover:underline">View All</a> --}}
            </div>
            <div class="divide-y divide-slate-100">
                <div class="px-6 py-4 flex items-start gap-3">
                    <div class="bg-emerald-100 text-emerald-600 rounded-full p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-800"><span class="font-bold">{{ auth()->user()->name }}</span> logged in.</p>
                        <p class="text-xs text-slate-400 mt-1">Session started.</p>
                    </div>
                    <span class="ml-auto text-xs text-slate-400">Now</span>
                </div>
                
                @if($todayAttendance)
                <div class="px-6 py-4 flex items-start gap-3">
                    <div class="bg-indigo-100 text-indigo-600 rounded-full p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-800">Checked In</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $todayAttendance->check_in_time }}</p>
                    </div>
                </div>
                @endif
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
                height: 350,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#6366f1'],
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