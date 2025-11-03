<?php
class Logger {
    // Ruta relativa al proyecto: app/helpers -> ../../logs/app.log -> /logs/app.log
    protected static $logFile = __DIR__ . '/../../logs/app.log';

    protected static function write($level, $message, $data = null)
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] [$level] $message";
        if ($data !== null) {
            if (!is_string($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
            }
            $entry .= ' ' . $data;
        }
        $entry .= PHP_EOL;
        @file_put_contents(self::$logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    public static function info($message, $data = null)
    {
        self::write('INFO', $message, $data);
    }

    public static function error($message, $data = null)
    {
        self::write('ERROR', $message, $data);
    }

    public static function debug($message, $data = null)
    {
        self::write('DEBUG', $message, $data);
    }
}
