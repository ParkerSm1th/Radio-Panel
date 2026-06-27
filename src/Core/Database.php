<?php

namespace RadioPanel\Core;

use PDO;
use PDOException;

class Database
{
    
    private static $connection = null;

    private static $columnCache = [];

    private static $tableCache = [];

    
    public static function connection()
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = Config::get('database.host', 'localhost');
        $name = Config::get('database.name', 'keyfm');
        $user = Config::get('database.user', 'root');
        $pass = Config::get('database.pass', '');
        $charset = Config::get('database.charset', 'utf8mb4');

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $name, $charset);

        try {
            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            Logger::exception($e, [
                'host' => $host,
                'database' => $name,
            ]);

            if (Config::get('app.debug', false)) {
                throw $e;
            }

            throw new HttpException('Database connection failed.', 503, $e);
        }

        return self::$connection;
    }

    
    public static function getConnection()
    {
        return self::connection();
    }

    public static function hasTable($table)
    {
        $table = trim((string) $table);
        if ($table === '') {
            return false;
        }

        if (isset(self::$tableCache[$table])) {
            return self::$tableCache[$table];
        }

        $stmt = self::connection()->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table'
        );
        $stmt->bindValue(':table', $table);
        $stmt->execute();

        return self::$tableCache[$table] = ((int) $stmt->fetchColumn() > 0);
    }

    public static function hasColumn($table, $column)
    {
        $table = trim((string) $table);
        $column = trim((string) $column);
        $key = $table . '.' . $column;

        if (isset(self::$columnCache[$key])) {
            return self::$columnCache[$key];
        }

        if (!self::hasTable($table)) {
            return self::$columnCache[$key] = false;
        }

        $stmt = self::connection()->prepare(
            'SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :table AND column_name = :column'
        );
        $stmt->bindValue(':table', $table);
        $stmt->bindValue(':column', $column);
        $stmt->execute();

        return self::$columnCache[$key] = ((int) $stmt->fetchColumn() > 0);
    }

    public static function ensureSchema()
    {
        $conn = self::connection();
        $changes = [
            'panel_pages' => [
                'pending' => 'ALTER TABLE panel_pages ADD COLUMN pending int(11) NOT NULL DEFAULT 0',
                'redirect' => 'ALTER TABLE panel_pages ADD COLUMN redirect int(11) NOT NULL DEFAULT 0',
            ],
            'users' => [
                'pending' => 'ALTER TABLE users ADD COLUMN pending int(11) NOT NULL DEFAULT 0',
                'guest' => "ALTER TABLE users ADD COLUMN guest varchar(10) NOT NULL DEFAULT '0'",
                'type' => "ALTER TABLE users ADD COLUMN type varchar(50) NOT NULL DEFAULT ''",
            ],
        ];

        foreach ($changes as $table => $columns) {
            if (!self::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column => $sql) {
                if (self::hasColumn($table, $column)) {
                    continue;
                }

                try {
                    $conn->exec($sql);
                    self::$columnCache[$table . '.' . $column] = true;
                    Logger::info('Added missing database column', ['table' => $table, 'column' => $column]);
                } catch (PDOException $e) {
                    Logger::warning('Unable to add missing database column', [
                        'table' => $table,
                        'column' => $column,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
