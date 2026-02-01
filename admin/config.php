<?php
session_start();

// Configurazione
define('ADMIN_PASSWORD_HASH', '$2y$10$0VcV1r0GRLLoDe7ie.HFtOQxDvJJVz9uLWw1cepCUXdY.PLP0ztc.'); // password: CRI#Selv2025!Admin@Secure
define('DATA_DIR', __DIR__ . '/../data');
define('IMAGES_DIR', __DIR__ . '/../news-images');
define('POSTS_FILE', DATA_DIR . '/posts.json');
define('MEDIA_FILE', DATA_DIR . '/media.json');

// Crea la cartella data se non esiste
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Inizializza i file JSON se non esistono
if (!file_exists(POSTS_FILE)) {
    file_put_contents(POSTS_FILE, '[]');
}
if (!file_exists(MEDIA_FILE)) {
    file_put_contents(MEDIA_FILE, '[]');
}

// Funzioni helper
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

function getPosts() {
    $json = file_get_contents(POSTS_FILE);
    return json_decode($json, true);
}

function getMedia() {
    $json = file_get_contents(MEDIA_FILE);
    return json_decode($json, true);
}

function savePosts($posts) {
    $json = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return file_put_contents(POSTS_FILE, $json, LOCK_EX);
}

function saveMedia($media) {
    $json = json_encode($media, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return file_put_contents(MEDIA_FILE, $json, LOCK_EX);
}

function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9]+/', '_', $slug);
    $slug = trim($slug, '_');
    return $slug;
}

function getNextId($posts) {
    $maxId = 0;
    foreach ($posts as $post) {
        if ($post['id'] > $maxId) {
            $maxId = $post['id'];
        }
    }
    return $maxId + 1;
}
