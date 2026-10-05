<?php
// DELETE — hanya via POST; DELETE + log aktivitas dibungkus TRANSACTION (bonus)
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$pdo = Database::getInstance()->getConnection();
$id  = (int) ($_POST['id'] ?? 0);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT id, name FROM products WHERE id = ? FOR UPDATE');
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        $pdo->rollBack();
        flash_set('error', 'Produk tidak ditemukan atau sudah dihapus.');
        redirect('index.php');
    }

    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

    $pdo->prepare(
        "INSERT INTO activity_logs (action, product_id, product_name) VALUES ('delete', ?, ?)"
    )->execute([$product['id'], $product['name']]);

    $pdo->commit(); // semua sukses -> simpan permanen
    flash_set('success', 'Produk "' . $product['name'] . '" berhasil dihapus.');
} catch (PDOException $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); // ada yang gagal -> batalkan semua
    }
    error_log('Delete error: ' . $ex->getMessage());
    flash_set('error', 'Gagal menghapus produk. Tidak ada data yang berubah.');
}

redirect('index.php');
