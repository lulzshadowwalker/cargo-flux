<?php

namespace App\Filament\Pages;

use App\Models\Page as ModelsPage;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrivacyPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.privacy-policy';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.content-management');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament/pages/privacy-policy.privacy-policy');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/pages/privacy-policy.privacy-policy');
    }

    public array $data = [];

    public function mount()
    {
        $page = ModelsPage::where('slug', ModelsPage::PRIVACY_POLICY)->first();

        $this->form->fill([
            'content' => $page->content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make(__('filament/pages/privacy-policy.privacy-policy'))
                    ->description(__('filament/pages/privacy-policy.privacy-policy-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label(__('filament/pages/privacy-policy.content'))
                            ->rows(10)
                            ->required(),
                    ]),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make(__('filament/pages/privacy-policy.publish'))
                ->action(fn() => $this->publish()),
        ];
    }

    public function publish()
    {
        $content = $this->data['content'];
        if (! $content) return;

        try {
            $page = ModelsPage::where('slug', ModelsPage::PRIVACY_POLICY)->first();
            if (! $page) {
                throw new Exception('prviacy policy page not found');
            }

            $page->content = Str::trim($content);
            $page->save();

            Notification::make()
                ->success()
                ->title(__('filament/pages/privacy-policy.privacy-policy'))
                ->body(__('filament/pages/privacy-policy.privacy-policy-published'))
                ->send();
        } catch (Exception $e) {
            Log::error('Failed to publish privacy policy page', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            Notification::make()
                ->danger()
                ->title(__('filament/pages/privacy-policy.publish-failed'))
                ->send();
        }
    }
}
