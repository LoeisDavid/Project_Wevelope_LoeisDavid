<?php
include '../../Control/Control.php';

// Handle delete action

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;
if($action == 'status'){
  $pic = getDataStatusTruePic();
        ubahStatus($pic[0]->getId(), !$pic[0]->getStatus());
        ubahStatus($id, 1);
        $action = null;
} else if($id){
  $_SESSION['PIC'] = ['ID' => $id];
  header("Location: inputSettingPic.php");
  exit();
};

$displayitem = readPics();

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
                  </div>
                  <!-- /.card-body -->
                </div>
              <!-- Search Form -->
              

              <!-- Unified Table -->
              <div class="card">
                <div class="card-header text-start clearfix">
                  <h3 class="card-title mt-2 mx-3">PIC</h3>
                  <a href="inputSettingPic.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-start align-middle" style="width: 60%;">NAME</th>
    <th class="text-start align-middle" style="width: 60%;">JABATAN</th>
    <th class="text-start align-middle" style="width: 60%;">NOMOR</th>
    <th class="text-start align-middle" style="width: 60%;">EMAIL</th>
    <th class="text-start align-middle" style="width: 30%;">STATUS</th>
    <th class="text-center align-middle" style="width: 10%;">ACTIONS</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayitem) > 0): ?>
                        <?php foreach ($displayitem as $inv): 
                          
                          $inv= new Pic($inv['ID'],$inv['NAMA'], $inv['JABATAN'], $inv['NOMOR'],$inv['EMAIL'],$inv['STATUS']);
                          
                          ?>
                          <tr>
    <th class="text-start align-middle" style="width: 60%;"><?= $inv->getNama()?></th>
    <th class="text-start align-middle" style="width: 60%;"><?= $inv->getJabatan()?></th>
    <th class="text-start align-middle" style="width: 60%;"><?= $inv->getNomor()?></th>
    <th class="text-start align-middle" style="width: 60%;"><?= $inv->getEmail()?></th>
    <th class="text-start align-middle" style="width: 30%;"><?= $inv->getStatus() ? 'Use' : 'No Use' ?></th>
<td class="text-center align-middle">

                            
                                <a href="?id=<?= $inv->getId()?>" class="btn btn-sm btn-warning" title="Edit Item">
                                  <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?action=status&id=<?= $inv->getId()?>" class="btn btn-sm btn-success" title="Edit Item">
                                  <i class="bi bi-toggle-on"></i>
                                </a>
                                <a href="?type=pic&amp;action=delete&amp;id=<?= $inv->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Item ini?');" title="Delete Item">
                                  <i class="bi bi-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                        <td colspan="6" class="text-center align-middle text-muted">No Data</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer text-start clearfix">
                                    <ul class="pagination pagination-sm m-0 float-end">
    
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
<?php endif; 
session_unset();
?>

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
