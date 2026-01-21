<div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
    
    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 blur-xl"></div>

    <h3 class="text-lg font-bold text-slate-800 mb-2">Daily Attendance</h3>
    
    @if (session()->has('message'))
        <div class="mb-4 p-2 bg-emerald-50 text-emerald-700 text-xs rounded border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col items-center justify-center py-4">
        
        <div class="text-3xl font-mono font-bold text-slate-700 mb-1">
            {{ now()->format('H:i') }}
        </div>
        <div class="text-xs text-slate-400 mb-6">{{ now()->format('l, d F Y') }}</div>

        @if(!$todayAttendance)
            <button wire:click="checkIn" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg font-bold shadow-lg shadow-indigo-200 transition transform hover:scale-105 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Check In Now
            </button>

        @elseif(!$todayAttendance->check_out_time)
            <div class="text-center w-full">
                <p class="text-xs text-slate-500 mb-2">You checked in at <span class="font-bold text-indigo-600">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}</span></p>
                <button wire:click="checkOut" wire:confirm="Are you sure you want to check out now?" class="w-full py-3 bg-white border-2 border-red-500 text-red-600 hover:bg-red-50 rounded-lg font-bold transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Check Out
                </button>
            </div>

        @else
            <div class="w-full bg-emerald-50 border border-emerald-100 rounded-lg p-4 text-center">
                <p class="text-emerald-700 font-bold text-sm">Attendance Complete!</p>
                <div class="flex justify-center gap-4 mt-2 text-xs text-slate-600">
                    <div>
                        <span class="block text-slate-400">In</span>
                        <span class="font-mono font-bold">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400">Out</span>
                        <span class="font-mono font-bold">{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>