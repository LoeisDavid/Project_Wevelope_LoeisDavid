<?php

// Handle delete action
if (
    isset($_GET['type'], $_GET['action'], $_GET['id'])
    && $_GET['type'] === 'invoice'
    && $_GET['action'] === 'delete'
) {
    $id = (int) $_GET['id'];
    deleteInvoice($id);
    $_SESSION['alert_delete'] = [
        'type'    => 'success',
        'message' => 'Invoice berhasil dihapus.',
    ];
    // Redirect to avoid resubmission
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Fetch all data
$allInvoices = readInvoices();
$customers   = readCustomers();

// Get filters from query string
$keyword     = $_GET['keyword']    ?? '';
$customerId  = $_GET['customer']   ?? '';
$startDate   = $_GET['start_date'] ?? '';
$endDate     = $_GET['end_date']   ?? '';

// Determine which set to display
if (
    $keyword !== ''
    || $customerId !== ''
    || ($startDate !== '' || $endDate !== '')
) {
    if ($keyword !== ''
    || $customerId !== ''
    || ($startDate !== '' || $endDate !== '')) {

      if($startDate==''){
        $startDate = $endDate;
      } elseif ($endDate== ''){
        $endDate = $startDate;
      }

        $displayInvoices = searchInvoices($keyword,$startDate,$endDate, $customerId, $keyword);
    } elseif ($customerId !== '') {
        $displayInvoices = readInvoiceByCustomer($customerId);
    } else {
        $displayInvoices = readInvoiceByRangeDate($startDate, $endDate);
    }
    $isSearch = true;
} else {
    $displayInvoices = $allInvoices;
    $isSearch = false;
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


<div class="card">
                <div class="card-header text-start clearfix">
                  <h3 class="card-title mt-2 mx-3"><?= $isSearch ? 'Search Results' : 'All Invoices' ?></h3>
                  <a href="inputInvoices.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New
                  </a>
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                  <thead>
  <tr>
    <th class="text-center align-middle" style="width: 20%;">TANGGAL</th>
    <th class="text-end align-middle" style="width: 15%;">TOTAL</th>
  </tr>
</thead>
<tbody>

                      <?php if (count($displayInvoices) > 0): ?>
                        <?php foreach ($displayInvoices as $inv): $total=0;
                        
                        $items=readItemInvByInvoice($inv->getId());
                        
                        ?>
                            <?php foreach ($items as $i => $item):
                            
                            $total+=$item->getPrice()*$item->getQty();
                            
                            ?>
                            <?php endforeach; ?>
                          <tr>
                          <td class="text-center align-middle"><?= htmlspecialchars($inv->getDate()) ?></td>
<td class="text-end align-middle">Rp<?= htmlspecialchars($total) ?></td>
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
    <?php if($page > 1): ?>
        <?php if($selectPage - 1 >= 0): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage - 1 ?>&keyword=<?= $keyword ?>">«</a>
            </li>
        <?php endif; ?>

        <?php for($i = 0; $i < $page; $i++): ?>
            <li class="page-item <?= ($i == $selectPage) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>">
                    <?= htmlspecialchars($i + 1) ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if($selectPage + 1 < $page): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $selectPage + 1 ?>&keyword=<?= $keyword ?>">»</a>
            </li>
        <?php endif; ?>
    <?php endif; ?>
</ul>

                </div>
              </div>