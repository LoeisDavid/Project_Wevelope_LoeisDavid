<?php
include_once '../../Control/urlController.php';

// handle url
$redirect = $_GET['redirect'] ?? null;
$url = null;

if(isset($redirect)){
  $url = sessionGetRedirectUrl2();
} else {
  $url = sessionGetRedirectUrl();
$uri = $_SERVER['REQUEST_URI'];
sessionSetRedirectUrl2($uri);
}

$namaPerusahaan= $_POST['nama'] ?? '';
$pic = '';
$alamat= $_POST['alamat'] ?? '';
$kota= $_POST['kota'] ?? '';
$provinsi= $_POST['provinsi'] ?? '';
$kodePos= $_POST['kodePos'] ?? '';
$negara= $_POST['negara'] ?? ''; 
$telepon= $_POST['telepon'] ?? ''; 
$email= $_POST['email'] ?? ''; 

$data = $_SESSION['COMPANY'] ?? null;
// var_dump($data['ID']);die();
if($data['ID']){
  $company = readCompanyById($data['ID']);
  $namaPerusahaan= $company->getNama();
  $alamat= $company->getAlamat();
  $kota= $company->getKota();
  $provinsi= $company->getProvinsi();
  $kodePos= $company->getKodePos();
  $negara= $company->getNegara();
  $telepon= $company->getTelepon();
  $email= $company->getEmail();
}

$id = $company->getId() ?? $_POST['id'] ?? null;

// var_dump($action);die;
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE 4 | General Form Elements</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE 4 | General Form Elements" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <!--end::Sidebar-->
    <!--begin::App Main-->
        <?php include __DIR__ . '/../widget/alert.php'; ?>
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
                        <!-- Alert Session Message -->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="card card-success card-outline mb-8">
      <div class="app-content-header">
        <div class="card-header">
          <div class="card-title">Company Data</div>
        </div>

        
                  <!--begin::Header-->
                  <!--end::Header-->
                  <!--begin::Body-->
                  <div class="card-body">
        <!--end::Header-->
        <form method="post" action="<?=getUrlControl('type=company')?>" enctype="multipart/form-data">

        <div class="mb-3">
          <input type="text" value="<?= $id?>" name="id" hidden>
      <label for="ref_no" class="form-label">Nama Perusahaan</label>
      <input
        type="text"
        class="form-control"
        id="ref_no"
        name="nama"
        value="<?=$namaPerusahaan?>"
        required
      />
    </div>

    <div class="mb-3">
    <label for="name" class="form-label">Alamat</label>
      <input
        type="text"
        class="form-control"
        name="alamat"
        value="<?= $alamat?>"
        required
      />
    </div>

    <label for="price" class="form-label">Kode Pos</label>
    <div class="input-group mb-3">
      <input
        type="text"
        class="form-control"
        name="kodePos"
        value="<?=$kodePos ?>"
        required
      />
    </div>

    <label for="price" class="form-label">Kota</label>
    <div class="input-group mb-3">
      <input
        type="text"
        class="form-control"
        name="kota"
        value="<?=$kota ?>"
        required
      />
    </div>

    <label for="price" class="form-label">Provinsi</label>
    <div class="input-group mb-3">
      <input
        type="text"
        class="form-control"
        name="provinsi"
        value="<?=$provinsi ?>"
        required
      />
    </div>

    <label for="price" class="form-label">Negara</label>
    <div class="input-group mb-3">
      <input
        type="text"
        class="form-control"
        name="negara"
        value="<?=$negara ?>"
        required
      />
    </div>

    <div class="mb-3">
    <label for="name" class="form-label">TELEPON</label>
      <input
        type="text"
        class="form-control"
        name="telepon"
        value="<?= $telepon?>"
        required
      />
    </div>

    <div class="mb-3">
    <label for="name" class="form-label">EMAIL</label>
      <input
        type="text"
        class="form-control"
        name="email"
        value="<?= $email?>"
        required
      />
    </div>

    <div class="mb-3">
    <label for="file">Pilih logo</label>
  <input type="file" name="gambar" id="file" accept="image/*" required>
    </div>

    <div class="card-footer">
                      <button type="submit" class="btn btn-success  float-end" >Sumbit</button>
                      <a href="<?=$url?>" class="btn btn-secondary" >Cancel</a>
                    </div>
    <div class="card-footer">
    </div>
  </form>
  </div>
  </div>
  </div>
  </main>
        <!--begin::Form-->
        <!--begin::JavaScript-->
    
        <!--end::JavaScript-->

    <!--end::App Main-->
    <!--begin::Footer-->
    <?php include __DIR__ . '/../widget/footer.php'; ?>
    <!--end::Footer-->
  </div>

  <script>
          // Example starter JavaScript for disabling form submissions if there are invalid fields
          (() => {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.from(forms).forEach((form) => {
              form.addEventListener(
                'submit',
                (event) => {
                  if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                  }

                  form.classList.add('was-validated');
                },
                false,
              );
            });
          })();
        </script>
        
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="../js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <!--end::OverlayScrollbars Configure-->
  <!--end::Script-->
  <script const SELECTOR_SIDEBAR_WRAPPER='.sidebar-wrapper' ; const Default={ scrollbarTheme: 'os-theme-light' ,
  scrollbarAutoHide: 'leave' , scrollbarClickScroll: true, }; document.addEventListener('DOMContentLoaded', function
  () { const sidebarWrapper=document.querySelector(SELECTOR_SIDEBAR_WRAPPER); if (sidebarWrapper && typeof
  OverlayScrollbarsGlobal?.OverlayScrollbars !=='undefined' ) {
  OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, { scrollbars: { theme: Default.scrollbarTheme, autoHide:
  Default.scrollbarAutoHide, clickScroll: Default.scrollbarClickScroll, }, }); } });></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<!--end::Script-->
  <!--end::OverlayScrollbars Configure-->
  <!--end::Script-->
  <script 
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script>
</body>
<!--end::Body-->

</html>