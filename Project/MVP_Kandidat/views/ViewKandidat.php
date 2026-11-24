<?php
require_once __DIR__ . '/KontrakView.php';

class ViewKandidat implements KontrakView
{
    // Bungkus konten ke dalam layout utama (skin.html)
    private function bungkus(string $subjudul, string $konten): string
    {
        return str_replace(
            ['{{SUBJUDUL}}', '{{KONTEN}}'],
            [$subjudul, $konten],
            file_get_contents(__DIR__ . '/../template/skin.html')
        );
    }

    private function e(mixed $v): string
    {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }

    public function renderDaftar(string $rowsHtml, int $totalKandidat): string
    {
        $konten = str_replace(
            ['{{ROWS}}', '{{TOTAL}}'],
            [$rowsHtml, (string)$totalKandidat],
            file_get_contents(__DIR__ . '/../template/daftar_kandidat.html')
        );
        return $this->bungkus('Menampilkan halaman daftar', $konten);
    }

    public function renderForm(string $judul, string $aksi, array $formData): string
    {
        // Nilai kosong dipakai saat form tambah (belum ada data)
        $konten = str_replace(
            ['{{JUDUL_FORM}}', '{{AKSI}}', '{{ID}}', '{{NAMA}}', '{{POSISI}}', '{{DOMISILI}}', '{{SKOR}}', '{{PENGALAMAN}}'],
            [$this->e($judul), $this->e($aksi), $this->e($formData['id'] ?? ''),
             $this->e($formData['nama'] ?? ''), $this->e($formData['posisi'] ?? ''),
             $this->e($formData['domisili'] ?? ''), $this->e($formData['skorTes'] ?? ''),
             $this->e($formData['pengalamanTahun'] ?? '')],
            file_get_contents(__DIR__ . '/../template/form.html')
        );
        return $this->bungkus('Menampilkan halaman form', $konten);
    }
}
