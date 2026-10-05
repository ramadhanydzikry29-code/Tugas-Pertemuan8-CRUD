<?php
/**
 * Form produk dipakai bersama oleh create.php & edit.php.
 * Variabel: $product (array nilai form), $categories, $suppliers,
 *           $errors, $action (url), $submitLabel
 */
?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?= e($action) ?>" class="card form">
    <div class="field">
        <label for="name">Nama Produk</label>
        <input type="text" id="name" name="name" maxlength="150" required
               value="<?= e($product['name']) ?>">
    </div>

    <div class="grid-2">
        <div class="field">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Pilih kategori --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id'] ?>"
                        <?= (int) $product['category_id'] === (int) $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id" required>
                <option value="">-- Pilih supplier --</option>
                <?php foreach ($suppliers as $s): ?>
                    <option value="<?= (int) $s['id'] ?>"
                        <?= (int) $product['supplier_id'] === (int) $s['id'] ? 'selected' : '' ?>>
                        <?= e($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="grid-2">
        <div class="field">
            <label for="price">Harga (Rp)</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required
                   value="<?= e($product['price']) ?>">
        </div>
        <div class="field">
            <label for="stock">Stok</label>
            <input type="number" id="stock" name="stock" min="0" step="1" required
                   value="<?= e($product['stock']) ?>">
        </div>
    </div>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= e($submitLabel) ?></button>
        <a href="index.php" class="btn btn-ghost">Batal</a>
    </div>
</form>
