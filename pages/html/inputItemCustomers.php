<?php

include '../../Control/urlController.php';

// handle url
$redirect = $_GET['redirect'] ?? false;
$url = null;

if($redirect){
  $url = sessionGetRedirectUrl2();
} else {
  $url = sessionGetRedirectUrl();
$uri = $_SERVER['REQUEST_URI'];
sessionSetRedirectUrl2($uri);
}

$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;
$ref_no = $_GET['ref_no']?? null;
$price = $_GET['price'] ?? null;
$customer_id = null;
$it = null;
$index = $_GET['index'] ?? null;
$null = $_GET['null'] ?? null;

if(isset($null)){
  sessionSetPass(null, 'INDEX');
} else
if($index){
  sessionSetPass($id, 'ID_itemcustomer');
  sessionSetPass($index, 'INDEX_itemcustomer');
  header('Location: ?');
  exit();
}

if(sessionGetPass('ID_itemcustomer')){
  $id = sessionGetPass('ID_itemcustomer');
  $index = sessionGetPass('INDEX_itemcustomer');
  $customer_id = readItemCustomerById($id)->getCustomer();
$it = readItemCustomerById($id)->getItem();
$price = readItemCustomerById($id)->getHarga();
}

$customers = [];

$items = sessionGetObjectItems();
$customers = sessionGetObjectCustomers();

sessionSetPass(null, 'ID_itemcustomer');
sessionSetPass($index, 'INDEX');
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE 4 | General Form Elements</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE 4 | General Form Elements" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <!--end::Sidebar-->
    <!--begin::App Main-->
        <?php include __DIR__ . '/../widget/alert.php'; ?>
    <?php include __DIR__ . '/../widget/header.php'; ?>
    <?php include __DIR__ . '/../widget/sidebar.php'; ?>
                        <!-- Alert Session Message -->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
      <div class="card card-primary card-outlinr mb-6">
        <div class="card-header">
          <div class="card-title">Items-Customers</div>
        </div>
        <!--end::Header-->
        <!--begin::Form-->
        <form class="needs-validation" novalidate method="post" action="<?= getUrlControl('type=itemcustomer'. '&redirect='.$redirect)?>">
  <div class="card-body">
  <input type="text" value="<?= $id?>" name="id" hidden>
    <div class="row g-3">
      <!-- Nama Item -->
      <div class="col-md-6">
        <label for="item_id" class="form-label">Item</label>
        <select class="form-select" id="item_id" name="item_id" required>
          <option value="">-- Select Item --</option>
          <?php foreach ($items as $row): 
            if(is_object($row)){
                          $item = $row;
                        } else {
                              $item = new Item(
                $row['ID'],
                $row['NAME'],
                $row['REF_NO'],
                $row['PRICE']
            );
                        }
            ?>
           <option value="<?= $item->getId() ?>" <?= $item->getId() == $it ? 'selected' : '' ?> data-price="<?= $item->getPrice() ?>">
        <?= htmlspecialchars($item->getName()) ?>
      </option>
          <?php endforeach; ?>
        </select>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Nama Customer -->
      <div class="col-md-6">
        <label for="customer_id" class="form-label">Customer</label>
        <select class="form-select" id="customer_id" name="customer_id" required>
          <option value="">-- Select Customer --</option>
          <?php foreach ($customers as $row): 
            if(is_object($row)){
                          $customer = $row;
                        } else {
                          $customer = new Customer(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO'],
            $row['EMAIL'],
            $row['ALAMAT'],
            $row['TELEPON']
        );
      }
            ?>
            <option value="<?= $customer->getId() ?>" <?= $customer->getId() == $customer_id ? 'selected' : '' ?>>
        <?= htmlspecialchars($customer->getName()) ?>
      </option>
          <?php endforeach; ?>
        </select>
        <div class="valid-feedback">Looks good!</div>
      </div>

      <!-- Harga -->
      <div class="col-md-6">
        <label for="price" class="form-label">Price</label>
        <div class="input-group mb-3">
          <span class="input-group-text">Rp</span>
          <input type="text" class="form-control" id="price" value="<?= $price?>" required name="price" />
          <span class="input-group-text">.00</span>
        </div>
        <div class="valid-feedback">Looks good!</div>
      </div>
    </div>
  </div>

  <!-- Submit -->
  <div class="card-footer">
  <button type="submit" action="create" class="btn btn-info  float-end">Sumbit</button>
    <a href="<?= $url?>" class="btn btn-secondary">Cancel</a>
  </div>
</form>

</div>
</div>
</main>

    <!--end::App Main-->
    <!--begin::Footer-->
    <?php include __DIR__ . '/../widget/footer.php'; ?>
    <!--end::Footer-->
  </div>


  <script>
          // Example starter JavaScript for disabling form submissions if there are invalid fields
          (() => {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.from(forms).forEach((form) => {
              form.addEventListener(
                'submit',
                (event) => {
                  if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                  }

                  form.classList.add('was-validated');
                },
                false,
              );
            });
          })();
        </script>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="../js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <!--end::OverlayScrollbars Configure-->
  <!--end::Script-->
  <script const SELECTOR_SIDEBAR_WRAPPER='.sidebar-wrapper' ; const Default={ scrollbarTheme: 'os-theme-light' ,
  scrollbarAutoHide: 'leave' , scrollbarClickScroll: true, }; document.addEventListener('DOMContentLoaded', function
  () { const sidebarWrapper=document.querySelector(SELECTOR_SIDEBAR_WRAPPER); if (sidebarWrapper && typeof
  OverlayScrollbarsGlobal?.OverlayScrollbars !=='undefined' ) {
  OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, { scrollbars: { theme: Default.scrollbarTheme, autoHide:
  Default.scrollbarAutoHide, clickScroll: Default.scrollbarClickScroll, }, }); } });></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<!--end::Script-->
  <!--end::OverlayScrollbars Configure-->
  <!--end::Script-->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const itemSelect = document.getElementById('item_id');
    const priceInput = document.getElementById('price');

    itemSelect.addEventListener('change', function () {
      const selectedOption = itemSelect.options[itemSelect.selectedIndex];
      const price = selectedOption.getAttribute('data-price');

      if (price) {
        priceInput.value = price;
      } else {
        priceInput.value = '';
      }
    });
  });
</script>
<script 
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script>
</body>
<!--end::Body-->

</html>