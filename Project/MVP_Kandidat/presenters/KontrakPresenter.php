<?php
// Kontrak Presenter: operasi yang dipanggil oleh index.php
interface KontrakPresenter
{
    public function tampilkanKandidat(): string;
    public function tampilkanFormKandidat(?int $id = null): string;
    public function tambahKandidat(array $data): bool;
    public function ubahKandidat(array $data): bool;
    public function hapusKandidat(int $id): bool;
}

interface KontrakPresenterLowongan
{
    public function tampilkanLowongan(): string;
    public function tampilkanFormLowongan(?int $id = null): string;
    public function tambahLowongan(array $data): bool;
    public function ubahLowongan(array $data): bool;
    public function hapusLowongan(int $id): bool;
}
