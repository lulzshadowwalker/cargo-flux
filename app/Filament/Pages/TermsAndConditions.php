<?php

namespace App\Filament\Pages;

use App\Models\Page as ModelsPage;
use Exception;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TermsAndConditions extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.terms-and-conditions';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.content-management');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament/pages/terms-and-conditions.terms-and-conditions');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/pages/terms-and-conditions.terms-and-conditions');
    }

    public array $data = [];

    public function mount()
    {
        $page = ModelsPage::where('slug', ModelsPage::TERMS_AND_CONDITIONS)->first();

        $this->form->fill([
            'content' => $page->content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make(__('filament/pages/terms-and-conditions.terms-and-conditions'))
                    ->description(__('filament/pages/terms-and-conditions.terms-and-conditions-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label(__('filament/pages/terms-and-conditions.content'))
                            ->rows(10)
                            ->required(),
                    ]),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make(__('filament/pages/terms-and-conditions.publish'))
                ->action(fn() => $this->publish()),
        ];
    }

    public function publish()
    {
        $content = $this->data['content'];
        if (! $content) return;

        try {
            $page = ModelsPage::where('slug', ModelsPage::TERMS_AND_CONDITIONS)->first();
            if (! $page) {
                throw new Exception('Terms and conditions page not found');
            }

            $page->content = Str::trim($content);
            $page->save();

            Notification::make()
                ->success()
                ->title(__('filament/pages/terms-and-conditions.terms-and-conditions'))
                ->body(__('filament/pages/terms-and-conditions.terms-and-conditions-published'))
                ->send();
        } catch (Exception $e) {
            Log::error('Failed to publish terms and conditions', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            Notification::make()
                ->danger()
                ->title(__('filament/pages/terms-and-conditions.publish-failed'))
                ->send();
        }
    }
}
