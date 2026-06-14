<?php $pageTitle='Log Aktivitas'; include __DIR__.'/_layout.php';
$logs = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 300")->fetchAll();
?>
<h1 class="text-2xl font-bold mb-5">Log Aktivitas Global</h1>
<div class="bg-white border rounded-xl overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
      <tr><th class="p-3">Waktu</th><th class="p-3">Admin</th><th class="p-3">Aksi</th><th class="p-3">Detail</th></tr>
    </thead>
    <tbody class="divide-y">
    <?php if (!$logs): ?><tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada aktivitas.</td></tr><?php endif; ?>
    <?php foreach($logs as $l): ?>
      <tr>
        <td class="p-3 text-xs text-gray-500 whitespace-nowrap"><?= e(date('d M Y H:i:s', strtotime($l['created_at']))) ?></td>
        <td class="p-3 font-medium"><?= e($l['admin_username'] ?? 'system') ?></td>
        <td class="p-3"><span class="text-xs px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded"><?= e($l['action']) ?></span></td>
        <td class="p-3 text-gray-700"><?= e($l['detail']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</main></body></html>
