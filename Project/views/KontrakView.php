<?php
// Kontrak View: View pasif, hanya menerima string/array siap tampil dari Presenter
interface KontrakView
{
    public function renderDaftar(string $rowsHtml, int $totalKandidat): string;
    public function renderForm(string $judul, string $aksi, array $formData): string;
}

interface KontrakViewLowongan
{
    public function renderDaftar(string $rowsHtml, int $totalLowongan): string;
    public function renderForm(string $judul, string $aksi, array $formData): string;
}
