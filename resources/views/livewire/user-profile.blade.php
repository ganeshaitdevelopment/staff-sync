<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        
        <div class="bg-indigo-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white">My Profile Settings</h2>
        </div>

        <div class="p-8">
            <form wire:submit="save" class="flex flex-col md:flex-row gap-8 items-center">
                
                <div class="relative group">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}"
                            style="width: 10rem; height: 10rem;" 
                            class="rounded-full object-cover border-4 border-indigo-100 shadow-md">
                    
                    @elseif ($existingPhoto)
                        <img src="{{ asset('storage/' . $existingPhoto) }}"
                            style="width: 10rem; height: 10rem;" 
                            class="rounded-full object-cover border-4 border-indigo-100 shadow-md">
                    
                    @else
                        <div style="width: 10rem; height: 10rem;"
                        class="rounded-full bg-slate-200 flex items-center justify-center text-4xl font-bold text-slate-500 border-4 border-white shadow-md">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif

                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/50 rounded-full flex items-center justify-center">
                        <span class="text-indigo-600 font-bold animate-pulse">Uploading...</span>
                    </div>
                </div>

                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Change Profile Photo</label>
                        <input type="file" wire:model="photo" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                        "/>
                        <p class="mt-1 text-xs text-slate-500">JPG, JPEG, PNG (Max. 2MB)</p>
                        @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition disabled:opacity-50"
                            wire:loading.attr="disabled">
                            Save Changes
                        </button>
                    </div>

                    @if (session()->has('message'))
                        <div class="text-emerald-600 text-sm font-medium bg-emerald-50 p-2 rounded">
                            ✅ {{ session('message') }}
                        </div>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>