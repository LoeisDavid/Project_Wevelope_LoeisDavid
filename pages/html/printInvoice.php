<?php
include_once '../../Control/Control.php';
$data= $_SESSION['INVOICE'];
$invoice= readInvoiceById($data['ID']);
$items = readItemInvByInvoice($invoice->getId());
$customer = readCustomerById($invoice->getCustomerId());
$company = readCompanies();
$pic = getDataStatusTruePic();
$pic = $pic[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
  <style>
    @media print {
      @page {
        size: A4;
        margin: 20mm;
      }
    }

    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    .invoice-box {
      max-width: 900px;
      margin: auto;
      border: 1px solid #000;
      padding: 30px;
    }

    .top-section {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .company-info {
      width: 60%;
      font-size: 14px;
      line-height: 1.5;
    }

    .invoice-title {
      width: 40%;
      text-align: right;
      font-size: 24px;
      font-weight: bold;
    }

    .info-section {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      margin-top: 10px;
    }

    .info-left, .info-right {
      width: 48%;
      line-height: 1.6;
      vertical-align: top;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      font-size: 14px;
    }

    table, th, td {
      border: 1px solid #000;
    }

    th, td {
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    .text-right {
      text-align: right;
    }

    .footer {
      text-align: right;
      margin-top: 30px;
      font-size: 13px;
      font-style: italic;
    }
  </style>
  <script>
    window.onload = function () {
      window.print();
    };
  </script>
</head>
<body>

<div class="invoice-box">

  <!-- Header Atas -->
  <div class="top-section">
    <div class="company-info">
      <strong><?= htmlspecialchars($company['NAMA_PERUSAHAAN'])?></strong><br>
      <?= htmlspecialchars($company['ALAMAT'])?><br>
      <?= htmlspecialchars($company['KOTA'])?>, <?= htmlspecialchars($company['NEGARA'])?><br>
      Kode Pos: <?= htmlspecialchars($company['KODE_POS'])?><br>
      Telp: <?= htmlspecialchars($company['TELEPON'])?><br>
      Email: <?= htmlspecialchars($company['EMAIL'])?>
    </div>
    <div class="invoice-title">
      INVOICE
    </div>
  </div>

  <!-- PIC dan Customer -->
  <div class="info-section">
    <div class="info-left">
      <strong>Ditangani oleh (PIC):</strong><br>
      Nama: <?= htmlspecialchars($pic->getNama())?><br>
      Jabatan: <?= htmlspecialchars($pic->getJabatan())?><br>
      Kontak: <?= htmlspecialchars($pic->getNomor())?><br>
      Email: <?= htmlspecialchars($pic->getEmail())?>
    </div>
    <div class="info-right">
      <strong>Kode Invoice:</strong> <?= htmlspecialchars($invoice->getKode())?><br>
      <strong>Tanggal:</strong> <?= htmlspecialchars($invoice->getDate())?><br>
      <strong>Jatuh Tempo:</strong> <?= htmlspecialchars($invoice->getDeadline())?><br><br>
      <strong>Customer:</strong> <?= htmlspecialchars($customer->getName())?><br>
      Alamat: <?= htmlspecialchars($customer->getAlamat())?><br>
      Telepon: <?= htmlspecialchars($customer->getTelepon())?><br>
      Email: <?= htmlspecialchars($customer->getEmail())?>
    </div>
  </div>

  <!-- Tabel Items -->
  <table>
    <thead>
      <tr>
        <th>REF NO</th>
        <th>Nama Barang</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $total=0; foreach ($items as $item): 
      $item = new ItemInv($item['ID'], $item['INVOICE_ID'],$item['ITEM_ID'], $item['QTY'], $item['PRICE'], $item['TOTAL']);
        $subTotal = $item->getTotal();
        $total += $subTotal;
      ?>
        <tr>
          <td><?= htmlspecialchars(readItemById($item->getItemId())->getRefNo()) ?></td>
          <td><?= htmlspecialchars(readItemById($item->getItemId())->getName()) ?></td>
          <td class="right"><?= $item->getQty() ?></td>
          <td class="right"><?= number_format($item->getPrice(), 0, ',', '.') ?></td>
          <td class="right"><?= number_format($subTotal, 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="4" class="right">Total</td>
        <td class="right"><?= number_format($total, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <!-- Footer -->
  <div class="footer">
    Terima kasih atas pembelian Anda.<br>
    Harap simpan invoice ini sebagai bukti pembayaran.
  </div>

</div>

</body>
</html>
