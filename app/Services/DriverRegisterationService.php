<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\DriverRegisterationRequest;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverRegisterationService implements RegisterationService
{
    public function register(Request $request): User
    {
        return DB::transaction(function () use ($request) {
            Log::info('Registering driver', ['request' => $request->all()]);
            $request = $this->validate($request);

            $user = User::create($request->mappedAttributes(['type' => UserType::DRIVER])->toArray());

            $user->driver()->create([
                'first_name' => $request->firstName(),
                'middle_name' => $request->middleName(),
                'last_name' => $request->lastName(),
                'residence_address' => $request->residenceAddress(),
                'secondary_phone' => $request->secondaryPhone(),
            ]);

            if ($request->avatar()) {
                $user->addMedia($request->avatar())->toMediaCollection(User::MEDIA_COLLECTION_AVATAR);
            }

            $user->driver->addMedia($request->passport())->toMediaCollection(Driver::MEDIA_COLLECTION_PASSPORT);

            $user->driver->addMedia($request->driverLicense())->toMediaCollection(Driver::MEDIA_COLLECTION_LICENSE);

            $user->driver->truck()->create([
                'license_plate' => $request->licensePlate(),
                'truck_category_id' => $request->truckCategory(),
            ]);

            $user->driver->truck->addMedia($request->truckLicense())->toMediaCollection(Truck::MEDIA_COLLECTION_LICENSE);

            foreach ($request->truckImages() as $image) {
                $user->driver->truck->addMedia($image)->toMediaCollection(Truck::MEDIA_COLLECTION_IMAGES);
            }

            return $user;
        });
    }

    private function validate(Request $request): DriverRegisterationRequest
    {
        $r = new DriverRegisterationRequest;
        $r->setMethod('POST');
        $r->merge($request->all());
        $r->files = $request->files;

        $r->validate($r->rules());

        return $r;
    }
}
