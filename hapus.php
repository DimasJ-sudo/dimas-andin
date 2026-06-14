<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle)?e($pageTitle).' — ':''; ?>Admin Kotak Suara</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body{font-family:'Plus Jakarta Sans',system-ui,sans-serif}
  .side-link{display:flex;align-items:center;gap:.65rem;padding:.6rem .8rem;border-radius:.6rem;color:#d1fae5;font-weight:500}
  .side-link:hover{background:rgba(255,255,255,.08);color:#fff}
  .side-link.active{background:linear-gradient(90deg,rgba(16,185,129,.35),rgba(20,184,166,.25));color:#fff;box-shadow:inset 3px 0 0 #34d399}
  .side-link svg{width:1.1rem;height:1.1rem;flex-shrink:0}
</style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-emerald-50/60 min-h-screen flex">
<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="w-64 bg-gradient-to-b from-emerald-900 via-emerald-800 to-teal-900 text-emerald-50 min-h-screen p-4 hidden md:flex md:flex-col">
  <div class="flex items-center gap-2 mb-8 px-2 pt-2">
    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg">
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 11l18-8-8 18-2-8-8-2z"/></svg>
    </div>
    <div>
      <div class="text-lg font-extrabold leading-tight">Kotak Suara</div>
      <div class="text-[10px] uppercase tracking-widest text-emerald-300">Admin Panel</div>
    </div>
  </div>
  <nav class="space-y-1 text-sm flex-1">
    <a href="dashboard.php" class="side-link <?= $cur==='dashboard.php'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
      Dashboard
    </a>
    <a href="laporan.php" class="side-link <?= ($cur==='laporan.php' && empty($_GET['sensitif']))?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 2h6a2 2 0 012 2v2H7V4a2 2 0 012-2z"/><rect x="5" y="6" width="14" height="16" rx="2"/><path d="M9 12h6M9 16h4"/></svg>
      Manajemen Laporan
    </a>
    <a href="laporan.php?sensitif=1" class="side-link <?= !empty($_GET['sensitif'])?'active':'' ?>" style="<?= empty($_GET['sensitif'])?'background:rgba(239,68,68,.15)':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fca5a5" stroke-width="2"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
      <span class="text-red-100">Laporan Sensitif</span>
    </a>
    <a href="aktivitas.php" class="side-link <?= $cur==='aktivitas.php'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 4 4 5-5"/></svg>
      Log Aktivitas
    </a>
  </nav>
  <a href="logout.php" class="side-link mt-4 border-t border-emerald-700/60 pt-4 !rounded-none hover:!bg-red-500/20">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
    Logout
  </a>
</aside>
<main class="flex-1 p-4 sm:p-8 overflow-x-hidden">
  <div class="md:hidden bg-gradient-to-r from-emerald-800 to-teal-800 text-white -m-4 mb-4 p-3 flex gap-2 text-xs overflow-x-auto rounded-b-xl shadow">
    <a href="dashboard.php" class="px-3 py-1.5 bg-white/10 rounded-lg">Dashboard</a>
    <a href="laporan.php" class="px-3 py-1.5 bg-white/10 rounded-lg">Laporan</a>
    <a href="laporan.php?sensitif=1" class="px-3 py-1.5 bg-red-500/30 rounded-lg">Sensitif</a>
    <a href="aktivitas.php" class="px-3 py-1.5 bg-white/10 rounded-lg">Log</a>
    <a href="logout.php" class="px-3 py-1.5 bg-black/20 rounded-lg ml-auto">Logout</a>
  </div>
