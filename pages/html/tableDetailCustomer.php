<?php
include '../../Control/urlController.php';


// Inisialisasi data item
$customer_id = $_GET['customer'];
$items = invoiceDeadlineByCustomer($customer_id);
$customer = readCustomerById($customer_id);

// Hapus item dari iteminv
if (
    isset($_GET['type'], $_GET['action'], $_GET['id']) &&
    $_GET['type'] === 'invoice' &&
    $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteItemInv($id);    
    $_SESSION['alert_delete'] = [
        'type' => 'success',
        'message' => 'invoice berhasil dihapus.',
    ];
    // Refresh data setelah penghapusan
    $items = invoiceDeadlineByCustomer($customer_id);
}

// Menangani pencarian item dalam invoice
$type = $_GET['type'] ?? 'invoice';
$action = $_GET['action'] ?? 'read';


// Ambil data masing-masing Item berdasarkan hasil pencarian

$count = 0;
$subTotal = 0;


$countPage = 5;

// Fetch all data

// Get filters from query string
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
                <div class="card-body text-center"><h2>Customer</h2></div>
                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-person"></i>
                    </button> NAME: <?= htmlspecialchars($customer->getName()) ?>
                  </h3>
                </div>
                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-info"></i>
                    </button> REFERAL NUMBER: <?= htmlspecialchars($customer->getRefNo()) ?>
                  </h3>
                </div>
                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-info"></i>
                    </button> ALAMAT: <?= htmlspecialchars($customer->getAlamat()) ?>
                  </h3>
                </div>
                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-info"></i>
                    </button> TELEPON: <?= htmlspecialchars($customer->getTelepon()) ?>
                  </h3>
                </div>
                <div class="card-body text-center">
                  <h3 class="card-title">
                    <button class="btn btn-primary" disabled>
                      <i class="bi bi-info"></i>
                    </button> EMAIL: <?= htmlspecialchars($customer->getEmail()) ?>
                  </h3>
                </div>

                


  <div class="card-header d-flex justify-content-start gap-2 flex-wrap">
  <a href="<?=getUrlInputCustomer('method=get&id='. $customer->getId())?>" class="btn btn-warning">
    <i class="bi bi-pencil-square"></i> Edit Customer
  </a>
 
  
</div>

                <div class="d-flex justify-content-between align-items-center m-3 flex-wrap">
  <div class="mb-3">
  <a href="<?=getUrlInputInvoices('customer='.$customer_id)?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Invoice
                  </a>
  </div>
  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-start align-middle" style="width: 10%;">KODE</th>
    <th class="text-start align-middle" style="width: 20%;">TANGGAL</th>
        <th class="text-start align-middle" style="width: 20%;">DEADLINE</th>
    <th class="text-end align-middle" style="width: 20%;">GRAND TOTAL</th>
        <th class="text-start align-middle" style="width: 20%;">STATUS</th>
    <th class="text-center align-middle" style="width: 10%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($items) > 0): $index=0;?>
                        <?php foreach ($items as $inv):
                        $status = invoiceStatusById($inv['ID']);
                        $grand = invoiceGrandTotalById($inv['ID']);
                         $warna = $status['status']=='Lunas' ? 'bg-success' : 'bg-danger';
                        if(!$grand){
                          $total=0;
                        } else {
                          $total=$grand;
                        }
                          
                          $inv= new Invoice($inv['ID'],$inv['KODE'], $inv['DATE'], $inv['CUSTOMER_ID'], $inv['DEADLINE']);
                          ?>
                          <tr>
                          <td class="text-start align-middle"><?= htmlspecialchars($inv->getKode()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars($inv->getDate()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars($inv->getDeadline()) ?></td>
<td class="text-end align-middle">Rp<?=number_format($total, 0, ',', '.')  ?></td>
<td class="text-start align-middle"><span class="badge <?= $warna ?>">
                            <i class="bi bi-cash-coin me-1"></i> Status: <?= $status['status'] ?>
                              </span></td>
<td class="text-center align-middle">

                              <div class="btn-group" role="group">
                                <a href="tableItemInv.php?invoice=<?= $inv->getId() ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                  <i class="bi bi-eye"></i>
                                </a>
                                <a href="?type=invoice&amp;action=delete&amp;id=<?= $inv->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus invoice ini?');" title="Delete Invoice">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                        <td colspan="7" class="text-center align-middle text-muted">No invoices found.</td>
                        </tr>
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
   <script 
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script>
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
