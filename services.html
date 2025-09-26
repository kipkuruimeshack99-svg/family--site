<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reset password.html'); // or reset_password.html if you renamed it
    exit;
}

$token = $_POST['token'] ?? '';
$newpass = $_POST['password'] ?? '';

if ($token === '' || strlen($newpass) < 6) {
    $msg = rawurlencode('Invalid token or password too short.');
    // include token in redirect so the user can try again
    header("Location: reset%20password.html?token=" . rawurlencode($token) . "&status=error&msg=$msg");
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM family_members WHERE reset_token = ? AND reset_expires > NOW() LIMIT 1');
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $msg = rawurlencode('Invalid or expired token.');
    header("Location: reset%20password.html?status=error&msg=$msg");
    exit;
}

$hash = password_hash($newpass, PASSWORD_DEFAULT);
$pdo->prepare('UPDATE family_members SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?')
    ->execute([$hash, $user['id']]);

$msg = rawurlencode('Password reset successful. You can now login.');
header("Location: login.html?status=success&msg=$msg");
exit;
