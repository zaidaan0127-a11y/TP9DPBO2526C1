<?php
// Entity: representasi satu baris tabel kandidat
class Kandidat
{
    public function __construct(
        private int $id,
        private string $nama,
        private string $posisi,
        private string $domisili,
        private int $skorTes,
        private int $pengalamanTahun
    ) {}

    public function getId(): int { return $this->id; }
    public function getNama(): string { return $this->nama; }
    public function getPosisi(): string { return $this->posisi; }
    public function getDomisili(): string { return $this->domisili; }
    public function getSkorTes(): int { return $this->skorTes; }
    public function getPengalamanTahun(): int { return $this->pengalamanTahun; }
}
