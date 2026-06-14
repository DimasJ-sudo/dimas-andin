<?php $pageTitle='Manajemen Laporan'; include __DIR__.'/_layout.php';
$sensitif = !empty($_GET['sensitif']);
$status = $_GET['status'] ?? '';
$q = trim($_GET['q'] ?? '');

$where = []; $params = [];
if ($sensitif) { $where[] = "category = 'Perundungan (Bullying)'"; }
if ($status) { $where[] = "status = ?"; $params[] = $status; }
if ($q) { $where[] = "(description LIKE ? OR report_uid LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }

$sql = "SELECT * FROM reports";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY (status='Diterima') DESC, urgency='Tinggi' DESC, created_at DESC LIMIT 200";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<div class="flex items-center justify-between flex-wrap gap-3 mb-5">
  <h1 class="text-2xl font-extrabold flex items-center gap-2 <?= $sensitif?'text-red-700':'text-gray-800' ?>">
    <?php if($sensitif): ?>
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4M12 17h.01"/></svg>
      Laporan Sensitif (Bullying)
    <?php else: ?>
      <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 2h6a2 2 0 012 2v2H7V4a2 2 0 012-2z"/><rect x="5" y="6" width="14" height="16" rx="2"/></svg>
      Manajemen Laporan
    <?php endif; ?>
  </h1>
  <form method="get" class="flex gap-2 flex-wrap">
    <?php if ($sensitif): ?><input type="hidden" name="sensitif" value="1"><?php endif; ?>
    <div class="relative">
      <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari ID / kata kunci..." class="border-gray-300 border rounded-lg pl-9 pr-3 py-2 text-sm">
    </div>
    <select name="status" class="border-gray-300 border rounded-lg px-3 py-2 text-sm">
      <option value="">Semua status</option>
      <?php foreach(['Diterima','Diproses','Selesai','Ditolak'] as $s): ?>
        <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
    <button class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">Filter</button>
  </form>
</div>

<?php if ($sensitif): ?>
<div class="bg-red-50 border border-red-200 p-3 text-red-800 rounded-xl mb-4 text-sm flex items-start gap-2">
  <svg class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
  Laporan ini bersifat rahasia. Jangan dibagikan di luar Guru BK / OSIS yang berwenang.
</div>
<?php endif; ?>

<?php if (!empty($_GET['deleted'])): ?>
<div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-3 rounded-xl mb-4 text-sm flex items-center gap-2">
  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
  Laporan berhasil dihapus dari sistem.
</div>
<?php endif; ?>

<div class="bg-white border rounded-2xl overflow-hidden shadow-sm overflow-x-auto">
  <table class="w-full text-sm min-w-[760px]">
    <thead class="bg-gradient-to-r from-emerald-50 to-teal-50 text-left text-xs uppercase text-emerald-800">
      <tr><th class="p-3">ID</th><th class="p-3">Kategori</th><th class="p-3">Deskripsi</th><th class="p-3">Urgensi</th><th class="p-3">Status</th><th class="p-3">Tgl</th><th></th></tr>
    </thead>
    <tbody class="divide-y">
    <?php if (!$rows): ?>
      <tr><td colspan="7" class="text-center text-gray-400 p-8">Tidak ada laporan.</td></tr>
    <?php endif; ?>
    <?php foreach($rows as $r): ?>
      <tr class="hover:bg-emerald-50/40 <?= $r['category']==='Perundungan (Bullying)'?'bg-red-50/50':'' ?>">
        <td class="p-3 font-mono text-xs"><?= e($r['report_uid']) ?></td>
        <td class="p-3"><span class="text-xs px-2 py-0.5 bg-gray-100 rounded-full"><?= e($r['category']) ?></span></td>
        <td class="p-3 max-w-xs truncate"><?= e(mb_substr($r['description'],0,100)) ?></td>
        <td class="p-3"><?= urgency_badge($r['urgency']) ?></td>
        <td class="p-3"><?= status_badge($r['status']) ?></td>
        <td class="p-3 text-xs text-gray-500"><?= e(date('d/m H:i', strtotime($r['created_at']))) ?></td>
        <td class="p-3">
          <div class="flex items-center gap-3">
            <a href="detail.php?id=<?= (int)$r['id'] ?>" class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-900 font-semibold">
              Detail
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
            <form method="post" action="hapus.php" onsubmit="return confirm('Hapus laporan <?= e($r['report_uid']) ?>? Tindakan ini tidak dapat dibatalkan.');">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <input type="hidden" name="back" value="laporan.php<?= !empty($_GET['sensitif'])?'?sensitif=1':'' ?>">
              <button type="submit" title="Hapus laporan" class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 font-semibold">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                Hapus
              </button>
            </form>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</main></body></html>
