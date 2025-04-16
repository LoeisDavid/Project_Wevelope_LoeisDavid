<?php

include '../../Control/Control.php';

$customers = readCustomers(); // ambil semua customer
$id = $_GET['id'];
$itemInvs = readItemInvById($id); // ambil item berdasarkan ID invoice
$items = readItems(); // ambil daftar barang

?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE 4 | Edit Item Invoice</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE 4 | Edit Item Invoice" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
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

    <main class="app-main">
      <div class="container mt-4">
        <div class="card mx-auto" style="max-width: 600px;">
          <div class="card-header text-center">
            <h4>Edit Item Invoice</h4>
          </div>
          <div class="card-body">
            <form method="post" action="../../Control/Control.php?type=iteminv&action=update&id=<?=$itemInvs->getId();?>">
                <div class="border rounded p-3 mb-3">
                  <div class="mb-3">
                    <label class="form-label">Barang</label>
                    <select name="item_id" class="form-select" required>
                      <option value="">-- Pilih Barang --</option>
                      <?php foreach ($items as $it): ?>
                        <option value="<?= $it->getId() ?>" <?= $it->getId() == $itemInvs->getItemId() ? 'selected' : '' ?>>
                          <?= htmlspecialchars($it->getName()) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Qty</label>
                    <input type="number" name="qty" class="form-control" required value="<?= $itemInvs->getQty() ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" value="<?= $itemInvs->getPrice() ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="total" class="form-control" value="<?= $itemInvs->getPrice()*$itemInvs->getQty() ?>" disabled>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 mt-3" style="display:block;">Simpan Perubahan</button>
                  <input type="hidden" name="invoice_id" value="<?= $itemInvs->getInvoiceId() ?>">
                </div>
                
            </form>
          </div>
        </div>
      </div>
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>
        Copyright &copy; 2014-2024&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
      </strong>
      All rights reserved.
    </footer>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
  integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="../js/adminlte.js"></script>
</body>
<!--end::Body-->

</html>
