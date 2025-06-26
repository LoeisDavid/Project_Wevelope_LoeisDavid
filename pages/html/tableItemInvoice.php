

<!-- Mulai dari sini lanjutkan bagian HTML sama seperti sebelumnya -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE 4 | Items Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary sidebar-open app-loaded">
<div class="card-header bg-info text-center">
        <h6 class="mb-0">Detail Item:</h6>
      </div>
                <div class="d-flex justify-content-between align-items-center m-3 flex-wrap">
  <div class="mb-3">
  <a href="<?=getUrlInputItemInv('invoice='. $invoice)?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Item
                  </a>
  </div>
  <table class="table table-bordered mx-auto">
                  <thead>
                    <tr>
                      <th>NO</th>
                      <th>REF NO</th>
                      <th>Barang</th>
                      <th>Qty</th>
                      <th class="text-end">Price</th>
                      <th class="text-end">Total</th>
                      <th class="text-center" style="width: 10%;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($items) > 0): $index=0;  $subTotal = 0;?>
                      <?php 
                        $number=0;
                        foreach ($items as $i => $row): $number++;

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

                        ?>
                        <tr>
                          <td><?= htmlspecialchars($number)?></td>
                          <td><?= htmlspecialchars(readItemById($item->getItemId())->getRefNo()) ?></td>
                          <td><?= htmlspecialchars(readItemById($item->getItemId())->getName()) ?></td>
                          <td><?= htmlspecialchars($item->getQty()) ?></td>
                          <td class="text-end">Rp<?= number_format($item->getPrice(), 0, ',', '.') ?></td>
                          <td class="text-end">Rp<?= number_format($item->getQty() * $item->getPrice(), 0, ',', '.') ?></td>
                          <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                              <a href="<?=getUrlInputItemInv('invoice='. $invoice. '&index='. $index)?>" class="btn btn-sm btn-warning" title="Edit ItemInv">
                                <i class="bi bi-pencil-square"></i>
                              </a>
                              <a href="<?=getUrlControl('type=iteminv&action=delete&id='. $item->getId(). '&invoice='. $invoice. '&index='. $index+1)?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus item ini?');" title="Delete Item">
                                <i class="bi bi-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <!-- <tr>
                        <td colspan="5" class="text-end">Grand Total</td>
                        <td colspan="1" class="text-end">Rp
                          </td>
      <td></td> -->
                      </tr>
                    <?php else: ?>
                      <tr><td colspan="6" class="text-center">Tidak ada item.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
                
  </div>
</div>


  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="../js/adminlte.js"></script>
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
