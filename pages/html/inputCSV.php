<?php

include_once '../../Control/urlController.php';
$redirect = $_GET['redirect'] ?? null;
if (isset($redirect)) {
    $url = sessionGetRedirectUrl2();
} else {
    $url = sessionGetRedirectUrl();
    $uri = $_SERVER['REQUEST_URI'];
    sessionSetRedirectUrl2($uri);
}
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
    <?php include __DIR__ . '/../widget/alert.php'; ?>
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <div class="card-header">
          <div class="card-title">Customers input</div>
        </div>

        <!--begin::Form Import CSV -->
<div class="card card-success card-outline mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Import Customers from CSV</h3>
  </div>

  <div class="card-body">
    <form method="post" enctype="multipart/form-data" action="<?= getUrlControl() ?>" class="needs-validation" novalidate>
    <input name="csv" value="customer" hidden>
    <input name="action" value="import" hidden>  
    <div class="mb-3">
        <label for="csv_file" class="form-label">Pilih File CSV</label>
        <input type="file" class="form-control" name="file" id="csv_file" accept=".csv" required>
        <div class="invalid-feedback">Harap pilih file CSV.</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Format File CSV</label>
        <div class="alert alert-secondary small" role="alert">
          File harus memiliki kolom berikut (urutan penting):<br>
          <code>REF_NO, NAME, EMAIL, TELEPON, ALAMAT</code><br>
          Contoh:
          <pre class="mb-0">C001,John Doe,john@example.com,08123456789,Jakarta</pre>
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <button type="submit" name="import" class="btn btn-success">
          <i class="bi bi-upload"></i> Import CSV
        </button>
      </div>
    </form>
  </div>
</div>
</div>
</main>
<!--end::Form Import CSV -->

        <!--end::Header-->
        <!--begin::Form-->
       
        <!--end::Form-->
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
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
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