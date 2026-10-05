<?php
// CREATE — tambah produk (Post/Redirect/Get)
require_once __DIR__ . '/includes/functions.php';
$pdo = Database::getInstance()->getConnection();

$product = ['name' => '', 'category_id' => 0, 'supplier_id' => 0, 'price' => '', 'stock' => 0];
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_product($_POST);
    $product = $data;

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO products (name, category_id, supplier_id, price, stock)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $data['name'], $data['category_id'], $data['supplier_id'],
                $data['price'], $data['stock'],
            ]);
            flash_set('success', 'Produk "' . $data['name'] . '" berhasil ditambahkan.');
            redirect('index.php');
        } catch (PDOException $ex) {
            error_log('Create error: ' . $ex->getMessage());
            $errors[] = ($ex->getCode() === '23000')
                ? 'Kategori atau supplier tidak valid.'
                : 'Terjadi kesalahan database. Coba lagi.';
        }
    }
}

$categories  = get_categories($pdo);
$suppliers   = get_suppliers($pdo);
$pageTitle   = 'Tambah Produk';
$action      = 'create.php';
$submitLabel = 'Simpan Produk';

require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1>Tambah Produk</h1></div>
<?php require __DIR__ . '/includes/form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
