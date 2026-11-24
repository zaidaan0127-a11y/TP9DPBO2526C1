<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

// Merangkai Model + View + Presenter (satu-satunya tempat kelas konkret dipakai bersamaan)
$kandidat = new PresenterKandidat(new TabelKandidat(), new ViewKandidat());
$lowongan = new PresenterLowongan(new TabelLowongan(), new ViewLowongan());

// ---- Request POST: tambah / ubah / hapus ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modul  = ($_POST['page'] ?? '') === 'lowongan' ? 'lowongan' : 'kandidat';
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($modul === 'kandidat') {
        if ($action === 'tambah')     $kandidat->tambahKandidat($_POST);
        elseif ($action === 'ubah')   $kandidat->ubahKandidat($_POST);
        elseif ($action === 'hapus')  $kandidat->hapusKandidat($id);
    } else {
        if ($action === 'tambah')     $lowongan->tambahLowongan($_POST);
        elseif ($action === 'ubah')   $lowongan->ubahLowongan($_POST);
        elseif ($action === 'hapus')  $lowongan->hapusLowongan($id);
    }

    // Post-Redirect-Get: cegah submit ulang saat refresh
    header('Location: index.php?page=' . $modul);
    exit();
}

// ---- Request GET: routing halaman ----
$page = $_GET['page'] ?? 'kandidat';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : null;   // ada id => mode ubah

switch ($page) {
    case 'form_kandidat': echo $kandidat->tampilkanFormKandidat($id); break;
    case 'lowongan':      echo $lowongan->tampilkanLowongan(); break;
    case 'form_lowongan': echo $lowongan->tampilkanFormLowongan($id); break;
    default:              echo $kandidat->tampilkanKandidat();
}
