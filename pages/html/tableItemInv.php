<?php
include '../../Control/urlController.php';

// handle url
$url = $_SERVER['REQUEST_URI'];
sessionSetRedirectUrl($url);
sessionSetPass('invoice','FROM');
sessionSetPass(null, 'INDEX');
// Inisialisasi data item
$index = $_GET['invoice'] ?? null;
if(isset($index)){
  $index= (int)$index;
  sessionSetPass($index, 'INDEX_INVOICE');
  $invoice = sessionGetObjectInvoices()[$index-1];
  sessionSetPass($invoice, 'INVOICE');
          if(!is_object($invoice)){
    $invoice = new Invoice(
            $invoice['ID'],
            $invoice['KODE'],
            $invoice['DATE'],
            $invoice['CUSTOMER_ID'],
            $invoice['DEADLINE'],
            $invoice['NOTES']
        );
      }
  $items =readItemInvByInvoice($invoice->getId());
  sessionSetObjectItemInv(null);  
  sessionSetObjectItemInv($items);
  header('Location: ?');
  exit();
}
  
$index = sessionGetPass('INDEX_INVOICE');
$invoice = $index;

$inv = sessionGetObjectInvoices()[$index-1];
$items = sessionGetObjectItemInv();
$company = sessionGetObjectCompany();
        if(!is_object($inv)){
    $inv = new Invoice(
            $inv['ID'],
            $inv['KODE'],
            $inv['DATE'],
            $inv['CUSTOMER_ID'],
            $inv['DEADLINE'],
            $inv['NOTES']
        );
      }
$customer = readCustomerById($inv->getCustomerId());

if(!$inv->getNotes() || $inv->getNotes() === ''){
  $inv->setNotes('tidak ada note');
}

// Hapus item dari iteminv
// Menangani pencarian item dalam invoice
// $type = $_GET['type'] ?? 'iteminv';
$action = $_GET['action'] ?? 'read';

// Ambil data masing-masing Item berdasarkan hasil pencarian
$it = [];
foreach ($items as $i => $row) {
  if(is_object($row)){
                          $item = $row;
                        } else {
                          $item = new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
                        }
    $it[$i] = readItemById($item->getItemId());
}

$count = 0;
$subTotal = 0;
$countainer= invoiceTersisa($inv->getId()); 
$totalTertagih = $countainer['grand_total']-$countainer['total_payment'];

$countPage = 5;

// Fetch all data
;

$kondisi=false;
 $countainer= invoiceTersisa($inv->getId()); 
        if($countainer['total_payment']<$countainer['grand_total']){
            $kondisi=false;
        } else {
            $kondisi=true;
        }
// Get filters from query string
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice View</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/adminlte.css" />
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <style>
  .action-btn {
    width: 100%;
    margin-bottom: 5px;
  }
  .table-fixed {
    table-layout: fixed;
    width: 100%;
  }
</style>

</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary sidebar-open app-loaded">
  <div class="app-wrapper">
        <?php include __DIR__ . '/../widget/alert.php'; ?>
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>

    <main class="app-main">
<div class="wrapper">
  <div class="content-wrapper">
    <div class="container-fluid mt-4">

     <!-- Informasi Layout 2x2 dalam 1 Card -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card shadow">
      <div class="card-header bg-info text-center">
        <h6 class="mb-0">INFORMASI</h6>
      </div>
      <div class="card-body p-4">
        <table class="table table-borderless mb-0" style="table-layout: fixed;">
          <tr>
            <!-- Logo Perusahaan -->
            <td style="width: 50%; vertical-align: top; background-color: #f8f9fa; border-right: 1px solid #dee2e6;">
  <div style="text-align: left;">
    <img src="<?= '../' . $company->getUrlLogo() ?>" alt="Logo Perusahaan" style="max-height: 100px; max-width: 100%;">
  </div>
