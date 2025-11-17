<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSqlFile extends Command
{
    protected $signature = 'db:import-sql {file=elearning.sql} {--fresh : Drop all existing tables before import} {--show-errors=10 : Show first N errors} {--exclude=cache,sessions,jobs,failed_jobs,cache_locks,password_reset_tokens,personal_access_tokens : Comma-separated tables to skip}';
    protected $description = 'Import SQL file into current database connection';

    public function handle()
    {
        $filePath = base_path($this->argument('file'));
        
        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }
        
        $this->info("Reading SQL file: {$filePath}");

        $driver = DB::getDriverName();
        $this->info("Using DB driver: {$driver}");
        if ($driver !== 'mysql') {
            $this->warn('This dump looks like a MySQL/phpMyAdmin export.');
            $this->warn('Current connection is not MySQL. Import may fail.');
            if (!$this->confirm('Continue anyway?')) {
                return 1;
            }
        }

        $sql = File::get($filePath);
        // Split SQL into statements safely (respect quotes and comments)
        $statements = $this->splitSqlStatements($sql);

        $this->info("Found " . count($statements) . " SQL statements");

        // Optionally drop all existing tables first (MySQL only)
        if ($this->option('fresh') && $driver === 'mysql') {
            $dbName = DB::getDatabaseName();
            $this->warn("Destructive action: this will DROP ALL TABLES in database '{$dbName}'.");
            $typed = $this->ask("To confirm, type the database name exactly: ");
            if ($typed !== $dbName) {
                $this->error('Database name mismatch. Aborting drop.');
                return 1;
            }
            $this->warn('Dropping all existing tables first (--fresh)...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $row) {
                $tableName = collect((array)$row)->first();
                DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->info('All tables dropped. Proceeding with import.');
        }
        
        if (!$this->confirm('This will execute ALL statements. Continue?')) {
            $this->info('Import cancelled.');
            return 0;
        }

        $bar = $this->output->createProgressBar(count($statements));
        $bar->start();
        
        $success = 0;
        $failed = 0;
        $errors = [];
        $excluded = collect(explode(',', (string) $this->option('exclude')))
            ->map(fn($t) => strtolower(trim($t)))
            ->filter(fn($t) => $t !== '')
            ->values();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($statements as $statement) {
            if ($this->targetsExcludedTable($statement, $excluded->all())) {
                $bar->advance();
                continue;
            }
            try {
                DB::unprepared($statement);
                $success++;
            } catch (\Exception $e) {
                // Only log errors, don't stop
                $failed++;
                $errors[] = [
                    'statement' => substr($statement, 0, 100) . '...',
                    'error' => $e->getMessage()
                ];
            }
            $bar->advance();
        }

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("✅ Import completed!");
        $this->info("  Success: {$success}");
        
        if ($failed > 0) {
            $this->warn("  Failed: {$failed}");

            $this->newLine();
            $limit = (int) $this->option('show-errors');
            $this->warn("Showing first {$limit} error(s):");
            foreach (array_slice($errors, 0, $limit) as $error) {
                $this->error("  • " . $error['statement']);
                $this->error("    " . $error['error']);
            }
            if (count($errors) > $limit) {
                $this->warn("  ... and " . (count($errors) - $limit) . " more errors");
                $this->line("Tip: increase with --show-errors=50 or run with -vvv for more context");
            }
        }
        
        $this->newLine();
        $this->info("Next step: php artisan db:export-seeders --with-truncate");
        
        return 0;
    }

    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $inSingle = false; // '
        $inDouble = false; // "
        $inBacktick = false; // `
        $inLineDash = false; // -- comment
        $inLineHash = false; // # comment
        $inBlock = false; // /* */ comment
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $ch = $sql[$i];
            $next = $i + 1 < $len ? $sql[$i + 1] : '';
            $prev = $i > 0 ? $sql[$i - 1] : '';

            // Handle exiting comments
            if ($inLineDash || $inLineHash) {
                if ($ch === "\n") {
                    $inLineDash = false;
                    $inLineHash = false;
                }
                continue; // skip comment content
            }
            if ($inBlock) {
                if ($ch === '*' && $next === '/') {
                    $inBlock = false;
                    $i++; // skip '/'
                }
                continue; // skip block comment content
            }

            // Enter comments (only when not inside quotes/backticks)
            if (!$inSingle && !$inDouble && !$inBacktick) {
                if ($ch === '-' && $next === '-') {
                    // MySQL treats "-- " as comment. We'll accept any "--" until end of line.
                    $inLineDash = true;
                    $i++; // skip second '-'
                    continue;
                }
                if ($ch === '#') {
                    $inLineHash = true;
                    continue;
                }
                if ($ch === '/' && $next === '*') {
                    $inBlock = true;
                    $i++; // skip '*'
                    continue;
                }
            }

            // Toggle quote states
            if (!$inDouble && !$inBacktick && $ch === "'" ) {
                if ($inSingle) {
                    // end if not escaped via backslash, and not doubled ''
                    if ($next === "'") {
                        // escaped by doubling quotes inside string
                        $buffer .= "''";
                        $i++; // skip the next quote as it's part of escape
                        continue;
                    }
                    if ($prev !== '\\') {
                        $inSingle = false;
                    }
                } else {
                    $inSingle = true;
                }
                $buffer .= $ch;
                continue;
            }
            if (!$inSingle && !$inBacktick && $ch === '"') {
                if ($inDouble) {
                    if ($prev !== '\\') {
                        $inDouble = false;
                    }
                } else {
                    $inDouble = true;
                }
                $buffer .= $ch;
                continue;
            }
            if (!$inSingle && !$inDouble && $ch === '`') {
                if ($inBacktick) {
                    if ($prev !== '\\') {
                        $inBacktick = false;
                    }
                } else {
                    $inBacktick = true;
                }
                $buffer .= $ch;
                continue;
            }

            // Statement delimiter ';' only outside quotes/backticks/comments
            if (!$inSingle && !$inDouble && !$inBacktick && $ch === ';') {
                $trimmed = trim($buffer);
                if ($trimmed !== '') {
                    $statements[] = $trimmed;
                }
                $buffer = '';
                continue;
            }

            // Normal character
            $buffer .= $ch;
        }

        $trimmed = trim($buffer);
        if ($trimmed !== '') {
            $statements[] = $trimmed;
        }

        return $statements;
    }

    private function targetsExcludedTable(string $statement, array $excluded): bool
    {
        if (empty($excluded)) return false;
        $stmt = ltrim($statement);
        // Common statement patterns targeting a single table
        $patterns = [
            '/^insert\s+into\s+`?([a-z0-9_]+)`?/i',
            '/^replace\s+into\s+`?([a-z0-9_]+)`?/i',
            '/^create\s+table\s+(?:if\s+not\s+exists\s+)?`?([a-z0-9_]+)`?/i',
            '/^alter\s+table\s+`?([a-z0-9_]+)`?/i',
            '/^drop\s+table\s+(?:if\s+exists\s+)?`?([a-z0-9_]+)`?/i',
            '/^lock\s+tables\s+`?([a-z0-9_]+)`?/i',
        ];
        foreach ($patterns as $regex) {
            if (preg_match($regex, $stmt, $m)) {
                $table = strtolower($m[1]);
                return in_array($table, $excluded, true);
            }
        }
        return false;
    }
}
