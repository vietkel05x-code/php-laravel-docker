<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::table('users')->truncate();
        
        $data = [
  0 => [
    'id' => 1,
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'avatar' => NULL,
    'password' => '$2y$12$1K9GoUy/CSDezbSJADsfJ.RcBOd5QeC5B/3pEYnle.LMo2u7W6m7C',
    'role' => 'admin',
    'email_verified_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-10-15 13:08:10',
    'updated_at' => '2025-10-15 13:08:10',
  ],
  1 => [
    'id' => 2,
    'name' => 'Trinh Nguyen',
    'email' => 'vietkel05x@gmail.com',
    'avatar' => NULL,
    'password' => '$2y$12$ah1AEiHtbUYhbv.dPPLtp.9R4xftJ51eINKdIbwbA4Ff3ck/NiF.O',
    'role' => 'student',
    'email_verified_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-10-29 14:10:21',
    'updated_at' => '2025-10-29 14:10:21',
  ],
  2 => [
    'id' => 3,
    'name' => 'Việt',
    'email' => 'vietkel05@gmail.com',
    'avatar' => NULL,
    'password' => '$2y$12$./5p0/8ef9QBWd031HW9iuxRNQB3E2teC16KtY79dvSDOqkts4OlC',
    'role' => 'student',
    'email_verified_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-10-29 17:49:54',
    'updated_at' => '2025-10-29 17:49:54',
  ],
  3 => [
    'id' => 4,
    'name' => 'Trinh Nguyen',
    'email' => 'vietkel05a@gmail.com',
    'avatar' => NULL,
    'password' => '$2y$12$bLzQUVg/Bn2hEHzc4JF1SuytuZaOLsMCq5a0Eph/7c32os579cEg.',
    'role' => 'student',
    'email_verified_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-11-11 19:16:02',
    'updated_at' => '2025-11-11 19:16:02',
  ],
  4 => [
    'id' => 5,
    'name' => 'Việt',
    'email' => 'vietkel@gmail.com',
    'avatar' => NULL,
    'password' => '$2y$12$q.PuFOKd4LJpD2CbuAWaDuCeVjXg39u2GPg63rd4s0xhk2bw9comS',
    'role' => 'student',
    'email_verified_at' => NULL,
    'remember_token' => NULL,
    'created_at' => '2025-11-12 17:32:35',
    'updated_at' => '2025-11-12 17:32:35',
  ],
];
        
        // Insert data
        DB::table('users')->insert($data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('users', 'id'),
                    (SELECT MAX(id) FROM users)
                )
            ");
        } catch (\Exception $e) {
            // Ignore if table doesn't have id column
        }
    }
}
