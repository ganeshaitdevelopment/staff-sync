
<div class="space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Leave Requests</h1>
            <p class="text-slate-500 text-sm">Submit and track your time off requests.</p>
        </div>
    </div>

    @if(auth()->user()->role !== 'user' && count($incomingRequests) > 0)
        <div class="bg-white rounded-xl shadow-lg border border-indigo-100 p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
            
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full mr-2 animate-pulse">
                    {{ count($incomingRequests) }}
                </span>
                Incoming Requests
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-indigo-800 uppercase">Employee</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-indigo-800 uppercase">Dates</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-indigo-800 uppercase">Reason</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-indigo-800 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($incomingRequests as $req)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800">{{ $req->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $req->user->position->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - 
                                {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                <div class="text-xs font-bold text-indigo-600">({{ $req->duration }} days)</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600 italic">
                                "{{ $req->reason }}"
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <button wire:click="reject({{ $req->id }})" 
                                    wire:confirm="Are you sure you want to REJECT this request?"
                                    class="text-xs bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-1 rounded-lg font-bold transition">
                                    Reject
                                </button>
                                
                                <button wire:click="approve({{ $req->id }})" 
                                    wire:confirm="Approve this leave request?"
                                    class="text-xs bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-1 rounded-lg font-bold shadow-md transition">
                                    Approve
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif


    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">New Request</h3>
        
        <form wire:submit="submit" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
                <input type="date" wire:model="start_date" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
                <input type="date" wire:model="end_date" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-slate-700 mb-1">Reason</label>
                <textarea wire:model="reason" rows="2" placeholder="e.g. Family vacation, Sick leave, etc." class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-3 text-right">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition shadow-sm">
                    Submit Request
                </button>
            </div>
        </form>

        @if (session()->has('message'))
            <div class="mt-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-lg text-sm border border-emerald-200">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="text-md font-semibold text-slate-700">My Leave History</h3>
        </div>
        
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Reason</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($leaves as $leave)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $leave->duration }} Days
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $leave->reason }}
                        @if($leave->admin_note)
                            <div class="text-xs text-red-500 mt-1">Note: {{ $leave->admin_note }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($leave->status === 'approved')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">APPROVED</span>
                        @elseif($leave->status === 'rejected')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">REJECTED</span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">PENDING</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                        No leave requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>