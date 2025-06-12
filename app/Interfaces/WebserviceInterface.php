<?php

namespace App\Interfaces;

interface WebserviceInterface {
    /**
     * Get the base URL for the web service
     */
    public function getBaseUrl(): string;

    /**
     * Get the headers to be used in requests
     */
    public function getHeaders(): array;
}
