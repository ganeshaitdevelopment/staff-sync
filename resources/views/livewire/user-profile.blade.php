<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-bold text-slate-800">My Profile</h1>
        <p class="text-slate-500 text-sm">Manage photo, details, and security.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Profile Photo</h3>
            
            <form wire:submit="updatePhoto" class="flex flex-col items-center">
                <div class="relative mb-4">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100 shadow-md">
                    @elseif ($existingPhoto)
                        <img src="{{ asset('storage/' . $existingPhoto) }}" class="w-32 h-32 rounded-full object-cover border-4 border-slate-100 shadow-md">
                    @else
                        <div class="w-32 h-32 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 border-4 border-slate-100 shadow-md">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    @endif
                </div>

                <input type="file" wire:model="photo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-4 bg-gray-50 rounded-lg border border-gray-300"/>
                
                <button type="submit" wire:loading.attr="disabled" class="w-full bg-white border-2 border-slate-300 text-slate-700 hover:bg-slate-50 font-bold py-2 px-4 rounded-lg transition text-sm">
                    <span wire:loading.remove wire:target="photo, updatePhoto">Change Photo</span>
                    <span wire:loading wire:target="photo, updatePhoto">Uploading...</span>
                </button>

                @error('photo') <span class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</span> @enderror
                @if (session()->has('photo_success')) <span class="text-emerald-600 text-xs mt-2 font-bold">{{ session('photo_success') }}</span> @endif
            </form>
        </div>

        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Personal Information</h3>
                <form wire:submit="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Full Name</label>
                            <input type="text" wire:model="name" class="w-full rounded-lg border-gray-400 bg-gray-50 text-black focus:ring-indigo-600 focus:border-indigo-600 shadow-sm" placeholder="Enter full name">
                            @error('name') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                            <input type="email" wire:model="email" class="w-full rounded-lg border-gray-400 bg-gray-50 text-black focus:ring-indigo-600 focus:border-indigo-600 shadow-sm" placeholder="email@example.com">
                            @error('email') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Phone Number</label>
                            <input type="number" wire:model="phone_number" class="w-full rounded-lg border-gray-400 bg-gray-50 text-black focus:ring-indigo-600 focus:border-indigo-600 shadow-sm" placeholder="0812...">
                            @error('phone_number') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="mt-6 flex justify-between items-center border-t border-slate-100 pt-4">
                        @if (session()->has('profile_success'))
                            <span class="text-emerald-600 text-sm font-bold animate-pulse">✅ {{ session('profile_success') }}</span>
                        @else
                            <span></span>
                        @endif
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition transform hover:-translate-y-0.5">Save Details</button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Change Password</h3>
                <form wire:submit="updatePassword">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Current Password</label>
                            <input type="password" wire:model="current_password" class="w-full rounded-lg border-gray-400 bg-gray-50 text-black focus:ring-indigo-600 focus:border-indigo-600 shadow-sm" placeholder="••••••••">
                            @error('current_password') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">New Password</label>
                                <input type="password" wire:model="new_password" class="w-full rounded-lg border-gray-400 bg-gray-50 text-black focus:ring-indigo-600 focus:border-indigo-600 shadow-sm" placeholder="New password">
                                @error('new_password') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Confirm Password</label>
                                <input type="password" wire:model="new_password_confirmation" class="w-full rounded-lg border-gray-400 bg-gray-50 text-black focus:ring-indigo-600 focus:border-indigo-600 shadow-sm" placeholder="Confirm password">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between items-center border-t border-slate-100 pt-4">
                        @if (session()->has('password_success'))
                            <span class="text-emerald-600 text-sm font-bold animate-pulse">✅ {{ session('password_success') }}</span>
                        @else
                            <span></span>
                        @endif
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition transform hover:-translate-y-0.5">Update Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>