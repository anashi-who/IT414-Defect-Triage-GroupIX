<?php
require_once __DIR__ . '/../includes/client_auth.php';
$client = require_client();

$stmt = db()->prepare(
    'SELECT l.status, l.sms_body, l.created_at, r.reference_no
     FROM request_status_log l
     JOIN requests r ON r.id = l.request_id
     WHERE r.client_id = ?
     ORDER BY l.created_at DESC'
);
$stmt->execute([$client['id']]);
$notes = $stmt->fetchAll();

$pageTitle = 'Notifications';
require __DIR__ . '/../includes/client_header.php';
?>
<div class="dash-shell">
  <div class="dash-top"><div class="wrap"><h1>Notifications</h1><p>Status updates sent for all of your requests (also delivered by SMS to your contact number).</p></div></div>
  <div class="wrap">
    <div class="panel-card">
      <?php if (!$notes): ?>
        <p style="font-size:13px;color:var(--ink-faint)">No notifications yet.</p>
      <?php else: foreach ($notes as $n): ?>
        <div class="sms-item"><strong class="mono"><?= esc($n['reference_no']) ?></strong> &middot; <?= esc($n['sms_body']) ?><div class="sms-when"><?= fmt_datetime($n['created_at']) ?></div></div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
