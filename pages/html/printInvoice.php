<?php
include_once '../../Control/urlController.php';

$invoice = readInvoiceById($_POST['invoice']);
$itemInv = sessionGetObjectItemInv();
$company = sessionGetObjectCompany();
$customer = readCustomerById($_POST['customer']);

$tglTtd = $_POST['tanggalTtd'];
$ttdImage = $_FILES['ttdImage'];
$nameTtd = $_POST['namaTtd'];
$keterangan = $_POST['keterangan'];
$syaratketentuan = $_POST['syarat&ketentuan'];

// Ambil path relatif dari objek
$kondisi=false;
 $countainer= invoiceTersisa($invoice->getId()); 
        if($countainer['total_payment']<$countainer['grand_total']){
            $kondisi=false;
        } else {
            $kondisi=true;
        }
$relativePath = $company->getUrlLogo(); // "../pages/html/img/Hunter logo (BG bebas)-02.png"
$basePath = realpath(__DIR__ . '/../../');

    // Bersihkan karakter / atau \ di awal path
    $cleanPath = preg_replace('/^(\.\.\/)+/', '', $relativePath);

    // 2. Ganti semua "/" dengan "\"
    $cleanPath = str_replace('/', '\\', $cleanPath);


    // Gabungkan jadi full path absolut
    $fullPath = realpath($basePath . DIRECTORY_SEPARATOR . $cleanPath);

    // Validasi dan konversi ke base64
$type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $path64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$ttdPath = $ttdImage['tmp_name']; // file sementara
$ttdType = pathinfo($ttdImage['name'], PATHINFO_EXTENSION);

