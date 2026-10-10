<?php

namespace App\Filament\Pages;

use App\Models\InstitucionalSetting;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ConfiguracionInstitucional extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configuración Web';
    protected static ?string $title = 'Configuración del Portal Web';
    protected static ?string $slug = 'configuracion-institucional';
    protected static string | UnitEnum | null $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.configuracion-institucional';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = InstitucionalSetting::instance();
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Configuracion')
                    ->tabs([
                        Tab::make('Contacto y Redes')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('telefono_principal')
                                    ->label('Teléfono Principal')
                                    ->placeholder('957 756 538'),
                                TextInput::make('email_principal')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->placeholder('mesadepartes@munilamay.gob.pe'),
                                TextInput::make('direccion_corta')
                                    ->label('Dirección Corta (Barra superior)')
                                    ->placeholder('Plaza De Armas Lamay'),
                                Textarea::make('direccion_completa')
                                    ->label('Dirección Completa (Pie de página)')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                TextInput::make('mapa_url')
                                    ->label('Enlace de Google Maps')
                                    ->url()
                                    ->columnSpanFull(),
                                TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url(),
                                TextInput::make('whatsapp_numero')
                                    ->label('Número WhatsApp (solo dígitos)')
                                    ->placeholder('957756538'),
                                TextInput::make('tiktok_url')
                                    ->label('TikTok URL')
                                    ->url(),
                                TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url(),
                            ])->columns(2),

                        Tab::make('Hero y Portada')
                            ->icon('heroicon-o-film')
                            ->schema([
                                FileUpload::make('logo')
                                    ->label('Escudo / Logo Oficial')
                                    ->directory('configuracion')
                                    ->image()
                                    ->openable()
                                    ->downloadable(),
                                TextInput::make('hero_titulo')
                                    ->label('Título Principal del Hero')
                                    ->default('LAMAY'),
                                TextInput::make('hero_boton_url')
                                    ->label('Enlace del Botón "Ver Más"')
                                    ->url()
                                    ->columnSpanFull(),
                                FileUpload::make('video_hero')
                                    ->label('Video de Fondo (MP4 o WebM)')
                                    ->directory('configuracion/videos')
                                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                                    ->maxSize(512000),
                                TextInput::make('video_hero_url')
                                    ->label('O URL Directa del Video (si está alojado externamente)')
                                    ->placeholder('https://munilamay.gob.pe/.../video.mp4'),
                                Repeater::make('hero_textos_rotativos')
                                    ->label('Textos Rotativos del Hero')
                                    ->schema([
                                        TextInput::make('texto')
                                            ->label('Frase / Cargo / Mensaje')
                                            ->required(),
                                    ])
                                    ->defaultItems(3)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Tab::make('Emergencias y Horarios')
                            ->icon('heroicon-o-shield-exclamation')
                            ->schema([
                                TextInput::make('emergencia_pnp')
                                    ->label('Teléfono PAR (PNP)')
                                    ->placeholder('957 756 621'),
                                TextInput::make('emergencia_serenazgo')
                                    ->label('Teléfono Serenazgo')
                                    ->placeholder('957 756 611'),
                                TextInput::make('emergencia_defensa_civil')
                                    ->label('Teléfono Defensa Civil')
                                    ->placeholder('957 756 610'),
                                TextInput::make('horario_dias')
                                    ->label('Días de Atención')
                                    ->placeholder('Lunes a Viernes'),
                                TextInput::make('horario_manana')
                                    ->label('Horario Mañana')
                                    ->placeholder('8:00 a.m. - 1:00 p.m.'),
                                TextInput::make('horario_tarde')
                                    ->label('Horario Tarde')
                                    ->placeholder('2:00 p.m. - 5:00 p.m.'),
                                TextInput::make('horario_feriados')
                                    ->label('Aviso Feriados')
                                    ->placeholder('Feriados: Cerrado')
                                    ->columnSpanFull(),
                            ])->columns(3),

                        Tab::make('Accesos de Interés')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Repeater::make('accesos_interes')
                                    ->label('Logos y Enlaces a Entidades')
                                    ->schema([
                                        TextInput::make('nombre')
                                            ->label('Nombre de la entidad')
                                            ->placeholder('Ej: SUNAT, RENIEC')
                                            ->required(),
                                        TextInput::make('url')
                                            ->label('Enlace Web')
                                            ->url()
                                            ->required(),
                                        FileUpload::make('imagen')
                                            ->label('Logo / Imagen')
                                            ->directory('configuracion/accesos')
                                            ->image()
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(2)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar Cambios')
                ->action('save')
                ->color('primary')
                ->icon('heroicon-o-check'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $settings = InstitucionalSetting::instance();
        $settings->update($state);

        Notification::make()
            ->title('Configuración actualizada con éxito')
            ->success()
            ->send();
    }
}