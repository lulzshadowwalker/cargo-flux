<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WalletResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WalletController extends ApiController
{
    public function rewards()
    {
        return WalletResource::make(Auth::user()->getWallet(User::WALLET_REWARDS))->response()->setStatusCode(Response::HTTP_OK);
    }
}
