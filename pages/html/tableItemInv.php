<?php
include '../../Control/Control.php';


// Inisialisasi data item
$invoice = $_GET['invoice'];
$inv = readInvoiceById($invoice);
$items = readItemInvByInvoice($invoice);

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
$keyword = $_GET['keyword'] ?? '';


// Ambil data masing-masing Item berdasarkan hasil pencarian
$it = [];
foreach ($items as $i => $item) {
    $it[$i] = readItemById($item->getItemId());
}

$count = 0;
$subTotal = 0;


$countPage = 5;

// Fetch all data
$allitemCustomers = readItemInvByInvoice($invoice);

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';

// Determine which set to display
if (
    $keyword !== ''
) {
    
  $items = searchItemInvsInInvoice($invoice, $keyword);
        $isSearch = true;
} else {
    $items = $allitemCustomers;
    $isSearch = false;
}

$count= count($items);

$page=count($items)/$countPage;
$selectPage= $_GET['page'] ?? 0;
$offset = $selectPage*$countPage;
$contain= [];

for ($i = 0; $i < $countPage; $i++) {
  if ($offset >= count($items)) {
      break;
  } else {
      $contain[] = $items[$offset];
      $offset++;
  }
}


$items = $contain;
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
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
      
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Item Inv Table</h3>
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
            <div class="col-md-8 mx-auto">
              <div class="card mb-4">
                <div class="card-body text-center"><h2>Detail Invoice</h2></div>

                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-info-circle"></i>
                    </button> Kode Invoice: <?= htmlspecialchars($inv->getKode()) ?>
                  </h3>
                </div>

                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-calendar-event"></i>
                    </button> Tanggal: <?= htmlspecialchars($inv->getDate()) ?>
                  </h3>
                </div>

                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-person-circle"></i>
                    </button> Customer: <?= htmlspecialchars(readCustomerById($inv->getCustomerId())->getName()) ?>
                  </h3>
                </div>

  <div class="card-header d-flex justify-content-start gap-2 flex-wrap">
  <a href="editInvoices.php?method=get&id=<?= $inv->getId() ?>&kode=<?= $inv->getKode()?>&customer=<?= $inv->getCustomerId()?>&kondisi=<?=true?>" class="btn btn-warning">
    <i class="bi bi-pencil-square"></i> Edit Invoice
  </a>
  <a href="printInvoice.php?invoice=<?= $invoice ?>" class="btn btn-success" target="_blank">
    <i class="bi bi-printer"></i> Print Invoice
  </a>
</div>

                <div class="d-flex justify-content-between align-items-center m-3 flex-wrap">
  <div class="d-flex gap-5 align-items-center fs-5">
    <p class="mb-0"><strong>Jumlah Barang : </strong><?= $count ?></p>
    <p class="mb-0"><strong>Harga Total : Rp</strong> 
      <?php 
        $subTotal = 0;
        foreach ($items as $i => $item) {
          $subTotal += $item->getQty() * $item->getPrice();
        }
        echo number_format($subTotal, 0, ',', '.' );
      ?> 
    </p>
  </div>

  <form method="GET" action="tableiteminv.php?action=read&invoice=<?= $invoice?>" class="d-flex mt-2 mt-md-0">
  <input type="hidden" name="type" value="iteminv">
  <input type="hidden" name="action" value="<?= $action === 'search' ? 'search' : 'read' ?>">
  <input type="hidden" name="invoice" value="<?= $invoice ?>">
  <input type="text" name="keyword" class="form-control w-auto me-2" placeholder="Search Item..." value="<?= htmlspecialchars($keyword) ?>">
  <button type="submit" class="btn btn-secondary">Search</button>
</form>

</div>

                <table class="table table-bordered mx-auto">
                  <thead>
                    <tr>
                      <th>REF NO</th>
                      <th>Barang</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Total</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($items) > 0): ?>
                      <?php foreach ($items as $i => $item):?>
                        <tr>
                          <td><?= $it[$i]->getRefNo() ?></td>
                          <td><?= htmlspecialchars(readItemById($item->getItemId())->getName()) ?></td>
                          <td><?= htmlspecialchars($item->getQty()) ?></td>
                          <td>Rp<?= number_format($item->getPrice(), 0, ',', '.') ?></td>
                          <td>Rp<?= number_format($item->getQty() * $item->getPrice(), 0, ',', '.') ?></td>
                          <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                              <a href="editItemInv.php?method=get&id=<?= $item->getId() ?>&invoice=<?= $invoice ?>" class="btn btn-sm btn-warning" title="Edit ItemInv">
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <a href="?type=iteminv&action=delete&id=<?= $item->getId() ?>&invoice=<?= $invoice ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus item ini?');" title="Delete Item">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="6" class="text-center">Tidak ada item.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>

                <div class="text-start mt-3 clearfix">
                  <a href="inputItemInv.php?invoice=<?= $invoice ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                  <ul class="pagination pagination-sm m-0 float-end">
    <?php if($page > 1): ?>
        <?php if($selectPage - 1 >= 0): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage - 1 ?>&keyword=<?= $keyword ?>&invoice=<?= $invoice ?>">«</a>
            </li>
        <?php endif; ?>

        <?php for($i = 0; $i < $page; $i++): ?>
            <li class="page-item <?= ($i == $selectPage) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>&invoice=<?= $invoice ?>">
                    <?= htmlspecialchars($i + 1) ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if($selectPage + 1 < $page): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage + 1 ?>&keyword=<?= $keyword ?>&invoice=<?= $invoice ?>">»</a>
            </li>
        <?php endif; ?>
    <?php endif; ?>
</ul>

                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>  
    </main>

    <?php include __DIR__ . '/../widget/footer.php'; ?>
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
