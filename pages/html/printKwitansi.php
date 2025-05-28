<?php
include_once '../../Control/Control.php';
$data= $_SESSION['PAYMENT'];
$id = $data['ID'];
$payment = readPaymentById($id);
$invoice= readInvoiceById($payment->getInvoice());
$customer = readCustomerById($invoice->getCustomerId());
$company = readCompanies();
$pic = getDataStatusTruePic();
$pic = $pic[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kwitansi Pembayaran</title>
  <style>
    @media print {
      @page {
        size: A4 portrait;
        margin: 20mm;
      }
      body {
        font-family: Arial, sans-serif;
        color: #000;
        background: #fff;
      }
    }

    body {
      width: 210mm;
      min-height: 297mm;
      margin: auto;
      padding: 20mm;
      font-family: Arial, sans-serif;
      background: #fff;
      color: #000;
    }

    .kwitansi-container {
      border: 1px solid #000;
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .header img {
      height: 60px;
    }

    .header h1 {
      font-size: 24px;
      margin: 0;
    }

    .info {
      margin-bottom: 20px;
    }

    .info table {
      width: 100%;
      border-collapse: collapse;
    }

    .info td {
      padding: 6px 0;
      vertical-align: top;
    }

    .amount {
      font-size: 18px;
      font-weight: bold;
      margin: 20px 0;
    }

    .signature {
      margin-top: 60px;
      display: flex;
      justify-content: space-between;
    }

    .signature div {
      text-align: center;
      width: 40%;
    }

    .footer {
      margin-top: 40px;
      font-size: 12px;
      text-align: center;
      color: #666;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="kwitansi-container">
    <div class="header">
      <!-- <img src="https://via.placeholder.com/150x60?text=LOGO" alt="Logo Perusahaan"> -->
      <h1>KWITANSI PEMBAYARAN</h1>
    </div>

    <div class="info">
      <table>
        <tr>
          <td style="width: 30%;">Sudah terima dari</td>
          <td>: <?= htmlspecialchars($company['NAMA_PERUSAHAAN']) ?></td>
        </tr>
        <tr>
          <td>Uang sejumlah</td>
          <td>: <strong>Rp <?= htmlspecialchars($payment->getNomial())?>,-</strong></td>
        </tr>
        <tr>
          <td>Untuk pembayaran</td>
          <td>: Invoice Kode: <?= htmlspecialchars($invoice->getKode())?></td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td>: <?=htmlspecialchars($payment->getDate())?></td>
        </tr>
        <tr>
          <td>No. Kwitansi</td>
          <td>: <?=htmlspecialchars($payment->getKode())?></td>
        </tr>
      </table>
    </div>

    <div class="amount">
      Total: Rp <?= htmlspecialchars($payment->getNomial())?>,-
    </div>

    <div class="signature">
      <div>
        <p>Penerima,</p>
        <br><br><br>
        <p>_____________________<br><?= htmlspecialchars($customer->getName())?></p>
      </div>

      <div>
        <p>Hormat Kami,</p>
        <br><br><br>
        <p>_____________________<br><?= htmlspecialchars($pic->getNama())?></p>
      </div>
    </div>

    <div class="footer">
      <?= $company['NAMA_PERUSAHAAN']?> - <?= $company['ALAMAT']?>, <?= $company['KODE_POS']?>, <?= $company['KOTA']?>, <?= $company['PROVINSI']?>, <?= $company['NEGARA']?> | Telp: <?= $company['TELEPON']?> | <?= $company['EMAIL']?>
    </div>
  </div>
</body>
</html>
