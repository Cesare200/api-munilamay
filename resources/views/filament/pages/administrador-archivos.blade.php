<x-filament-panels::page>
    {{-- Librería QR inyectada mediante assets de Filament --}}
    @assets
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @endassets

    <style>
        .fe {
            --fe-bg: #ffffff; --fe-bg2: #f6f7f9; --fe-bg3: #eceef2;
            --fe-bd: #e2e5ea; --fe-tx: #1f2430; --fe-mut: #6b7280;
            --fe-hov: #eef1f6; --fe-sel: #d9e8fb; --fe-selbd: #9cc2f4;
            --fe-guide: #d4d8df; --fe-folder: #f5b82e;
            display: flex; flex-direction: column; height: 680px; min-height: 420px;
            background: var(--fe-bg); color: var(--fe-tx);
            border: 1px solid var(--fe-bd); border-radius: 10px; overflow: hidden;
            font-size: 13px; user-select: none;
        }
        .dark .fe {
            --fe-bg: #14171d; --fe-bg2: #1b1f27; --fe-bg3: #252a34;
            --fe-bd: #2b303b; --fe-tx: #e5e8ee; --fe-mut: #8c94a3;
            --fe-hov: #222732; --fe-sel: #1e3556; --fe-selbd: #35609b; --fe-guide: #323846;
        }

        .fe svg { flex: none; display: inline-block; vertical-align: middle; }
        .fe .i14 { width: 14px !important; height: 14px !important; }
        .fe .i16 { width: 16px !important; height: 16px !important; }
        .fe .i18 { width: 18px !important; height: 18px !important; }
        .fe .i44 { width: 44px !important; height: 44px !important; }

        /* Barras */
        .fe-bar { display: flex; align-items: center; gap: 6px; padding: 6px 10px; background: var(--fe-bg2); border-bottom: 1px solid var(--fe-bd); flex-wrap: wrap; }
        .fe-btn { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 6px; border: 0; background: transparent; color: var(--fe-tx); font-size: 12.5px; cursor: pointer; white-space: nowrap; }
        .fe-btn:hover:not(:disabled) { background: var(--fe-bg3); }
        .fe-btn:disabled { opacity: .35; cursor: default; }
        .fe-sep { width: 1px; height: 18px; background: var(--fe-bd); margin: 0 4px; }
        .fe-spacer { flex: 1; }
        .fe-seg { display: inline-flex; background: var(--fe-bg3); border-radius: 7px; padding: 2px; }
        .fe-seg button { padding: 4px 7px; border: 0; border-radius: 5px; background: transparent; color: var(--fe-mut); cursor: pointer; }
        .fe-seg button.on { background: var(--fe-bg); color: var(--fe-tx); box-shadow: 0 1px 2px rgba(0,0,0,.15); }

        .fe-addr { display: flex; align-items: center; gap: 8px; padding: 6px 10px; border-bottom: 1px solid var(--fe-bd); background: var(--fe-bg); }
        .fe-crumbs { flex: 1; min-width: 0; display: flex; align-items: center; gap: 2px; padding: 3px 6px; border: 1px solid var(--fe-bd); border-radius: 6px; background: var(--fe-bg); overflow-x: auto; white-space: nowrap; }
        .fe-crumb { display: inline-flex; align-items: center; gap: 5px; padding: 2px 6px; border: 0; border-radius: 4px; background: transparent; color: var(--fe-tx); cursor: pointer; font-size: 12.5px; }
        .fe-crumb:hover { background: var(--fe-hov); }
        .fe-crumb.last { font-weight: 600; }
        .fe-search { position: relative; width: 220px; }
        .fe-search input { width: 100%; padding: 5px 8px 5px 28px; border: 1px solid var(--fe-bd); border-radius: 6px; background: var(--fe-bg); color: var(--fe-tx); font-size: 12.5px; user-select: text; }
        .fe-search svg { position: absolute; left: 8px; top: 8px; color: var(--fe-mut); }

        /* Progreso */
        .fe-prog { padding: 8px 12px; background: var(--fe-bg); border-bottom: 1px solid var(--fe-bd); }
        .fe-prog-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; font-size: 12px; margin-bottom: 5px; }
        .fe-track { height: 6px; border-radius: 99px; background: var(--fe-bg3); overflow: hidden; position: relative; }
        .fe-fill { height: 100%; border-radius: 99px; background: #2563eb; transition: width .15s linear; }
        .fe-prog-del .fe-fill { background: #ef4444; }
        .fe-indet { position: absolute; width: 35%; animation: fe-slide 1.1s ease-in-out infinite; }
        @keyframes fe-slide { 0% { left: -35%; } 100% { left: 100%; } }
        .fe-prog-err { background: #fef2f2; color: #b91c1c; }
        .dark .fe-prog-err { background: #3b1518; color: #fca5a5; }
        .fe-prog-err .fe-prog-top { margin-bottom: 0; }
        @media (prefers-reduced-motion: reduce) { .fe-indet { animation: none; width: 100%; opacity: .6; } }

        /* Cuerpo */
        .fe-body { flex: 1; display: flex; min-height: 0; }
        .fe-tree { width: 270px; flex: none; overflow: auto; padding: 8px 4px; background: var(--fe-bg2); border-right: 1px solid var(--fe-bd); }
        .fe-main { flex: 1; min-width: 0; overflow: auto; position: relative; }

        /* Árbol */
        .fe-node { display: flex; align-items: center; height: 28px; padding-right: 8px; border-radius: 5px; cursor: pointer; white-space: nowrap; border: 1px solid transparent; }
        .fe-node:hover { background: var(--fe-hov); }
        .fe-node.active { background: var(--fe-sel); border-color: var(--fe-selbd); }
        .fe-node.active .nm { font-weight: 600; }
        .fe-guide { width: 16px; flex: none; align-self: stretch; position: relative; }
        .fe-guide::before { content: ""; position: absolute; left: 8px; top: -1px; bottom: -1px; border-left: 1px solid var(--fe-guide); }
        .fe-chev { width: 18px; height: 18px; flex: none; display: inline-flex; align-items: center; justify-content: center; border: 0; background: transparent; color: var(--fe-mut); border-radius: 4px; cursor: pointer; padding: 0; }
        .fe-chev:hover { background: var(--fe-bg3); color: var(--fe-tx); }
        .fe-chev svg { transition: transform .12s; }
        .fe-chev.open svg { transform: rotate(90deg); }
        .fe-node .nm { margin-left: 6px; overflow: hidden; text-overflow: ellipsis; }

        /* Lista (detalles) */
        .fe-table { width: 100%; border-collapse: collapse; }
        .fe-table th { position: sticky; top: 0; z-index: 2; background: var(--fe-bg); text-align: left; font-weight: 500; color: var(--fe-mut); padding: 7px 12px; border-bottom: 1px solid var(--fe-bd); cursor: pointer; white-space: nowrap; font-size: 12px; }
        .fe-table th:hover { background: var(--fe-hov); }
        .fe-table td { padding: 0 12px; height: 32px; white-space: nowrap; color: var(--fe-mut); }
        .fe-table td.nm { color: var(--fe-tx); width: 100%; max-width: 0; }
        .fe-table td.nm > div { display: flex; align-items: center; gap: 9px; overflow: hidden; }
        .fe-table td.nm span { overflow: hidden; text-overflow: ellipsis; }
        .fe-table tr.row { cursor: default; }
        .fe-table tr.row:hover td { background: var(--fe-hov); }
        .fe-table tr.row.sel td { background: var(--fe-sel); }
        .fe-table .r { text-align: right; }
        .fe-dl { color: var(--fe-mut); display: inline-flex; padding: 4px; border-radius: 4px; }
        .fe-dl:hover { background: var(--fe-bg3); color: var(--fe-tx); }
        .fe-qr-btn { color: #6366f1; display: inline-flex; padding: 4px; border-radius: 4px; cursor: pointer; border: 0; background: transparent; }
        .fe-qr-btn:hover { background: rgba(99, 102, 241, 0.15); color: #4f46e5; }

        /* Cuadrícula */
        .fe-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 6px; padding: 12px; }
        .fe-tile { position: relative; display: flex; flex-direction: column; align-items: center; text-align: center; padding: 8px 4px; border: 1px solid transparent; border-radius: 6px; cursor: default; }
        .fe-tile:hover { background: var(--fe-hov); }
        .fe-tile.sel { background: var(--fe-sel); border-color: var(--fe-selbd); }
        .fe-tile .ic { height: 48px; display: flex; align-items: center; justify-content: center; }
        .fe-tile .ic img { width: 48px; height: 48px; object-fit: cover; border-radius: 4px; }
        .fe-tile .tn { margin-top: 4px; width: 100%; font-size: 12px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; word-break: break-word; }
        .fe-tile .grid-actions { position: absolute; top: 4px; right: 4px; display: none; gap: 2px; background: var(--fe-bg); border: 1px solid var(--fe-bd); border-radius: 4px; padding: 2px; box-shadow: 0 2px 5px rgba(0,0,0,.1); z-index: 5; }
        .fe-tile:hover .grid-actions { display: flex; }

        .fe-empty { height: 100%; min-height: 220px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: var(--fe-mut); }
        .fe-status { display: flex; align-items: center; gap: 14px; padding: 5px 12px; background: var(--fe-bg2); border-top: 1px solid var(--fe-bd); font-size: 12px; color: var(--fe-mut); }

        .fe-pop { position: absolute; top: calc(100% + 4px); left: 0; z-index: 30; width: 260px; padding: 10px; background: var(--fe-bg); border: 1px solid var(--fe-bd); border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.18); }
        .fe-pop input { flex: 1; min-width: 0; padding: 5px 8px; border: 1px solid var(--fe-bd); border-radius: 5px; background: var(--fe-bg); color: var(--fe-tx); user-select: text; }
        .fe-pop .go { padding: 5px 12px; border: 0; border-radius: 5px; background: #2563eb; color: #fff; font-weight: 600; cursor: pointer; }

        /* Modal QR */
        .fe-modal-overlay { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.6); backdrop-filter: blur(2px); padding: 16px; }
        .fe-modal { width: 100%; max-width: 380px; background: var(--fe-bg); border: 1px solid var(--fe-bd); border-radius: 12px; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,.25); text-align: center; color: var(--fe-tx); }

        @media (max-width: 760px) { .fe-tree { width: 190px; } .fe-search { width: 140px; } .fe-table .hide-sm { display: none; } }
    </style>

    @php
        $directories = $this->directories;
        $files = $this->files;
        $tree = $this->tree;
        $parts = $currentPath === '' ? [] : explode('/', $currentPath);
        $arrow = $sortDir === 'asc' ? '▲' : '▼';
    @endphp

    <div class="fe"
         x-data="{
            up: false, pct: 0, err: '', name: '',
            qrOpen: false, qrUrl: '', qrFileName: '', copied: false,
            showQr(url, name) {
                this.qrUrl = url;
                this.qrFileName = name;
                this.qrOpen = true;
                this.copied = false;
                this.$nextTick(() => {
                    const c = document.getElementById('fe-qr-target');
                    if (c) {
                        c.innerHTML = '';
                        new QRCode(c, {
                            text: url,
                            width: 190,
                            height: 190,
                            colorDark: '#1e293b',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    }
                });
            },
            copyLink() {
                navigator.clipboard.writeText(this.qrUrl);
                this.copied = true;
                setTimeout(() => this.copied = false, 2500);
            },
            downloadQr() {
                const img = document.querySelector('#fe-qr-target img');
                if (img) {
                    const a = document.createElement('a');
                    a.href = img.src;
                    a.download = 'QR-' + this.qrFileName + '.png';
                    a.click();
                }
            },
            send(prop, input, multiple, done) {
                const files = [...input.files];
                if (!files.length) return;
                this.err = ''; this.pct = 0; this.up = true;
                this.name = files.length > 1 ? files.length + ' archivos' : files[0].name;
                const ok = () => { this.up = false; input.value = ''; done(); };
                const bad = () => { this.up = false; input.value = ''; this.err = 'No se pudo subir: el archivo supera el límite del servidor o la conexión falló.'; };
                const prog = (e) => { this.pct = e.detail.progress; };
                if (multiple) { this.$wire.uploadMultiple(prop, files, ok, bad, prog); }
                else { this.$wire.upload(prop, files[0], ok, bad, prog); }
            }
         }"
         @keydown.escape.window="qrOpen = false">

        {{-- 1. CINTA DE OPCIONES --}}
        <div class="fe-bar">
            <div x-data="{ open: false }" style="position: relative;">
                <button type="button" class="fe-btn" @click="open = !open">
                    <x-filament::icon icon="heroicon-s-folder-plus" class="i16" style="color: var(--fe-folder);" />
                    Nueva carpeta
                </button>
                <div class="fe-pop" x-show="open" x-cloak @click.outside="open = false">
                    <div style="font-size: 11px; color: var(--fe-mut); margin-bottom: 6px;">
                        Se creará dentro de: {{ $currentPath === '' ? 'Raíz' : $currentPath }}
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <input type="text" wire:model="newFolderName" placeholder="Nombre de la carpeta"
                               @keydown.enter="$wire.createFolder(); open = false" />
                        <button type="button" class="go" wire:click="createFolder" @click="open = false">Crear</button>
                    </div>
                </div>
            </div>

            <span class="fe-sep"></span>

            <label class="fe-btn" x-on:change="send('uploadedFiles', $event.target, true, () => $wire.uploadDocuments())">
                <x-filament::icon icon="heroicon-o-arrow-up-tray" class="i16" style="color: #059669;" />
                Subir archivos
                <input type="file" multiple style="display: none;" />
            </label>

            <label class="fe-btn" x-on:change="send('uploadedZip', $event.target, false, () => $wire.extractZip())">
                <x-filament::icon icon="heroicon-o-archive-box-arrow-down" class="i16" style="color: #6366f1;" />
                Subir carpeta (.zip)
                <input type="file" accept=".zip" style="display: none;" />
            </label>

            <span class="fe-sep"></span>

            <button type="button" class="fe-btn" wire:click="deleteSelected"
                    wire:confirm="¿Eliminar el elemento seleccionado? Esta acción no se puede deshacer."
                    @disabled($selected === '')>
                <x-filament::icon icon="heroicon-o-trash" class="i16" style="color: #ef4444;" />
                Eliminar
            </button>

            <span class="fe-spacer"></span>

            <div class="fe-seg">
                <button type="button" wire:click="setViewMode('list')" title="Detalles" class="{{ $viewMode === 'list' ? 'on' : '' }}">
                    <x-filament::icon icon="heroicon-o-list-bullet" class="i16" />
                </button>
                <button type="button" wire:click="setViewMode('grid')" title="Iconos grandes" class="{{ $viewMode === 'grid' ? 'on' : '' }}">
                    <x-filament::icon icon="heroicon-o-squares-2x2" class="i16" />
                </button>
            </div>
        </div>

        {{-- PROGRESO: subida --}}
        <div class="fe-prog" x-show="up" x-cloak>
            <div class="fe-prog-top">
                <span>Subiendo <strong x-text="name"></strong>…</span>
                <span x-text="pct + '%'"></span>
            </div>
            <div class="fe-track"><div class="fe-fill" :style="'width:' + pct + '%'"></div></div>
        </div>

        {{-- PROGRESO: servidor --}}
        <div class="fe-prog" wire:loading.flex wire:target="uploadDocuments,extractZip" style="flex-direction: column;">
            <div class="fe-prog-top"><span>Guardando en el servidor…</span></div>
            <div class="fe-track"><div class="fe-fill fe-indet"></div></div>
        </div>

        {{-- PROGRESO: eliminando --}}
        <div class="fe-prog fe-prog-del" wire:loading.flex wire:target="deleteSelected" style="flex-direction: column;">
            <div class="fe-prog-top"><span>Eliminando…</span></div>
            <div class="fe-track"><div class="fe-fill fe-indet"></div></div>
        </div>

        {{-- Error de subida --}}
        <div class="fe-prog fe-prog-err" x-show="err" x-cloak>
            <div class="fe-prog-top">
                <span x-text="err"></span>
                <button type="button" class="fe-crumb" @click="err = ''">Cerrar</button>
            </div>
        </div>

        {{-- 2. BARRA DE DIRECCIÓN Y BÚSQUEDA --}}
        <div class="fe-addr">
            <button type="button" class="fe-btn" style="padding: 5px 7px;" wire:click="goToParent" title="Subir un nivel" @disabled($currentPath === '')>
                <x-filament::icon icon="heroicon-o-arrow-up" class="i16" />
            </button>

            <div class="fe-crumbs">
                <button type="button" class="fe-crumb {{ empty($parts) ? 'last' : '' }}" wire:click="changeDirectory('')">
                    <x-filament::icon icon="heroicon-s-home" class="i14" style="color: #3b82f6;" /> Raíz
                </button>
                @php $step = ''; @endphp
                @foreach ($parts as $part)
                    @php $step .= ($loop->first ? '' : '/') . $part; @endphp
                    <x-filament::icon icon="heroicon-m-chevron-right" class="i14" style="color: var(--fe-mut);" />
                    <button type="button" class="fe-crumb {{ $loop->last ? 'last' : '' }}" wire:click="changeDirectory({{ Js::from($step) }})">{{ $part }}</button>
                @endforeach
            </div>

            <div class="fe-search">
                <x-filament::icon icon="heroicon-o-magnifying-glass" class="i14" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar en esta carpeta" />
            </div>
        </div>

        {{-- 3. CUERPO: ÁRBOL + CONTENIDO --}}
        <div class="fe-body">

            {{-- Panel de navegación (árbol) --}}
            <nav class="fe-tree" aria-label="Árbol de carpetas">
                @foreach ($tree as $node)
                    <div class="fe-node {{ $node['active'] ? 'active' : '' }}"
                         wire:key="node-{{ $node['path'] === '' ? 'root' : md5($node['path']) }}"
                         wire:click="changeDirectory({{ Js::from($node['path']) }})"
                         title="{{ $node['name'] }}">

                        @for ($i = 1; $i < $node['depth']; $i++)
                            <span class="fe-guide"></span>
                        @endfor

                        @if ($node['depth'] === 0)
                            <span style="width: 18px; flex: none;"></span>
                            <x-filament::icon icon="heroicon-s-home" class="i16" style="color: #3b82f6; margin-left: 2px;" />
                        @else
                            @if ($node['has_children'])
                                <button type="button" class="fe-chev {{ $node['expanded'] ? 'open' : '' }}"
                                        wire:click.stop="toggleNode({{ Js::from($node['path']) }})"
                                        aria-label="{{ $node['expanded'] ? 'Contraer' : 'Expandir' }}">
                                    <x-filament::icon icon="heroicon-m-chevron-right" class="i14" />
                                </button>
                            @else
                                <span style="width: 18px; flex: none;"></span>
                            @endif
                            <x-filament::icon :icon="$node['expanded'] ? 'heroicon-s-folder-open' : 'heroicon-s-folder'" class="i16" style="color: var(--fe-folder); margin-left: 2px;" />
                        @endif

                        <span class="nm">{{ $node['name'] }}</span>
                    </div>
                @endforeach
            </nav>

            {{-- Contenido de la carpeta actual --}}
            <section class="fe-main" wire:click="selectItem('')">

                @if (count($directories) === 0 && count($files) === 0)
                    <div class="fe-empty">
                        <x-filament::icon icon="heroicon-o-folder-open" class="i44" style="opacity: .5;" />
                        <span>{{ $search !== '' ? 'No hay resultados para “' . $search . '”' : 'Esta carpeta está vacía. Sube archivos o crea una carpeta.' }}</span>
                    </div>

                @elseif ($viewMode === 'list')
                    <table class="fe-table">
                        <thead>
                            <tr>
                                <th wire:click.stop="sortList('name')">Nombre {{ $sortBy === 'name' ? $arrow : '' }}</th>
                                <th class="hide-sm" wire:click.stop="sortList('modified')">Fecha de modificación {{ $sortBy === 'modified' ? $arrow : '' }}</th>
                                <th class="hide-sm" style="cursor: default;">Tipo</th>
                                <th class="r" wire:click.stop="sortList('size')">Tamaño {{ $sortBy === 'size' ? $arrow : '' }}</th>
                                <th style="width: 65px; text-align: right; cursor: default;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($directories as $dir)
                                <tr class="row {{ $selected === $dir['path'] ? 'sel' : '' }}" wire:key="d-{{ md5($dir['path']) }}"
                                    wire:click.stop="selectItem({{ Js::from($dir['path']) }})"
                                    wire:dblclick="changeDirectory({{ Js::from($dir['path']) }})">
                                    <td class="nm">
                                        <div>
                                            <x-filament::icon icon="heroicon-s-folder" class="i18" style="color: var(--fe-folder);" />
                                            <span>{{ $dir['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="hide-sm">—</td>
                                    <td class="hide-sm">Carpeta de archivos</td>
                                    <td class="r">{{ $dir['items_count'] }} ítems</td>
                                    <td></td>
                                </tr>
                            @endforeach

                            @foreach ($files as $file)
                                <tr class="row {{ $selected === $file['path'] ? 'sel' : '' }}" wire:key="f-{{ md5($file['path']) }}"
                                    wire:click.stop="selectItem({{ Js::from($file['path']) }})"
                                    x-on:dblclick="window.open({{ Js::from($file['url']) }}, '_blank')">
                                    <td class="nm">
                                        <div>
                                            @if ($file['is_image'])
                                                <img src="{{ $file['url'] }}" alt="" style="width: 18px; height: 18px; border-radius: 3px; object-fit: cover;" />
                                            @else
                                                <x-filament::icon :icon="$file['icon']" class="i18" :style="'color: ' . $file['color'] . ';'" />
                                            @endif
                                            <span>{{ $file['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="hide-sm">{{ $file['last_modified'] }}</td>
                                    <td class="hide-sm">{{ $file['type'] }}</td>
                                    <td class="r">{{ $file['size'] }}</td>
                                    <td class="r" style="display: flex; align-items: center; justify-content: flex-end; gap: 3px; height: 32px;">
                                        {{-- Botón Enlace / QR --}}
                                        <button type="button" class="fe-qr-btn" title="Enlace público y Código QR"
                                                x-on:click.stop="showQr({{ Js::from($file['url']) }}, {{ Js::from($file['name']) }})">
                                            <x-filament::icon icon="heroicon-o-qr-code" class="i16" />
                                        </button>
                                        {{-- Botón Descargar --}}
                                        <a href="{{ $file['url'] }}" target="_blank" download class="fe-dl" title="Descargar" wire:click.stop>
                                            <x-filament::icon icon="heroicon-o-arrow-down-tray" class="i14" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else
                    <div class="fe-grid">
                        @foreach ($directories as $dir)
                            <div class="fe-tile {{ $selected === $dir['path'] ? 'sel' : '' }}" wire:key="gd-{{ md5($dir['path']) }}"
                                 wire:click.stop="selectItem({{ Js::from($dir['path']) }})"
                                 wire:dblclick="changeDirectory({{ Js::from($dir['path']) }})" title="{{ $dir['name'] }}">
                                <div class="ic"><x-filament::icon icon="heroicon-s-folder" class="i44" style="color: var(--fe-folder);" /></div>
                                <div class="tn">{{ $dir['name'] }}</div>
                                <div style="font-size: 11px; color: var(--fe-mut);">{{ $dir['items_count'] }} ítems</div>
                            </div>
                        @endforeach

                        @foreach ($files as $file)
                            <div class="fe-tile {{ $selected === $file['path'] ? 'sel' : '' }}" wire:key="gf-{{ md5($file['path']) }}"
                                 wire:click.stop="selectItem({{ Js::from($file['path']) }})"
                                 x-on:dblclick="window.open({{ Js::from($file['url']) }}, '_blank')" title="{{ $file['name'] }}">
                                
                                {{-- Acciones sobre el mosaico --}}
                                <div class="grid-actions">
                                    <button type="button" class="fe-qr-btn" title="Código QR"
                                            x-on:click.stop="showQr({{ Js::from($file['url']) }}, {{ Js::from($file['name']) }})">
                                        <x-filament::icon icon="heroicon-o-qr-code" class="i14" />
                                    </button>
                                    <a href="{{ $file['url'] }}" target="_blank" download class="fe-dl" title="Descargar" wire:click.stop>
                                        <x-filament::icon icon="heroicon-o-arrow-down-tray" class="i14" />
                                    </a>
                                </div>

                                <div class="ic">
                                    @if ($file['is_image'])
                                        <img src="{{ $file['url'] }}" alt="" />
                                    @else
                                        <x-filament::icon :icon="$file['icon']" class="i44" :style="'color: ' . $file['color'] . ';'" />
                                    @endif
                                </div>
                                <div class="tn">{{ $file['name'] }}</div>
                                <div style="font-size: 11px; color: var(--fe-mut);">{{ $file['size'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        {{-- 4. BARRA DE ESTADO --}}
        <div class="fe-status">
            <span>{{ count($directories) + count($files) }} elementos ({{ count($directories) }} carpetas, {{ count($files) }} archivos)</span>
            @if ($selected !== '')
                <span>· Seleccionado: <strong style="color: var(--fe-tx);">{{ basename($selected) }}</strong></span>
            @endif
            <span class="fe-spacer"></span>
            <span>Tamaño de archivos aquí: <strong style="color: var(--fe-tx);">{{ $this->stats['total_size'] }}</strong></span>
        </div>

        {{-- 5. MODAL DE CÓDIGO QR Y ENLACE DIRECTO --}}
        <div class="fe-modal-overlay" x-show="qrOpen" x-cloak x-transition.opacity>
            <div class="fe-modal" @click.outside="qrOpen = false">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--fe-bd); padding-bottom: 10px; margin-bottom: 14px;">
                    <div style="text-align: left; overflow: hidden; padding-right: 8px;">
                        <div style="font-weight: 700; font-size: 13px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" x-text="qrFileName"></div>
                        <div style="font-size: 11px; color: var(--fe-mut);">Código QR y enlace directo</div>
                    </div>
                    <button type="button" @click="qrOpen = false" class="fe-crumb" style="font-size: 14px; font-weight: bold;">✕</button>
                </div>

                {{-- Contenedor del código QR renderizado con fondo blanco para escaneo perfecto --}}
                <div style="display: flex; justify-content: center; padding: 12px; background: #ffffff; border-radius: 8px; border: 1px solid var(--fe-bd); width: fit-content; margin: 0 auto 14px auto;">
                    <div id="fe-qr-target" style="width: 190px; height: 190px; display: flex; align-items: center; justify-content: center;"></div>
                </div>

                {{-- Campo con la URL directa y botón copiar --}}
                <div style="display: flex; gap: 6px; margin-bottom: 12px;">
                    <input type="text" :value="qrUrl" readonly select-all
                           style="flex: 1; min-width: 0; padding: 5px 8px; border: 1px solid var(--fe-bd); border-radius: 6px; background: var(--fe-bg2); color: var(--fe-tx); font-size: 11.5px; user-select: text;" />
                    <button type="button" class="fe-btn" style="background: #2563eb; color: #fff; font-weight: 600; padding: 5px 10px;" @click="copyLink()">
                        <span x-show="!copied">Copiar</span>
                        <span x-show="copied" style="color: #a7f3d0;">¡Listo!</span>
                    </button>
                </div>

                {{-- Acciones del pie --}}
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="fe-btn" style="flex: 1; justify-content: center; background: var(--fe-bg2); border: 1px solid var(--fe-bd);" @click="downloadQr()">
                        Descargar QR
                    </button>
                    <a :href="qrUrl" target="_blank" class="fe-btn" style="flex: 1; justify-content: center; background: #059669; color: #fff; text-decoration: none;">
                        Abrir archivo
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>