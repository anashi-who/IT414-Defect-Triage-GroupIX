<?php
/**
 * CLIENT authentication (Assessor's Clients / residents) only. Mirrors
 * includes/auth.php in shape but is entirely independent of it: separate
 * session key (`client`, not `internal_user`), separate table (`clients`,
 * not `users`), separate password set. A client session can never satisfy
 * require_role() on the internal side, and an internal session can never
 * satisfy require_client() here — there is no shared code path where a
 * bug could blur the two.
 */
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_client(): ?array
{
    return $_SESSION['client'] ?? null;
}

function register_client(string $firstName, string $lastName, string $email, string $contact, string $password): array
{
    $email = strtolower(trim($email));
    $existing = db()->prepare('SELECT id FROM clients WHERE email = ?');
    $existing->execute([$email]);
    if ($existing->fetch()) {
        return ['ok' => false, 'error' => 'An account with that email already exists. Try logging in instead.'];
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = db()->prepare('INSERT INTO clients (first_name, last_name, email, contact_number, password_hash) VALUES (?,?,?,?,?)');
    $stmt->execute([$firstName, $lastName, $email, $contact ?: null, $hash]);
    $id = (int) db()->lastInsertId();

    $_SESSION['client'] = ['id' => $id, 'first_name' => $firstName, 'last_name' => $lastName, 'email' => $email];
    audit('client', $id, "$firstName $lastName", 'Registered a client account');
    return ['ok' => true];
}

function login_client(string $email, string $password): ?array
{
    $stmt = db()->prepare('SELECT * FROM clients WHERE email = ? LIMIT 1');
    $stmt->execute([strtolower(trim($email))]);
    $client = $stmt->fetch();

    if (!$client || $client['status'] !== 'Active') return null;
    if (!password_verify($password, $client['password_hash'])) return null;

    $_SESSION['client'] = [
        'id' => (int) $client['id'],
        'first_name' => $client['first_name'],
        'last_name' => $client['last_name'],
        'email' => $client['email'],
        'contact_number' => $client['contact_number'],
    ];
    audit('client', $client['id'], $client['first_name'] . ' ' . $client['last_name'], 'Logged in to client portal');
    return $_SESSION['client'];
}

function logout_client(): void
{
    $c = current_client();
    if ($c) audit('client', $c['id'], $c['first_name'] . ' ' . $c['last_name'], 'Logged out of client portal');
    unset($_SESSION['client']);
    session_regenerate_id(true);
}

/** Redirects to the CLIENT login (never an internal one) if not signed in. */
function require_client(): array
{
    $client = current_client();
    if (!$client) {
        header('Location: /login.php');
        exit;
    }
    return $client;
}
