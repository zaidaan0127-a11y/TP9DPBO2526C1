<?php
// Kelas dasar koneksi database (PDO). Dipakai oleh semua Tabel* lewat extends.
class DB
{
    private PDO $pdo;
    private ?PDOStatement $stmt = null;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=mvp_db;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    // Prepared statement: query + parameter dipisah agar aman dari SQL injection
    protected function executeQuery(string $query, array $params = []): bool
    {
        $this->stmt = $this->pdo->prepare($query);
        return $this->stmt->execute($params);
    }

    // Ambil semua baris hasil query terakhir (untuk SELECT)
    protected function getAllResult(): array
    {
        return $this->stmt ? $this->stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
