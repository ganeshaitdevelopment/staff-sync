<div class="space-y-6">
    
    @if (session()->has('message'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Employee Management</h1>
            <p class="text-slate-500 text-sm">List of all staff members and access roles.</p>
        </div>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add New Employee
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" 
                class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                placeholder="Search by name, phone, or email...">
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $employee->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $employee->phone_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($employee->position)
                                <span class="text-sm text-slate-900 font-medium">{{ $employee->position->name }}</span>
                                <span class="block text-xs text-slate-500">
                                    Rp {{ number_format($employee->position->basic_salary, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-sm text-slate-400 italic">No Position</span>
                            @endif
                        </td>                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($employee->role === 'supervisor')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Supervisor</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">Staff</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $employee->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            
                            <button wire:click="edit({{ $employee->id }})" class="text-indigo-600 hover:text-indigo-900 mr-4 font-semibold transition">
                                Edit
                            </button>
                            
                            <button wire:click="delete({{ $employee->id }})" 
                                    wire:confirm="Are you sure you want to delete {{ $employee->name }}?"
                                    class="text-red-600 hover:text-red-900 font-semibold transition">
                                Delete
                            </button>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                            No employees found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $employees->links() }} 
        </div>
    </div>

    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-slate-900 mb-4">
                            {{ $employeeId ? 'Edit Employee' : 'Add New Employee' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Name</label>
                                <input type="text" wire:model="name" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Phone</label>
                                <input type="text" wire:model="phone_number" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm">
                                @error('phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Email (Optional)</label>
                                <input type="email" wire:model="email" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Job Position</label>
                                <select wire:model="position_id" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm">
                                    <option value="">-- No Position --</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->id }}">
                                            {{ $pos->name }} (Salary: {{ number_format($pos->basic_salary/1000000, 1) }}M)
                                        </option>
                                    @endforeach
                                </select>
                                @error('position_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Role</label>
                                <select wire:model="role" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm">
                                    <option value="">Select Role</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="user">Staff</option>
                                </select>
                                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">
                                    Password {{ $employeeId ? '(Leave blank to keep current)' : '' }}
                                </label>
                                <input type="password" wire:model="password" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm">
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm">
                            {{ $employeeId ? 'Update Data' : 'Save Data' }}
                        </button>
                        <button type="button" wire:click="closeModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endif

</div>