<?php
include_once '../../Control/Control.php';

// Handle delete action
if (
    isset($_GET['type'], $_GET['action'], $_GET['id'])
    && $_GET['type'] === 'item'
    && $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteItem($id);
    $_SESSION['alert_delete'] = [
        'type'    => 'success',
        'message' => 'Item berhasil dihapus.',
    ];
    // Redirect to avoid resubmission
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$countPage = 5;

// Fetch all data
$allitems = readItems();

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';

// Determine which set to display
if (
    $keyword !== ''
) {
    
        $displayitem = searchItems($keyword);
        $isSearch = true;
} else {
    $displayitem = $allitems;
    $isSearch = false;
}

$page=count($displayitem)/$countPage;
$selectPage= $_GET['page'] ?? 0;
$offset = $selectPage*$countPage;
$contain= [];

for ($i = 0; $i < $countPage; $i++) {
  if ($offset >= count($displayitem)) {
      break;
  } else {
      $contain[] = $displayitem[$offset];
      $offset++;
  }
}


$displayitem = $contain;


?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>item Table</title>
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
              <h3 class="mb-0">item Table</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">item</li>
              </ol>
            </div>
          </div>
          <!-- Centered Content -->
          <div class="row justify-content-center">
            <div class="col-lg-12">
              <div class="card mb-4">
                  <!-- /.card-header -->
                  <div class="card-body">
                  <form method="GET" class="d-flex w-100">
  <input type="hidden" name="type" value="item">
  <input type="text" id="keyword" name="keyword" class="form-control me-2" placeholder="Search item..." value="<?= htmlspecialchars($keyword) ?>">
  <button type="submit" class="btn btn-primary">
    <i class="bi bi-search"></i>
  </button>
  <?php if (!empty($keyword)): ?>
    <a href="?type=item" class="btn btn-outline-secondary d-flex align-items-center" title="Reset Pencarian">
      <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
    </a>
  <?php endif; ?>
</form>
                  </div>
                  <!-- /.card-body -->
                </div>
              <!-- Search Form -->
              

              <!-- Unified Table -->
              <div class="card">
                <div class="card-header text-start clearfix">
                  <h3 class="card-title mt-2 mx-3"><?= $isSearch ? 'Search Results' : 'All Items' ?></h3>
                  <a href="inputItems.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-start align-middle" style="width: 10%;">REF NO</th>
    <th class="text-start align-middle" style="width: 60%;">NAME</th>
    <th class="text-end align-middle" style="width: 15%;">PRICE</th>
    <th class="text-center align-middle" style="width: 10%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayitem) > 0): ?>
                        <?php foreach ($displayitem as $inv): ?>
                          <tr>
                          <td class="text-start align-middle"><?= htmlspecialchars($inv->getRefNo()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars($inv->getName()) ?></td>
<td class="text-end align-middle">Rp<?=number_format($inv->getPrice(), 0, ',', '.')  ?></td>
<td class="text-center align-middle">

                            
                                <a href="editItems.php?method=get&amp;id=<?= $inv->getId() ?>&amp;ref_no=<?= urlencode($inv->getRefNo()) ?>&amp;name=<?= $inv->getName() ?>&amp;price=<?= $inv->getPrice() ?>" class="btn btn-sm btn-warning" title="Edit Item">
                                  <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?type=item&amp;action=delete&amp;id=<?= $inv->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Item ini?');" title="Delete Item">
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

<!-- Alert Delete -->
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
