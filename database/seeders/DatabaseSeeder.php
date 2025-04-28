<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Tạo admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Tạo các phòng ban
        $departments = [
            'Phòng Nhân sự',
            'Phòng Kế toán',
            'Phòng Kỹ thuật',
            'Phòng Kinh doanh',
            'Phòng Marketing',
            'Phòng Hành chính',
            'Phòng IT',
            'Phòng Quản lý chất lượng'
        ];

        foreach ($departments as $dept) {
            Department::create([
                'name' => $dept,
                'description' => fake()->sentence(),
                'status' => 'active',
            ]);
        }

        // Tạo các vị trí
        $positions = [
            'Trưởng phòng',
            'Phó phòng',
            'Nhân viên',
            'Chuyên viên',
            'Kế toán trưởng',
            'Kỹ sư',
            'Nhân viên kinh doanh',
            'Nhân viên marketing',
            'Lập trình viên',
            'Kiểm soát viên chất lượng'
        ];

        foreach ($positions as $pos) {
            Position::create([
                'name' => $pos,
                'description' => fake()->sentence(),
                'status' => 'active',
            ]);
        }

        // Tạo 50 nhân viên mẫu
        Employee::factory(50)->create();

        $this->call([
            KpiSeeder::class,
        ]);
    }
}
