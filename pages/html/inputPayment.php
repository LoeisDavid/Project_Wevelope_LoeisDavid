<?php

include '../../Control/urlController.php';

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

$id = $_GET['id'] ?? null;
$date = $_GET['date'] ?? null;
$nominal = $_GET['nominal'] ?? null;
$invoice_id = $_GET['invoice'] ?? null;
$notes = $_GET['notes'] ?? null;
$index = $_GET['index'] ?? null;
$null = $_GET['null'] ?? null;

if($null){
  sessionSetPass(null, 'INDEX');
} else
if($index){
  sessionSetPass($id, 'ID');
  sessionSetPass($index, 'INDEX');
  header('Location: ?');
  exit();
}
if (sessionGetPass('INDEX')) {
  $index= sessionGetPass('INDEX')-1;
	$payment = sessionGetObjectPayments()[$index];
  if(!is_object($payment)){
    $payment = new Payment(
            $payment['ID'],
            $payment['DATE'],
            $payment['NOMINAL'],
            $payment['ID_INVOICE'],
            $payment['NOTES'],
            $payment['KODE']
        );
      }
  $id = $payment->getId();
    $date = $payment->getDate();
    $nominal = $payment->getNomial();
    $invoice_id = $payment->getInvoice();
    $notes = $payment->getNotes();
}


$invoice = readInvoiceById($invoice_id);


$allinvoice= null;

if (!$invoice) {
	$items=readInvoices();
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
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
          <main class="app-main">
            <div class="app-content-header">
        <div class="card card-success card-outline mb-12">
          <div class="card-header text-center"><h4>Payment</h4></div>
          <div class="card-body">
          <?php if($invoice) : ;
            $hasil= invoiceTersisa($invoice->getId());
            ?>
          <form method="post" action="<?=getUrlControl('type=payment')?>">
                <div class="border rounded p-3 mb-3">
                <div class="mb-3">
                <input type="text" value="<?= $id?>" name="id" hidden>
                <input type="text" value="<?= $invoice->getId()?>" name="invoice_id" hidden>
                    <label class="form-label">KODE INVOICE</label>
                    <input type="text" name="kode" class="form-control" value="<?= $invoice->getKode() ?>" disabled>
                  </div>
                  <div>
                    <label class="form-label">
                      Total yang perlu dibayarkan : Rp<?=number_format($hasil['grand_total'], 0, ',', '.')  ?>
                    </label>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">
                      Sisa yang perlu dibayarkan : Rp<?= number_format($hasil['grand_total']-$hasil['total_payment'], 0, ',', '.')?>
                    </label>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nominal Pembayaran</label>
                    <div class="input-group mb-3">
      <span class="input-group-text">Rp</span>
      <input
        type="number"
        class="form-control"
        name="nominal"
        value="<?=$nominal ?>"
        required
      />
    </div>
    <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="date" id="tanggal" class="form-control" value="<?= $date ?>">
              <div class="form-text">dapat dikosongi - apabila kosong maka akan mengikuti tanggal saat ini</div>
              </div>
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
                  <button type="submit" class="btn btn-primary float-end">Bayar</button>
<a href="?" class="btn btn-secondary">Back

</a>

                </div>
                    
            </form>
            <?php else: ?>
            <form method="get" action="?">
              <div class="border rounded p-3 mb-3">

                <div class="mb-3">
                    <label class="form-label">KODE INVOICE</label>
                   <select name="invoice" id="invoice" class="form-select" required>
      <option value="">-- Pilih Item --</option>
      <?php foreach ($items as $item): 
        
        ?>
       <option value="<?= $item->getId() ?>">
                          <?= htmlspecialchars($item->getKode()) ?>
        </option>
      <?php endforeach; ?>
    </select>
                  </div>
                  <button type="submit" class="btn btn-primary float-end">Next</button>
            <a href="tablePayments.php" class="btn btn-secondary">Cancel</a>
          </div>        
          </form>
            <?php endif; ?>
        </div>
        </div>
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <?php include __DIR__ . '/../widget/footer.php'; ?>
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