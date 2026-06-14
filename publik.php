<?php
session_start();
require_once __DIR__ . '/includes/koneksi.php';
require_once __DIR__ . '/includes/helpers.php';
$pageTitle = 'Papan Transparansi';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upvote_id'])) {
    csrf_check();
    $rid = (int)$_POST['upvote_id'];
    $ip = get_client_ip();
    try {
        $pdo->beginTransaction();
        $ins = $pdo->prepare("INSERT INTO report_votes (report_id, ip_address) VALUES (?,?)");
        $ins->execute([$rid, $ip]);
        $upd = $pdo->prepare("UPDATE reports SET upvotes = upvotes + 1 WHERE id = ? AND is_public = 1 AND category <> 'Perundungan (Bullying)'");
        $upd->execute([$rid]);
        $pdo->commit();
    } catch (PDOException $e) { $pdo->rollBack(); }
    header('Location: publik.php'); exit;
}

$cat = $_GET['cat'] ?? '';
$sql = "SELECT * FROM reports WHERE is_public = 1 AND category <> 'Perundungan (Bullying)'";
$params = [];
if ($cat) { $sql .= " AND category = ?"; $params[] = $cat; }
$sql .= " ORDER BY upvotes DESC, created_at DESC LIMIT 100";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$reports = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="flex items-end justify-between flex-wrap gap-3 mb-5">
  <div class="flex items-center gap-3">
    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-md shadow-amber-200">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 4 4 5-5"/></svg>
    </div>
    <div>
      <h1 class="text-2xl font-extrabold text-emerald-900">Papan Transparansi</h1>
      <p class="text-sm text-gray-600">Aspirasi terverifikasi. Upvote yang juga kamu rasakan.</p>
    </div>
  </div>
  <form method="get" class="flex gap-2">
    <select name="cat" onchange="this.form.submit()" class="border-gray-300 rounded-lg px-3 py-2 border text-sm bg-white shadow-sm">
      <option value="">Semua kategori</option>
      <?php foreach(['Fasilitas Rusak','Keamanan','Kantin','Akademik','Lainnya'] as $c): ?>
        <option value="<?= e($c) ?>" <?= $cat===$c?'selected':'' ?>><?= e($c) ?></option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<?php if (!empty($_GET['deleted'])): ?>
<div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-3 rounded-xl mb-4 text-sm flex items-center gap-2">
  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
  Laporan dihapus dari papan & sistem.
</div>
<?php endif; ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<?php if (!$reports): ?>
  <div class="col-span-full bg-white border border-emerald-100 rounded-2xl p-10 text-center text-gray-500">
    <svg class="w-12 h-12 mx-auto mb-2 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
    Belum ada laporan publik.
  </div>
<?php endif; ?>
<?php foreach ($reports as $r): ?>
  <article class="bg-white border border-emerald-100 rounded-2xl p-5 flex gap-4 card-hover">
    <form method="post" class="flex flex-col items-center">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="upvote_id" value="<?= (int)$r['id'] ?>">
      <button type="submit" title="Upvote" class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 hover:from-emerald-500 hover:to-teal-500 hover:text-white text-emerald-700 flex items-center justify-center transition shadow-sm border border-emerald-200">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
      </button>
      <div class="text-sm font-extrabold text-emerald-700 mt-1.5"><?= (int)$r['upvotes'] ?></div>
    </form>
    <div class="flex-1 min-w-0">
      <div class="flex items-center gap-2 flex-wrap mb-1.5">
        <span class="text-xs font-mono text-gray-500"><?= e($r['report_uid']) ?></span>
        <span class="text-xs px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full"><?= e($r['category']) ?></span>
        <?= urgency_badge($r['urgency']) ?>
        <?= status_badge($r['status']) ?>
      </div>
      <p class="text-sm text-gray-800 line-clamp-4 whitespace-pre-line"><?= e($r['description']) ?></p>
      <?php if (!empty($r['admin_response'])): ?>
        <div class="mt-2 text-xs bg-emerald-50 border-l-4 border-emerald-500 p-2.5 text-emerald-900 rounded">
          <b>Respon Admin:</b> <?= e($r['admin_response']) ?>
        </div>
      <?php endif; ?>
      <div class="text-xs text-gray-400 mt-2 flex items-center justify-between gap-2 flex-wrap">
        <span class="inline-flex items-center gap-1">
          <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          <?= e(date('d M Y H:i', strtotime($r['created_at']))) ?>
        </span>
        <?php if (!empty($_SESSION['admin_id'])): ?>
          <form method="post" action="admin/hapus.php" onsubmit="return confirm('Hapus laporan <?= e($r['report_uid']) ?> dari papan & sistem?');">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <input type="hidden" name="back" value="../publik.php">
            <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 font-semibold">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
              Hapus
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </article>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
