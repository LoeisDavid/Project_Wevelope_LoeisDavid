<?php
include_once '../../Control/urlController.php';

// handle url
$url = $_SERVER['REQUEST_URI'];
sessionSetRedirectUrl($url);

$company = readCompanies();
$pic = sessionGetObjectTruePices();

// var_dump($company);die();
$id = $company['ID'] ?? null;
$nama = $company['NAMA_PERUSAHAAN'] ?? "";
$pic = $pic->getNama() ?? "";
$alamat = $company['ALAMAT'] ?? "";
$kota = $company['KOTA'] ?? '';
$provinsi = $company['PROVINSI'] ?? '';
$kodePos = $company['KODE_POS'] ?? '';
$negara = $company['NEGARA'] ?? '';
$telepon = $company['TELEPON'] ?? '';
$email = $company['EMAIL'] ?? '';
$logo = $company['URLOGO'] ?? '';

$company = new Company($id, $nama, $pic, $alamat, $kodePos,$kota, $provinsi, $negara, $telepon, $email, $logo);

if($id){
$_SESSION['COMPANY'] = [
  'ID' => $id
] ;
}


?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>item Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary sidebar-open app-loaded">
  <div class="app-wrapper">
        <?php include __DIR__ . '/../widget/alert.php'; ?>
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>

    <main class="app-main">
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <h1>Informasi Perusahaan</h1>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Detail Pengaturan</h3>
            <div class="card-tools">
              <a href="<?=getUrlInputSettingCompany()?>" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Ubah
              </a>
            </div>
          </div>
          <div class="card-body">
            <dl class="row">

            <dt class="col-sm-3">Logo Perusahaan</dt>
<dd class="col-sm-9">
  <?php if ($logo): ?>
    <img src="<?= '../' . $logo ?>" alt="Logo Perusahaan" style="max-height: 100px;">
  <?php else: ?>
    <span class="text-muted">Belum ada logo</span>
  <?php endif; ?>
</dd>


              <dt class="col-sm-3">Nama Perusahaan</dt>
              <dd class="col-sm-9"><?= $nama?></dd>

              <dt class="col-sm-3">PIC</dt>
              <dd class="col-sm-9"><?= $pic?></dd>

              <dt class="col-sm-3">Alamat</dt>
              <dd class="col-sm-9"><?= $alamat?></dd>

              <dt class="col-sm-3">Kota</dt>
              <dd class="col-sm-9"><?= $kota?></dd>

              <dt class="col-sm-3">Provinsi</dt>
              <dd class="col-sm-9"><?= $provinsi?></dd>

              <dt class="col-sm-3">Kode Pos</dt>
              <dd class="col-sm-9"><?= $kodePos?></dd>

              <dt class="col-sm-3">Negara</dt>
              <dd class="col-sm-9"><?= $negara?></dd>

              <dt class="col-sm-3">Telepon</dt>
              <dd class="col-sm-9"><?= $telepon?></dd>

              <dt class="col-sm-3">Email</dt>
              <dd class="col-sm-9"><?= $email?></dd>

            </dl>
          </div>
        </div>
      </div>
    </section>
    </main>

    <?php include __DIR__ . '/../widget/footer.php'; ?>
  </div>

<!-- Alert Delete -->

  <!-- Scripts -->
   <script 
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="../../js/adminlte.js"></script>
</body>
</html>
