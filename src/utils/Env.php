<?php

class Env {
    private static $variables = [];

    public static function load($file = __DIR__ . '/../../.env') {
        if (!file_exists($file)) {
            throw new Exception('.env file not found.');
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            [$name, $value] = explode('=', $line, 2);
            self::$variables[trim($name)] = trim($value);
        }
    }

    public static function get($key, $default = null) {
        return self::$variables[$key] ?? $default;
    }
}

// Load the .env file
Env::load();
