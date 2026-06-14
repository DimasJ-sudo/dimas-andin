<?php
session_start();
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: laporan.php'); exit;
}
csrf_check();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { header('Location: laporan.php'); exit; }

$stmt = $pdo->prepare("SELECT report_uid FROM reports WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if ($row) {
    // Hapus relasi terlebih dahulu (jaga-jaga jika FK CASCADE belum aktif)
    $pdo->prepare("DELETE FROM report_logs  WHERE report_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM report_votes WHERE report_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM reports      WHERE id = ?")->execute([$id]);
    log_activity($pdo, 'Hapus Laporan', "ID {$row['report_uid']} dihapus dari sistem");
}

$back = $_POST['back'] ?? 'laporan.php';
// hanya izinkan path relatif sederhana (tanpa skema/host)
if (!preg_match('#^[A-Za-z0-9_./\-?=&]+$#', $back) || str_contains($back, '://')) $back = 'laporan.php';
header('Location: ' . $back . (strpos($back,'?')===false?'?':'&') . 'deleted=1');
exit;
