<?php

namespace App\Filament\Resources\RouteGroupResource\Pages;

use App\Filament\Resources\RouteGroupResource;
use App\Models\RouteGroup;
use App\Models\RouteGroupDestination;
use App\Models\RouteGroupTruckOption;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateRouteGroup extends CreateRecord
{
    protected static string $resource = RouteGroupResource::class;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getLivewire()->data;

            $group = RouteGroup::create([
                'pickup_state_id' => $data['pickup_state_id'],
            ]);

            foreach ($data['truck_options'] as $option) {
                //  NOTE: This is a workaround to prevent creating empty truck options from the feature test
                if (! isset($option['truck_category_id'], $option)) continue;

                RouteGroupTruckOption::create([
                    'route_group_id' => $group->id,
                    'truck_category_id' => $option['truck_category_id'],
                    'amount' => $option['amount'],
                    'currency_id' => $option['currency_id'],
                ]);
            }

            foreach ($data['destinations'] as $destination) {
                RouteGroupDestination::create([
                    'route_group_id' => $group->id,
                    'delivery_state_id' => $destination,
                ]);
            }

            $this->commitDatabaseTransaction();

            Notification::make()
                ->success()
                ->title(__('filament/resources/route-group-resource.route-create-success-title'))
                ->body(__('filament/resources/route-group-resource.route-create-success-body'))
                ->send();

            if ($another) {
                $this->redirect(RouteGroupResource::getUrl('create'));
                return;
            }

            $this->redirect(RouteGroupResource::getUrl('edit', ['record' => $group]));
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            Notification::make()
                ->danger()
                ->title(__('filament/resources/route-group-resource.route-create-failure-title'))
                ->body(__('filament/resources/route-group-resource.route-create-failure-body'))
                ->send();

            Log::error("failed to create route group", [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
