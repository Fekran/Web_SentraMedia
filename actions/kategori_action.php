<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

check_role(['Admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf($csrf_token)) {
        set_flash_message('error', 'Token CSRF tidak valid.');
        header("Location: ../pages/kategori.php");
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $nama_kategori = trim($_POST['nama_kategori'] ?? '');

        if (empty($nama_kategori)) {
            set_flash_message('error', 'Nama kategori tidak boleh kosong.');
            header("Location: ../pages/kategori.php");
            exit;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO categories (nama_kategori) VALUES (:nama_kategori)");
            $stmt->execute([':nama_kategori' => $nama_kategori]);
            set_flash_message('success', 'Kategori baru berhasil ditambahkan!');
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                set_flash_message('error', 'Kategori dengan nama tersebut sudah ada.');
            } else {
                set_flash_message('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
            }
        }
    } 
    
    elseif ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $nama_kategori = trim($_POST['nama_kategori'] ?? '');

        if ($id <= 0 || empty($nama_kategori)) {
            set_flash_message('error', 'Data input tidak valid.');
            header("Location: ../pages/kategori.php");
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE categories SET nama_kategori = :nama_kategori WHERE id = :id");
            $stmt->execute([
                ':nama_kategori' => $nama_kategori,
                ':id' => $id
            ]);
            set_flash_message('success', 'Kategori berhasil diperbarui!');
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                set_flash_message('error', 'Kategori dengan nama tersebut sudah ada.');
            } else {
                set_flash_message('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
            }
        }
    } 
    
    elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            set_flash_message('error', 'ID tidak valid.');
            header("Location: ../pages/kategori.php");
            exit;
        }

        try {
            
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->execute([':id' => $id]);
            set_flash_message('success', 'Kategori berhasil dihapus!');
        } catch (PDOException $e) {
            set_flash_message('error', 'Gagal menghapus kategori. Kemungkinan masih ada produk yang menggunakan kategori ini.');
        }
    }

    header("Location: ../pages/kategori.php");
    exit;
} else {
    header("Location: ../pages/kategori.php");
    exit;
}
?>
