<?php

include '../../Control/Control.php';

$customers = readCustomers(); // ambil semua customer

$customerId = $_GET['customer'] ?? '';
    $tanggal = $_GET['tanggal'] ?? '';
    $kode = $_GET['kode'] ?? '';
     $id = $_GET['id'] ?? '';
    $deadline = $_GET['deadline'] ?? null;

$invoice = readInvoiceById($id);

     if($invoice){
      $tanggal = $invoice->getDate();
      $deadline = $invoice->getDeadline();
     }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $action = 'create';
//   $type = 'invoice';
//     $customerId = $_POST['customer'] ?? '';
//     $tanggal = $_POST['tanggal'] ?? '';
//     $kode = $_POST['kode'] ?? ''; // Ambil input kode

//     if ($customerId && $tanggal && $kode) { // Pastikan kode juga terisi
//               // Buat invoice dengan kode, tanggal, dan customerId

//               // var_dump($customerId, $tanggal, $kode);die();
//               $contain= readInvoiceByKode($kode);
//               if (!empty($contain)) {
//                 setAlert('danger', 'Gagal menambahkan invoice.');
//                 header("Location: ../html/inputInvoices.php?tanggal=$tanggal&customer=$customerId&kode=$kode");
//                 exit();
//             } else {
//               createInvoice($customerId, $tanggal, $kode);
//                 setAlert('success', 'invoice berhasil ditambahkan!');
//                 header("Location: ../html/tableInvoice.php");
//             exit();
//             }
    
//     } else {
//         $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Mohon isi semua field'];
//     }
// }
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
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
          <main class="app-main">
            <div class="app-content-header">
        <div class="card card-success card-outline mb-12">
          <div class="card-header text-center"><h4>Invoice</h4></div>
          <div class="card-body">
            <form method="POST" action="../../Control/Control.php?type=invoice">
            <input type="text" value="<?= $id?>" name="id" hidden>
              <div class="mb-3">
                <label for="kode" class="form-label">Kode Invoice</label>
                <input type="text" name="kode" id="kode" class="form-control" required placeholder="Masukkan Kode Invoice" value="<?= $kode?>">
              </div>
              <div class="mb-3">
                <label for="customer" class="form-label">Customer</label>
                <select name="customer_id" id="customer" class="form-select" required>
                  <option value="">-- Pilih Customer --</option>
                  <?php foreach ($customers as $cust): 
                    
                    $cust = new Customer($cust['ID'],$cust['NAME'], $cust['REF_NO']);
                    ?>
                    <option value="<?= $cust->getId() ?>" <?= $cust->getId() == $customerId ? 'selected' : ''?>><?= htmlspecialchars($cust->getName()) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?= $tanggal ?>">
              </div>
              <div class="mb-3">
                <label for="tanggal" class="form-label">Deadline</label>
                <input type="date" name="deadline" id="tanggal" class="form-control" required value="<?= $deadline ?>">
              </div>
                  <div class="mb-3">
      <label for="notes" class="form-label">Notes</label>
      <textarea rows="5"
        type="text"
        class="form-control"
        id="notes"
        name="notes"
        value="<?=$notes?>"
      ></textarea>
    </div>
              <button type="submit" class="btn btn-primary float-end">Simpan Invoice</button>
              <a href="tableInvoice.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
      </div>
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
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
</body>
<!--end::Body-->

</html>