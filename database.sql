<?php
// Helper functions

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function get_client_ip() {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = explode(',', $_SERVER[$k])[0];
            return trim($ip);
        }
    }
    return '0.0.0.0';
}

// Filter kata kasar sederhana - sensor menjadi ***
function filter_kasar($text) {
    $bad = ['anjing','bangsat','bajingan','kontol','memek','tolol','goblok','idiot','asu','babi','jancok','ngentot','bego','sialan'];
    foreach ($bad as $w) {
        $text = preg_replace('/\b' . preg_quote($w, '/') . '\b/i', str_repeat('*', strlen($w)), $text);
    }
    return $text;
}

function generate_report_uid($pdo) {
    // Format: SK-MMDD-XX
    $prefix = 'SK-' . date('md') . '-';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE report_uid LIKE ?");
    $stmt->execute([$prefix . '%']);
    $n = (int)$stmt->fetchColumn() + 1;
    return $prefix . str_pad($n, 2, '0', STR_PAD_LEFT);
}

function status_badge($status) {
    $map = [
        'Diterima' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'Diproses' => 'bg-blue-100 text-blue-800 border-blue-300',
        'Selesai'  => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        'Ditolak'  => 'bg-red-100 text-red-800 border-red-300',
    ];
    $cls = $map[$status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $cls . '">' . e($status) . '</span>';
}

function urgency_badge($u) {
    $map = [
        'Rendah' => 'bg-gray-100 text-gray-700',
        'Sedang' => 'bg-amber-100 text-amber-800',
        'Tinggi' => 'bg-red-100 text-red-800',
    ];
    $cls = $map[$u] ?? 'bg-gray-100 text-gray-700';
    return '<span class="px-2 py-0.5 rounded text-xs font-semibold ' . $cls . '">' . e($u) . '</span>';
}

function log_activity($pdo, $action, $detail = null) {
    $username = $_SESSION['admin_username'] ?? null;
    $adminId  = $_SESSION['admin_id'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, admin_username, action, detail) VALUES (?, ?, ?, ?)");
    $stmt->execute([$adminId, $username, $action, $detail]);
}

function check_spam_limit($pdo, $ip, $limit = 3) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE ip_address = ? AND DATE(created_at) = CURDATE()");
    $stmt->execute([$ip]);
    return ((int)$stmt->fetchColumn()) < $limit;
}

function require_admin() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_check() {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(400);
        die('CSRF token tidak valid.');
    }
}
