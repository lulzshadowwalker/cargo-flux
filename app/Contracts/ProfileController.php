<?php

namespace App\Contracts;

interface ProfileController
{
    public function index();
    public function update(string $language, $request);
}
