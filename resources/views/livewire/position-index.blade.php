<div class="space-y-6">
    
    @if (session()->has('message'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Job Positions</h1>
            <p class="text-slate-500 text-sm">Manage job titles and basic salaries.</p>
        </div>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Position
        </button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Position Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Basic Salary</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($positions as $position)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            {{ $position->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            Rp {{ number_format($position->basic_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $position->id }})" class="text-indigo-600 hover:text-indigo-900 mr-4 font-semibold">Edit</button>
                            <button wire:click="delete({{ $position->id }})" 
                                wire:confirm="Delete position {{ $position->name }}?"
                                class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-slate-500">No positions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $positions->links() }} 
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
                            {{ $positionId ? 'Edit Position' : 'Add Position' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Position Name</label>
                                <input type="text" wire:model="name" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm" placeholder="e.g. Marketing Manager">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Basic Salary (Rp)</label>
                                <input type="number" wire:model="basic_salary" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm" placeholder="e.g. 5000000">
                                @error('basic_salary') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm">
                            Save
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