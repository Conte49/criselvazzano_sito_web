<?php
require_once 'config.php';
requireLogin();

$posts = getPosts();
usort($posts, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $posts = array_filter($posts, function($p) use ($deleteId) {
        return $p['id'] !== $deleteId;
    });
    $posts = array_values($posts);
    savePosts($posts);
    header('Location: dashboard.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRI Selvazzano</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: white; border-bottom: 1px solid #e0e0e0; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .header h1 { color: #E31E24; font-size: 16px; display: flex; align-items: center; gap: 6px; }
        .header a { color: #757575; text-decoration: none; font-size: 14px; padding: 8px 12px; }
        .container { max-width: 100%; margin: 0; padding: 16px; }
        .actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; }
        .actions h2 { font-size: 18px; }
        .btn { padding: 10px 16px; background: #E31E24; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block; font-size: 14px; white-space: nowrap; }
        .btn:active { background: #B71C1C; transform: scale(0.98); }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .news-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .news-item { padding: 16px; border-bottom: 1px solid #e0e0e0; }
        .news-item:last-child { border-bottom: none; }
        .news-title { font-weight: 600; color: #212121; margin-bottom: 8px; font-size: 15px; }
        .news-date { color: #757575; font-size: 13px; margin-bottom: 12px; }
        .news-actions { display: flex; gap: 8px; }
        .btn-small { padding: 8px 14px; font-size: 13px; border-radius: 6px; text-decoration: none; flex: 1; text-align: center; }
        .btn-edit { background: #1976d2; color: white; }
        .btn-delete { background: #d32f2f; color: white; }
        .btn-edit:active { background: #1565c0; }
        .btn-delete:active { background: #c62828; }
        table { display: none; }
        @media (min-width: 768px) {
            .header h1 { font-size: 20px; }
            .container { max-width: 1200px; margin: 24px auto; padding: 0 24px; }
            .news-item { display: none; }
            table { display: table; width: 100%; border-collapse: collapse; }
            th { background: #f5f5f5; padding: 12px; text-align: left; font-weight: 600; color: #212121; }
            td { padding: 12px; border-top: 1px solid #e0e0e0; }
            .actions-cell { display: flex; gap: 8px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><svg width="20" height="20" viewBox="0 0 24 24" fill="#E31E24" style="vertical-align: middle; margin-right: 8px;"><path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>CRI Selvazzano - Dashboard</h1>
        <a href="logout.php">Esci</a>
    </div>
    
    <div class="container">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="success">News eliminata con successo</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['saved'])): ?>
            <div class="success">News salvata con successo</div>
        <?php endif; ?>
        
        <div class="actions">
            <h2>News (<?= count($posts) ?>)</h2>
            <div style="display: flex; gap: 8px;">
                <a href="create-post.html" class="btn" style="background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    Crea Veloce
                </a>
                <a href="edit.php" class="btn">+ Nuova News</a>
            </div>
        </div>
        
        <div class="news-table">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Data</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <div class="news-title"><?= htmlspecialchars(strip_tags($post['title']['rendered'])) ?></div>
                        </td>
                        <td>
                            <div class="news-date"><?= date('d/m/Y H:i', strtotime($post['date'])) ?></div>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="edit.php?id=<?= $post['id'] ?>" class="btn-small btn-edit">Modifica</a>
                                <a href="?delete=<?= $post['id'] ?>" class="btn-small btn-delete" onclick="return confirm('Sei sicuro di voler eliminare questa news?')">Elimina</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php foreach ($posts as $post): ?>
            <div class="news-item">
                <div class="news-title"><?= htmlspecialchars(strip_tags($post['title']['rendered'])) ?></div>
                <div class="news-date"><?= date('d/m/Y H:i', strtotime($post['date'])) ?></div>
                <div class="news-actions">
                    <a href="edit.php?id=<?= $post['id'] ?>" class="btn-small btn-edit">Modifica</a>
                    <a href="?delete=<?= $post['id'] ?>" class="btn-small btn-delete" onclick="return confirm('Elimina?')">Elimina</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
