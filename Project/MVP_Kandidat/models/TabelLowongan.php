<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModel.php';
require_once __DIR__ . '/Lowongan.php';

class TabelLowongan extends DB implements KontrakModelLowongan
{
    private function mapRowToLowongan(array $row): Lowongan
    {
        return new Lowongan(
            (int)$row['id'], $row['judul'], $row['departemen'],
            $row['lokasi'], (int)$row['kuota'], $row['status']
        );
    }

    public function getAllLowongan(): array
    {
        $this->executeQuery("SELECT * FROM lowongan ORDER BY id ASC");
        $list = [];
        foreach ($this->getAllResult() as $row) {
            $list[] = $this->mapRowToLowongan($row);
        }
        return $list;
    }

    public function getLowonganById(int $id): ?Lowongan
    {
        $this->executeQuery("SELECT * FROM lowongan WHERE id = :id LIMIT 1", [':id' => $id]);
        $rows = $this->getAllResult();
        return empty($rows) ? null : $this->mapRowToLowongan($rows[0]);
    }

    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): bool
    {
        return $this->executeQuery(
            "INSERT INTO lowongan (judul, departemen, lokasi, kuota, status)
             VALUES (:judul, :dept, :lokasi, :kuota, :status)",
            [':judul' => $judul, ':dept' => $departemen, ':lokasi' => $lokasi,
             ':kuota' => $kuota, ':status' => $status]
        );
    }

    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): bool
    {
        return $this->executeQuery(
            "UPDATE lowongan SET judul = :judul, departemen = :dept, lokasi = :lokasi,
             kuota = :kuota, status = :status WHERE id = :id",
            [':judul' => $judul, ':dept' => $departemen, ':lokasi' => $lokasi,
             ':kuota' => $kuota, ':status' => $status, ':id' => $id]
        );
    }

    public function deleteLowongan(int $id): bool
    {
        return $this->executeQuery("DELETE FROM lowongan WHERE id = :id", [':id' => $id]);
    }
}
