<?php

namespace App\Contracts;

//  NOTE: Not sure if I would rather have a controller interface or some service
//  could use a service that accepts a params object not necessarily a request object. but validation ? hmm.
interface ProfileController
{
    public function index();
    public function update(string $language, $request);
}
