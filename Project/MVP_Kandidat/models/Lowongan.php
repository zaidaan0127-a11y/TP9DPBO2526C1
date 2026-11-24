<?php
// Entity: representasi satu baris tabel lowongan
class Lowongan
{
    public function __construct(
        private int $id,
        private string $judul,
        private string $departemen,
        private string $lokasi,
        private int $kuota,
        private string $status
    ) {}

    public function getId(): int { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getDepartemen(): string { return $this->departemen; }
    public function getLokasi(): string { return $this->lokasi; }
    public function getKuota(): int { return $this->kuota; }
    public function getStatus(): string { return $this->status; }
}
