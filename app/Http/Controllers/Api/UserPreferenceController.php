<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function index()
    {
        return UserPreferenceResource::make(Auth::user()->preferences);
    }

    public function update(string $language, UpdateUserPreferenceRequest $request)
    {
        $preferences = Auth::user()->preferences()->updateOrCreate(
            ['user_id' => Auth::user()->id],
            $request->mappedAttributes()->toArray(),
        );

        return UserPreferenceResource::make($preferences);
    }
}
