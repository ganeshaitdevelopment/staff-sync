<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // <--- Wajib Import ini
use Illuminate\Support\Facades\Storage;

class UserProfile extends Component
{
    use WithFileUploads; // <--- Pasang Trait ini

    public $photo; // Variabel penampung file sementara
    public $existingPhoto; // Untuk simpan path foto lama

    public function mount()
    {
        $this->existingPhoto = auth()->user()->avatar;
    }

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:2048', // Maksimal 2MB, harus Gambar
        ]);

        $user = auth()->user();

        // 1. Upload Foto Baru
        // Foto disimpan di folder: storage/app/public/avatars
        $path = $this->photo->store('avatars', 'public');

        // 2. Hapus Foto Lama (Opsional, biar hemat memori)
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // 3. Simpan Path ke Database
        $user->update([
            'avatar' => $path
        ]);

        $this->existingPhoto = $path; // Update tampilan
        
        // Reset input file
        $this->reset('photo');

        session()->flash('message', 'Profile photo updated successfully!');
        
        // Refresh halaman biar navbar ikut berubah
        return redirect()->route('profile'); 
    }

    public function render()
    {
        return view('livewire.user-profile')->layout('layouts.app');
    }
}