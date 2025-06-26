<?php
include_once '../../Control/urlController.php';

// handle call
$call = sessionGetCall('customer');

// handle url
$url = $_SERVER['REQUEST_URI'];
sessionSetRedirectUrl($url);

// Handle delete action
if (
    isset($_GET['type'], $_GET['action'], $_GET['id'])
    && $_GET['type'] === 'customer'
    && $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteCustomer($id);
    $_SESSION['alert_delete'] = [
        'type'    => 'success',
        'message' => 'Customer berhasil dihapus.',
    ];
    // Redirect to avoid resubmission
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$countPage = 5;

// Fetch all data

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';

// Determine which set to display
if (
    $keyword !== ''
) {
    
        sessionSetObjectCustomers(searchCustomers($keyword));
        sessionSetCall('customer', true);
        $isSearch = true;
} else {

  if($call){
    sessionSetObjectCustomers(readCustomers());
    sessionSetCall('customer',false);
  }
    $isSearch = false;
}

$display = sessionGetObjectCustomers();

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


$displayCustomer = $contain;


?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Customer Table</title>
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
              <h3 class="mb-0">Customer Table</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Customer</li>
              </ol>
            </div>
          </div>
          <!-- Centered Content -->
          <div class="row justify-content-center">
            <div class="col-lg-12">
              <!-- Search Form -->

              <div class="card mb-4">
                <div class="card-body">
                <form method="GET" class="d-flex w-100">
                <input type="hidden" name="type" value="customer">
                  <input type="text" id="keyword" name="keyword" class="form-control me-2" placeholder="Search Customer..." value="<?= htmlspecialchars($keyword) ?>">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                  </button>
                  <?php if (!empty($keyword)): ?>
    <a href="?type=customer" class="btn btn-outline-secondary d-flex align-items-center" title="Reset Pencarian">
      <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
    </a>
  <?php endif; ?>
              </form>
                </div>
              </div>
              <!-- Unified Table -->
              <div class="card">
               <div class="card-header text-start d-flex justify-content-between align-items-center flex-wrap gap-2">
  <h3 class="card-title m-0"><?= $isSearch ? 'Search Results' : 'All Invoices' ?></h3>
  <div class="d-flex gap-2">
    <!-- Import CSV Button -->
    <a href="inputCSV.php" class="btn btn-outline-success btn-sm">
  <i class="bi bi-upload"></i> Import CSV
</a>


    <!-- Export CSV Button -->
    <a href="?csv=customer&action=export" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-download"></i> Export CSV
    </a>
                  <a href="<?=getUrlInputCustomer('null=null')?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                </div>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-start align-middle" style="width: 20%;">REF NO</th>
    <th class="text-start align-middle" style="width: 60%;">NAME</th>
    <th class="text-center align-middle" style="width: 10%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayCustomer) > 0): $index=$countPage*$selectPage?>
                        <?php foreach ($displayCustomer as $row): 
                          $index++;

                          if(is_object($row)){
                          $inv = $row;
                        } else {
                          $inv = new Customer(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO'],
            $row['EMAIL'],
            $row['ALAMAT'],
            $row['TELEPON']
        );
                        }
                         
                          ?>
                          <tr>
                          <td class="text-start align-middle"><?= htmlspecialchars($inv->getRefNo()) ?></td>
<td class="text-start align-middle"><?= htmlspecialchars($inv->getName()) ?></td>
<td class="text-center align-middle">

                            
                                <a href="inputCustomers.php?id=<?= $inv->getId() ?>&amp;index=<?=$index?>" class="btn btn-sm btn-warning" title="Edit Customer">
                                  <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?type=customer&amp;action=delete&amp;id=<?= $inv->getId() ?>&amp;index=<?=$index?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Customer ini?');" title="Delete Customer">
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

<!-- Alert Delete -->

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
