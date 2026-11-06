<?php
// Fallback entry point when FlyEnv serves the project root instead of /public.
$basePath = rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
header('Location: ' . ($basePath === '' ? '' : $basePath) . '/public/');
exit;
