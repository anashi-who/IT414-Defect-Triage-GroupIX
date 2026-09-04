<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['admin']);

$pdo = db();
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'add_account') {
        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $roleCode = in_array($_POST['role'] ?? '', ['admin', 'staff', 'head'], true) ? $_POST['role'] : 'staff';
        if ($name !== '' && $username !== '') {
            try {
                $tempPassword = 'Passw0rd!';
                $hash = password_hash($tempPassword, PASSWORD_BCRYPT);
                $pdo->prepare('INSERT INTO users (role_id, name, username, password_hash, status) VALUES (?,?,?,?,"Active")')
                    ->execute([ROLE_CODE_TO_ID[$roleCode], $name, $username, $hash]);
                $flash = "Account created for $name. Temporary password: $tempPassword";
                audit('admin', $me['id'], $me['name'], "Created $roleCode account", $username);
            } catch (Throwable $e) {
                $flash = 'Could not create account (username may already be taken).';
            }
        }
    } elseif ($action === 'update_account') {
        $uid = (int) ($_POST['user_id'] ?? 0);
        $roleCode = in_array($_POST['role'] ?? '', ['admin', 'staff', 'head'], true) ? $_POST['role'] : null;
        $status = in_array($_POST['status'] ?? '', ['Active', 'Inactive'], true) ? $_POST['status'] : null;
        if ($uid && $roleCode && $status) {
            $pdo->prepare('UPDATE users SET role_id = ?, status = ? WHERE id = ?')
                ->execute([ROLE_CODE_TO_ID[$roleCode], $status, $uid]);
            audit('admin', $me['id'], $me['name'], "Set role=$roleCode status=$status", "user #$uid");
        }
    }
}

$accounts = $pdo->query('SELECT * FROM users ORDER BY created_at')->fetchAll();

$pageTitle = 'Manage Users';
require __DIR__ . '/../includes/internal_header.php';
?>
<div class="dash-shell">
  <div class="dash-top"><div class="wrap"><h1>User Accounts</h1><p>Create and manage internal accounts for Assessor's Staff, Admin, and Department Head.</p></div></div>
  <div class="wrap">
    <?php if ($flash): ?><div class="flash success"><?= esc($flash) ?></div><?php endif; ?>
    <form method="post" class="inline-add">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add_account">
      <div class="field"><label>Full Name</label><input type="text" name="name" placeholder="Full name"></div>
      <div class="field"><label>Username</label><input type="text" name="username" placeholder="username"></div>
      <div class="field"><label>Role</label>
        <select name="role"><option value="staff">Assessor's Staff</option><option value="admin">Admin</option><option value="head">Department Head</option></select>
      </div>
      <button type="submit" class="btn btn-primary"><?= icon_span('users') ?> Add Account</button>
    </form>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Name</th><th>Username</th><th colspan="2">Role (Level of Access) &amp; Status</th></tr></thead>
        <tbody>
        <?php foreach ($accounts as $a): $roleCode = ROLE_ID_TO_CODE[(int)$a['role_id']] ?? 'staff'; ?>
          <tr>
            <td><?= esc($a['name']) ?></td>
            <td class="mono"><?= esc($a['username']) ?></td>
            <td colspan="2">
              <form method="post" style="display:flex;gap:8px;align-items:center">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_account">
                <input type="hidden" name="user_id" value="<?= (int)$a['id'] ?>">
                <select name="role" class="mono">
                  <option value="staff" <?= $roleCode === 'staff' ? 'selected' : '' ?>>Assessor's Staff</option>
                  <option value="admin" <?= $roleCode === 'admin' ? 'selected' : '' ?>>Admin</option>
                  <option value="head" <?= $roleCode === 'head' ? 'selected' : '' ?>>Department Head</option>
                </select>
                <select name="status">
                  <option value="Active" <?= $a['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                  <option value="Inactive" <?= $a['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <button type="submit" class="icon-btn">Save</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
