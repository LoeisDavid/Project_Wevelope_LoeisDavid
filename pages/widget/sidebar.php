<?php
// sidebar.php
include_once __DIR__ . '/../../Control/Control.php';
?>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <span class="brand-text fw-light">WEVELOPE</span>
  </div>
  <div class="sidebar-wrapper">
    <div class="os-size-observer"><div class="os-size-observer-listener">::before</div></div>
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= $base_url ?>index.php" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Items -->
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableItems.php" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Items</p>
          </a>
        </li>

        <!-- Customers -->
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableCustomers.php" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Customers</p>
          </a>
        </li>

        <!-- Suppliers -->
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableSuppliers.php" class="nav-link">
            <i class="nav-icon bi bi-people-fill"></i>
            <p>Suppliers</p>
          </a>
        </li>

        <!-- Item Customers -->
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableItemCustomers.php" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Item Customers</p>
          </a>
        </li>

        <!-- Invoice -->
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableInvoice.php" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Invoice</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/omzet.php" class="nav-link">
            <i class="nav-icon bi bi-bookmark-fill"></i>
            <p>Omzet</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tablePayments.php" class="nav-link">
            <i class="nav-icon bi bi-bookmark-fill"></i>
            <p>Payments</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableBestSeller.php" class="nav-link">
            <i class="nav-icon bi bi-fire"></i>
            <p>Best Seller</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $base_url ?>pages/html/tableDeadline.php" class="nav-link">
            <i class="nav-icon bi bi-bookmark-fill"></i>
            <p>Deadline</p>
          </a>
        </li>

        <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-ui-checks-grid"></i>
                  <p>
                    Pengaturan
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview" style="box-sizing: border-box; display: none;">
                  <li class="nav-item">
                    <a href="<?= $base_url ?>pages/html/settingCompany.php" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Company</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $base_url ?>pages/html/settingPic.php" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>PIC</p>
                    </a>
                  </li>
                </ul>
              </li>
      </ul>
    </nav>
  </div>
</aside>

