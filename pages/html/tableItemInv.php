<?php
include '../../Control/urlController.php';


// Inisialisasi data item
$invoice = $_GET['invoice'];
$inv = readInvoiceById($invoice);
$items = readItemInvByInvoice($invoice);

if(!$inv->getNotes() || $inv->getNotes() === ''){
  $inv->setNotes('tidak ada note');
}

// Hapus item dari iteminv
if (
    isset($_GET['type'], $_GET['action'], $_GET['id']) &&
    $_GET['type'] === 'iteminv' &&
    $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteItemInv($id);    
    $_SESSION['alert_delete'] = [
        'type' => 'success',
        'message' => 'Item dalam invoice berhasil dihapus.',
    ];
    // Refresh data setelah penghapusan
    $items = readItemInvByInvoice($invoice);
}

// Menangani pencarian item dalam invoice
$type = $_GET['type'] ?? 'iteminv';
$action = $_GET['action'] ?? 'read';


// Ambil data masing-masing Item berdasarkan hasil pencarian
$it = [];
foreach ($items as $i => $item) {
    $it[$i] = readItemById($item['ITEM_ID']);
}

$count = 0;
$subTotal = 0;


$countPage = 5;

// Fetch all data
$allitemCustomers = readItemInvByInvoice($invoice);

// Get filters from query string
$items = $allitemCustomers;
$_SESSION['INVOICE'] = ['ID' => $invoice];
?>

<!-- Mulai dari sini lanjutkan bagian HTML sama seperti sebelumnya -->
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
        <?php include __DIR__ . '/../widget/alert.php'; ?>
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
      
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Detail Invoice</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Item Inv</li>
              </ol>
            </div>  
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body text-center"><h2>Invoice <?= htmlspecialchars($inv->getKode()) ?></h2></div>
                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-calendar-event"></i>
                    </button> Tanggal: <?= htmlspecialchars($inv->getDate()) ?>
                  </h3>
                </div>

                <div class="card-body">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-person-circle"></i>
                    </button> Customer: <?= htmlspecialchars(readCustomerById($inv->getCustomerId())->getName()) ?>
                  </h3>
                </div>

                <div class="card-body">
                  <h3 class="card-title ">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-info-circle"></i>
                    </button>
                   <label>Catatan: <?= htmlspecialchars($inv->getNotes())?></label>
                </h3>
                </div>


  <div class="card-header d-flex justify-content-start gap-2 flex-wrap">
  <a href="<?= getUrlInputInvoices('method=get&id='. $inv->getId(). '&kode='. $inv->getKode(). '&customer='. $inv->getCustomerId(). '&kondisi=')?>" class="btn btn-warning">
    <i class="bi bi-pencil-square"></i> Edit Invoice
  </a>
  <a href="printInvoice.php" class="btn btn-success" target="_blank">
    <i class="bi bi-printer"></i> Print Invoice
  </a>
  <a href="<?=getUrlInputPayment('invoice='.  $invoice)?>" class="btn btn-success">
    <i class="bi bi-calendar3"></i> Payment
  </a>
  
</div>

                <div class="d-flex justify-content-between align-items-center m-3 flex-wrap">
  <div class="mb-3">
  <a href="<?=getUrlInputItemInv('invoice='. $invoice)?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Item
                  </a>
  </div>
  <table class="table table-bordered mx-auto">
                  <thead>
                    <tr>
                      <th>NO</th>
                      <th>REF NO</th>
                      <th>Barang</th>
                      <th>Qty</th>
                      <th class="text-end">Price</th>
                      <th class="text-end">Total</th>
                      <th class="text-center" style="width: 10%;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($items) > 0): ?>
                      <?php 
                        $number=0;
                        foreach ($items as $i => $item): $number++;
                        
                        $item = new ItemInv($item['ID'],$item['INVOICE_ID'],$item['ITEM_ID'],$item['QTY'],$item['PRICE'],$item['TOTAL']);

                        ?>
                        <tr>
                          <td><?= htmlspecialchars($number)?></td>
                          <td><?= htmlspecialchars(readItemById($item->getItemId())->getRefNo()) ?></td>
                          <td><?= htmlspecialchars(readItemById($item->getItemId())->getName()) ?></td>
                          <td><?= htmlspecialchars($item->getQty()) ?></td>
                          <td class="text-end">Rp<?= number_format($item->getPrice(), 0, ',', '.') ?></td>
                          <td class="text-end">Rp<?= number_format($item->getQty() * $item->getPrice(), 0, ',', '.') ?></td>
                          <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                              <a href="InputItemInv.php?method=get&id=<?= $item->getId() ?>&invoice=<?= $invoice ?>" class="btn btn-sm btn-warning" title="Edit ItemInv">
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <a href="<?=getUrlControl('type=iteminv&action=delete&id='. $item->getId(). '&invoice='. $invoice)?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus item ini?');" title="Delete Item">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <tr>
                        <td colspan="5" class="text-end">Grand Total</td>
                        <td colspan="1" class="text-end">Rp<?php 
        $subTotal = 0;
        foreach ($items as $i => $item) {
          $item = readItemInvById($item['ID']);
          $subTotal += $item->getQty() * $item->getPrice();
        }
        echo number_format($subTotal, 0, ',', '.' );
      ?> </td>
      <td></td>
                      </tr>
                    <?php else: ?>
                      <tr><td colspan="6" class="text-center">Tidak ada item.</td></tr>
                    <?php endif; ?>
                  </tbody>
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

  <!-- Scripts -->
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
