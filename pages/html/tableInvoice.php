<?php
include_once '../../Control/urlController.php';

//handle call
$call = sessionGetCall('invoice');
$callCustomer = sessionGetCall('customer');

// handle url
$url = $_SERVER['REQUEST_URI'];
sessionSetRedirectUrl($url);

// Handle delete action
if (
    isset($_GET['type'], $_GET['action'], $_GET['id'])
    && $_GET['type'] === 'invoice'
    && $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteInvoice($id);
    $_SESSION['alert'] = [
        'type'    => 'success',
        'message' => 'Invoice berhasil dihapus.',
    ];
    // Redirect to avoid resubmission
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Fetch all data

if($callCustomer){
  sessionSetObjectCustomers(readCustomers());
}
$customers   = sessionGetObjectCustomers();

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';
$customerId  = $_GET['customer']   ?? '';
$startDate   = $_GET['start_date'] ?? '';
$endDate     = $_GET['end_date']   ?? '';

// Determine which set to display
if (
    $keyword !== ''
    || $customerId !== ''
    || ($startDate !== '' || $endDate !== '')
) {
    if ($keyword !== ''
    || $customerId !== ''
    || ($startDate !== '' || $endDate !== '')) {

      if($startDate==''){
        $startDate = $endDate;
      } elseif ($endDate== ''){
        $endDate = $startDate;
      }

      sessionSetObjectInvoices(searchInvoices($keyword,$startDate,$endDate, $customerId, $keyword));
    } elseif ($customerId !== '') {
        sessionSetObjectInvoices(readInvoiceByCustomer($customerId));
    } else {
        sessionSetObjectInvoices(readInvoiceByRangeDate($startDate, $endDate));
    }
    sessionSetCall('invoice',true);
    $isSearch = true;
} else {

  if($call){
    sessionSetObjectInvoices(readInvoices());
    sessionSetCall('invoice', false);
  }

    $isSearch = false;
}

$display = sessionGetObjectInvoices();

$countPage = 5;

$page = ceil(count($display) / $countPage);
$selectPage = $_GET['page'] ?? 0;
$offset = $selectPage * $countPage;

$contain = [];
for ($i = 0; $i < $countPage; $i++) {
    if ($offset >= count($display)) {
        break;
    } else {
        $contain[] = $display[$offset];
        $offset++;
    }
}


$displayInvoices = $contain;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Invoices Table</title>
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
          <!-- Page Header -->
          <div class="row mb-3">
            <div class="col-sm-6">
              <h3 class="mb-0">Invoices Table</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Invoices</li>
              </ol>
            </div>
          </div>
          <!-- Centered Content -->
          <div class="row justify-content-center">
            <div class="col-lg-12">
              <!-- Alert Delete -->
              <!-- Search Form -->
              <div class="card mb-4">
  <div class="card-body">
    <form method="GET" class="row g-3 align-items-end">
      <input type="hidden" name="type" value="invoice">

      <!-- Keyword -->
      <div class="col-md-3">
        <label for="keyword" class="form-label">Keyword</label>
        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Search invoice..." value="<?= htmlspecialchars($keyword) ?>">
      </div>

      <!-- Customer -->
      <div class="col-md-3">
        <label for="customer" class="form-label">Customer</label>
        <select name="customer" id="customer" class="form-select">
          <option value="">-- Pilih Customer --</option>
          <?php foreach ($customers as $row): 
            
            if(is_object($row)){
                          $cust = $row;
                        } else {
                          $cust = new Customer(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO'],
            $row['EMAIL'],
            $row['ALAMAT'],
            $row['TELEPON']
        );
                        }
            ?>
            <option value="<?= $cust->getId() ?>" <?= $cust->getId() == $customerId ? 'selected' : '' ?>>
              <?= htmlspecialchars($cust->getName()) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Start Date -->
      <div class="col-md-2">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
      </div>

      <!-- End Date -->
      <div class="col-md-2">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
      </div>

      <!-- Buttons -->
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100" title="Cari Data">
          <i class="bi bi-search me-1"></i> Cari
        </button>

        <?php if (!empty($keyword) || !empty($customerId) || !empty($startDate) || !empty($endDate)): ?>
          <a href="?type=invoice" class="btn btn-outline-secondary w-100" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

              

              <!-- Unified Table -->
              <div class="card">
                <div class="card-header text-start clearfix">
                  <h3 class="card-title mt-2 mx-3"><?= $isSearch ? 'Search Results' : 'All Invoices' ?></h3>
                  <a href="<?= getUrlInputInvoices('null=null')?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-start align-middle" style="width: 10%;">KODE</th>
    <th class="text-start align-middle" style="width: 20%;">TANGGAL</th>
    <th class="text-start align-middle" style="width: 40%;">CUSTOMER</th>
    <th class="text-start align-middle" style="width: 20%;">KODE CUSTOMR</th>
    <th class="text-end align-middle" style="width: 20%;">GRAND TOTAL</th>
        <th class="text-start align-middle" style="width: 20%;">STATUS</th>
    <th class="text-center align-middle" style="width: 10%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayInvoices) > 0): $index=$countPage*$selectPage;?>
                        <?php foreach ($displayInvoices as $row):
                        $index++;

                        if(is_object($row)){
                          $inv = $row;
                        } else {
                          $inv = new Invoice(
            $row['ID'],
            $row['KODE'],
            $row['DATE'],
            $row['CUSTOMER_ID'],
            $row['DEADLINE'],
            $row['NOTES']
        );
                        }

                        
                        $status = invoiceStatusById($inv->getId());
                        $grand = invoiceGrandTotalById($inv->getId());
                         $warna = $status['status']=='Lunas' ? 'bg-success' : 'bg-danger';
                        if(!$grand){
                          $total=0;
                        } else {
                          $total=$grand;
                        }
                          
                          ?>
                          <tr>
                          <td class="text-start align-middle"><?= htmlspecialchars($inv->getKode()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars($inv->getDate()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars(readCustomerById($inv->getCustomerId())->getName()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars(readCustomerById($inv->getCustomerId())->getRefNo()) ?></td>
<td class="text-end align-middle">Rp<?=number_format($total, 0, ',', '.')  ?></td>
<td class="text-start align-middle"><span class="badge <?= $warna ?>">
                            <i class="bi bi-cash-coin me-1"></i> Status: <?= $status['status'] ?>
                              </span></td>
<td class="text-center align-middle">

                              <div class="btn-group" role="group">
                                <a href="tableItemInv.php?invoice=<?=$index?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                  <i class="bi bi-eye"></i>
                                </a>
                                <a href="?type=invoice&amp;action=delete&amp;id=<?= $inv->getId() ?>&index=<?= $index?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus invoice ini?');" title="Delete Invoice">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                        <td colspan="7" class="text-center align-middle text-muted"><?= $isSearch ? 'No matching records.' : 'No invoices found.' ?></td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer text-start clearfix">
                  <ul class="pagination pagination-sm m-0 float-end">
    <?php
    // Hitung jumlah halaman maksimum yang ingin ditampilkan (misal: 7)
    $totalPagesToShow = 7;
    $start = max(0, $selectPage - 2);
    $end = min($page - 1, $selectPage + 2);

    if ($page > $totalPagesToShow) {
        if ($selectPage <= 2) {
            $start = 0;
            $end = 4;
        } elseif ($selectPage >= $page - 3) {
            $start = $page - 5;
            $end = $page - 1;
        }
    } else {
        $start = 0;
        $end = $page - 1;
    }
    ?>

    <!-- Tombol ke halaman pertama -->
    <?php if ($selectPage > 0): ?>
        <li class="page-item">
            <a class="page-link" href="?page=<?= $selectPage - 1 ?>&keyword=<?= $keyword ?>">«</a>
        </li>
    <?php endif; ?>

    <!-- Halaman 1 -->
    <?php if ($start > 0): ?>
        <li class="page-item">
            <a class="page-link" href="?page=0&keyword=<?= $keyword ?>">1</a>
        </li>
        <?php if ($start > 1): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Halaman Tengah -->
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <li class="page-item <?= ($i == $selectPage) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>">
                <?= $i + 1 ?>
            </a>
        </li>
    <?php endfor; ?>

    <!-- Halaman Terakhir -->
    <?php if ($end < $page - 1): ?>
        <?php if ($end < $page - 2): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>
        <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>&keyword=<?= $keyword ?>"><?= $page ?></a>
        </li>
    <?php endif; ?>

    <!-- Tombol ke halaman selanjutnya -->
    <?php if ($selectPage < $page - 1): ?>
        <li class="page-item">
            <a class="page-link" href="?page=<?= $selectPage + 1 ?>&keyword=<?= $keyword ?>">»</a>
        </li>
    <?php endif; ?>
</ul>

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
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="../../js/adminlte.js"></script>
</body>
</html>
