<?php if (session()->getFlashdata('success')): ?>
  <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> <?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
