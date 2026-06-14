<?php $pageTitle='Detail Laporan'; include __DIR__.'/_layout.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM reports WHERE id=?"); $stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) { echo '<div class="p-6 bg-red-50 text-red-700 rounded">Laporan tidak ditemukan.</div></main></body></html>'; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check();
    $newStatus = $_POST['status'] ?? $r['status'];
    $response  = trim($_POST['admin_response'] ?? '');
    $note      = trim($_POST['note'] ?? '');
    $valid = ['Diterima','Diproses','Selesai','Ditolak'];
    if (!in_array($newStatus,$valid,true)) $newStatus = $r['status'];

    $up = $pdo->prepare("UPDATE reports SET status=?, admin_response=? WHERE id=?");
    $up->execute([$newStatus, $response ?: null, $id]);

    if ($newStatus !== $r['status'] || $note !== '') {
        $pdo->prepare("INSERT INTO report_logs (report_id,status_change,note) VALUES (?,?,?)")
            ->execute([$id, $newStatus, $note ?: ('Status diubah ke '.$newStatus)]);
    }
    log_activity($pdo, 'Update Laporan', "ID {$r['report_uid']} -> $newStatus");
    header("Location: detail.php?id=$id&ok=1"); exit;
}

$logs = $pdo->prepare("SELECT * FROM report_logs WHERE report_id=? ORDER BY created_at ASC");
$logs->execute([$id]); $logs = $logs->fetchAll();
?>
<a href="laporan.php" class="inline-flex items-center gap-1 text-sm text-emerald-700 hover:text-emerald-900 font-semibold">
  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
  Kembali
</a>
<?php if (!empty($_GET['ok'])): ?>
<div class="bg-emerald-100 border border-emerald-300 text-emerald-800 p-3 rounded-xl my-3 text-sm flex items-center gap-2">
  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
  Laporan diperbarui.
</div>
<?php endif; ?>

<div class="grid md:grid-cols-3 gap-5 mt-3">
  <div class="md:col-span-2 bg-white border rounded-2xl p-5 shadow-sm">
    <div class="flex items-center gap-2 flex-wrap mb-3">
      <span class="font-mono text-sm text-gray-500"><?= e($r['report_uid']) ?></span>
      <span class="text-xs px-2 py-0.5 bg-gray-100 rounded-full"><?= e($r['category']) ?></span>
      <?= urgency_badge($r['urgency']) ?>
      <?= status_badge($r['status']) ?>
      <?php if ($r['category']==='Perundungan (Bullying)'): ?>
        <span class="text-xs px-2 py-0.5 bg-red-600 text-white rounded-full inline-flex items-center gap-1">
          <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          RAHASIA
        </span>
      <?php endif; ?>
    </div>
    <h2 class="font-bold mb-2 text-gray-800">Deskripsi</h2>
    <p class="whitespace-pre-line text-gray-800 text-sm bg-emerald-50/40 border border-emerald-100 p-4 rounded-xl"><?= e($r['description']) ?></p>
    <div class="text-xs text-gray-400 mt-3 flex flex-wrap gap-x-3 gap-y-1">
      <span>Dikirim <?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></span>
      <span>·</span><span>Anonim: <?= $r['is_anon']?'Ya':'Tidak' ?></span>
      <span>·</span><span>IP: <?= e($r['ip_address']) ?></span>
      <span>·</span><span>Upvotes: <?= (int)$r['upvotes'] ?></span>
    </div>

    <h3 class="font-bold mt-6 mb-3 text-gray-800">Riwayat</h3>
    <ol class="border-l-2 border-emerald-200 ml-2 space-y-3">
      <?php foreach($logs as $lg): ?>
        <li class="ml-4 relative">
          <span class="absolute -left-[1.35rem] top-1.5 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white badge-glow"></span>
          <div class="flex items-center gap-2"><?= status_badge($lg['status_change']) ?><span class="text-xs text-gray-400"><?= e(date('d/m H:i', strtotime($lg['created_at']))) ?></span></div>
          <?php if($lg['note']): ?><div class="text-sm text-gray-700 mt-1"><?= nl2br(e($lg['note'])) ?></div><?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>

  <form method="post" class="bg-white border rounded-2xl p-5 space-y-3 h-fit sticky top-4 shadow-sm">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <h3 class="font-bold text-gray-800 flex items-center gap-2">
      <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 113 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
      Tindak Lanjut
    </h3>
    <div>
      <label class="text-sm font-medium block mb-1">Ubah Status</label>
      <select name="status" class="w-full border-gray-300 border rounded-lg px-3 py-2 text-sm">
        <?php foreach(['Diterima','Diproses','Selesai','Ditolak'] as $s): ?>
          <option value="<?= $s ?>" <?= $r['status']===$s?'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="text-sm font-medium block mb-1">Respon ke Pelapor (publik)</label>
      <textarea name="admin_response" rows="4" class="w-full border-gray-300 border rounded-lg px-3 py-2 text-sm"><?= e($r['admin_response']) ?></textarea>
    </div>
    <div>
      <label class="text-sm font-medium block mb-1">Catatan Log (opsional)</label>
      <input type="text" name="note" placeholder="cth: meneruskan ke wakasek" class="w-full border-gray-300 border rounded-lg px-3 py-2 text-sm">
    </div>
    <button class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-2.5 rounded-lg shadow-md shadow-emerald-200">Simpan</button>
  </form>

  <form method="post" action="hapus.php" onsubmit="return confirm('Hapus permanen laporan <?= e($r['report_uid']) ?>? Tindakan ini tidak dapat dibatalkan.');" class="bg-white border border-red-200 rounded-2xl p-5 mt-4 h-fit">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
    <input type="hidden" name="back" value="laporan.php">
    <h3 class="font-bold text-red-700 flex items-center gap-2 mb-2">
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
      Zona Berbahaya
    </h3>
    <p class="text-xs text-gray-600 mb-3">Menghapus laporan akan menghilangkannya dari Papan Publik & sistem pelacakan secara permanen, termasuk semua riwayat status & upvote.</p>
    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg inline-flex items-center justify-center gap-2">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
      Hapus Laporan
    </button>
  </form>
</div>
</main></body></html>
