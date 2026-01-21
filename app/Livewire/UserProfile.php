<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfile extends Component
{
    use WithFileUploads;

    // 1. FOTO
    public $photo;
    public $existingPhoto;

    // 2. PROFILE INFO (Update: Ada Phone Number)
    public $name;
    public $email;
    public $phone_number;

    // 3. PASSWORD
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        // $this->existingPhoto = auth()->user()->avatar;
        $user = auth()->user();
        $this->existingPhoto = $user->avatar;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;

    }

    // === FITUR 1: UPDATE FOTO ===
    public function updatePhoto()
    {
        $this->validate(['photo' => 'image|max:2048']);

        $user = auth()->user();
        $path = $this->photo->store('avatars', 'public');

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => $path]);

        $this->existingPhoto = $path;
        $this->reset('photo');
        session()->flash('photo_success', 'Foto profil berhasil diperbarui!');
    }

    // === FITUR 3: GANTI PASSWORD ===
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama salah.',
            ]);
        }

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password_success', 'Password berhasil diganti!');
    }

    // public function save()
    // {
    //     $this->validate([
    //         'photo' => 'image|max:2048', // Maksimal 2MB, harus Gambar
    //     ]);

    //     $user = auth()->user();

    //     // 1. Upload Foto Baru
    //     // Foto disimpan di folder: storage/app/public/avatars
    //     $path = $this->photo->store('avatars', 'public');

    //     // 2. Hapus Foto Lama (Opsional, biar hemat memori)
    //     if ($user->avatar) {
    //         Storage::disk('public')->delete($user->avatar);
    //     }

    //     // 3. Simpan Path ke Database
    //     $user->update([
    //         'avatar' => $path
    //     ]);

    //     $this->existingPhoto = $path; // Update tampilan
        
    //     // Reset input file
    //     $this->reset('photo');

    //     session()->flash('message', 'Profile photo updated successfully!');
        
    //     // Refresh halaman biar navbar ikut berubah
    //     return redirect()->route('profile'); 
    // }

    public function render()
    {
        return view('livewire.user-profile')->layout('layouts.app');
    }
}