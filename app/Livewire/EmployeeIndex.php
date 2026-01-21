<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use App\Models\Position;

class EmployeeIndex extends Component
{
    use WithPagination;

    // --- VARIABLES ---
    public $search = '';
    public $isModalOpen = false;
    public $position_id;
    
    // Variable Penting untuk Edit
    public $employeeId = null; 

    // Variable Form Input
    public $name, $email, $phone_number, $role, $password;

    // --- RULES VALIDASI ---
    // Kita pindah ke dalam function store() biar dinamis (bisa beda rule buat edit & create)

    // --- FUNCTION 1: Reset Halaman saat Search ---
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- FUNCTION 2: Buka Modal (Mode Tambah) ---
    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    // --- FUNCTION 3: Buka Modal (Mode Edit) - INI YANG PENTING ---
    public function edit($id)
    {
        // Cari user berdasarkan ID
        $employee = User::findOrFail($id);
        
        // Isi form dengan data database
        $this->employeeId = $id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->phone_number = $employee->phone_number;
        $this->role = $employee->role;
        $this->position_id = $employee->position_id;
        $this->password = ''; // Password kosongkan (biar aman)
        
        // Buka Modal
        $this->isModalOpen = true;
    }

    // --- FUNCTION 4: Tutup Modal ---
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    // Helper untuk kosongkan form
    private function resetInputFields()
    {
        $this->employeeId = null;
        $this->reset(['name', 'email', 'phone_number', 'role', 'password', 'position_id']);
        $this->resetValidation();
    }

    // --- FUNCTION 5: Simpan Data (Create / Update) ---
    public function store()
    {
        // Validasi Canggih
        // Kalau Edit, abaikan cek unik untuk ID diri sendiri
        $rules = [
            'name' => 'required|min:3',
            'role' => 'required|in:administrator,supervisor,user',
            'phone_number' => 'required|numeric|unique:users,phone_number,' . $this->employeeId,
            'email' => 'nullable|email|unique:users,email,' . $this->employeeId,
        ];

        // Password wajib cuma kalau user baru (Create)
        if (!$this->employeeId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        // Siapkan Data
        $data = [
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'position_id' => $this->position_id ?: null,
        ];

        // Jika password diisi, enkripsi dan masukkan ke data
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // LOGIKA UTAMA: Cek apakah ini Edit atau Create?
        if ($this->employeeId) {
            // Update Data Lama
            User::find($this->employeeId)->update($data);
            $action = 'Updated';
        } else {
            // Buat Data Baru
            User::create($data);
            $action = 'Created';
        }

        // Catat Log (Audit Trail)
        if(class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => $action . ' User',
                'details' => $action . ' employee: ' . $this->name,
                'ip_address' => request()->ip()
            ]);
        }

        $this->closeModal();
        session()->flash('message', 'Employee ' . $action . ' successfully.');
    }

    // --- FUNCTION 6: Delete ---
    public function delete($id)
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            session()->flash('message', 'Employee deleted successfully.');
        }
    }

    public function render()
    {
        // Ambil semua jabatan buat dropdown
        $positions = Position::all(); 

        $employees = User::with('position') // Eager load biar ringan
            ->where('role', '!=', 'administrator')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.employee-index', [
            'employees' => $employees,
            'positions' => $positions // <--- Kirim ke view
        ])->layout('layouts.app');
    }
}