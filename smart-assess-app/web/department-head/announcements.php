<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['head']);

$pdo = db();
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if ($title !== '' && $body !== '') {
        $pdo->prepare('INSERT INTO announcements (title, body, author) VALUES (?,?,?)')
            ->execute([$title, $body, $me['name']]);
        audit('head', $me['id'], $me['name'], 'Sent announcement', $title);
        $flash = "Announcement sent to Admin and Assessor's Staff.";
    }
}

$announcements = $pdo->query('SELECT * FROM announcements ORDER BY created_at DESC')->fetchAll();

$pageTitle = 'Announcements';
require __DIR__ . '/../includes/internal_header.php';
?>
<div class="dash-shell">
  <div class="dash-top"><div class="wrap"><h1>Announcements</h1><p>Send office-wide notices to Admin and Assessor's Staff.</p></div></div>
  <div class="wrap" style="max-width:720px">
    <?php if ($flash): ?><div class="flash success"><?= esc($flash) ?></div><?php endif; ?>
    <form method="post" class="compose-form">
      <?= csrf_field() ?>
      <div class="field"><label>Title</label><input type="text" name="title" placeholder="e.g. Office schedule update"></div>
      <div class="field"><label>Message</label><textarea name="body" rows="4" placeholder="Write the announcement for admin and staff..."></textarea></div>
      <div><button type="submit" class="btn btn-primary"><?= icon_span('send') ?> Send Announcement</button></div>
    </form>
    <?php if (!$announcements): ?><div class="empty-state">No announcements sent yet.</div>
    <?php else: foreach ($announcements as $a): ?>
      <div class="announcement-card"><h4><?= esc($a['title']) ?></h4><div class="ac-meta"><?= esc($a['author']) ?> &middot; <?= fmt_datetime($a['created_at']) ?></div><p><?= esc($a['body']) ?></p></div>
    <?php endforeach; endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
