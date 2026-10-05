<?php
// READ — daftar produk (JOIN 2 tabel) + pencarian + pagination
require_once __DIR__ . '/includes/functions.php';
$pdo = Database::getInstance()->getConnection();

$perPage = 5;
$q       = trim($_GET['q'] ?? '');
$page    = max(1, (int) ($_GET['page'] ?? 1));
$like    = '%' . $q . '%';
$offset  = 0;

try {
    // Hitung total (untuk pagination) — prepared statement
    $stmt = $pdo->prepare(
        "SELECT COUNT(*)
           FROM products p
           JOIN categories c ON p.category_id = c.id
           JOIN suppliers  s ON p.supplier_id = s.id
          WHERE p.name LIKE :q OR c.name LIKE :q2 OR s.name LIKE :q3"
    );
    $stmt->execute([':q' => $like, ':q2' => $like, ':q3' => $like]);
    $total      = (int) $stmt->fetchColumn();
    $totalPages = max(1, (int) ceil($total / $perPage));
    $page       = min($page, $totalPages);
    $offset     = ($page - 1) * $perPage;

    $stmt = $pdo->prepare(
        "SELECT p.id, p.name, p.price, p.stock,
                c.name AS category, s.name AS supplier
           FROM products p
           JOIN categories c ON p.category_id = c.id
           JOIN suppliers  s ON p.supplier_id = s.id
          WHERE p.name LIKE :q OR c.name LIKE :q2 OR s.name LIKE :q3
          ORDER BY p.name
          LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':q',  $like);
    $stmt->bindValue(':q2', $like);
    $stmt->bindValue(':q3', $like);
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (PDOException $ex) {
    error_log('Index error: ' . $ex->getMessage());
    $products = [];
    $total = 0;
    $totalPages = 1;
    flash_set('error', 'Terjadi kesalahan database saat memuat data.');
}

$pageTitle = 'Daftar Produk';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <h1>Daftar Produk</h1>
    <form method="GET" class="search">
        <input type="search" name="q" placeholder="Cari produk / kategori / supplier…"
               value="<?= e($q) ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
        <?php if ($q !== ''): ?><a class="btn btn-ghost" href="index.php">Reset</a><?php endif; ?>
    </form>
</div>

<div class="card table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th><th>Nama Produk</th><th>Kategori</th><th>Supplier</th>
                <th class="num">Harga</th><th class="num">Stok</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$products): ?>
            <tr><td colspan="7" class="empty">Tidak ada data produk.</td></tr>
        <?php endif; ?>
        <?php foreach ($products as $i => $p): ?>
            <tr>
                <td><?= $offset + $i + 1 ?></td>
                <td><?= e($p['name']) ?></td>
                <td><span class="badge"><?= e($p['category']) ?></span></td>
                <td><?= e($p['supplier']) ?></td>
                <td class="num"><?= e(format_rupiah($p['price'])) ?></td>
                <td class="num <?= (int) $p['stock'] <= 10 ? 'low' : '' ?>"><?= (int) $p['stock'] ?></td>
                <td class="row-actions">
                    <a class="btn btn-sm" href="edit.php?id=<?= (int) $p['id'] ?>">Edit</a>
                    <form method="POST" action="delete.php"
                          onsubmit="return confirm('Yakin hapus produk ini?');">
                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="pager">
    <span><?= $total ?> produk · halaman <?= $page ?> dari <?= $totalPages ?></span>
    <div>
        <?php if ($page > 1): ?>
            <a class="btn btn-sm btn-ghost" href="?q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>">← Sebelumnya</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a class="btn btn-sm btn-ghost" href="?q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>">Berikutnya →</a>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
