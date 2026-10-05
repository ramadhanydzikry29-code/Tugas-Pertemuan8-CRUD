<?php
// UPDATE — form pre-filled
require_once __DIR__ . '/includes/functions.php';
$pdo = Database::getInstance()->getConnection();

$id = (int) ($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
} catch (PDOException $ex) {
    error_log('Edit load error: ' . $ex->getMessage());
    flash_set('error', 'Terjadi kesalahan database.');
    redirect('index.php');
}

if (!$product) {
    flash_set('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_product($_POST);
    $product = array_merge($product, $data); // form tetap terisi saat ada error

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE products
                    SET name = ?, category_id = ?, supplier_id = ?, price = ?, stock = ?
                  WHERE id = ?'
            );
            $stmt->execute([
                $data['name'], $data['category_id'], $data['supplier_id'],
                $data['price'], $data['stock'], $id,
            ]);
            flash_set('success', 'Produk "' . $data['name'] . '" berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $ex) {
            error_log('Update error: ' . $ex->getMessage());
            $errors[] = ($ex->getCode() === '23000')
                ? 'Kategori atau supplier tidak valid.'
                : 'Terjadi kesalahan database. Coba lagi.';
        }
    }
}

$categories  = get_categories($pdo);
$suppliers   = get_suppliers($pdo);
$pageTitle   = 'Edit Produk';
$action      = 'edit.php?id=' . $id;
$submitLabel = 'Perbarui Produk';

require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1>Edit Produk</h1></div>
<?php require __DIR__ . '/includes/form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
