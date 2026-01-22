<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow truncate
        Schema::disableForeignKeyConstraints();

        // Empty the table first
        DB::table('menus')->truncate();

        // Insert data
        DB::table('menus')->insert([
            [
                'id' => 1,
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>',
                'allowed_roles' => '["administrator","supervisor","user"]',
                'order' => 1,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 2,
                'name' => 'Master Data',
                'route' => null,
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>',
                'allowed_roles' => '["administrator","supervisor"]',
                'order' => 2,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 3,
                'name' => 'Employees',
                'route' => 'employees.index',
                'icon_svg' => null,
                'allowed_roles' => '["administrator","supervisor"]',
                'order' => 1,
                'parent_id' => 2,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 4,
                'name' => 'Job Positions',
                'route' => 'positions.index',
                'icon_svg' => null,
                'allowed_roles' => '["administrator"]',
                'order' => 2,
                'parent_id' => 2,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 5,
                'name' => 'System',
                'route' => null,
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>',
                'allowed_roles' => '["administrator"]',
                'order' => 99,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 6,
                'name' => 'Activity Logs',
                'route' => 'logs.index',
                'icon_svg' => null,
                'allowed_roles' => '["administrator"]',
                'order' => 1,
                'parent_id' => 5,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 7,
                'name' => 'Attendance History',
                'route' => 'attendance.index',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'allowed_roles' => '["administrator","supervisor"]',
                'order' => 2,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 8,
                'name' => 'Payroll',
                'route' => 'payroll.index',
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'allowed_roles' => '["administrator","supervisor","user"]',
                'order' => 4,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 9,
                'name' => 'Profile',
                'route' => 'profile',
                'icon_svg' => '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /> </svg>',
                'allowed_roles' => '["administrator","supervisor","user"]',
                'order' => 5,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
            [
                'id' => 10,
                'name' => 'Leaves',
                'route' => 'leaves.index',
                'icon_svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>',
                'allowed_roles' => '["administrator","supervisor","user"]',
                'order' => 3,
                'parent_id' => null,
                'created_at' => '2026-01-18 21:29:28',
                'updated_at' => '2026-01-18 21:29:28',
            ],
        ]);

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}