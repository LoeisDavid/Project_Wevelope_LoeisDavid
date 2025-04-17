<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Panduan Website | AdminLTE 4</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
  <div class="app-wrapper">
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Panduan Penggunaan Website</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Panduan</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Panduan Langkah demi Langkah</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered align-middle">
                <tr>
                  <!-- Kolom Gambar -->
                  <td style="width: 50%; text-align: center;">
                    <img src="img/sidebar.jpg" class="img-fluid rounded" alt="Panduan Gambar" style="max-height: 400px;">
                    <p class="text-muted mt-2"></p>
                  </td>

                  <!-- Kolom Penjelasan -->
                  <td style="width: 50%;">
                    <h5 class="mb-3">Sidebar</h5>
                    <ol class="list-group list-group-numbered">
                      <li class="list-group-item">Tekan/arahkan kursor ke sidebar untuk Menampilkan menu yang tersedia <!-- Penjelasan bisa ditambah di sini --></li>
                    </ol>
                  </td>
                </tr>
                <tr>
                  <!-- Kolom Gambar -->
                  <td style="width: 50%; text-align: center;">
                    <img src="img/menu.jpg" class="img-fluid rounded" alt="Panduan Gambar" style="max-height: 400px;">
                    <p class="text-muted mt-2"></p>
                  </td>

                  <!-- Kolom Penjelasan -->
                  <td style="width: 50%;">
                    <h5 class="mb-3">Menu</h5>
                    <ol class="list-group list-group-numbered">
                      <li class="list-group-item">Tekan Dashboard untuk Menampilkan berapa data yang tersimpan didalam Website<!-- Penjelasan bisa ditambah di sini --></li>
                      <li class="list-group-item">Tekan Data Items untuk Menampilkan data item yang tersimpan didalam Website</li>
                      <li class="list-group-item">Tekan Data Customers untuk Menampilkan data customer yang tersimpan didalam Website</li>
                      <li class="list-group-item">Tekan Data Suppliers untuk Menampilkan data supplier yang tersimpan dalam Website</li>
                      <li class="list-group-item">Tekan Data Item-Customers untuk menampilkan data item-customer yang tersimpan dalam Website</li>
                      <li class="list-group-item">Tekan Data Invoice untuk menampilkan data invoice yang tersimpan dalam Website</li>
                    </ol>
                  </td>
                </tr>
                <tr>
                  <!-- Kolom Gambar -->
                  <td style="width: 50%; text-align: center;">
                  <div class="small-box text-bg-primary">
                <div class="inner">
                  <h3>--</h3>
                  <p>Data</p>
                </div>
                <a href="#" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
              </div>
                  </td>

                  <!-- Kolom Penjelasan -->
                  <td style="width: 50%;">
                    <h5 class="mb-3">Dashboard</h5>
                    <ol class="list-group list-group-numbered">
                      <li class="list-group-item">Menampilkan jumlah data yang tersimpan</li>
                      <li class="list-group-item">Tekan untuk <b>more info</b> menampilkan lebih detail mengenai data yang tersimpan<!-- Penjelasan bisa ditambah di sini --></li>
                    </ol>
                  </td>
                </tr>
                <tr>
                  <!-- Kolom Gambar -->
                  <td style="width: 50%; text-align: center;">
                  <table class="table table-bordered mx-auto">
  <thead>
    <tr>
      <th>Kode / ID</th>
      <th>Name</th>
      <th>Price</th>
      <th style="width: 120px">Actions</th>
    </tr>
  </thead>
  <tbody>
      <tr>
        <td colspan="4" class="text-center text-muted">No data found.</td>
      </tr>
      </tbody>
      </table>
                  </td>

                  <!-- Kolom Penjelasan -->
                  <td style="width: 50%;">
                    <h5 class="mb-3">Tabel</h5>
                    <ol class="list-group list-group-numbered">
                      <li class="list-group-item">Menampilkan data yang tersimpan</li>
                      <li class="list-group-item"><b>Kode / ID</b> -- data yang tersimpan didalam Website<!-- Penjelasan bisa ditambah di sini --></li>
                      <li class="list-group-item"><b>Name</b> -- data yang tersimpan didalam Website<!-- Penjelasan bisa ditambah di sini --></li>
                      <li class="list-group-item"><b>Price</b> -- data yang tersimpan didalam Website<!-- Penjelasan bisa ditambah di sini --></li>
                      <li class="list-group-item"><b>Action</b> -- terdapat tombol yang digunakan dalam mengubah, menghapus, melihat data yang tersimpan didalam Website<!-- Penjelasan bisa ditambah di sini --></li>
                    </ol>
                    </ol>
                  </td>
                </tr>
                <tr>
                  <!-- Kolom Gambar -->
                  <td style="width: 50%; text-align: center;">
                  <a
    href="#"
    class="btn btn-sm btn-warning"
    title="Edit"
  >
    <i class="bi bi-pencil-square"></i>Edit
  </a>
  <a
    href="#"
    class="btn btn-sm btn-danger"
    title="Delete"
  >
    <i class="bi bi-trash"></i>Delete
  </a>
  <a
    href="#"
    class="btn btn-sm btn-info"
    title="Preview"
  >
    <i class="bi bi-eye"></i>Preview
  </a>
  <a href="inputInvoices.php" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Create New
  </a>
  <a href="printInvoice.php?invoice=<?= $invoice ?>" class="btn btn-success" target="_blank">
    <i class="bi bi-printer"></i> Print
  </a>
                  </td>

                  <!-- Kolom Penjelasan -->
                  <td style="width: 50%;">
                    <h5 class="mb-3">Action</h5>
                    <ol class="list-group list-group-numbered">
                      <li class="list-group-item"><b>Tombol Edit</b> -- Tekan tombol untuk mengubah data</li>
                      <li class="list-group-item"><b>Tombol Delete</b> -- Tekan tombol untuk menghapus data</li>
                      <li class="list-group-item"><b>Tombol Preview</b> -- Tekan tombol untuk melihat detail data</li>
                      <li class="list-group-item"><b>Tombol Create</b> -- Tekan tombol untuk menambahkan data</li>
                      <li class="list-group-item"><b>Tombol Print</b> -- Tekan tombol untuk melakukan print data</li>
                    </ol>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../widget/footer.php'; ?>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="../../js/adminlte.js"></script>
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
