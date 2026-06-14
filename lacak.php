<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — Kotak Suara' : 'Kotak Suara' ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body{font-family:'Plus Jakarta Sans',system-ui,sans-serif}
  .blob-bg{
    background-image:
      radial-gradient(circle at 10% 10%, rgba(16,185,129,.18), transparent 40%),
      radial-gradient(circle at 90% 20%, rgba(20,184,166,.15), transparent 45%),
      radial-gradient(circle at 50% 100%, rgba(59,130,246,.10), transparent 50%);
  }
  .grid-pattern{
    background-image:linear-gradient(rgba(16,185,129,.07) 1px,transparent 1px),
      linear-gradient(90deg,rgba(16,185,129,.07) 1px,transparent 1px);
    background-size:32px 32px;
  }
  .card-hover{transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease}
  .card-hover:hover{transform:translateY(-3px);box-shadow:0 14px 30px -12px rgba(5,150,105,.25)}
  .badge-glow{box-shadow:0 0 0 4px rgba(16,185,129,.12)}
  .fade-in{animation:fi .5s ease both}
  @keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
</style>
</head>
<body class="bg-emerald-50/40 min-h-screen text-gray-800 blob-bg">
<nav class="bg-white/90 backdrop-blur border-b border-emerald-100 shadow-sm sticky top-0 z-40">
  <div class="max-w-6xl mx-auto px-3 sm:px-4 py-2.5 sm:py-3 flex items-center justify-between gap-2">
    <a href="index.php" class="flex items-center gap-2 font-extrabold text-emerald-700 text-base sm:text-lg shrink-0">
      <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-md shadow-emerald-300/50 shrink-0">
        <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-8-8 18-2-8-8-2z"/></svg>
      </span>
      <span class="whitespace-nowrap">Kotak<span class="text-teal-500"> Suara</span></span>
    </a>
    <input type="checkbox" id="navtoggle" class="hidden peer">
    <label for="navtoggle" class="md:hidden p-2 rounded-lg text-emerald-700 hover:bg-emerald-50 cursor-pointer shrink-0" aria-label="Menu">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </label>
    <div class="hidden peer-checked:flex md:flex flex-col md:flex-row items-stretch md:items-center gap-1 md:gap-2 text-sm absolute md:static top-full left-0 right-0 md:top-auto bg-white md:bg-transparent border-b md:border-0 border-emerald-100 p-3 md:p-0 shadow-lg md:shadow-none">
      <a href="index.php" class="px-3 py-2 rounded-lg hover:bg-emerald-50 text-gray-700 font-medium">Beranda</a>
      <a href="lapor.php" class="px-3 py-2 rounded-lg hover:bg-emerald-50 text-gray-700 font-medium">Lapor</a>
      <a href="publik.php" class="px-3 py-2 rounded-lg hover:bg-emerald-50 text-gray-700 font-medium">Papan</a>
      <a href="lacak.php" class="px-3 py-2 rounded-lg hover:bg-emerald-50 text-gray-700 font-medium">Lacak</a>
      <a href="admin/login.php" class="md:ml-1 px-3 py-2 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 font-semibold inline-flex items-center justify-center gap-1.5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 11a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0"/></svg>
        Admin
      </a>
    </div>
  </div>
</nav>
<main class="max-w-6xl mx-auto px-4 py-6 fade-in">
