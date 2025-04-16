<?php

include '../../Control/Control.php';

$id = $_GET['id'] ?? 0;

// Ambil data invoice dan item
$invoice = readInvoiceById($id);
$items = readItemInvByInvoice($id);

// Ambil nama customer
$customer = readCustomerById($invoice->getCustomerId());

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Print Invoice</title>
  <style>
    @media print {
      @page {
        size: A4;
        margin: 20mm;
      }
    }
    body { font-family: Arial, sans-serif; margin: 20px; }
    .invoice-box { padding: 20px; border: 1px solid #000; }
    h2 { text-align: center; margin-bottom: 30px; }
    .info { margin-bottom: 20px; }
    .info div { margin-bottom: 5px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .right { text-align: right; }
    .total-row td { font-weight: bold; }
  </style>
</head>
<body onload="window.print()">

<div class="invoice-box">
  <h2>INVOICE</h2>

  <div class="info">
    <div><strong>Kode Invoice:</strong> <?= htmlspecialchars($invoice->getKode()) ?></div>
    <div><strong>Tanggal:</strong> <?= htmlspecialchars($invoice->getDate()) ?></div>
    <div><strong>Customer:</strong> <?= htmlspecialchars($customer->getName()) ?></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): 
        $subTotal = $item->getQty() * $item->getPrice();
        $total += $subTotal;
      ?>
        <tr>
          <td><?= htmlspecialchars($item->getItemId()) ?></td>
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

  <div style="text-align: right;">
    <em>Terima kasih atas pembelian Anda.</em>
  </div>
</div>

</body>
</html>
