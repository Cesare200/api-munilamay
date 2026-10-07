<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use ZipArchive;

class AdministradorArchivos extends Page
{
    use WithFileUploads;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Archivos y Carpetas';
    protected static ?string $title = 'Administrador de Archivos';
    protected static ?string $slug = 'administrador-archivos';

    protected static string | UnitEnum | null $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.administrador-archivos';

    public string $currentPath = '';
    public string $newFolderName = '';
    public string $search = '';
    public string $viewMode = 'list';   // 'list' (detalles) o 'grid' (iconos)
    public string $sortBy = 'name';     // name | modified | size
    public string $sortDir = 'asc';
    public string $selected = '';       // ruta del elemento seleccionado
    public array $expanded = [];        // carpetas abiertas en el árbol
    public $uploadedFiles = [];
    public $uploadedZip = null;

    protected string $disk = 'public';

    public function mount(): void
    {
        $this->currentPath = '';
    }

    /* ------------------------------------------------------------------ */
    /*  Utilidades                                                         */
    /* ------------------------------------------------------------------ */

    /** Normaliza una ruta y bloquea cualquier intento de salir de la raíz (..). */
    private function clean(string $path): string
    {
        $path = trim(str_replace('\\', '/', $path), '/');
        $parts = array_values(array_filter(explode('/', $path), fn ($p) => $p !== '' && $p !== '.'));

        return in_array('..', $parts, true) ? '' : implode('/', $parts);
    }

    private function formatBytes($bytes, $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function fileKind(string $ext): array
    {
        return match (true) {
            $ext === 'pdf' => ['icon' => 'heroicon-s-document-text', 'color' => '#ef4444', 'type' => 'Documento PDF'],
            in_array($ext, ['doc', 'docx']) => ['icon' => 'heroicon-s-document-text', 'color' => '#2563eb', 'type' => 'Documento Word'],
            in_array($ext, ['xls', 'xlsx', 'csv']) => ['icon' => 'heroicon-s-table-cells', 'color' => '#059669', 'type' => 'Hoja de cálculo'],
            in_array($ext, ['ppt', 'pptx']) => ['icon' => 'heroicon-s-presentation-chart-bar', 'color' => '#ea580c', 'type' => 'Presentación'],
            in_array($ext, ['zip', 'rar', '7z']) => ['icon' => 'heroicon-s-archive-box', 'color' => '#d97706', 'type' => 'Archivo comprimido'],
            in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']) => ['icon' => 'heroicon-s-photo', 'color' => '#7c3aed', 'type' => 'Imagen ' . strtoupper($ext)],
            default => ['icon' => 'heroicon-s-document', 'color' => '#9ca3af', 'type' => $ext ? 'Archivo ' . strtoupper($ext) : 'Archivo'],
        };
    }

    /* ------------------------------------------------------------------ */
    /*  Datos para la vista                                                */
    /* ------------------------------------------------------------------ */

    #[Computed]
    public function directories(): array
    {
        $disk = Storage::disk($this->disk);
        $dirs = $disk->directories($this->currentPath);

        $mapped = array_map(fn ($dir) => [
            'name' => basename($dir),
            'path' => $dir,
            'items_count' => count($disk->files($dir)) + count($disk->directories($dir)),
        ], $dirs);

        if ($this->search !== '') {
            $mapped = array_filter($mapped, fn ($d) => str_contains(strtolower($d['name']), strtolower($this->search)));
        }

        usort($mapped, fn ($a, $b) => strnatcasecmp($a['name'], $b['name']));

        return $mapped;
    }

    #[Computed]
    public function files(): array
    {
        $disk = Storage::disk($this->disk);

        $mapped = array_map(function ($file) use ($disk) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $size = $disk->size($file);
            $modified = $disk->lastModified($file);
            $kind = $this->fileKind($ext);

            return [
                'name' => basename($file),
                'path' => $file,
                'size_raw' => $size,
                'size' => $this->formatBytes($size),
                'modified_raw' => $modified,
                'last_modified' => date('d/m/Y H:i', $modified),
                'url' => $disk->url($file),
                'extension' => $ext,
                'is_image' => in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']),
                'icon' => $kind['icon'],
                'color' => $kind['color'],
                'type' => $kind['type'],
            ];
        }, $disk->files($this->currentPath));

        if ($this->search !== '') {
            $mapped = array_filter($mapped, fn ($f) => str_contains(strtolower($f['name']), strtolower($this->search)));
        }

        $key = match ($this->sortBy) {
            'modified' => 'modified_raw',
            'size' => 'size_raw',
            default => 'name',
        };

        usort($mapped, function ($a, $b) use ($key) {
            $r = $key === 'name' ? strnatcasecmp($a[$key], $b[$key]) : $a[$key] <=> $b[$key];
            return $this->sortDir === 'asc' ? $r : -$r;
        });

        return $mapped;
    }

    /** Árbol aplanado: cada nodo trae su profundidad para dibujar la sangría y las guías. */
    #[Computed]
    public function tree(): array
    {
        $out = [[
            'name' => 'Raíz',
            'path' => '',
            'depth' => 0,
            'has_children' => true,
            'expanded' => true,
            'active' => $this->currentPath === '',
        ]];

        $this->walk('', 1, $out);

        return $out;
    }

