<?php
$pageTitle = 'Artigos Completos';
require_once '../config/db.php';

// Create table if not exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        summary TEXT,
        abstract TEXT,
        full_text LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
} catch(PDOException $e) {
    // Table already exists or error
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = $_POST['name'];
            $summary = $_POST['summary'];
            $abstract = $_POST['abstract'];
            $full_text = $_POST['full_text'];

            $stmt = $pdo->prepare("INSERT INTO articles (name, summary, abstract, full_text) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $summary, $abstract, $full_text]);
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($_POST['action'] === 'update') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $summary = $_POST['summary'];
            $abstract = $_POST['abstract'];
            $full_text = $_POST['full_text'];

            $stmt = $pdo->prepare("UPDATE articles SET name = ?, summary = ?, abstract = ?, full_text = ? WHERE id = ?");
            $stmt->execute([$name, $summary, $abstract, $full_text, $id]);
        }
        header("Location: articles.php");
        exit;
    }
}

// Fetch articles
try {
    $stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
    $articles = $stmt->fetchAll();
} catch(PDOException $e) {
    $articles = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Artigos</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div style="margin-bottom: var(--spacing-md); display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem;">Artigos Completos</h2>
                <button class="btn" onclick="openModal('addArticleModal')"><i class="fas fa-plus"></i> Adicionar Artigo</button>
            </div>

            <div class="glass-panel" style="padding: var(--spacing-lg);">
                <?php if (count($articles) > 0): ?>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--glass-border);">
                                    <th style="padding: 12px; text-align: left; color: var(--accent-color);">ID</th>
                                    <th style="padding: 12px; text-align: left; color: var(--accent-color);">Nome</th>
                                    <th style="padding: 12px; text-align: left; color: var(--accent-color);">Resumo</th>
                                    <th style="padding: 12px; text-align: left; color: var(--accent-color);">Data</th>
                                    <th style="padding: 12px; text-align: center; color: var(--accent-color);">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($articles as $article): ?>
                                    <tr style="border-bottom: 1px solid var(--glass-border);">
                                        <td style="padding: 12px;"><?= $article['id'] ?></td>
                                        <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($article['name']) ?></td>
                                        <td style="padding: 12px; color: var(--text-secondary); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?= htmlspecialchars(substr($article['summary'] ?? '', 0, 100)) ?><?= strlen($article['summary'] ?? '') > 100 ? '...' : '' ?>
                                        </td>
                                        <td style="padding: 12px; color: var(--text-secondary);">
                                            <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            <button class="btn-outline" style="padding: 5px 10px; font-size: 0.9rem; margin-right: 5px;" onclick='openViewModal(<?= json_encode($article) ?>)' title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-outline" style="padding: 5px 10px; font-size: 0.9rem; margin-right: 5px;" onclick='openEditModal(<?= json_encode($article) ?>)' title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                                <button type="submit" class="btn-outline" style="padding: 5px 10px; font-size: 0.9rem; color: #ff6b6b; border-color: #ff6b6b;" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-secondary); text-align: center; padding: 40px 0;">Nenhum artigo cadastrado.</p>
                <?php endif; ?>
            </div>

            <!-- Add Article Modal -->
            <div id="addArticleModal" class="modal-overlay">
                <div class="modal-content glass-panel xlarge">
                    <div class="modal-header">
                        <h3>Adicionar Novo Artigo</h3>
                        <button class="close-modal" onclick="closeModal('addArticleModal')">&times;</button>
                    </div>
                    <div class="chat-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="add">
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Nome do Artigo</label>
                                <input type="text" name="name" class="search-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Resumo</label>
                                <textarea name="summary" class="search-input" rows="3" style="width: 100%; resize: vertical;"></textarea>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Abstract</label>
                                <textarea name="abstract" class="search-input" rows="4" style="width: 100%; resize: vertical;"></textarea>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Texto Completo</label>
                                <textarea name="full_text" class="search-input" rows="10" style="width: 100%; resize: vertical;"></textarea>
                            </div>
                            <button type="submit" class="btn" style="width: 100%;">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Article Modal -->
            <div id="editArticleModal" class="modal-overlay">
                <div class="modal-content glass-panel xlarge">
                    <div class="modal-header">
                        <h3>Editar Artigo</h3>
                        <button class="close-modal" onclick="closeModal('editArticleModal')">&times;</button>
                    </div>
                    <div class="chat-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" id="edit_id">
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Nome do Artigo</label>
                                <input type="text" name="name" id="edit_name" class="search-input" required style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Resumo</label>
                                <textarea name="summary" id="edit_summary" class="search-input" rows="3" style="width: 100%; resize: vertical;"></textarea>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Abstract</label>
                                <textarea name="abstract" id="edit_abstract" class="search-input" rows="4" style="width: 100%; resize: vertical;"></textarea>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Texto Completo</label>
                                <textarea name="full_text" id="edit_full_text" class="search-input" rows="10" style="width: 100%; resize: vertical;"></textarea>
                            </div>
                            <button type="submit" class="btn" style="width: 100%;">Atualizar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Article Modal -->
            <div id="viewArticleModal" class="modal-overlay">
                <div class="modal-content glass-panel xlarge">
                    <div class="modal-header">
                        <h3 id="view_name"></h3>
                        <button class="close-modal" onclick="closeModal('viewArticleModal')">&times;</button>
                    </div>
                    <div class="chat-body" style="max-height: 70vh; overflow-y: auto;">
                        <div style="margin-bottom: 20px;">
                            <h4 style="color: var(--accent-color); margin-bottom: 10px;">Resumo</h4>
                            <p id="view_summary" style="color: var(--text-secondary);"></p>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <h4 style="color: var(--accent-color); margin-bottom: 10px;">Abstract</h4>
                            <p id="view_abstract" style="color: var(--text-secondary); white-space: pre-wrap;"></p>
                        </div>
                        <div>
                            <h4 style="color: var(--accent-color); margin-bottom: 10px;">Texto Completo</h4>
                            <div id="view_full_text" style="color: var(--text-primary); white-space: pre-wrap; line-height: 1.6;"></div>
                        </div>
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
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_summary').value = data.summary || '';
                    document.getElementById('edit_abstract').value = data.abstract || '';
                    document.getElementById('edit_full_text').value = data.full_text || '';
                    openModal('editArticleModal');
                }

                function openViewModal(data) {
                    document.getElementById('view_name').textContent = data.name;
                    document.getElementById('view_summary').textContent = data.summary || 'Sem resumo';
                    document.getElementById('view_abstract').textContent = data.abstract || 'Sem abstract';
                    document.getElementById('view_full_text').textContent = data.full_text || 'Sem texto completo';
                    openModal('viewArticleModal');
                }

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
