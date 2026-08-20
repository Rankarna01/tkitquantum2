<?php

class UserModel extends Model
{
    protected string $table = 'users';

    public function findByUsernameOrEmail(string $identity): ?array
    {
        $row = $this->raw(
            "SELECT u.*, r.nama_role FROM users u
             JOIN roles r ON r.id = u.role_id
             WHERE u.username = :identity1 OR u.email = :identity2 LIMIT 1",
            ['identity1' => $identity, 'identity2' => $identity]
        )->fetch();
        return $row ?: null;
    }

    public function incrementFailedAttempts(int $id): void
    {
        $this->raw("UPDATE users SET failed_attempts = failed_attempts + 1 WHERE id = :id", ['id' => $id]);
    }

    public function resetFailedAttempts(int $id): void
    {
        $this->raw("UPDATE users SET failed_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = :id", ['id' => $id]);
    }

    public function lockAccount(int $id, int $minutes): void
    {
        $this->raw(
            "UPDATE users SET locked_until = DATE_ADD(NOW(), INTERVAL :m MINUTE) WHERE id = :id",
            ['m' => $minutes, 'id' => $id]
        );
    }

    public function isLocked(array $user): bool
    {
        return !empty($user['locked_until']) && strtotime($user['locked_until']) > time();
    }

    public function updatePassword(int $id, string $hash): void
    {
        $this->raw("UPDATE users SET password = :p WHERE id = :id", ['p' => $hash, 'id' => $id]);
    }
}