    private function walk(string $path, int $depth, array &$out): void
    {
        $disk = Storage::disk($this->disk);
        $dirs = $disk->directories($path);
        usort($dirs, fn ($a, $b) => strnatcasecmp(basename($a), basename($b)));

        foreach ($dirs as $dir) {
            $hasChildren = count($disk->directories($dir)) > 0;
            $isOpen = $hasChildren && in_array($dir, $this->expanded, true);

            $out[] = [
                'name' => basename($dir),
                'path' => $dir,
                'depth' => $depth,
                'has_children' => $hasChildren,
                'expanded' => $isOpen,
                'active' => $this->currentPath === $dir,
            ];

            if ($isOpen) {
                $this->walk($dir, $depth + 1, $out);
            }
        }
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'total_size' => $this->formatBytes(array_sum(array_column($this->files, 'size_raw'))),
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Navegación                                                         */
    /* ------------------------------------------------------------------ */

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['grid', 'list']) ? $mode : 'list';
    }

    public function sortList(string $column): void
    {
        if (! in_array($column, ['name', 'modified', 'size'])) {
            return;
        }

        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    public function toggleNode(string $path): void
    {
        $path = $this->clean($path);

        $this->expanded = in_array($path, $this->expanded, true)
            ? array_values(array_diff($this->expanded, [$path]))
            : [...$this->expanded, $path];
    }

    public function changeDirectory(string $path): void
    {
        $this->currentPath = $this->clean($path);
        $this->search = '';
        $this->selected = '';

        // Abre en el árbol todos los ancestros (y la propia carpeta)
        $acc = '';
        foreach (array_filter(explode('/', $this->currentPath)) as $part) {
            $acc = $acc === '' ? $part : $acc . '/' . $part;
            if (! in_array($acc, $this->expanded, true)) {
                $this->expanded[] = $acc;
            }
        }
    }

    public function goToParent(): void
    {
        if ($this->currentPath === '') {
            return;
        }

        $parts = explode('/', $this->currentPath);
        array_pop($parts);
        $this->changeDirectory(implode('/', $parts));
    }

    public function selectItem(string $path): void
    {
        $this->selected = $this->clean($path);
    }

    /* ------------------------------------------------------------------ */
    /*  Acciones                                                           */
    /* ------------------------------------------------------------------ */

    public function createFolder(): void
    {
        $this->validate([
            'newFolderName' => ['required', 'string', 'max:100', 'regex:/^(?!\.+$)[\w\s\-\.]+$/u'],
        ]);

        $target = $this->clean($this->currentPath . '/' . $this->newFolderName);

        if (Storage::disk($this->disk)->exists($target)) {
            Notification::make()->title('La carpeta ya existe')->warning()->send();
            return;
        }

        Storage::disk($this->disk)->makeDirectory($target);
        $this->newFolderName = '';
        $this->changeDirectory($this->currentPath); // asegura que el árbol quede abierto
        Notification::make()->title('Carpeta creada')->success()->send();
    }

    public function uploadDocuments(): void
    {
        $this->validate([
            'uploadedFiles.*' => 'file|max:102400',
        ]);

        foreach ($this->uploadedFiles as $file) {
            $file->storeAs($this->currentPath, basename($file->getClientOriginalName()), $this->disk);
        }

        $this->uploadedFiles = [];
        Notification::make()->title('Archivos subidos')->success()->send();
    }

    public function extractZip(): void
    {
        $this->validate([
            'uploadedZip' => 'required|file|mimes:zip|max:512000',
        ]);

        $zip = new ZipArchive();

        if ($zip->open($this->uploadedZip->getRealPath()) !== true) {
            Notification::make()->title('No se pudo abrir el ZIP')->danger()->send();
            return;
        }

        // Evita "zip slip": solo extrae entradas cuya ruta no salga de la carpeta actual
        $safe = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            $normalized = str_replace('\\', '/', $name);

            if (str_starts_with($normalized, '/') || in_array('..', explode('/', $normalized), true)) {
                continue;
            }
            $safe[] = $name;
        }

        $zip->extractTo(Storage::disk($this->disk)->path($this->currentPath), $safe);
        $zip->close();

        $this->uploadedZip = null;
        Notification::make()->title('ZIP extraído')->success()->send();
    }

    public function deleteSelected(): void
    {
        $path = $this->clean($this->selected);

        if ($path === '') {
            return;
        }

        $disk = Storage::disk($this->disk);

        if (in_array($path, $disk->directories($this->currentPath), true)) {
            $disk->deleteDirectory($path);
            $this->expanded = array_values(array_filter(
                $this->expanded,
                fn ($e) => $e !== $path && ! str_starts_with($e, $path . '/')
            ));
            Notification::make()->title('Carpeta eliminada')->success()->send();
        } else {
            $disk->delete($path);
            Notification::make()->title('Archivo eliminado')->success()->send();
        }

        $this->selected = '';
    }
}