<?php

require_once __DIR__.'/api.php';

$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$projectRoot = realpath(__DIR__.'/..');
$baseUrl = '';

if ($documentRoot !== false && $projectRoot !== false) {
    $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');
    $projectRoot = str_replace('\\', '/', $projectRoot);
    if (stripos($projectRoot, $documentRoot.'/') === 0) {
        $baseUrl = substr($projectRoot, strlen($documentRoot));
    }
}

$baseUrl = '/'.trim($baseUrl, '/');
if ($baseUrl === '/') {
    $baseUrl = '';
}

$institutional = muni_api_data('configuracion-institucional') ?? [];
