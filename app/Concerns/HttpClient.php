<?php

namespace App\Concerns;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HttpClient
{
    /**
     * DELETE request to the specified URL with optional headers
     *
     * @param string $url URL to send the DELETE request to
     * @param array $headers Optional headers to include in the request
     * @param array $data Data to send in the request body
     * @param string $token JWT token for authentication
     */
    public function delete(string $url, array $headers = [], array $data = [], string $token = null): Response
    {
        $client = Http::withHeaders($headers);

        if ($token) {
            $client = $client->withToken($token);
        }

        return $client->delete($url, $data);
    }

    /**
     * GET request to the specified URL with optional headers
     *
     * @param string $url URL to send the GET request to
     * @param array $headers Optional headers to include in the request
     * @param array $data Query params to send in the request body
     * @param string $token JWT token for authentication
     */
    public function get(string $url, array $headers = [], array $data = [], string $token = null): Response
    {
        $client = Http::withHeaders($headers)->withQueryParameters($data);

        if ($token) {
            $client = $client->withToken($token);
        }

        return $client->get($url);
    }

    /**
     * POST request to the specified URL with optional headers
     *
     * @param string $url URL to send the POST request to
     * @param array $headers Optional headers to include in the request
     * @param array $data Data to send in the request body
     * @param string $token JWT token for authentication
     */
    public function post(string $url, array $headers = [], array $data = [], string $token = null): Response
    {
        $client = Http::withHeaders($headers);

        if ($token) {
            $client = $client->withToken($token);
        }

        return $client->post($url, $data);
    }

    /**
     * PUT request to the specified URL with optional headers
     *
     * @param string $url URL to send the PUT request to
     * @param array $headers Optional headers to include in the request
     * @param array $data Data to send in the request body
     * @param string $token JWT token for authentication
     */
    public function put(string $url, array $headers = [], array $data = [], string $token = null): Response
    {
        $client = Http::withHeaders($headers);

        if ($token) {
            $client = $client->withToken($token);
        }

        return $client->put($url, $data);
    }
}
