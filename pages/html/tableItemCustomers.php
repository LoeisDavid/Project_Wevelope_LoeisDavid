<?php
include __DIR__ . '/../../Control/Control.php';

// Handle delete ItemCustomer
if (
    isset($_GET['type'], $_GET['action'], $_GET['id']) &&
    $_GET['type'] === 'itemcustomer' &&
    $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteItemCustomer($id);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Determine keyword and action for search
$keyword = $_GET['keyword'] ?? '';
$action  = !empty($keyword) ? 'search' : 'read';

// Fetch ItemCustomer entries (read or search)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($keyword)) {
        $itemCustomersSearch = searchItemCustomers($keyword);
    } else {
        $itemCustomersSearch = [];
    }
}
    $itemCustomers = readItemCustomers();


// Prepare related Item and Customer objects
$items     = [];
$customers = [];
foreach ($itemCustomers as $ic) {
    $items[]     = readItemById($ic->getItem());
    $customers[] = readCustomerById($ic->getCustomer());
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE 4 | Item-Customer Table</title>
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
        <div class="container mt-3">
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Item Customer Table</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Item-Customer</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
              <div class="card mb-4">
                <div class="card-body text-center">

                  <!-- Search Form -->
                  <form method="GET" class="mb-3 d-flex justify-content-center">
                    <input type="hidden" name="type" value="itemcustomer">
                    <input type="hidden" name="action" value="<?= $action ?>">
                    <input
                      type="text"
                      name="keyword"
                      class="form-control w-auto me-2"
                      placeholder="Search Item-Customer..."
                      value="<?= htmlspecialchars($keyword) ?>"
                    >
                    <button type="submit" class="btn btn-secondary">Search</button>
                  </form>

                  <!-- Data Table -->
                  <table class="table table-bordered mx-auto">
                    <thead>
                      <tr>
                        <th>Item Name</th>
                        <th>Customer Name</th>
                        <th>Price</th>
                        <th style="width: 120px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($itemCustomersSearch) > 0): ?>
                        <?php for ($i = 0; $i < count($itemCustomersSearch); $i++): ?>
                          <tr>
                            <td><?= htmlspecialchars($items[$i]->getName()) ?></td>
                            <td><?= htmlspecialchars($customers[$i]->getName()) ?></td>
                            <td>Rp <?= number_format($itemCustomers[$i]->getHarga(), 0, ',', '.') ?></td>
                            <td class="text-center">
                              <a
                                href="editItemCustomers.php?method=get&id=<?= $itemCustomersSearch[$i]->getId() ?>"
                                class="btn btn-sm btn-warning me-1"
                                title="Edit Item-Customer"
                              >
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <a
                                href="?type=itemcustomer&action=delete&id=<?= $itemCustomersSearch[$i]->getId() ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus item ini?');"
                                title="Delete Item-Customer"
                              >
                                <i class="bi bi-trash"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endfor; ?>
                      <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted">No data found.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8 mx-auto">
              <div class="card mb-4">
                <div class="card-header text-center"><h3>Data Item-Customer Tersimpan</h3></div>
                <div class="card-body text-center">

                  <!-- Data Table -->
                  <table class="table table-bordered mx-auto">
                    <thead>
                      <tr>
                        <th>Item Name</th>
                        <th>Customer Name</th>
                        <th>Price</th>
                        <th style="width: 120px">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($itemCustomers) > 0): ?>
                        <?php for ($i = 0; $i < count($itemCustomers); $i++): ?>
                          <tr>
                            <td><?= htmlspecialchars($items[$i]->getName()) ?></td>
                            <td><?= htmlspecialchars($customers[$i]->getName()) ?></td>
                            <td>Rp <?= number_format($itemCustomers[$i]->getHarga(), 0, ',', '.') ?></td>
                            <td class="text-center">
                              <a
                                href="editItemCustomers.php?method=get&id=<?= $itemCustomers[$i]->getId() ?>"
                                class="btn btn-sm btn-warning me-1"
                                title="Edit Item-Customer"
                              >
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <a
                                href="?type=itemcustomer&action=delete&id=<?= $itemCustomers[$i]->getId() ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus item ini?');"
                                title="Delete Item-Customer"
                              >
                                <i class="bi bi-trash"></i>
                              </a>
                            </td>
                          </tr>
                        <?php endfor; ?>
                      <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted">No data found.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>

                  <div class="text-start mt-3">
                    <a href="inputItemCustomers.php" class="btn btn-primary">
                      <i class="bi bi-plus-circle"></i> Create New
                    </a>
                  </div>

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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="../js/adminlte.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true }
        });
      }
    });
  </script>
</body>
</html>
