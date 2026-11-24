<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModel.php';
require_once __DIR__ . '/Kandidat.php';

class TabelKandidat extends DB implements KontrakModel
{
    // Ubah satu baris array hasil query menjadi objek Kandidat
    private function mapRowToKandidat(array $row): Kandidat
    {
        return new Kandidat(
            (int)$row['id'], $row['nama'], $row['posisi'], $row['domisili'],
            (int)$row['skorTes'], (int)$row['pengalamanTahun']
        );
    }

    public function getAllKandidat(): array
    {
        // Urut skor tertinggi dulu, id sebagai pembeda jika skor sama
        $this->executeQuery("SELECT * FROM kandidat ORDER BY skorTes DESC, id ASC");
        $list = [];
        foreach ($this->getAllResult() as $row) {
            $list[] = $this->mapRowToKandidat($row);
        }
        return $list;
    }

    public function getKandidatById(int $id): ?Kandidat
    {
        $this->executeQuery("SELECT * FROM kandidat WHERE id = :id LIMIT 1", [':id' => $id]);
        $rows = $this->getAllResult();
        return empty($rows) ? null : $this->mapRowToKandidat($rows[0]);
    }

    // CREATE
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool
    {
        return $this->executeQuery(
            "INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun)
             VALUES (:nama, :posisi, :domisili, :skor, :pengalaman)",
            [':nama' => $nama, ':posisi' => $posisi, ':domisili' => $domisili,
             ':skor' => $skorTes, ':pengalaman' => $pengalamanTahun]
        );
    }

    // UPDATE
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool
    {
        return $this->executeQuery(
            "UPDATE kandidat SET nama = :nama, posisi = :posisi, domisili = :domisili,
             skorTes = :skor, pengalamanTahun = :pengalaman WHERE id = :id",
            [':nama' => $nama, ':posisi' => $posisi, ':domisili' => $domisili,
             ':skor' => $skorTes, ':pengalaman' => $pengalamanTahun, ':id' => $id]
        );
    }

    // DELETE
    public function deleteKandidat(int $id): bool
    {
        return $this->executeQuery("DELETE FROM kandidat WHERE id = :id", [':id' => $id]);
    }
}
