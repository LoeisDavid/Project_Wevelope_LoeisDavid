<?php
include '../../Control/Control.php';


if (
  isset($_GET['type'], $_GET['action'], $_GET['id']) &&
  $_GET['type'] === 'supplier' &&
  $_GET['action'] === 'delete'
) {
  $id = (int) $_GET['id'];
  deleteSupplier($id);                    // pastikan fungsi ini ada di Control.php / repository
}

$items = []; // pastikan ini array kosong, bukan array berisi null

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['type'] ?? 'supplier';
    $action = $_GET['action'] ?? 'read';

    if ($type === 'supplier' && $action === 'read') {
        $items = readSuppliers(); // langsung assign array of Item object
    }
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE 4 | Items Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
  <div class="app-wrapper">
  <?php include __DIR__ . '/../widget/sidebar.php'; ?>
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <!-- Alert Session Message -->
<?php if (isset($_SESSION['alert'])): ?>
  <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['alert']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['alert_delete'])): ?>
  <div class="alert alert-<?= $_SESSION['alert_delete']['type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['alert_delete']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['alert_delete']); ?>
<?php endif; ?>
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Suppliers Table</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Suppliers</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="app-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8 mx-auto">
              <div class="card mb-4">
                <div class="card-header text-center"><h3 class="card-title">Suppliers Table</h3></div>
                <div class="card-body text-center">
                <table class="table table-bordered mx-auto">
  <thead>
    <tr>
      <th style="width: 10px">ID</th>
      <th>Name</th>
      <th>REF NO</th>
      <th style="width: 120px">Actions</th> <!-- kolom baru -->
    </tr>
  </thead>
  <tbody>
    <?php if (count($items) > 0): ?>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item->getId()) ?></td>
          <td><?= htmlspecialchars($item->getName()) ?></td>
          <td><?= htmlspecialchars($item->getRefNo()) ?></td>
          <td class="text-center">
            <!-- Tombol Edit -->
            <a
              href="editSuppliers.php?method=get&id=<?= $item->getId() ?>&name=<?= $item->getName()?>&ref_no=<?= $item->getRefNo()?>"
              class="btn btn-sm btn-warning me-1"
              title="Edit Suppliers"
            >
              <i class="bi bi-pencil-square"></i>
            </a>
            <!-- Tombol Delete -->
            <a
              href="?type=supplier&action=delete&id=<?= $item->getId() ?>"
              class="btn btn-sm btn-danger"
              onclick="return confirm('Yakin ingin menghapus supplier ini?');"
              title="Delete Supplier"
            >
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>        
      <?php endforeach; ?>
      
      </tr>
    <?php endif; ?>
  </tbody>
</table>
<div class="text-start mt-3">
  <a href="/../Project_Wevelope_LoeisDavid/pages/html/inputSuppliers.php" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Create New
  </a>
</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
      </main>
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../../js/adminlte.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
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
  </body>
  <!--end::Body-->
</html>
