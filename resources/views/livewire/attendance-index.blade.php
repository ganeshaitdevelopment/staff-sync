<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Attendance History</h1>
            <p class="text-slate-500 text-sm">Monitor employee check-in and check-out times.</p>
        </div>

        <a href="{{ route('attendance.export') }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export to Excel
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Check Out</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Work Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Overtime Pay</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-slate-50 transition-colors">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ substr($attendance->user->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-900">{{ $attendance->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $attendance->user->role }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-mono font-semibold bg-slate-100 text-slate-700 rounded">
                                {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance->check_out_time)
                                <span class="px-2 py-1 text-xs font-mono font-semibold bg-slate-100 text-slate-700 rounded">
                                    {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">-- : --</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            @if($attendance->check_out_time)
                                @php
                                    $in = \Carbon\Carbon::parse($attendance->check_in_time);
                                    $out = \Carbon\Carbon::parse($attendance->check_out_time);
                                    $diff = $in->diff($out);
                                @endphp
                                {{ $diff->h }}h {{ $diff->i }}m
                            @else
                                <span class="text-xs text-indigo-500 font-medium">Working...</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance->status === 'late')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Late
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    On Time
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                            @if($attendance->overtime_pay > 0)
                                <span class="text-emerald-600">+ Rp {{ number_format($attendance->overtime_pay, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            No attendance records found for this date.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $attendances->links() }}
        </div>
    </div>
</div>