<?php

namespace App\Contracts;

interface ResponseBuilder
{
    public function data(array $data): self;
    public function meta(array $meta): self;
    public function message(string $message): self;
    public function error(string $title, string $detail, int $code = null, array $meta = [], string $pointer = null): self;
    public function build(): mixed;
}
