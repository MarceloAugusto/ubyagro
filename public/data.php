<?php
$pageTitle = 'Dados Agronômicos';
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Dados Agronômicos</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <!-- Platforms Integration -->
            <div class="inline-card-grid" style="margin-bottom: var(--spacing-xl);">
                <div class="card glass-panel compact">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h3><i class="fas fa-leaf"></i> Agrofit</h3>
                            <p>Sistema de Agrotóxicos Fitossanitários</p>
                        </div>
                        <a href="https://agrofit.agricultura.gov.br/agrofit_cons/principal_agrofit_cons" target="_blank" class="btn">Acessar</a>
                    </div>
                </div>
                <div class="card glass-panel compact">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h3><i class="fas fa-database"></i> AGROAPI</h3>
                            <p>APIs de Dados Agropecuários (Embrapa)</p>
                        </div>
                        <a href="https://www.embrapa.br/agroapi" target="_blank" class="btn">Acessar</a>
                    </div>
                </div>
                <div class="card glass-panel compact">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h3><i class="fas fa-tractor"></i> CONAB</h3>
                            <p>Companhia Nacional de Abastecimento</p>
                        </div>
                        <a href="https://www.conab.gov.br/" target="_blank" class="btn">Acessar</a>
                    </div>
                </div>
            </div>

            <!-- Data Visualization Section -->
            <div class="dashboard-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="card glass-panel">
                    <h3>Padrões de Uso de Produtos</h3>
                    <div style="height: 250px; width: 100%;">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
                <div class="card glass-panel">
                    <h3>Tendências de Mercado (Insumos)</h3>
                    <div style="height: 250px; width: 100%;">
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
