<?php
$pageTitle = 'Segurança (FISPQ & FDS)';
require_once '../config/db.php';

// Mock documents if DB is empty
$documents = [
    ['name' => 'Glifosato - FISPQ', 'type' => 'FISPQ', 'supplier' => 'Monsanto', 'date' => '2023-08-10'],
    ['name' => 'Manual de Manuseio de Sementes Tratadas', 'type' => 'Manual', 'supplier' => 'Syngenta', 'date' => '2024-02-15'],
    ['name' => 'Ureia Agrícola - Ficha de Segurança', 'type' => 'FISPQ', 'supplier' => 'Yara', 'date' => '2023-11-20'],
    ['name' => 'Diretrizes de Armazenamento de Biológicos', 'type' => 'Manual', 'supplier' => 'Embrapa', 'date' => '2024-01-05']
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Segurança</title>
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
                <h3><i class="fas fa-shield-virus"></i> Consultar Documentos de Segurança</h3>
                <p style="margin-bottom: 15px; color: var(--text-secondary);">Busque por FISPQ (Ficha de Informações de Segurança de Produtos Químicos) ou Manuais de Manuseio.</p>
                <div style="display: flex; gap: 10px;">
                    <select class="search-input" style="width: 150px; color: var(--text-primary); background: var(--surface-light);">
                        <option value="">Todos</option>
                        <option value="FISPQ">FISPQ</option>
                        <option value="Manual">Manual/FDS</option>
                    </select>
                    <input type="text" class="search-input" placeholder="Nome do produto, substância ou fornecedor..." style="flex: 1;">
                    <button class="btn">Buscar</button>
                </div>
            </div>

            <!-- Documents List -->
            <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                <?php foreach($documents as $doc): ?>
                <div class="card glass-panel" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; background: <?= $doc['type'] == 'FISPQ' ? 'rgba(255, 87, 34, 0.2)' : 'rgba(33, 150, 243, 0.2)' ?>; color: <?= $doc['type'] == 'FISPQ' ? '#ff8a65' : '#64b5f6' ?>;">
                                <?= $doc['type'] ?>
                            </span>
                            <i class="fas fa-file-pdf" style="color: var(--text-secondary);"></i>
                        </div>
                        <h4 style="margin-bottom: 5px;"><?= $doc['name'] ?></h4>
                        <p style="font-size: 0.85rem; color: var(--text-secondary);">Fornecedor: <?= $doc['supplier'] ?></p>
                        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 5px;">Atualizado em: <?= date('d/m/Y', strtotime($doc['date'])) ?></p>
                    </div>
                    <button class="btn-outline" style="width: 100%; margin-top: 15px; text-align: center;">Download PDF <i class="fas fa-download" style="margin-left: 5px;"></i></button>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Guidelines Section -->
            <div class="card glass-panel" style="margin-top: var(--spacing-xl);">
                <h3><i class="fas fa-exclamation-triangle"></i> Diretrizes Gerais de Segurança</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px;">
                    <div style="background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 8px;">
                        <h4 style="color: var(--accent-color); margin-bottom: 10px;">Armazenamento</h4>
                        <ul style="list-style: disc; padding-left: 20px; color: var(--text-secondary); font-size: 0.9rem;">
                            <li>Manter em local seco e ventilado.</li>
                            <li>Evitar exposição direta ao sol.</li>
                            <li>Separar produtos químicos de sementes.</li>
                        </ul>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 8px;">
                        <h4 style="color: var(--accent-color); margin-bottom: 10px;">Manuseio</h4>
                        <ul style="list-style: disc; padding-left: 20px; color: var(--text-secondary); font-size: 0.9rem;">
                            <li>Utilizar EPIs adequados (Luvas, Máscara).</li>
                            <li>Ler atentamente o rótulo antes do uso.</li>
                            <li>Não reutilizar embalagens vazias.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
