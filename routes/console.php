<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('storage:cleanup-orphans {--dry-run}', function () {
    $paths = [];

    $sources = [
        ['table' => 'user_documents', 'column' => 'arquivo'],
        ['table' => 'despesas', 'column' => 'arquivo'],
        ['table' => 'alunos', 'column' => 'foto_perfil'],
        ['table' => 'professores', 'column' => 'foto_perfil'],
        ['table' => 'settings', 'column' => 'logo'],
        ['table' => 'aluno_registros', 'column' => 'arquivo'],
    ];

    foreach ($sources as $source) {
        $values = DB::table($source['table'])
            ->whereNotNull($source['column'])
            ->where($source['column'], '!=', '')
            ->pluck($source['column']);

        foreach ($values as $value) {
            $path = ltrim((string) $value, '/');
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, 8);
            }
            $paths[$path] = true;
        }
    }

    $allFiles = Storage::disk('public')->allFiles();
    $orphans = array_values(array_filter($allFiles, fn($file) => !isset($paths[$file])));

    $this->info('Orfaos encontrados: ' . count($orphans));

    if ($this->option('dry-run')) {
        foreach ($orphans as $file) {
            $this->line($file);
        }
        return;
    }

    foreach ($orphans as $file) {
        Storage::disk('public')->delete($file);
    }

    $this->info('Orfaos removidos: ' . count($orphans));
})->purpose('Remove arquivos orfaos do storage publico');
