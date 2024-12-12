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

class AboutUs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.about-us';

    public function getTitle(): string|Htmlable
    {
        return __('filament/pages/about-us.about-us');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/pages/about-us.about-us');
    }

    public array $data = [];

    public function mount()
    {
        $page = ModelsPage::where('slug', ModelsPage::ABOUT_US)->first();

        $this->form->fill([
            'content' => $page->content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make(__('filament/pages/about-us.about-us'))
                    ->description(__('filament/pages/about-us.about-us-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label(__('filament/pages/about-us.content'))
                            ->rows(10)
                            ->required(),
                    ]),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make(__('filament/pages/about-us.publish'))
                ->action(fn() => $this->publish()),
        ];
    }

    public function publish()
    {
        $content = $this->data['content'];
        if (! $content) return;

        try {
            $page = ModelsPage::where('slug', ModelsPage::ABOUT_US)->first();
            if (! $page) {
                throw new Exception('About us page not found');
            }

            $page->content = Str::trim($content);
            $page->save();

            Notification::make()
                ->success()
                ->title(__('filament/pages/about-us.about-us'))
                ->body(__('filament/pages/about-us.about-us-published'))
                ->send();
        } catch (Exception $e) {
            Log::error('Failed to publish about us page', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            Notification::make()
                ->danger()
                ->title(__('filament/pages/about-us.publish-failed'))
                ->send();
        }
    }
}
