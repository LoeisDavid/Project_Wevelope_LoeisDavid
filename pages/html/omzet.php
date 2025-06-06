<?php
include_once '../../Control/urlController.php';

$periode = $_GET['periode'] ?? 'harian';

if($periode == 'harian'){
  $displayInvoices=omzetDay();
} elseif ($periode == 'mingguan'){
  $displayInvoices=omzetWeek();
} else {
  $displayInvoices=omzetMonth();
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
  <title>Customer Table</title>
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
              <h3 class="mb-0">Omzet</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Omzet</li>
              </ol>
            </div>
          </div>
          <!-- Centered Content -->
          <div class="row justify-content-center">
            <div class="col-lg-12">
              <!-- Search Form -->
              <!-- Unified Table -->

              <div class="card">
                <div class="card-header">
                  <div class="card-tools">
              <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                  <a class="nav-link me-2 <?= ($_GET['periode'] ?? 'harian') == 'harian' ? 'active' : '' ?>" 
                    href="?periode=harian">Harian</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2 <?= ($_GET['periode'] ?? '') == 'mingguan' ? 'active' : '' ?>" 
                    href="?periode=mingguan">Mingguan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2 <?= ($_GET['periode'] ?? '') == 'bulanan' ? 'active' : '' ?>" 
                    href="?periode=bulanan">Bulanan</a>
                </li>
              </ul>

            </div>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-center align-middle" style="width: 10%;"><?= $periode === 'bulanan' ? 'Periode (Bulan-Tahun)' : ($periode === 'mingguan' ? 'Periode (Minggu-ke)' : 'Tanggal')?></th>
    <th class="text-end align-middle" style="width: 25%;">TOTAL</th>
  </tr>
</thead>
<tbody>
                      <?php 
                      
                      if (count($displayInvoices) > 0): ?>
                        <?php foreach ($displayInvoices as $inv):
                        
                        ?>
                            
                          <tr>
                          <td class="text-center align-middle"><?= htmlspecialchars($inv['tgl']) ?></td>
<td class="text-end align-middle">Rp<?= htmlspecialchars($inv['total_omzet']) ?></td>
                          </tr>
                        <?php endforeach ?>
                      <?php else: ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer text-start clearfix">
                  <ul class="pagination pagination-sm m-0 float-end">
    <?php if($page > 1): ?>
        <?php if($selectPage - 1 >= 0): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage - 1 ?>&periode=<?= $periode?>">«</a>
            </li>
        <?php endif; ?>

        <?php for($i = 0; $i < $page; $i++): ?>
            <li class="page-item <?= ($i == $selectPage) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&periode=<?= $periode?>">
                    <?= htmlspecialchars($i + 1) ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if($selectPage + 1 < $page): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage + 1 ?>&periode=<?= $periode?>">»</a>
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
