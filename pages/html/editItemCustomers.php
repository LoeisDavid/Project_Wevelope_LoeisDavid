<?php

include '../../Control/Control.php';

  $id = $_GET['id'];

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['type'] ?? 'itemcustomer';
    $action = $_GET['action'] ?? 'read'; // default action adalah 'read'
    $keyword = $_GET['keyword'] ?? ''; // Ambil keyword dari form pencarian
  
    // Jika ada keyword, set action menjadi 'search'
  
    // Sesuaikan aksi berdasarkan action
    if ($type === 'itemcustomer') {
        $itemCustomers = readItemCustomerById($id);
    }
  }

  $customers = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $type = $_GET['type'] ?? 'customer';
  $action = $_GET['action'] ?? 'read'; // default action adalah 'read'

  // Sesuaikan aksi berdasarkan action
  if ($type === 'customer') {
    $customers = readCustomers();
  }
}

$items = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $type = $_GET['type'] ?? 'item';
  $action = $_GET['action'] ?? 'read'; // default action adalah 'read'

  // Sesuaikan aksi berdasarkan action
  if ($type === 'item') {
    $items = readItems();
  }
}

$cus= readCustomerById($itemCustomers->getCustomer());
$it= readItemById($itemCustomers->getItem());

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
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
    <main class="app-main"><main class="app-main">
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
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Item-Customers Edit</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">General Form</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="card card-success card-outline mb-4">
                  <!--begin::Header-->
                  <!--end::Header-->
                  <!--begin::Body-->
                  <div class="card-body">
  <form method="post" action="../../Control/Control.php?type=itemcustomer&action=update">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>" />

    <div class="input-group mb-3">
      <span class="input-group-text" id="basic-addon1">ID</span>
      <input
        type="text"
        class="form-control"
        name="id"
        value="<?= htmlspecialchars($id) ?>"
        disabled
      />
    </div>

    <div class="col-md-6">
  <label for="item_id" class="form-label">Item Name</label>
  <select class="form-select" id="item_id" name="item_id" required>
    <option value="">-- Select Item --</option>
    <?php foreach ($items as $item): ?>
      <option value="<?= $item->getId() ?>" <?= $item->getId() == $it->getId() ? 'selected' : '' ?> data-price="<?= $item->getPrice() ?>">
        <?= htmlspecialchars($item->getName()) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div class="valid-feedback">Looks good!</div>
</div>

      <!-- Nama Customer -->
      <div class="col-md-6">
  <label for="customer_id" class="form-label">Customer Name</label>
  <select class="form-select" id="customer_id" name="customer_id" required>
    <option value="">-- Select Customer --</option>
    <?php foreach ($customers as $customer): ?>
      <option value="<?= $customer->getId() ?>" <?= $customer->getId() == $cus->getId() ? 'selected' : '' ?>>
        <?= htmlspecialchars($customer->getName()) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div class="valid-feedback">Looks good!</div>
</div>


      <!-- Harga -->
      <div class="col-md-6">
        <label for="price" class="form-label">Price</label>
        <div class="input-group mb-3">
          <span class="input-group-text">Rp</span>
          <input type="text" class="form-control" id="price" value="<?= $itemCustomers->getHarga()?>" required name="price" />
          <span class="input-group-text">.00</span>
        </div>
        <div class="valid-feedback">Looks good!</div>
    </div>

    <div class="card-footer">
      <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </form>
</div>
                  <!--end::Footer-->
                </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
    <!--end::App Main-->
    <!--begin::Footer-->
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
</body>
<!--end::Body-->

</html>