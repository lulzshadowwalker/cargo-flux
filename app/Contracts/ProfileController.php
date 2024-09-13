<?php

namespace App\Contract;

use Illuminate\Support\Facades\Response;

interface ProfileController
{
    public function me(): Response;
    public function update(): Response;
}
