<?php
require_once 'config.php';
requireLogin();

$posts = getPosts();
$media = getMedia();
$editMode = isset($_GET['id']);
$post = null;

if ($editMode) {
    $editId = (int)$_GET['id'];
    foreach ($posts as $p) {
        if ($p['id'] === $editId) {
            $post = $p;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $excerpt = trim($_POST['excerpt'] ?? '');
    
    if ($title && $content) {
        $now = date('Y-m-d\TH:i:s');
        $slug = generateSlug($title);
        
        // Handle image upload
        $featuredMediaId = $post['featured_media'] ?? 0;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $slug . '.' . $ext;
            $filepath = IMAGES_DIR . '/' . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                $featuredMediaId = getNextId($media);
                $media[] = [
                    'id' => $featuredMediaId,
                    'source_url' => '/news-images/' . $filename
                ];
                saveMedia($media);
            }
        }
        
        $newPost = [
            'id' => $editMode ? $post['id'] : getNextId($posts),
            'date' => $editMode ? $post['date'] : $now,
            'date_gmt' => $editMode ? $post['date_gmt'] : $now,
            'modified' => $now,
            'modified_gmt' => $now,
            'slug' => $slug,
            'status' => 'publish',
            'type' => 'post',
            'title' => ['rendered' => $title],
            'content' => ['rendered' => $content, 'protected' => false],
            'excerpt' => ['rendered' => $excerpt, 'protected' => false],
            'featured_media' => $featuredMediaId,
            'author' => 3,
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'sticky' => false,
            'template' => '',
            'format' => 'standard',
            'meta' => ['ngg_post_thumbnail' => 0],
            'categories' => [5],
            'tags' => [],
            'guid' => ['rendered' => 'https://www.criselvazzanodentro.it/?p=' . ($editMode ? $post['id'] : getNextId($posts))],
            'link' => 'https://www.criselvazzanodentro.it/' . $slug . '/',
            '_links' => []
        ];
        
        if ($editMode) {
            foreach ($posts as $key => $p) {
                if ($p['id'] === $post['id']) {
                    $posts[$key] = $newPost;
                    break;
                }
            }
        } else {
            array_unshift($posts, $newPost);
        }
        
        savePosts($posts);
        header('Location: dashboard.php?saved=1');
        exit;
    }
}

$currentImage = '';
if ($post && $post['featured_media']) {
    foreach ($media as $m) {
        if ($m['id'] === $post['featured_media']) {
            $currentImage = $m['source_url'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editMode ? 'Modifica' : 'Nuova' ?> News - CRI Selvazzano</title>
    <script src="https://cdn.tiny.cloud/1/wbab0bqy4l3fwlmzq0jpbdjsd61ut94ehw2m1qewnxlv54l1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: white; border-bottom: 1px solid #e0e0e0; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .header h1 { color: #E31E24; font-size: 16px; display: flex; align-items: center; gap: 6px; }
        .header a { color: #757575; text-decoration: none; font-size: 14px; }
        .container { max-width: 100%; margin: 0; padding: 16px; }
        .form-box { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #212121; font-weight: 600; font-size: 14px; }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; font-family: inherit; }
        input[type="text"]:focus, textarea:focus { outline: none; border-color: #E31E24; }
        textarea { min-height: 100px; resize: vertical; }
        .dropzone { border: 2px dashed #e0e0e0; border-radius: 12px; padding: 30px 20px; text-align: center; cursor: pointer; transition: all 0.3s; position: relative; }
        .dropzone:active { border-color: #E31E24; background: #fafafa; }
        .dropzone.dragover { border-color: #E31E24; background: #ffebee; }
        .dropzone-content svg { margin-bottom: 12px; }
        .dropzone-content p { color: #757575; margin: 0; font-size: 14px; }
        .preview { position: relative; }
        .preview img { max-width: 100%; border-radius: 8px; }
        .remove-btn { position: absolute; top: 8px; right: 8px; width: 36px; height: 36px; background: #d32f2f; color: white; border: none; border-radius: 50%; font-size: 24px; cursor: pointer; line-height: 1; }
        .remove-btn:active { background: #c62828; }
        .actions { display: flex; gap: 12px; flex-direction: column; }
        .btn { padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; display: block; text-align: center; }
        .btn-primary { background: #E31E24; color: white; }
        .btn-primary:active { background: #B71C1C; }
        .btn-secondary { background: #e0e0e0; color: #212121; }
        .btn-secondary:active { background: #bdbdbd; }
        @media (min-width: 768px) {
            .header h1 { font-size: 20px; }
            .container { max-width: 900px; margin: 24px auto; padding: 0 24px; }
            .form-box { padding: 32px; }
            .actions { flex-direction: row; }
            .btn { padding: 12px 24px; display: inline-block; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><svg width="20" height="20" viewBox="0 0 24 24" fill="#E31E24" style="vertical-align: middle; margin-right: 8px;"><path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg><?= $editMode ? 'Modifica' : 'Nuova' ?> News</h1>
        <a href="dashboard.php">← Torna alla dashboard</a>
    </div>
    
    <div class="container">
        <div class="form-box">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Titolo *</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']['rendered'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="excerpt">Estratto</label>
                    <textarea id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars(strip_tags($post['excerpt']['rendered'] ?? '')) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="content">Contenuto *</label>
                    <textarea id="content" name="content"><?= htmlspecialchars($post['content']['rendered'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Immagine in evidenza</label>
                    <div class="dropzone" id="dropzone">
                        <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                        <div class="dropzone-content">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="#757575"><path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z"/></svg>
                            <p>Trascina un'immagine qui o clicca per selezionare</p>
                        </div>
                        <div class="preview" id="preview" style="display: none;">
                            <img id="preview-img" src="" alt="Preview">
                            <button type="button" class="remove-btn" id="remove-btn">×</button>
                        </div>
                    </div>
                    <?php if ($currentImage): ?>
                        <div class="current-image">
                            <p style="margin-top: 12px; color: #757575; font-size: 14px;">Immagine attuale:</p>
                            <img src="..<?= htmlspecialchars($currentImage) ?>" alt="Current" style="max-width: 200px; border-radius: 4px; margin-top: 8px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Salva</button>
                    <a href="dashboard.php" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('image');
        const preview = document.getElementById('preview');
        const previewImg = document.getElementById('preview-img');
        const dropzoneContent = document.querySelector('.dropzone-content');
        const removeBtn = document.getElementById('remove-btn');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                showPreview(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                showPreview(e.target.files[0]);
            }
        });

        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            preview.style.display = 'none';
            dropzoneContent.style.display = 'block';
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                dropzoneContent.style.display = 'none';
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link | code',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, sans-serif; font-size: 16px; }',
            promotion: false
        });
    </script>
</body>
</html>
