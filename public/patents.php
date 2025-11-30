<?php
$pageTitle = 'Patentes';
require_once '../config/db.php';

// Mock patents if DB is empty or connection fails
$patents = [
    [
        'title' => 'Bioinseticida à base de Bacillus thuringiensis',
        'assignee' => 'Embrapa',
        'status' => 'Concedida',
        'date' => '2023-05-12'
    ],
    [
        'title' => 'Nova formulação de fertilizante organomineral',
        'assignee' => 'StartAgro',
        'status' => 'Em Análise',
        'date' => '2024-01-20'

        
    ],
    [
        'title' => 'Sistema de monitoramento de pragas via drone',
        'assignee' => 'AgroTech Solutions',
        'status' => 'Publicada',
        'date' => '2023-11-05'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Patentes</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <!-- Search Section -->
            <div class="card glass-panel" style="margin-bottom: var(--spacing-xl);">
                <h3><i class="fas fa-search"></i> Pesquisar Patentes</h3>
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <input type="text" class="search-input" placeholder="Palavras-chave, número ou depositante..." style="flex: 1;">
                    <button class="btn">Buscar</button>
                    <a href="https://worldwide.espacenet.com/" target="_blank" class="btn-outline" style="display: flex; align-items: center; gap: 5px;">
                        Espacenet <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Patents List -->
            <h2 style="margin-bottom: var(--spacing-md); font-size: 1.5rem;">Patentes Recentes no Setor</h2>
            <div class="glass-panel" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse; color: var(--text-primary);">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                            <th style="padding: 20px;">Título</th>
                            <th style="padding: 20px;">Depositante</th>
                            <th style="padding: 20px;">Status</th>
                            <th style="padding: 20px;">Data</th>
                            <th style="padding: 20px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($patents as $patent): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 20px; font-weight: 500;"><?= $patent['title'] ?></td>
                            <td style="padding: 20px; color: var(--text-secondary);"><?= $patent['assignee'] ?></td>
                            <td style="padding: 20px;">
                                <span style="padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; background: rgba(0, 121, 107, 0.2); color: var(--accent-color);">
                                    <?= $patent['status'] ?>
                                </span>
                            </td>
                            <td style="padding: 20px; color: var(--text-secondary);"><?= date('d/m/Y', strtotime($patent['date'])) ?></td>
                            <td style="padding: 20px;">
                                <button class="btn-outline" style="padding: 5px 10px; font-size: 0.8rem;">Detalhes</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
