<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * Ambil semua data absensi
    */
    public function collection()
    {
        // Kita ambil data terbaru dulu, beserta relasi User-nya
        return Attendance::with('user')->latest()->get();
    }

    /**
    * Mengatur Judul Kolom (Header)
    */
    public function headings(): array
    {
        return [
            'Date',
            'Employee Name',
            'Position',
            'Time In',
            'Time Out',
            'Work Hours',
            'Status',
            'Overtime Pay'
        ];
    }

    /**
    * Mengatur Isi Data per Baris
    */
    public function map($attendance): array
    {
        // 1. Siapkan variabel jam kerja default (0)
        $jamKerja = 0;

        // 2. Cek apakah sudah checkout? Kalau ada, baru hitung.
        if ($attendance->check_out_time) {
            // Gabungkan tanggal + jam biar Carbon paham
            $masuk = Carbon::parse($attendance->date . ' ' . $attendance->check_in_time);
            $pulang = Carbon::parse($attendance->date . ' ' . $attendance->check_out_time);
            
            // Hitung selisih (floatDiffInHours akan menghasilkan desimal, misal 8.5 jam)
            $jamKerja = $masuk->floatDiffInHours($pulang);
        }

        return [
            $attendance->date,
            $attendance->user->name,
            $attendance->user->position->name ?? '-', // Jabatan
            $attendance->check_in_time,
            $attendance->check_out_time ?? '-',
            number_format($jamKerja, 2) . ' hours',
            $attendance->status,
            $attendance->overtime_pay,
        ];
    }
}