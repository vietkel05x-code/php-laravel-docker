<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hash password for admin user if it's not already hashed
        $admin = DB::table('users')->where('email', 'admin@example.com')->first();
        
        if ($admin) {
            // Check if password is plain text (doesn't start with $2y$)
            if (!str_starts_with($admin->password, '$2y$')) {
                DB::table('users')
                    ->where('email', 'admin@example.com')
                    ->update([
                        'password' => Hash::make('12345678')
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to plain text (not recommended, but for rollback)
        DB::table('users')
            ->where('email', 'admin@example.com')
            ->update([
                'password' => '12345678'
            ]);
    }
};
