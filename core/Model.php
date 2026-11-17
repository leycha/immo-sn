<?php
namespace Core;

use PDO;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    protected static function db(): PDO
    {
        return Database::getConnection();
    }

    public static function all(string $orderBy = 'created_at DESC', int $limit = 100): array
    {
        $sql = "SELECT * FROM " . static::$table . " ORDER BY $orderBy LIMIT $limit";
        return static::db()->query($sql)->fetchAll();
    }

    public static function find(string|int $id): ?array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id LIMIT 1";
        $stmt = static::db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): string|int
    {
        $cols = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders) RETURNING " . static::$primaryKey;
        $stmt = static::db()->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchColumn();
    }

    public static function update(string|int $id, array $data): bool
    {
        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $sql = "UPDATE " . static::$table . " SET $set WHERE " . static::$primaryKey . " = :__id";
        $data['__id'] = $id;
        return static::db()->prepare($sql)->execute($data);
    }

    public static function delete(string|int $id): bool
    {
        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id";
        return static::db()->prepare($sql)->execute(['id' => $id]);
    }

    public static function count(string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM " . static::$table . " WHERE $where";
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}