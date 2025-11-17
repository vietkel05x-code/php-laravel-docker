<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDataToSeeders extends Command
{
    protected $signature = 'db:export-seeders {--tables=all} {--with-truncate}';
    protected $description = 'Export database data to seeders';

    public function handle()
    {
        $this->info("Exporting database data to seeders...\n");
        
        $withTruncate = $this->option('with-truncate');
        
        $tables = [
            'categories',
            'users',
            'category_media',
            'courses',
            'sections',
            'lessons',
            'lesson_progress',
            'enrollments',
            'reviews',
            'orders',
            'order_items',
            'payments',
            'coupons',
            'notifications',
            'user_notifications',
        ];
        
        foreach ($tables as $table) {
            $this->exportTable($table, $withTruncate);
        }
        
        $this->info("\n✅ Export completed!");
        $this->info("Seeders created in database/seeders/data/");
        if ($withTruncate) {
            $this->info("Seeders will TRUNCATE tables before inserting data");
        }
    }
    
    private function exportTable($table, $withTruncate = false)
    {
        try {
            $data = DB::table($table)->get();
            
            if ($data->isEmpty()) {
                $this->warn("  ⚠ {$table}: No data");
                return;
            }
            
            $this->info("  ✓ {$table}: {$data->count()} rows");
            
            $className = str_replace('_', '', ucwords($table, '_')) . 'DataSeeder';
            $seederPath = database_path('seeders/data');
            
            if (!File::exists($seederPath)) {
                File::makeDirectory($seederPath, 0755, true);
            }
            
            $seederContent = $this->generateSeederContent($className, $table, $data, $withTruncate);
            
            File::put("{$seederPath}/{$className}.php", $seederContent);
            
        } catch (\Exception $e) {
            $this->error("  ❌ {$table}: " . $e->getMessage());
        }
    }
    
    private function generateSeederContent($className, $table, $data, $withTruncate = false)
    {
        $dataArray = $data->map(function ($item) {
            return (array) $item;
        })->toArray();
        
        $dataExport = var_export($dataArray, true);
        
        // Clean up the export for better readability
        $dataExport = preg_replace('/^array \(/m', '[', $dataExport);
        $dataExport = preg_replace('/\)$/m', ']', $dataExport);
        $dataExport = str_replace('array (', '[', $dataExport);
        $dataExport = str_replace('),', '],', $dataExport);
        $dataExport = preg_replace('/=> \n\s+/', '=> ', $dataExport);
        
        $truncateCode = $withTruncate ? "
        // Clear existing data
        DB::table('{$table}')->truncate();" : "";
        
        return <<<PHP
<?php

namespace Database\Seeders\Data;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class {$className} extends Seeder
{
    public function run(): void
    {{$truncateCode}
        
        \$data = {$dataExport};
        
        // Insert data
        DB::table('{$table}')->insert(\$data);
        
        // Reset sequence if table has id column
        try {
            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('{$table}', 'id'),
                    (SELECT MAX(id) FROM {$table})
                )
            ");
        } catch (\Exception \$e) {
            // Ignore if table doesn't have id column
        }
    }
}

PHP;
    }
}
