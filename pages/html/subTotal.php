<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Summary</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- AdminLTE 4 & Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.2/dist/css/adminlte.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/adminlte.css" />

  <style>
    .invoice-summary {
      padding: 40px;
    }
    .signature-box {
      display: flex;
      align-items: flex-end;
      justify-content: flex-end;
      gap: 20px;
      flex-direction: column;
      align-items: flex-end;
    }
    .signature-image {
      width: 100px;
      height: auto;
    }
    .section-title {
      font-weight: 600;
      margin-bottom: 10px;
      border-bottom: 2px solid #ccc;
      display: inline-block;
    }
  </style>
</head>
<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="container invoice-summary">

        <!-- Form start -->
          <form method="POST" action="test.php" enctype="multipart/form-data">
            <input type="text" value="<?=$inv->getId()?>" name="invoice" hidden>
            <input type="text" value="<?=$customer->getId()?>" name="customer" hidden>
            <div class="row justify-content-end mb-4">
              <div class="col-md-6">
                <table class="table table-borderless text-end">
                  <tr>
                    <th>Subtotal</th>
                    <td><?= number_format($subTotal, 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td><?= number_format($subTotal, 0, ',', '.') ?></td>
                  </tr>
                  <tr class="fw-bold">
                    <th>Jumlah Tertagih</th>
                    <td><?= number_format($countainer['grand_total']-$countainer['total_payment'], 0, ',', '.') ?></td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <!-- Keterangan -->
              <div class="col-md-6">
                <p class="section-title">Keterangan</p>
                <textarea class="form-control mb-3" name="keterangan" id="keterangan" rows="5"
                  placeholder="Masukkan keterangan pembayaran atau instruksi lainnya..."></textarea>

                <p class="section-title">Syarat & Ketentuan</p>
                <textarea class="form-control mb-3" name="syarat&ketentuan" id="syarat&ketentuan" rows="5"
                  placeholder=""></textarea>
              </div>

              <!-- Tanggal, Tanda Tangan -->
              <div class="col-md-6 text-end">
                <div class="mb-2">
                  <label for="tanggalTtd" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" id="tanggalTtd" name="tanggalTtd" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="signature-box">
                  <label for="ttdImage" class="form-label">Unggah Tanda Tangan</label>
                  <input type="file" class="form-control mb-2" id="ttdImage" name="ttdImage" accept="image/*" onchange="previewTtd(event)">
                  <img id="previewSignature" class="signature-image" src="https://i.ibb.co/dQvKKQK/signature.png" alt="Tanda Tangan">
                </div>

                <div class="mt-2">
                  <label for="namaTtd" class="form-label">Nama Penandatangan</label>
                  <input type="text" class="form-control" id="namaTtd" name="namaTtd" value="">
                </div>
              </div>
            </div>

            <!-- Submit button -->
            <div class="row mt-4">
    <div class="col text-end">

    <a href="inputPayment.php?invoice=<?= $inv->getId()?>" class="btn btn-warning">
  <i class="fas fa-credit-card me-1"></i> Payment
</a>

  <!-- Tombol pertama untuk download invoice -->
  <button type="submit" name="aksi" value="download" class="btn btn-primary">
    <i class="fas fa-download me-1"></i> Download
  </button>

  <!-- Tombol kedua untuk print invoice -->
  <button type="submit" name="aksi" value="print" class="btn btn-success">
    <i class="fas fa-print me-1"></i> Print
  </button>
</div>

  </div>
          </form>
        <!-- End form -->

      </div>
    </div>
  </div>

  <!-- JS AdminLTE + Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.2/dist/js/adminlte.min.js"></script>

  <!-- Preview TTD -->
  <script>
    function previewTtd(event) {
      const img = document.getElementById('previewSignature');
      img.src = URL.createObjectURL(event.target.files[0]);
    }
  </script>
</body>
</html>
