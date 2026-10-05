<?php
session_start();
require_once __DIR__ . '/../config/database.php';

/** Escape output HTML (pencegah XSS) */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Flash message: simpan di session, tampil sekali setelah redirect */
function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function format_rupiah($n): string
{
    return 'Rp ' . number_format((float) $n, 0, ',', '.');
}

/** Ambil semua kategori / supplier untuk dropdown (tanpa input user) */
function get_categories(PDO $pdo): array
{
    return $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
}

function get_suppliers(PDO $pdo): array
{
    return $pdo->query('SELECT id, name FROM suppliers ORDER BY name')->fetchAll();
}

/**
 * Validasi + cast input form produk.
 * Return [data, errors].
 */
function validate_product(array $post): array
{
    $data = [
        'name'        => trim($post['name'] ?? ''),
        'category_id' => (int) ($post['category_id'] ?? 0),
        'supplier_id' => (int) ($post['supplier_id'] ?? 0),
        'price'       => (float) ($post['price'] ?? 0),
        'stock'       => (int) ($post['stock'] ?? 0),
    ];
    $errors = [];

    if ($data['name'] === '') {
        $errors[] = 'Nama produk wajib diisi.';
    } elseif (mb_strlen($data['name']) > 150) {
        $errors[] = 'Nama produk maksimal 150 karakter.';
    }
    if ($data['category_id'] <= 0) {
        $errors[] = 'Kategori wajib dipilih.';
    }
    if ($data['supplier_id'] <= 0) {
        $errors[] = 'Supplier wajib dipilih.';
    }
    if (!is_numeric($post['price'] ?? '') || $data['price'] < 0) {
        $errors[] = 'Harga harus berupa angka >= 0.';
    }
    if (!is_numeric($post['stock'] ?? '') || $data['stock'] < 0) {
        $errors[] = 'Stok harus berupa angka >= 0.';
    }
    return [$data, $errors];
}
