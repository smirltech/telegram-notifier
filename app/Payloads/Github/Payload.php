<?php

namespace App\Payloads\Github;

interface Payload
{

    public function content(): string;

    public function image(): string;

    public function url(): string;

}
