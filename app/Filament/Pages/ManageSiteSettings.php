<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Page Content';

    protected static ?string $title = 'Page Content & Site Settings';

    protected static string $view = 'filament.pages.manage-site-settings';

    public array $data = [];

    public function mount(): void
    {
        $this->data = SiteSetting::query()
            ->orderBy('category')
            ->orderBy('setting_key')
            ->get()
            ->mapWithKeys(fn(SiteSetting $s) => [$s->setting_key => static::castOut($s)])
            ->toArray();

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        $rows = SiteSetting::query()
            ->orderBy('category')
            ->orderBy('setting_key')
            ->get();

        $tabs = $rows
            ->groupBy('category')
            ->map(function ($group, $category) {
                return Forms\Components\Tabs\Tab::make(Str::headline($category))
                    ->schema(
                        $group->map(fn(SiteSetting $s) => static::componentFor($s))->all()
                    );
            })
            ->values()
            ->all();

        if (empty($tabs)) {
            return $form->schema([
                Forms\Components\Placeholder::make('empty')
                    ->content('No site settings have been seeded yet. Run php artisan db:seed --class=SiteContentSeeder --force on the server.'),
            ]);
        }

        return $form->schema([Forms\Components\Tabs::make('Settings')->tabs($tabs)->persistTabInQueryString()])
            ->statePath('data');
    }

    protected static function componentFor(SiteSetting $s): Forms\Components\Component
    {
        $label = $s->description ?: Str::of($s->setting_key)->replace('_', ' ')->title()->toString();
        $helper = null;

        $component = str_ends_with($s->setting_key, '_image')
            ? Forms\Components\FileUpload::make($s->setting_key)
                ->image()
                ->disk('public')
                ->directory('page-images')
                ->visibility('public')
            : match ($s->setting_type) {
            'textarea' => Forms\Components\Textarea::make($s->setting_key)->rows(4),
            'number'   => Forms\Components\TextInput::make($s->setting_key)->numeric(),
            'boolean'  => Forms\Components\Toggle::make($s->setting_key),
            'email'    => Forms\Components\TextInput::make($s->setting_key)->email(),
            'url'      => Forms\Components\TextInput::make($s->setting_key)->url(),
            default    => Forms\Components\TextInput::make($s->setting_key),
            };

        return $component->label($label)->helperText($helper);
    }

    protected static function castOut(SiteSetting $s): mixed
    {
        return match ($s->setting_type) {
            'boolean' => filter_var($s->setting_value, FILTER_VALIDATE_BOOLEAN),
            'number'  => is_numeric($s->setting_value) ? $s->setting_value + 0 : null,
            default   => $s->setting_value,
        };
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            $row = SiteSetting::where('setting_key', $key)->first();
            if (! $row) {
                continue;
            }
            if ($row->setting_type === 'boolean') {
                $value = $value ? '1' : '0';
            }
            $row->update(['setting_value' => is_array($value) ? json_encode($value) : (string) $value]);
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save settings')
                ->submit('save'),
        ];
    }
}
