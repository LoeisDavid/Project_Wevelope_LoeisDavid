<?php
include_once '../../Control/Control.php';

if (
  isset($_GET['type'], $_GET['action'], $_GET['id']) &&
  $_GET['type'] === 'invoice' &&
  $_GET['action'] === 'delete'
) {
  $id = (int) $_GET['id'];
  deleteInvoice($id);    
  $_SESSION['alert_delete'] = [
    'type' => 'success',
    'message' => 'Invoice berhasil dihapus.',
  ];
}

// Ambil semua invoice
$items = readInvoices();
$customers = readCustomers();

// Hapus invoice yang tidak memiliki item di iteminv

// Cek apakah ada request GET untuk pencarian atau aksi lainnya
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $type = $_GET['type'] ?? 'invoice';
  $action = $_GET['action'] ?? 'read';
  $keyword = $_GET['keyword'] ?? '';
  $customer = $_GET['customer'] ??'';
  $start_date = $_GET['start_date'] ??'';
  $end_date = $_GET['end_date'] ??'';

  if (!empty($keyword) && $keyword != '') {
    $action = 'search';
  }

  if ($type === 'invoice') {
    if ($action === 'search') {
      $itemSearch = searchInvoices($keyword);
    } else {
      if( $customer!= null && $customer != ''){
        $itemSearch = readInvoiceByCustomer($customer);
      } else {
        if($start_date != null && $start_date != '' && $end_date != null && $end_date != '' ){
          $itemSearch = readInvoiceByRangeDate($start_date,$end_date);
        } else {
          $itemSearch = [];
        }
      }
    }

    

    
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
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary sidebar-open app-loaded">
  <div class="app-wrapper">
  <?php include __DIR__ . '/../widget/header.php'; ?>
  <?php include __DIR__ . '/../widget/sidebar.php'; ?>
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
        <div class="container mt-3">
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Invoices Table</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Invoices</li>
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
                
                <div class="card-body text-center">
                <form method="GET" class="row row-cols-lg-auto g-2 align-items-end justify-content-center mb-3">
  <input type="hidden" name="type" value="invoice">

  <div class="col">
    <label for="keyword" class="form-label">Keyword</label>
    <input
      type="text"
      id="keyword"
      name="keyword"
      class="form-control"
      placeholder="Search invoice..."
      value="<?= htmlspecialchars($keyword) ?>"
    >
  </div>

  <div class="col">
    <label for="customer" class="form-label">Customer</label>
    <select name="customer" id="customer" class="form-select">
      <option value="">-- Pilih Customer --</option>
      <?php foreach ($customers as $cust): ?>
        <option value="<?= $cust->getId() ?>"><?= htmlspecialchars($cust->getName()) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col">
    <label for="start_date" class="form-label">Start Date</label>
    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
  </div>

  <div class="col">
    <label for="end_date" class="form-label">End Date</label>
    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
  </div>

  <div class="col">
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-search"></i> Search
    </button>
  </div>
</form>


                <table class="table table-bordered mx-auto">
  <thead>
    <tr>
      <th style="width: 10px">KODE</th>
      <th>TANGGAL</th>
      <th>CUSTOMERS</th>
      <th style="width: 120px">Actions</th> <!-- kolom baru -->
    </tr>
  </thead>
  <tbody>
    <?php if (count($itemSearch) > 0): ?>
      <?php foreach ($itemSearch as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item->getKode()) ?></td>
          <td><?= htmlspecialchars($item->getDate()) ?></td>
          <td><?= htmlspecialchars(readCustomerById($item->getCustomerId())->getName()) ?></td>
          <td class="text-center">
          <div class="d-flex justify-content-center gap-1">
  <!-- Tombol View -->
  <a
    href="tableItemInv.php?invoice=<?= $item->getId() ?>"
    class="btn btn-sm btn-info"
    title="Lihat Detail Invoice"
  >
    <i class="bi bi-eye"></i>
  </a>

  <!-- Tombol Edit -->
  <a
    href="editInvoices.php?method=get&id=<?= $item->getId() ?>&kode=<?= $item->getKode()?>&customer=<?= $item->getCustomerId()?>"
    class="btn btn-sm btn-warning"
    title="Edit Invoices"
  >
    <i class="bi bi-pencil-square"></i>
  </a>

  <!-- Tombol Delete -->
  <a
    href="?type=invoice&action=delete&id=<?= $item->getId() ?>"
    class="btn btn-sm btn-danger"
    onclick="return confirm('Yakin ingin menghapus invoice ini?');"
    title="Delete Invoice"
  >
    <i class="bi bi-trash"></i>
  </a>
</div>


        </tr>        
      <?php endforeach; ?>
      
      <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted">No data found.</td></tr>
                      <?php endif; ?>
  </tbody>
</table>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8 mx-auto">
              <div class="card mb-4">
                <div class="card-header text-center"><h3 class="card-title">Data Invoices tersimpan</h3></div>
                <div class="card-body text-center">
                
                <table class="table table-bordered mx-auto">
  <thead>
    <tr>
      <th style="width: 10px">KODE</th>
      <th>TANGGAL</th>
      <th>CUSTOMERS</th>
      <th style="width: 120px">Actions</th> <!-- kolom baru -->
    </tr>
  </thead>
  <tbody>
    <?php if (count($items) > 0): ?>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item->getKode()) ?></td>
          <td><?= htmlspecialchars($item->getDate()) ?></td>
          <td><?= htmlspecialchars(readCustomerById($item->getCustomerId())->getName()) ?></td>
          <td class="text-center">
          <div class="d-flex justify-content-center gap-1">
  <!-- Tombol View -->
  <a
    href="tableItemInv.php?invoice=<?= $item->getId() ?>"
    class="btn btn-sm btn-info"
    title="Lihat Detail Invoice"
  >
    <i class="bi bi-eye"></i>
  </a>

  <!-- Tombol Edit -->
  <a
    href="editInvoices.php?method=get&id=<?= $item->getId() ?>&kode=<?= $item->getKode()?>&customer=<?= $item->getCustomerId()?>"
    class="btn btn-sm btn-warning"
    title="Edit Invoices"
  >
    <i class="bi bi-pencil-square"></i>
  </a>

  <!-- Tombol Delete -->
  <a
    href="?type=invoice&action=delete&id=<?= $item->getId() ?>"
    class="btn btn-sm btn-danger"
    onclick="return confirm('Yakin ingin menghapus invoice ini?');"
    title="Delete Invoice"
  >
    <i class="bi bi-trash"></i>
  </a>
</div>


        </tr>        
      <?php endforeach; ?>
      
      </tr>
    <?php endif; ?>
  </tbody>
</table>
<div class="text-start mt-3">
  <a href="inputInvoices.php" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Create New
  </a>
</div>
</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
      </main>
      <?php include __DIR__ . '/../widget/footer.php'; ?>
      <!--end::Footer-->
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
  <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow" role="alert" style="z-index: 9999; width: fit-content; max-width: 90%;">
    <?= $_SESSION['alert']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <script>
    setTimeout(() => {
      const alert = document.querySelector('.alert');
      if (alert) {
        bootstrap.Alert.getOrCreateInstance(alert).close();
      }
    }, 3000);
  </script>
  <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['alert_delete'])): ?>
  <div class="alert alert-<?= $_SESSION['alert_delete']['type'] ?> alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow" role="alert" style="z-index: 9999; width: fit-content; max-width: 90%;">
    <?= $_SESSION['alert_delete']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <script>
    setTimeout(() => {
      const alert = document.querySelectorAll('.alert')[1];
      if (alert) {
        bootstrap.Alert.getOrCreateInstance(alert).close();
      }
    }, 3000);
  </script>
  <?php unset($_SESSION['alert_delete']); ?>
<?php endif; ?>

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
