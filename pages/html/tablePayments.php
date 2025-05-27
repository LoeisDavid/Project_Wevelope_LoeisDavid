<?php
include_once '../../Control/Control.php';

// Handle delete action

// Fetch all data

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';
$startDate   = $_GET['start_date'] ?? '';
$endDate     = $_GET['end_date']   ?? '';

// Determine which set to display
if (
    $keyword !== ''
    || ($startDate !== '' || $endDate !== '')
) {
    if ($keyword !== ''
    || ($startDate !== '' || $endDate !== '')) {

      if($startDate==''){
        $startDate = $endDate;
      } elseif ($endDate== ''){
        $endDate = $startDate;
      }

        $displayPayments = searchPayments($startDate,$endDate, $keyword);
    } else {
        $displayPayments = readPaymentByRangeDate($startDate, $endDate);
    }
    $isSearch = true;
} else {
    $displayPayments = readPayments();
    $isSearch = false;
}

$countPage = 5;

$page=count($displayPayments)/$countPage;
$selectPage= $_GET['page'] ?? 0;
$offset = $selectPage*$countPage;
$contain= [];

for ($i = 0; $i < $countPage; $i++) {
  if ($offset >= count($displayPayments)) {
      break;
  } else {
      $contain[] = $displayPayments[$offset];
      $offset++;
  }
}


$displayPayments = $contain;
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
                <li class="breadcrumb-item active">Payments</li>
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
                  <h3 class="card-title mt-2 mx-3"><?= $isSearch ? 'Search Results' : 'All Payments' ?></h3>
                  <a href="inputPayment.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-start align-middle" style="width: 10%;">NO</th>
    <th class="text-start align-middle" style="width: 20%;">TANGGAL</th>
    <th class="text-start align-middle" style="width: 40%;">KODE INVOICE</th>
    <th class="text-end align-middle" style="width: 40%;">NOMINAL</th>
    <th class="text-end align-middle" style="width: 60%;">NOTES</th>
    <th class="text-center align-middle" style="width: 10%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayPayments) > 0): $i=0; ?>
                        <?php foreach ($displayPayments as $inv): 
                          $i++;
                          $inv= new Payment($inv['ID'],$inv['DATE'], $inv['NOMINAL'], $inv['ID_INVOICE'], $inv['NOTES']);
                          $invoice = readInvoiceById($inv->getInvoice());
                          $customer = readCustomerById($invoice->getCustomerId());
                          ?>
                          <tr>
                          <td class="text-start align-middle"><?= htmlspecialchars($i)?></td>
<td class="text-start align-middle"><?= htmlspecialchars($inv->getDate()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars($invoice->getKode()) ?> - <?= htmlspecialchars($customer->getName())?></td>
<td class="text-end align-middle"><?= htmlspecialchars($inv->getNomial()) ?></td>
<td class="text-end align-middle"><?= htmlspecialchars($inv->getNotes()) ?></td>
<td class="text-center align-middle">
  

                              <div class="btn-group" role="group">
                                <a href="inputPayment.php?id=<?= $inv->getId() ?>" class="btn btn-sm btn-warning" title="Lihat Detail">
                                  <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?type=payment&amp;action=delete&amp;id=<?= $inv->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus invoice ini?');" title="Delete Invoice">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                        <td colspan="6" class="text-center align-middle text-muted"><?= $isSearch ? 'No matching records.' : 'No invoices found.' ?></td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer text-start clearfix">
                  <ul class="pagination pagination-sm m-0 float-end">
    <?php if($page > 1): ?>
        <?php if($selectPage - 1 >= 0): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage - 1 ?>&keyword=<?= $keyword ?>">«</a>
            </li>
        <?php endif; ?>

        <?php for($i = 0; $i < $page; $i++): ?>
            <li class="page-item <?= ($i == $selectPage) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>">
                    <?= htmlspecialchars($i + 1) ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if($selectPage + 1 < $page): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage + 1 ?>&keyword=<?= $keyword ?>">»</a>
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
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="../../js/adminlte.js"></script>
  <script 
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script>
</body>
</html>