</td>


            <!-- Informasi Invoice -->
            <td style="width: 50%; vertical-align: top; background-color: #f8f9fa;">
              <div class="d-flex justify-content-between align-items-start">
                <h6 class="border-bottom pb-1 mb-2">
                  <i class="fas fa-file-invoice-dollar me-1"></i>Informasi Invoice
                </h6>
                <a href="<?=getUrlInputInvoices('from=invoice&id='. $inv->getId())?>" class="btn btn-sm btn-outline-primary ms-2">Edit</a>
              </div>
              <p class="mb-1"><strong>Kode Invoice:</strong> <?=$inv->getKode(
                )?></p>
              <p class="mb-1"><strong>Tanggal:</strong> <?=$inv->getDate()?></p>
              <p class="mb-1"><strong>Deadline:</strong> <?=$inv->getDeadline()?></p>
              <p class="mb-1">
                <strong>Status:</strong>
<span style="color: <?= $kondisi ? 'green' : 'red'; ?>;">
  <?= $kondisi ? 'Lunas' : 'Belum Lunas'; ?>
</span>
              </p>
              <p class="mb-0"><strong>Catatan:</strong> <?=$inv->getNotes()?></p>
            </td>
          </tr>

          <tr>
            <!-- Informasi Perusahaan -->
            <td style="vertical-align: top; padding-top: 2rem;">
              <h6 class="border-bottom pb-1">
                <i class="fas fa-building me-1"></i>Informasi Perusahaan
              </h6>
              <p class="mb-1"><strong>Nama:</strong> <?=$company->getNama()?></p>
              <p class="mb-1"><strong>Alamat:</strong> <?=$company->getAlamat()?></p>
              <p class="mb-1"><strong>Kota:</strong> <?=$company->getKota()?></p>
              <p class="mb-1"><strong>Provinsi:</strong> <?=$company->getProvinsi()?></p>
              <p class="mb-1"><strong>Kode Pos:</strong> <?=$company->getKodePos()?></p>
              <p class="mb-1"><strong>Negara:</strong> <?=$company->getNegara()?></p>
              <p class="mb-1"><strong>Telepon:</strong> <?=$company->getTelepon()?></p>
              <p class="mb-0"><strong>Email:</strong> <?=$company->getEmail()?></p>
            </td>

            <!-- Informasi Customer -->
            <td style="vertical-align: top; padding-top: 2rem;">
              <div class="d-flex justify-content-between align-items-start">
                <h6 class="border-bottom pb-1 mb-2">
                  <i class="fas fa-user me-1"></i>Informasi Customer
                </h6>
                <a href="<?=getUrlInputCustomer('from=invoice&id='. $customer->getId())?>" class="btn btn-sm btn-outline-primary ms-2">Edit</a>
              </div>
              <p class="mb-1"><strong>REF_NO:</strong> <?=$customer->getRefNo()?></p>
              <p class="mb-1"><strong>Nama:</strong> <?=$customer->getName()?></p>
              <p class="mb-1"><strong>Alamat:</strong> <?=$customer->getAlamat()?></p>
              <p class="mb-1"><strong>Telepon:</strong> <?=$customer->getTelepon()?></p>
              <p class="mb-0"><strong>Email:</strong> <?=$customer->getEmail()?></p>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>



      <!-- Baris 2: Kolom include dan tombol -->
      <div class="row">
        
          <div class="card h-100">
            <div class="card-body">
              <table class="table table-bordered table-fixed">
                <tr>
                  <td style="height: 100px; vertical-align: middle;">
                    <!-- Include kontenmu di sini -->
                    <em><?php include 'tableItemInvoice.php'?></em>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        

        
      </div>

      <div class="row">

          <div class="card h-100">
            <div class="card-body">
              <table class="table table-bordered table-fixed">
                <tr>
                  <td style="height: 100px; vertical-align: middle;">
                    <!-- Include kontenmu di sini -->
                    <em><?php include 'subTotal.php'?></em>
                  </td>
                </tr>
              </table>
            </div>
          </div>
      </div>

    </div>
  </div>
</div>
    </main>

    <?php include __DIR__ . '/../widget/footer.php'; ?>
  </div>


<!-- AdminLTE & dependency JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="../js/adminlte.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true,
          },
        });
      }
    });
  </script>
</body>
</html>
