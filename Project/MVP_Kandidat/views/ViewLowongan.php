<?php
require_once __DIR__ . '/KontrakView.php';

class ViewLowongan implements KontrakViewLowongan
{
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

    public function renderDaftar(string $rowsHtml, int $totalLowongan): string
    {
        $konten = str_replace(
            ['{{ROWS}}', '{{TOTAL}}'],
            [$rowsHtml, (string)$totalLowongan],
            file_get_contents(__DIR__ . '/../template/daftar_lowongan.html')
        );
        return $this->bungkus('Menampilkan halaman daftar', $konten);
    }

    public function renderForm(string $judul, string $aksi, array $formData): string
    {
        $status = $formData['status'] ?? 'Buka';
        $konten = str_replace(
            ['{{JUDUL_FORM}}', '{{AKSI}}', '{{ID}}', '{{JUDUL}}', '{{DEPARTEMEN}}', '{{LOKASI}}', '{{KUOTA}}',
             '{{SEL_BUKA}}', '{{SEL_TUTUP}}'],
            [$this->e($judul), $this->e($aksi), $this->e($formData['id'] ?? ''),
             $this->e($formData['judul'] ?? ''), $this->e($formData['departemen'] ?? ''),
             $this->e($formData['lokasi'] ?? ''), $this->e($formData['kuota'] ?? '1'),
             $status === 'Buka' ? 'selected' : '', $status === 'Tutup' ? 'selected' : ''],
            file_get_contents(__DIR__ . '/../template/form_lowongan.html')
        );
        return $this->bungkus('Menampilkan halaman form', $konten);
    }
}
