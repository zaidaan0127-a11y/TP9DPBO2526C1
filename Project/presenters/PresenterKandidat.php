<?php
require_once __DIR__ . '/KontrakPresenter.php';
require_once __DIR__ . '/../models/KontrakModel.php';
require_once __DIR__ . '/../views/KontrakView.php';

class PresenterKandidat implements KontrakPresenter
{
    private KontrakModel $model;
    private KontrakView $view;

    public function __construct(KontrakModel $model, KontrakView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    // Susun satu baris <tr>; semua nilai di-escape agar aman dari XSS
    private function buildRowHtml(int $no, Kandidat $k): string
    {
        $e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
        $id = $k->getId();
        return '<tr>'
            . '<td>' . $no . '</td>'
            . '<td class="bold">' . $e($k->getNama()) . '</td>'
            . '<td>' . $e($k->getPosisi()) . '</td>'
            . '<td>' . $e($k->getDomisili()) . '</td>'
            . '<td>' . $k->getSkorTes() . ' / 100</td>'
            . '<td>' . $k->getPengalamanTahun() . ' Tahun</td>'
            . '<td class="aksi">'
            . '<a class="edit" href="index.php?page=form_kandidat&id=' . $id . '">Edit</a>'
            // Hapus memakai POST (bukan link GET) + konfirmasi
            . '<form method="post" action="index.php" onsubmit="return confirm(\'Hapus kandidat ini?\')">'
            . '<input type="hidden" name="page" value="kandidat">'
            . '<input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '">'
            . '<button class="hapus" type="submit">Hapus</button></form>'
            . '</td></tr>';
    }

    // READ: daftar kandidat
    public function tampilkanKandidat(): string
    {
        $listKandidat = $this->model->getAllKandidat();
        $totalKandidat = count($listKandidat);

        $rowsHtml = '';
        if (empty($listKandidat)) {
            $rowsHtml = '<tr><td colspan="7" class="kosong">Belum ada data kandidat.</td></tr>';
        } else {
            $no = 1;
            foreach ($listKandidat as $kandidat) {
                $rowsHtml .= $this->buildRowHtml($no++, $kandidat);
            }
        }
        return $this->view->renderDaftar($rowsHtml, $totalKandidat);
    }

    // Form tambah (id null) atau form ubah (id ada, data terisi)
    public function tampilkanFormKandidat(?int $id = null): string
    {
        if ($id !== null) {
            $kandidat = $this->model->getKandidatById($id);
            if ($kandidat === null) {
                header("Location: index.php");
                exit();
            }
            $formData = [
                'id'              => $kandidat->getId(),
                'nama'            => $kandidat->getNama(),
                'posisi'          => $kandidat->getPosisi(),
                'domisili'        => $kandidat->getDomisili(),
                'skorTes'         => $kandidat->getSkorTes(),
                'pengalamanTahun' => $kandidat->getPengalamanTahun(),
            ];
            return $this->view->renderForm("Ubah Data Kandidat", "ubah", $formData);
        }
        return $this->view->renderForm("Tambah Kandidat Baru", "tambah", []);
    }

    // Validasi input POST; return null jika tidak valid
    private function validasi(array $d): ?array
    {
        $nama = trim($d['nama'] ?? '');
        $posisi = trim($d['posisi'] ?? '');
        $domisili = trim($d['domisili'] ?? '');
        $skor = (int)($d['skorTes'] ?? -1);
        $pengalaman = (int)($d['pengalamanTahun'] ?? -1);

        if ($nama === '' || $posisi === '' || $domisili === '') return null;
        if ($skor < 0 || $skor > 100 || $pengalaman < 0) return null;
        return [$nama, $posisi, $domisili, $skor, $pengalaman];
    }

    // CREATE
    public function tambahKandidat(array $data): bool
    {
        $v = $this->validasi($data);
        return $v !== null && $this->model->addKandidat(...$v);
    }

    // UPDATE
    public function ubahKandidat(array $data): bool
    {
        $id = (int)($data['id'] ?? 0);
        $v = $this->validasi($data);
        return $id > 0 && $v !== null && $this->model->updateKandidat($id, ...$v);
    }

    // DELETE
    public function hapusKandidat(int $id): bool
    {
        return $id > 0 && $this->model->deleteKandidat($id);
    }
}
