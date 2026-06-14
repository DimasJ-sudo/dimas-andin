<?php
session_start();
require_once __DIR__ . '/includes/koneksi.php';
require_once __DIR__ . '/includes/helpers.php';
$pageTitle = 'Kirim Laporan';

$errors = [];
$success_uid = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $urgency = trim($_POST['urgency'] ?? 'Sedang');
    $is_anon = isset($_POST['is_anon']) ? 1 : 0;

    $valid_cat = ['Fasilitas Rusak','Perundungan (Bullying)','Keamanan','Kantin','Akademik','Lainnya'];
    $valid_urg = ['Rendah','Sedang','Tinggi'];

    if (!in_array($category, $valid_cat, true)) $errors[] = 'Kategori tidak valid.';
    if (mb_strlen($description) < 10) $errors[] = 'Deskripsi minimal 10 karakter.';
    if (mb_strlen($description) > 2000) $errors[] = 'Deskripsi maksimal 2000 karakter.';
    if (!in_array($urgency, $valid_urg, true)) $errors[] = 'Urgensi tidak valid.';

    $ip = get_client_ip();
    if (!check_spam_limit($pdo, $ip, 3)) {
        $errors[] = 'Batas 3 laporan per hari per perangkat telah tercapai.';
    }

    if (!$errors) {
        $description_clean = filter_kasar($description);
        $uid = generate_report_uid($pdo);
        $is_public = ($category === 'Perundungan (Bullying)' || $is_anon === 1) ? 0 : 1;

        $stmt = $pdo->prepare("INSERT INTO reports (report_uid, category, description, urgency, is_anon, ip_address, is_public) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$uid, $category, $description_clean, $urgency, $is_anon, $ip, $is_public]);
        $rid = (int)$pdo->lastInsertId();

        $stmtL = $pdo->prepare("INSERT INTO report_logs (report_id, status_change, note) VALUES (?, 'Diterima', 'Laporan masuk ke sistem.')");
        $stmtL->execute([$rid]);

        $success_uid = $uid;
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="max-w-2xl mx-auto">
  <div class="flex items-center gap-3 mb-5">
    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-md shadow-emerald-200">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 113 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
    </div>
    <div>
      <h1 class="text-2xl font-extrabold text-emerald-900">Kirim Laporan</h1>
      <p class="text-sm text-gray-600">Ceritakan masalah atau aspirasimu. Identitasmu aman.</p>
    </div>
  </div>

  <?php if ($success_uid): ?>
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-300 text-emerald-900 p-5 rounded-2xl mb-4 shadow-sm">
      <div class="flex items-center gap-2 font-bold mb-2">
        <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        Laporan berhasil dikirim!
      </div>
      <div class="text-sm">Simpan ID unik berikut untuk melacak progres laporanmu:</div>
      <div class="mt-2 text-3xl font-mono font-extrabold tracking-wider text-emerald-700"><?= e($success_uid) ?></div>
      <a href="lacak.php?id=<?= urlencode($success_uid) ?>" class="inline-flex items-center gap-1 mt-3 text-sm bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 font-semibold">
        Lacak Sekarang
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
    </div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl mb-4 text-sm">
      <ul class="list-disc list-inside"><?php foreach($errors as $er) echo '<li>'.e($er).'</li>'; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" class="bg-white border border-emerald-100 rounded-2xl p-5 sm:p-6 space-y-5 shadow-sm">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div>
      <label class="block text-sm font-semibold mb-1.5 text-gray-700">Kategori Masalah</label>
      <select name="category" required class="w-full border-gray-300 rounded-lg px-3 py-2.5 border focus:ring-emerald-500 focus:border-emerald-500">
        <option value="">— Pilih kategori —</option>
        <?php foreach(['Fasilitas Rusak','Perundungan (Bullying)','Keamanan','Kantin','Akademik','Lainnya'] as $c): ?>
          <option value="<?= e($c) ?>"><?= e($c) ?></option>
        <?php endforeach; ?>
      </select>
      <p class="text-xs text-gray-500 mt-1.5 flex items-start gap-1">
        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        Kategori <b>Bullying</b> hanya bisa dilihat Guru BK / OSIS — tidak ditampilkan publik.
      </p>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5 text-gray-700">Deskripsi Masalah</label>
      <textarea name="description" rows="5" maxlength="2000" required placeholder="Ceritakan detail masalahnya: apa, di mana, kapan..." class="w-full border-gray-300 rounded-lg px-3 py-2.5 border focus:ring-emerald-500 focus:border-emerald-500"></textarea>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-2 text-gray-700">Tingkat Urgensi</label>
      <div class="grid grid-cols-3 gap-2">
        <?php
          $urgs = [
            'Rendah'=>['from'=>'gray-100','text'=>'gray-700','icon'=>'<path d="M12 5v14M5 12h14"/>'],
            'Sedang'=>['from'=>'amber-100','text'=>'amber-700','icon'=>'<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>'],
            'Tinggi'=>['from'=>'red-100','text'=>'red-700','icon'=>'<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4M12 17h.01"/>'],
          ];
          foreach($urgs as $u=>$cfg):
        ?>
          <label class="cursor-pointer">
            <input type="radio" name="urgency" value="<?= $u ?>" class="peer sr-only" <?= $u==='Sedang'?'checked':'' ?>>
            <div class="text-center py-3 rounded-xl border-2 border-gray-200 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 peer-checked:text-emerald-800 font-semibold text-sm transition">
              <svg class="w-5 h-5 mx-auto mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $cfg['icon'] ?></svg>
              <?= $u ?>
            </div>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <label class="flex items-start gap-3 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 p-4 rounded-xl cursor-pointer">
      <input type="checkbox" name="is_anon" checked class="mt-1 accent-emerald-600 w-4 h-4">
      <div class="flex-1">
        <div class="font-semibold text-sm text-emerald-900 flex items-center gap-1.5">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          Kirim sebagai Anonim
        </div>
        <div class="text-xs text-emerald-700/80">Identitasmu disembunyikan & laporan tidak ditampilkan di Papan Publik (hanya dilihat Admin). Hilangkan centang jika ingin tampil di papan transparansi.</div>
      </div>
    </label>

    <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 inline-flex items-center justify-center gap-2">
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
      Kirim Laporan
    </button>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
