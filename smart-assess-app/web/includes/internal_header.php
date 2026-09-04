<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/icons.php';

/**
 * Header for the INTERNAL MUNICIPAL OFFICE PORTAL only — used by
 * /internal/login.php and everything under /staff/*, /admin/*,
 * /department-head/*. Nothing in the public client portal links here;
 * this is reached only via its own direct URL. See includes/auth.php for
 * the server-side enforcement — this header is presentation only.
 */
$pageTitle = $pageTitle ?? 'SMART ASSESS';
$user = current_user();
$dashHref = internal_dashboard_path($user['role'] ?? null);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($pageTitle) ?> · SMART ASSESS Internal Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Public+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header portal-header"><div class="wrap">
  <a class="brand" href="<?= esc($dashHref ?? '/internal/login.php') ?>">
    <?= brand_mark() ?>
    <span><span class="brand-name">Mabini Assessor Office</span><span class="portal-tag">Internal Portal</span></span>
  </a>
  <div class="header-actions">
    <?php if ($user): ?>
      <span class="role-tag"><?= icon_span('key', '14px') ?> <?= esc(role_label($user['role'])) ?> &middot; <?= esc($user['name']) ?></span>
      <a class="btn btn-ghost" href="<?= esc($dashHref) ?>"><?= icon_span('grid') ?> Dashboard</a>
      <a class="btn btn-ghost" href="/internal/logout.php"><?= icon_span('logout') ?> Log Out</a>
    <?php else: ?>
      <span class="role-tag"><?= icon_span('id', '14px') ?> Internal Staff Access Only</span>
      <a class="btn btn-ghost" href="/index.php"><?= icon_span('home', '14px') ?> View Public Site</a>
    <?php endif; ?>
  </div>
</div></header>
