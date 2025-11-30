<?php
$pageTitle = 'Pesquisa & Artigos';
require_once '../config/db.php';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = $_POST['title'];
            $url = $_POST['url'];
            $category = $_POST['category'];
            $source = $_POST['source'];
            $date = !empty($_POST['publication_date']) ? $_POST['publication_date'] : null;

            $stmt = $pdo->prepare("INSERT INTO research_links (title, url, category, source, publication_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $url, $category, $source, $date]);
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM research_links WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($_POST['action'] === 'update') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $url = $_POST['url'];
            $category = $_POST['category'];
            $source = $_POST['source'];
            $date = !empty($_POST['publication_date']) ? $_POST['publication_date'] : null;

            $stmt = $pdo->prepare("UPDATE research_links SET title = ?, url = ?, category = ?, source = ?, publication_date = ? WHERE id = ?");
            $stmt->execute([$title, $url, $category, $source, $date, $id]);
        }
        // Redirect to prevent resubmission
        header("Location: research.php");
        exit;
    }
}

// Fetch research links from database
try {
    $stmt = $pdo->query("SELECT * FROM research_links WHERE category != 'Platform' ORDER BY created_at DESC");
    $links = $stmt->fetchAll();
} catch(PDOException $e) {
    $links = []; // Fallback if DB fails
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Pesquisa</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <!-- Platforms Section -->
            <h2 style="margin-bottom: var(--spacing-md); font-size: 1.5rem;">Plataformas de Pesquisa</h2>
            <div class="inline-card-grid" style="margin-bottom: var(--spacing-xl);">
                <a href="https://scholar.google.com.br/" target="blank" class="card glass-panel compact" style="text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-graduation-cap" style="font-size: 2rem; color: #4285F4;"></i>
                        <div>
                            <h3>Google Acadêmico</h3>
                            <p style="margin: 0;">Pesquisa de literatura acadêmica</p>
                        </div>
                    </div>
                </a>
                <a href="https://scielo.org/" target="blank" class="card glass-panel compact" style="text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-book" style="font-size: 2rem; color: #F48F42;"></i>
                        <div>
                            <h3>SciELO</h3>
                            <p style="margin: 0;">Scientific Electronic Library Online</p>
                        </div>
                    </div>
                </a>
                <a href="https://www.redape.org/" target="blank" class="card glass-panel compact" style="text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-seedling" style="font-size: 2rem; color: var(--primary-color);"></i>
                        <div>
                            <h3>Redape</h3>
                            <p style="margin: 0;">Rede de Dados Agropecuários</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Add Article Button -->
            <div style="margin-bottom: var(--spacing-md); text-align: right;">
                <button class="btn" onclick="openModal('addArticleModal')"><i class="fas fa-plus"></i> Adicionar Artigo</button>
            </div>

            <!-- Articles Feed -->
            <h2 style="margin-bottom: var(--spacing-md); font-size: 1.5rem;">Artigos e Estudos adicionados</h2>
            <div class="glass-panel" style="padding: var(--spacing-lg);">
                <?php if (count($links) > 0): ?>
                    <ul style="display: flex; flex-direction: column; gap: 15px;">
                        <?php foreach($links as $link): ?>
                            <li style="border-bottom: 1px solid var(--glass-border); padding-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div>
                                        <span style="font-size: 0.8rem; color: var(--accent-color); text-transform: uppercase;"><?= htmlspecialchars($link['category']) ?></span>
                                        <h4 style="margin: 5px 0; font-size: 1.1rem;"><?= htmlspecialchars($link['title']) ?></h4>
                                        <p style="font-size: 0.9rem; color: var(--text-secondary);">Fonte: <?= htmlspecialchars($link['source']) ?> • <?= $link['publication_date'] ? date('d/m/Y', strtotime($link['publication_date'])) : 'Data N/A' ?></p>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank" class="btn-outline" style="padding: 5px 15px; font-size: 0.9rem;" title="Ler"><i class="fas fa-external-link-alt"></i></a>
                                        <button class="btn-outline" style="padding: 5px 10px; font-size: 0.9rem;" onclick='openEditModal(<?= json_encode($link) ?>)' title="Editar"><i class="fas fa-edit"></i></button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $link['id'] ?>">
                                            <button type="submit" class="btn-outline" style="padding: 5px 10px; font-size: 0.9rem; color: #ff6b6b; border-color: #ff6b6b;" title="Excluir"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: var(--text-secondary); text-align: center;">Nenhum artigo encontrado no momento.</p>
                <?php endif; ?>
            </div>

            <!-- Add Article Modal -->
            <div id="addArticleModal" class="modal-overlay">
                <div class="modal-content glass-panel" style="max-width: 500px;">
                    <div class="modal-header">
                        <h3>Adicionar Novo Artigo</h3>
                        <button class="close-modal" onclick="closeModal('addArticleModal')">&times;</button>
                    </div>
                    <div class="chat-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="add">
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Título</label>
                                <input type="text" name="title" class="search-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">URL</label>
                                <input type="url" name="url" class="search-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Categoria</label>
                                <select name="category" class="search-input" style="width: 100%; color: var(--text-primary); background: var(--surface-light);">
                                    <option value="Article">Artigo</option>
                                    <option value="Study">Estudo</option>
                                    <option value="Platform">Plataforma</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Fonte</label>
                                <input type="text" name="source" class="search-input" style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Data de Publicação</label>
                                <input type="date" name="publication_date" class="search-input" style="width: 100%; color: var(--text-primary);">
                            </div>
                            <button type="submit" class="btn" style="width: 100%;">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Article Modal -->
            <div id="editArticleModal" class="modal-overlay">
                <div class="modal-content glass-panel" style="max-width: 500px;">
                    <div class="modal-header">
                        <h3>Editar Artigo</h3>
                        <button class="close-modal" onclick="closeModal('editArticleModal')">&times;</button>
                    </div>
                    <div class="chat-body">
                        <form method="POST" id="editForm">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" id="edit_id">
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Título</label>
                                <input type="text" name="title" id="edit_title" class="search-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">URL</label>
                                <input type="url" name="url" id="edit_url" class="search-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Categoria</label>
                                <select name="category" id="edit_category" class="search-input" style="width: 100%; color: var(--text-primary); background: var(--surface-light);">
                                    <option value="Article">Artigo</option>
                                    <option value="Study">Estudo</option>
                                    <option value="Platform">Plataforma</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Fonte</label>
                                <input type="text" name="source" id="edit_source" class="search-input" style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Data de Publicação</label>
                                <input type="date" name="publication_date" id="edit_date" class="search-input" style="width: 100%; color: var(--text-primary);">
                            </div>
                            <button type="submit" class="btn" style="width: 100%;">Atualizar</button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function openModal(modalId) {
                    document.getElementById(modalId).classList.add('active');
                }

                function closeModal(modalId) {
                    document.getElementById(modalId).classList.remove('active');
                }

                function openEditModal(data) {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_url').value = data.url;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_source').value = data.source;
                    document.getElementById('edit_date').value = data.publication_date;
                    openModal('editArticleModal');
                }

                // Close modals when clicking outside
                window.onclick = function(event) {
                    if (event.target.classList.contains('modal-overlay')) {
                        event.target.classList.remove('active');
                    }
                }
            </script>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
