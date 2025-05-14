<div class="card">
                <div class="card-header text-start clearfix">
                  <h3 class="card-title mt-2 mx-3"></h3>
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
    <?php $week=6; for ($i = 0; $i < $week; $i++): 
        
        $displayInvoices=searchInvoicesWeek($i+1); ?>
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
                      <?php endfor; ?>
                    </tbody>
                  </table>
                </div>
              </div>