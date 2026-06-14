<?php
session_start();
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';
if (!empty($_SESSION['admin_id'])) log_activity($pdo, 'Logout', 'Admin keluar');
$_SESSION = []; session_destroy();
header('Location: ../index.php'); exit;
