<?php $pageTitle='Dashboard'; include __DIR__.'/_layout.php';
$total = (int)$pdo->query("SELECT COUNT(*) FROM reports")->fetchColumn();
$baru = (int)$pdo->query("SELECT COUNT(*) FROM reports WHERE admin_response IS NULL OR admin_response=''")->fetchColumn();
$proses = (int)$pdo->query("SELECT COUNT(*) FROM reports WHERE status='Diproses'")->fetchColumn();
$selesai = (int)$pdo->query("SELECT COUNT(*) FROM reports WHERE status='Selesai'")->fetchColumn();
$bully = (int)$pdo->query("SELECT COUNT(*) FROM reports WHERE category='Perundungan (Bullying)' AND status<>'Selesai'")->fetchColumn();
$latest = $pdo->query("SELECT * FROM reports ORDER BY created_at DESC LIMIT 6")->fetchAll();
?>
<div class="flex items-center justify-between flex-wrap gap-3 mb-6">
  <div>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 flex items-center gap-2">
      Selamat datang, <span class="text-emerald-700"><?= e($_SESSION['admin_username']) ?></span>
      <svg class="w-7 h-7 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M5 12c.5-2 2-3 4-3l1-3 2 3c2 0 3.5 1 4 3l-1 2 1 3-3-1-2 2-2-2-3 1 1-3-2-2z"/></svg>
    </h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan sistem aspirasi sekolah hari ini.</p>
  </div>
  <div class="hidden sm:flex items-center gap-2 bg-white border rounded-xl px-4 py-2 text-sm shadow-sm">
    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
    <?= date('l, d M Y') ?>
  </div>
</div>

<?php if ($bully > 0): ?>
<a href="laporan.php?sensitif=1" class="group relative block overflow-hidden bg-gradient-to-r from-red-500 to-rose-600 text-white p-5 rounded-2xl mb-6 shadow-lg shadow-red-200">
  <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full"></div>
  <div class="absolute -right-10 bottom-0 w-24 h-24 bg-white/10 rounded-full"></div>
  <div class="relative flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center animate-pulse">
      <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4M12 17h.01"/></svg>
    </div>
    <div class="flex-1">
      <div class="font-bold text-lg"><?= $bully ?> Laporan Bullying menunggu penanganan</div>
      <div class="text-sm text-red-50">Mohon segera ditangani oleh Guru BK secara privat.</div>
    </div>
    <svg class="w-6 h-6 group-hover:translate-x-1 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
  </div>
</a>
<?php endif; ?>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="relative bg-white p-5 rounded-2xl border overflow-hidden card-hover">
    <div class="absolute top-0 right-0 w-20 h-20 bg-gray-100 rounded-full -mr-8 -mt-8"></div>
    <div class="relative">
      <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center mb-2">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/><path d="M3 9h18M9 21V9"/></svg>
      </div>
      <div class="text-xs text-gray-500">Total</div>
      <div class="text-3xl font-extrabold text-gray-800"><?= $total ?></div>
    </div>
  </div>
  <div class="relative bg-white p-5 rounded-2xl border overflow-hidden card-hover">
    <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-100 rounded-full -mr-8 -mt-8"></div>
    <div class="relative">
      <div class="w-9 h-9 rounded-lg bg-yellow-100 text-yellow-700 flex items-center justify-center mb-2">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      </div>
      <div class="text-xs text-gray-500">Belum Dibalas</div>
      <div class="text-3xl font-extrabold text-yellow-600"><?= $baru ?></div>
    </div>
  </div>
  <div class="relative bg-white p-5 rounded-2xl border overflow-hidden card-hover">
    <div class="absolute top-0 right-0 w-20 h-20 bg-blue-100 rounded-full -mr-8 -mt-8"></div>
    <div class="relative">
      <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center mb-2">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.2-8.55"/></svg>
      </div>
      <div class="text-xs text-gray-500">Diproses</div>
      <div class="text-3xl font-extrabold text-blue-600"><?= $proses ?></div>
    </div>
  </div>
  <div class="relative bg-white p-5 rounded-2xl border overflow-hidden card-hover">
    <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-100 rounded-full -mr-8 -mt-8"></div>
    <div class="relative">
      <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
      </div>
      <div class="text-xs text-gray-500">Selesai</div>
      <div class="text-3xl font-extrabold text-emerald-600"><?= $selesai ?></div>
    </div>
  </div>
</div>

<div class="bg-white rounded-2xl border p-5 shadow-sm">
  <div class="flex items-center justify-between mb-4">
    <h2 class="font-bold text-gray-800 flex items-center gap-2">
      <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      Laporan Terbaru
    </h2>
    <a href="laporan.php" class="text-xs font-semibold text-emerald-700 hover:underline">Lihat semua →</a>
  </div>
  <div class="space-y-2">
    <?php foreach ($latest as $r): ?>
      <a href="detail.php?id=<?= (int)$r['id'] ?>" class="flex items-center gap-3 p-3 hover:bg-emerald-50 rounded-xl border border-gray-100 transition">
        <span class="font-mono text-xs text-gray-500"><?= e($r['report_uid']) ?></span>
        <span class="text-xs px-2 py-0.5 bg-gray-100 rounded-full"><?= e($r['category']) ?></span>
        <span class="flex-1 truncate text-sm text-gray-700"><?= e(mb_substr($r['description'],0,80)) ?></span>
        <?= status_badge($r['status']) ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>
</main></body></html>
