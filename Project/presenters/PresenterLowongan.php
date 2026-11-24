<?php
require_once __DIR__ . '/KontrakPresenter.php';
require_once __DIR__ . '/../models/KontrakModel.php';
require_once __DIR__ . '/../views/KontrakView.php';

class PresenterLowongan implements KontrakPresenterLowongan
{
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    // Susun satu baris <tr> tabel lowongan
    private function buildRowHtml(int $no, Lowongan $l): string
    {
        $e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
        $id = $l->getId();
        return '<tr>'
            . '<td>' . $no . '</td>'
            . '<td class="bold">' . $e($l->getJudul()) . '</td>'
            . '<td>' . $e($l->getDepartemen()) . '</td>'
            . '<td>' . $e($l->getLokasi()) . '</td>'
            . '<td>' . $l->getKuota() . ' Orang</td>'
            . '<td>' . $e($l->getStatus()) . '</td>'
            . '<td class="aksi">'
            . '<a class="edit" href="index.php?page=form_lowongan&id=' . $id . '">Edit</a>'
            . '<form method="post" action="index.php" onsubmit="return confirm(\'Hapus lowongan ini?\')">'
            . '<input type="hidden" name="page" value="lowongan">'
            . '<input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '">'
            . '<button class="hapus" type="submit">Hapus</button></form>'
            . '</td></tr>';
    }

    public function tampilkanLowongan(): string
    {
        $list = $this->model->getAllLowongan();
        $rowsHtml = '';
        if (empty($list)) {
            $rowsHtml = '<tr><td colspan="7" class="kosong">Belum ada data lowongan.</td></tr>';
        } else {
            $no = 1;
            foreach ($list as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no++, $lowongan);
            }
        }
        return $this->view->renderDaftar($rowsHtml, count($list));
    }

    public function tampilkanFormLowongan(?int $id = null): string
    {
        if ($id !== null) {
            $l = $this->model->getLowonganById($id);
            if ($l === null) {
                header("Location: index.php?page=lowongan");
                exit();
            }
            $formData = [
                'id'         => $l->getId(),
                'judul'      => $l->getJudul(),
                'departemen' => $l->getDepartemen(),
                'lokasi'     => $l->getLokasi(),
                'kuota'      => $l->getKuota(),
                'status'     => $l->getStatus(),
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", []);
    }

    private function validasi(array $d): ?array
    {
        $judul = trim($d['judul'] ?? '');
        $dept = trim($d['departemen'] ?? '');
        $lokasi = trim($d['lokasi'] ?? '');
        $kuota = (int)($d['kuota'] ?? 0);
        $status = $d['status'] ?? '';

        if ($judul === '' || $dept === '' || $lokasi === '') return null;
        if ($kuota < 1 || !in_array($status, ['Buka', 'Tutup'], true)) return null;
        return [$judul, $dept, $lokasi, $kuota, $status];
    }

    public function tambahLowongan(array $data): bool
    {
        $v = $this->validasi($data);
        return $v !== null && $this->model->addLowongan(...$v);
    }

    public function ubahLowongan(array $data): bool
    {
        $id = (int)($data['id'] ?? 0);
        $v = $this->validasi($data);
        return $id > 0 && $v !== null && $this->model->updateLowongan($id, ...$v);
    }

    public function hapusLowongan(int $id): bool
    {
        return $id > 0 && $this->model->deleteLowongan($id);
    }
}
