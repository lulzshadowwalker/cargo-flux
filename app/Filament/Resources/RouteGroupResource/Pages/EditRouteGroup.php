<?php

namespace App\Filament\Resources\RouteGroupResource\Pages;

use App\Filament\Resources\RouteGroupResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Log;
use Throwable;

use function Filament\Support\is_app_url;

class EditRouteGroup extends EditRecord
{
    protected static string $resource = RouteGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $original = $this->getRecord();
            $updated = $this->data;

            $this->callHook('afterValidate');

            if ($updated['pickup_state_id'] !== $original->pickup_state_id) {
                $original->update(['pickup_state_id' => $updated['pickup_state_id']]);
            }

            //  NOTE: Truck options
            foreach ($original->truckOptions as $opt) {
                $updatedOpt = collect($updated['truck_options'])->firstWhere('truck_category_id', $opt->truck_category_id);

                if ($updatedOpt) {
                    $opt->update($updatedOpt);
                    continue;
                }

                $opt->delete();
                continue;
            }

            foreach ($updated['truck_options'] as $opt) {
                $originalOpt = $original->truckOptions->firstWhere('truck_category_id', $opt['truck_category_id']);

                if ($originalOpt) continue;

                $original->truckOptions()->create($opt);
            }

            //  NOTE: Destinations
            foreach ($original->destinations as $dest) {
                if (! in_array($dest->delivery_state_id, $updated['destinations'])) {
                    $dest->delete();
                }
            }

            foreach ($updated['destinations'] as $dest) {
                if ($original->destinations->contains('delivery_state_id', $dest)) continue;

                $original->destinations()->create(['delivery_state_id' => $dest]);
            }

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            Notification::make()
                ->danger()
                ->title(__('filament/resources/route-group-resource.route-edit-failure-title'))
                ->body(__('filament/resources/route-group-resource.route-edit-failure-body'))
                ->send();

            Log::error("failed to edit route group", [
                'route_group_id' => $this->getRecord()->getKey(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        $this->rememberData();

        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()?->send();
        }

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }
}
