<?php

namespace App\Contracts;

interface UseCase
{
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function handle();

}
