<?php
include 'db.php';
session_start();

// Check if ID is provided and not empty
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = $_GET['id']; // Get ID directly from URL
$sql = "SELECT * FROM pins WHERE id_pins = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Gambar tidak ditemukan!");
}

$photo = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .upload-container {
            max-width: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-group label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }

         .upload-container h1{
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .upload-box {
            margin-top: 15px;
            max-width: 100%;
            border: 2px dashed #ddd;
            border-radius: 8px;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #666;
            cursor: pointer;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            box-sizing: border-box;
        }
        .upload-box:hover {
            border-color: #bbb;
            background-color: #f3f3f3;
        }
        #image {
            display: none;
        }
        .preview-image {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 15px;
        }
        .submit-btn {
            padding: 12px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #e60023;
            border: none;
            border-radius: 8px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <form method="post" enctype="multipart/form-data" action="edit_action.php?id=<?= $id; ?>">
        <h1>Edit Pin</h1>

        <!-- Title input -->
        <div class="form-group">
            <label for="title">Judul</label>
            <input type="text" name="title" value="<?= htmlspecialchars($photo['title']); ?>" required>
        </div>

        <!-- Description input -->
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea name="description" required><?= htmlspecialchars($photo['deskripsi']); ?></textarea>
        </div>

        <!-- Category dropdown -->
        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori">
                <option value="orang" <?= $photo['kategori'] == 'orang' ? 'selected' : ''; ?>>Manusia</option>
                <option value="makan dan minum" <?= $photo['kategori'] == 'makan dan minum' ? 'selected' : ''; ?>>Makanan dan Minuman</option>
                    <option value="imajinasi" <?= $photo['kategori'] == 'imajinasi' ? 'selected' : ''; ?>>Imajinasi</option>
                    <option value="hewan" <?= $photo['kategori'] == 'hewan' ? 'selected' : ''; ?>>Hewan</option>
                    <option value="tanaman" <?= $photo['kategori'] == 'tanaman' ? 'selected' : ''; ?>>Tanaman</option>
                    <option value="pacar" <?= $photo['kategori'] == 'pacar' ? 'selected' : ''; ?>>Pacar</option>
                    <option value="style" <?= $photo['kategori'] == 'style' ? 'selected' : ''; ?>>Style</option>
                    <option value="selebriti" <?= $photo['kategori'] == 'selebriti' ? 'selected' : ''; ?>>Selebriti</option>
                    <option value="seni" <?= $photo['kategori'] == 'seni' ? 'selected' : ''; ?>>Seni</option>
                    <option value="hiburan" <?= $photo['kategori'] == 'hiburan' ? 'selected' : ''; ?>>Hiburan</option>
                <!-- Add more options as needed -->
            </select>
        </div>

        <!-- File upload area -->
        <label class="upload-box" for="image">
            Pilih file atau seret dan jatuhkan di sini
            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="preview-image" src="<?= htmlspecialchars($photo['image_path']); ?>" alt="Current Image">
            <p class="description">Sebaiknya gunakan file .jpg berkualitas tinggi berukuran kurang dari 20 MB atau file .mp4 kurang dari 200 MB.</p>
        </label>

        <!-- Submit button -->
        <button type="submit" class="submit-btn">Simpan Pin</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>
