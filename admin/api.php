<?php
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    
    if (!$title || !$content) {
        echo json_encode(['success' => false, 'message' => 'Titolo e contenuto sono obbligatori']);
        exit;
    }
    
    $posts = getPosts();
    $media = getMedia();
    $now = date('Y-m-d\TH:i:s');
    $slug = generateSlug($title);
    
    // Handle image upload
    $featuredMediaId = 0;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = $slug . '-' . time() . '.' . $ext;
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
    
    // Create excerpt from content (first 150 chars)
    $excerpt = mb_substr(strip_tags($content), 0, 150);
    if (mb_strlen(strip_tags($content)) > 150) {
        $excerpt .= '...';
    }
    
    $newPost = [
        'id' => getNextId($posts),
        'date' => $now,
        'date_gmt' => $now,
        'modified' => $now,
        'modified_gmt' => $now,
        'slug' => $slug,
        'status' => 'publish',
        'type' => 'post',
        'title' => ['rendered' => $title],
        'content' => ['rendered' => nl2br(htmlspecialchars($content)), 'protected' => false],
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
        'guid' => ['rendered' => 'https://www.criselvazzanodentro.it/?p=' . getNextId($posts)],
        'link' => 'https://www.criselvazzanodentro.it/' . $slug . '/',
        '_links' => []
    ];
    
    array_unshift($posts, $newPost);
    savePosts($posts);
    
    echo json_encode(['success' => true, 'post_id' => $newPost['id']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Azione non valida']);
}
