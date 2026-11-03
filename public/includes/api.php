<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

const MUNI_API_BASE = '/api/';

function muni_api_data(string $endpoint): ?array
{
    static $cache = [];

    if (array_key_exists($endpoint, $cache)) {
        return $cache[$endpoint];
    }

    $projectRoot = dirname(__DIR__, 2);
    require_once $projectRoot.'/vendor/autoload.php';
    $app = require $projectRoot.'/bootstrap/app.php';
    $kernel = $app->make(Kernel::class);
    $request = Request::create(
        MUNI_API_BASE.ltrim($endpoint, '/'),
        'GET',
        server: [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? '127.0.0.1',
        ],
    );
    $response = $kernel->handle($request);
    $responseBody = $response->getContent();
    $kernel->terminate($request, $response);

    if ($responseBody === false) {
        error_log('Municipal API returned an unreadable response for '.MUNI_API_BASE.ltrim($endpoint, '/'));

        return $cache[$endpoint] = null;
    }

    $result = json_decode($responseBody, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Municipal API returned invalid JSON for '.MUNI_API_BASE.ltrim($endpoint, '/').': '.json_last_error_msg());

        return $cache[$endpoint] = null;
    }

    if (! is_array($result) || ($result['status'] ?? null) !== 'success' || ! is_array($result['data'] ?? null)) {
        error_log('Municipal API returned an unexpected response for '.MUNI_API_BASE.ltrim($endpoint, '/'));

        return $cache[$endpoint] = null;
    }

    return $cache[$endpoint] = $result['data'];
}

function muni_escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function muni_http_url(mixed $value): ?string
{
    if (! is_string($value) || trim($value) === '') {
        return null;
    }

    $url = filter_var(trim($value), FILTER_VALIDATE_URL);
    if ($url === false || ! in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)) {
        return null;
    }

    return $url;
}

function muni_phone_href(mixed $value): ?string
{
    if (! is_string($value) && ! is_numeric($value)) {
        return null;
    }

    $digits = preg_replace('/\D+/', '', (string) $value);
    if ($digits === '') {
        return null;
    }

    return 'tel:+'.(str_starts_with($digits, '51') ? $digits : '51'.$digits);
}

function muni_local_or_http_url(mixed $value, string $baseUrl): ?string
{
    if (! is_string($value) || trim($value) === '') {
        return null;
    }

    $value = trim($value);
    $parts = parse_url($value);
    if ($parts === false) {
        return null;
    }

    $host = strtolower($parts['host'] ?? '');
    if (in_array($host, ['munilamay.gob.pe', 'www.munilamay.gob.pe'], true)) {
        $path = $parts['path'] ?? '/';

        return $baseUrl.'/'.ltrim($path, '/')
            .(isset($parts['query']) ? '?'.$parts['query'] : '')
            .(isset($parts['fragment']) ? '#'.$parts['fragment'] : '');
    }

    if (! isset($parts['scheme'])) {
        if (str_starts_with($value, '//')) {
            return null;
        }

        return $baseUrl.'/'.ltrim($value, '/');
    }

    return muni_http_url($value);
}
