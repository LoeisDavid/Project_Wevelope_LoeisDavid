<?php
include_once '../../Control/Control.php';

// Handle delete action
if (
    isset($_GET['type'], $_GET['action'], $_GET['id'])
    && $_GET['type'] === 'invoice'
    && $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteInvoice($id);
    $_SESSION['alert_delete'] = [
        'type'    => 'success',
        'message' => 'Invoice berhasil dihapus.',
    ];
    // Redirect to avoid resubmission
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Fetch all data
$allInvoices = readInvoices();
$customers   = readCustomers();

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';
$customerId  = $_GET['customer']   ?? '';
$startDate   = $_GET['start_date'] ?? '';
$endDate     = $_GET['end_date']   ?? '';

// Determine which set to display
if (
    $keyword !== ''
    || $customerId !== ''
    || ($startDate !== '' && $endDate !== '')
) {
    if ($keyword !== '') {
        $displayInvoices = searchInvoices($keyword);
    } elseif ($customerId !== '') {
        $displayInvoices = readInvoiceByCustomer($customerId);
    } else {
        $displayInvoices = readInvoiceByRangeDate($startDate, $endDate);
    }
    $isSearch = true;
} else {
    $displayInvoices = $allInvoices;
    $isSearch = false;
}

$countPage = 5;

$page=count($displayInvoices)/$countPage;
$selectPage= $_GET['page'] ?? 0;
$offset = $selectPage*$countPage;
$contain= [];

for ($i = 0; $i < $countPage; $i++) {
  if ($offset >= count($displayInvoices)) {
      break;
  } else {
      $contain[] = $displayInvoices[$offset];
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
            <div class="col-lg-8">
              <!-- Alert Delete -->
              <!-- Search Form -->
              <form method="GET" class="row row-cols-lg-auto g-2 align-items-end mb-4 justify-content-center">
                <input type="hidden" name="type" value="invoice">
                <div class="col">
                  <label for="keyword" class="form-label">Keyword</label>
                  <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Search invoice..." value="<?= htmlspecialchars($keyword) ?>">
                </div>
                <div class="col">
                  <label for="customer" class="form-label">Customer</label>
                  <select name="customer" id="customer" class="form-select">
                    <option value="">-- Pilih Customer --</option>
                    <?php foreach ($customers as $cust): ?>
                      <option value="<?= $cust->getId() ?>" <?= $cust->getId() == $customerId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cust->getName()) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col">
                  <label for="start_date" class="form-label">Start Date</label>
                  <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                </div>
                <div class="col">
                  <label for="end_date" class="form-label">End Date</label>
                  <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                </div>
                <div class="col">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                  </button>
                </div>
              </form>

              <!-- Unified Table -->
              <div class="card">
                <div class="card-header text-center">
                  <h3 class="card-title"><?= $isSearch ? 'Search Results' : 'All Invoices' ?></h3>
                </div>
                <div class="card-body p-0">
                  <table class="table table-bordered mb-0">
                  <thead>
  <tr>
    <th class="text-center align-middle" style="width: 10%;">KODE</th>
    <th class="text-center align-middle" style="width: 20%;">TANGGAL</th>
    <th class="text-center align-middle" style="width: 40%;">CUSTOMER</th>
    <th class="text-center align-middle" style="width: 30%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayInvoices) > 0): ?>
                        <?php foreach ($displayInvoices as $inv): ?>
                          <tr>
                          <td class="text-center align-middle"><?= htmlspecialchars($inv->getKode()) ?></td>
<td class="text-center align-middle"><?= htmlspecialchars($inv->getDate()) ?></td>
<td class="text-center align-middle"><?= htmlspecialchars(readCustomerById($inv->getCustomerId())->getName()) ?></td>
<td class="text-center align-middle">

                              <div class="btn-group" role="group">
                                <a href="tableItemInv.php?invoice=<?= $inv->getId() ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                  <i class="bi bi-eye"></i>
                                </a>
                                <a href="editInvoices.php?method=get&amp;id=<?= $inv->getId() ?>&amp;kode=<?= urlencode($inv->getKode()) ?>&amp;customer=<?= $inv->getCustomerId() ?>" class="btn btn-sm btn-warning" title="Edit Invoice">
                                  <i class="bi bi-pencil-square"></i>
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
                        <td colspan="4" class="text-center align-middle text-muted"><?= $isSearch ? 'No matching records.' : 'No invoices found.' ?></td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer text-start clearfix">
                  <a href="inputInvoices.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
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
</body>
</html>
