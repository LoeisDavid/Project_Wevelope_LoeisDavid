<?php
// sidebar.php
include __DIR__ . '/../../Control/Control.php';

// Ambil data dari repository
$items = readItems();
$customers = readCustomers();
$suppliers = readSuppliers();
?>
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <span class="brand-text fw-light">WEVELOPE</span>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="/../Project_Wevelope_LoeisDavid/index.php" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Items -->
        <li class="nav-item">
          <a href="/../Project_Wevelope_LoeisDavid/pages/html/tableItems.php" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>
              Items
            </p>
          </a>
        </li>

        <!-- Customers -->
        <li class="nav-item">
          <a href="/../Project_Wevelope_LoeisDavid/pages/html/tableCustomers.php" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>
              Customers
            </p>
          </a>
        </li>

        <!-- Suppliers -->
        <li class="nav-item">
          <a href="/../Project_Wevelope_LoeisDavid/pages/html/tableSuppliers.php" class="nav-link">
            <i class="nav-icon bi bi-people-fill"></i>
            <p>
              Suppliers
            </p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
