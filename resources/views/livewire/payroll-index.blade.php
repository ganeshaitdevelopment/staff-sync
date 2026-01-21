<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">My Payslips</h1>
            <p class="text-slate-500 text-sm">View and download your monthly salary slips.</p>
        </div>
        
        @if(auth()->user()->role !== 'user')
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="month" wire:model.live="selectedMonth" class="border-slate-300 rounded-lg text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button wire:click="generatePayroll" 
                wire:confirm="Are you sure?"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
                Generate Payroll
            </button>
        </div>
        @endif
    </div>
    

    @if (session()->has('message'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Basic Salary</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Overtime</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Take Home Pay</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Slip</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($payrolls as $payroll)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-slate-900">{{ $payroll->user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $payroll->month }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ $payroll->user->position->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-600">
                        Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-emerald-600">
                        + Rp {{ number_format($payroll->overtime_salary, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-indigo-700">
                        Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 uppercase">
                            {{ $payroll->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('payroll.download', $payroll->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 flex justify-center" title="Download PDF">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                        No payroll data generated for this month yet. <br>
                        Click "Generate Payroll" to calculate salaries.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $payrolls->links() }}
        </div>
    </div>
</div>