<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (!$this?->id) {
            return [];
        }

        return [
            "type" => "wallet",
            "id" => (string) $this->id,
            "attributes" => [
                "balance" => (string) $this->balance,
            ],
            "links" => (object) [],
            "relationships" => (object) [],
            "includes" => (object) [],
        ];
    }
}
