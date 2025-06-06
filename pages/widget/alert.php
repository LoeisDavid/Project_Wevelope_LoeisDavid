<?php
// alert.php
// Include segera di dalam <body>, sebelum <div class="app-wrapper">,
// dan pastikan CSS Bootstrap sudah ter-load.


if (empty($_SESSION['alert']) && empty($_SESSION['alert_delete'])) {
    return;
}

$type    = !empty($_SESSION['alert']['type'])
           ? $_SESSION['alert']['type']
           : $_SESSION['alert_delete']['type'];
$message = !empty($_SESSION['alert']['message'])
           ? $_SESSION['alert']['message']
           : $_SESSION['alert_delete']['message'];

// unset setelah ambil
unset($_SESSION['alert'], $_SESSION['alert_delete']);
?>
<div id="flash-alert"
     class="alert alert-<?= htmlspecialchars($type) ?> fade show"
     role="alert"
     style="
       position: fixed;
       top: 1rem;
       left: 50%;
       transform: translateX(-50%);
       z-index: 99999;
       pointer-events: auto;
       max-width: 90%;
     ">
  <?= htmlspecialchars($message) ?>
  <button type="button" class="btn-close" aria-label="Close"></button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const flash = document.getElementById('flash-alert');
  if (!flash) return;

  // Close manual
  const btn = flash.querySelector('.btn-close');
  btn.addEventListener('click', () => flash.remove());

  // Auto‐close after 3 detik
  setTimeout(() => {
    if (flash.parentElement) flash.remove();
  }, 3000);
});
</script>