// Validasi dan konversi ke base64
if (file_exists($ttdPath)) {
    $ttdData = file_get_contents($ttdPath);
    $ttdBase64 = 'data:image/' . $ttdType . ';base64,' . base64_encode($ttdData);
} else {
    $ttdBase64 = ''; // Kosong jika gagal upload
}


        
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice - Hunter Comunity</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 30px;
      color: #000;
    }

    .header-invoice {
      text-align: right;
      font-size: 36px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    .section {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .logo {
      width: 150px;
    }

    .info-block {
      width: 48%;
    }

    .info-block table {
      width: 100%;
      font-size: 14px;
    }

    .info-block td {
      padding: 2px 0;
      vertical-align: top;
    }

    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .invoice-table th, .invoice-table td {
      border: 1px solid #000;
      padding: 8px;
    }

    .invoice-table th {
      background-color: #f0f0f0;
    }

    .invoice-table td:nth-child(5),
    .invoice-table td:nth-child(6) {
      text-align: right;
      white-space: nowrap;
    }

    .invoice-table td:nth-child(4) {
      text-align: center;
    }

    .summary {
      text-align: right;
      margin-bottom: 30px;
      font-size: 14px;
    }

    .summary b {
      font-size: 16px;
    }

    .notes {
      font-size: 14px;
      margin-bottom: 40px;
    }

    .signature {
      margin-top: 50px;
      text-align: right;
      font-size: 14px;
    }

    .signature .name {
      margin-top: 60px;
      text-decoration: underline;
    }

    .small {
      font-size: 12px;
    }
  </style>
</head>
<body>

  <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; border-collapse: collapse;">

  <!-- Baris 1: Judul Invoice -->
  <tr>
    <td style="padding: 10px;"></td>
    <td style="text-align: right; padding: 10px;">
      <span style="font-size: 32px; font-weight: bold;">Invoice</span>
    </td>
  </tr>

  <!-- Baris 2: Logo + Info Invoice -->
  <tr>
    <!-- Logo (di atas info perusahaan) -->
    <td style="width: 50%; vertical-align: top; padding: 10px;">
      <img src="<?=$path64?>" alt="Logo Perusahaan" style="max-height: 80px;"><br><br>
    </td>

    <!-- Info Invoice -->
    <td style="width: 50%; vertical-align: top; padding: 10px;">
      <table width="100%" style="font-size: 14px;">
        <tr>
          <td style="width: 40%; padding: 3px 10px 3px 0;"><strong>Kode Invoice</strong></td>
          <td>: <?=$invoice->getKode()?></td>
        </tr>
        <tr>
          <td><strong>Tanggal</strong></td>
          <td>: <?=$invoice->getDate()?></td>
        </tr>
        <tr>
          <td><strong>Deadline</strong></td>
          <td>: <?=$invoice->getDeadline()?></td>
        </tr>
        <tr>
          <td><strong>Status</strong></td>
<td>:<span style="color: <?= $kondisi ? 'green' : 'red'; ?>;">
  <?= $kondisi ? 'Lunas' : 'Belum Lunas'; ?>
</span></td>

        </tr>
        <tr>
          <td><strong>Catatan</strong></td>
          <td>: <?=$invoice->getNotes()?></td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Baris 3: Judul + garis tengah -->
  <tr>
    <td style="padding: 10px; font-weight: bold; border-bottom: 2px solid #000;">
      Informasi Perusahaan
    </td>
    <td style="padding: 10px; font-weight: bold; border-bottom: 2px solid #000;">
      Informasi Customer
    </td>
  </tr>

  <!-- Baris 4: Detail perusahaan dan customer -->
  <tr>
  <!-- Info Perusahaan -->
  <td style="vertical-align: top; padding: 10px; border-right: 1px solid #ccc;">
    <table style="font-size: 14px;">
      <tr>
        <td style="min-width: 100px;"><strong>Nama</strong></td>
        <td>: <?= $company->getNama() ?></td>
      </tr>
      <tr>
        <td><strong>Alamat</strong></td>
        <td>: <?= $company->getAlamat() ?></td>
      </tr>
      <tr>
        <td><strong>Kota</strong></td>
        <td>: <?=$company->getKota()?></td>
      </tr>
      <tr>
        <td><strong>Provinsi</strong></td>
        <td>: <?=$company->getProvinsi()?></td>
      </tr>
      <tr>
        <td><strong>Kode Pos</strong></td>
        <td>: <?=$company->getKodePos()?></td>
      </tr>
      <tr>
        <td><strong>Negara</strong></td>
        <td>: <?=$company->getNegara()?></td>
      </tr>
      <tr>
        <td><strong>Telepon</strong></td>
        <td>: <?=$company->getTelepon()?></td>
      </tr>
      <tr>
        <td><strong>Email</strong></td>
        <td>: <?=$company->getEmail()?></td>
      </tr>
    </table>
  </td>

  <!-- Info Customer -->
  <td style="vertical-align: top; padding: 10px;">
    <table style="font-size: 14px;">
      <tr>
        <td style="min-width: 100px;"><strong>REF_NO</strong></td>
        <td>: <?=$customer->getRefNo()?></td>
      </tr>
      <tr>
        <td><strong>Nama</strong></td>
        <td>: <?=$customer->getName()?></td>
      </tr>
      <tr>
        <td><strong>Alamat</strong></td>
        <td>: <?=$customer->getAlamat()?></td>
      </tr>
      <tr>
        <td><strong>Telepon</strong></td>
        <td>: <?=$customer->getTelepon()?></td>
      </tr>
      <tr>
        <td><strong>Email</strong></td>
        <td>: <?=$customer->getEmail()?></td>
      </tr>
    </table>
  </td>
</tr>


</table>


  <table class="invoice-table">
    <thead>
      <tr>
        <th>NO</th>
        <th>REF NO</th>
        <th>Barang</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
        <?php if (count($itemInv) > 0): $index=0;  $subTotal = 0;?>
        <?php
            foreach($itemInv as $row):
              
                        if(is_object($row)){
                          $item = $row;
                        } else {
                          $item = new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
                        }
                        $index++;
                        $subTotal += $item->getQty() * $item->getPrice();          
                    $it = readItemById($item->getItemId());
        ?>
      <tr>
        <td><?=$index?></td>
        <td><?=$it->getRefNo()?></td>
        <td><?=$it->getName()?></td>
        <td>Rp<?= number_format($item->getQty(), 0, ',', '.' )?></td>
        <td>Rp<?= number_format($item->getPrice(), 0, ',', '.' )?></td>
        <td>Rp<?= number_format($item->getTotal(), 0, ',', '.' )?></td>
    </tr>
    <?php endforeach;?>
    <
    </tbody>
                      </table>
  <div class="summary">
    <p><i>Subtotal:</i> Rp<?= number_format($subTotal, 0, ',', '.' )?></p>
    <p><i>Total:</i> RpRp<?= number_format($subTotal, 0, ',', '.' )?></p>
    <hr style="margin: 10px 0; border: 1px solid #000;">
    <p><b>Jumlah Tertagih:</b> Rp<?= number_format($countainer['grand_total']-$countainer['total_payment'], 0, ',', '.') ?></p>
  </div>
  <?php else: ?>
                      <tr><td colspan="6" class="text-center">Tidak ada item.</td></tr>
                    <?php endif; ?>

  <div class="notes">
    <p><b>Keterangan:</b></p>
    <p><?=$keterangan?></p>
    <p class="small"><b>Syarat &amp; Ketentuan:</b> <?=$syaratketentuan?></p>
  </div>

  <div class="signature">
  <p><?= $tglTtd ?></p>
  <?php if ($ttdBase64): ?>
    <p><img src="<?= $ttdBase64 ?>" style="max-height: 80px;"></p>
  <?php else: ?>
    <p><i>(Tanda tangan tidak tersedia)</i></p>
  <?php endif; ?>
  <p class="name"><?= htmlspecialchars($nameTtd) ?></p>
</div>


</body>
</html>
