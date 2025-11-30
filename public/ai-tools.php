<?php
$pageTitle = 'Ferramentas de IA';
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Ferramentas IA</title>
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

            <div class="dashboard-grid" style="grid-template-columns: repeat(3, 1fr);">
                <!-- Prediction Model -->
                <div class="card glass-panel">
                    <h3><i class="fas fa-magic"></i> Previsão de Desempenho</h3>
                    <p>Utilize nossos modelos de IA para prever o rendimento de culturas com base em insumos biológicos.</p>
                    <form action="#" method="POST" style="margin-top: 20px;">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; color: var(--text-secondary);">Cultura</label>
                            <select class="search-input" style="width: 100%; color: var(--text-primary); background: var(--surface-light);">
                                <option>Soja</option>
                                <option>Milho</option>
                                <option>Trigo</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; color: var(--text-secondary);">Insumo Biológico</label>
                            <input type="text" class="search-input" placeholder="Ex: Bacillus subtilis" style="width: 100%;">
                        </div>
                        <button type="button" class="btn" style="width: 100%;">Executar Previsão</button>
                    </form>
                </div>

                <!-- RAG Context Management -->
                <div class="card glass-panel" style="grid-column: 1 / -1;">
                    <h3><i class="fas fa-database"></i> Base de Conhecimento (RAG)</h3>
                    <p style="margin-bottom: 20px;">Gerencie os documentos que alimentam a inteligência do sistema.</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Session Context (User) -->
                        <div style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 8px; border: 1px solid var(--glass-border);">
                            <h4 style="color: var(--primary-color); margin-bottom: 10px;"><i class="fas fa-user-tag"></i> Contexto da Sessão</h4>
                            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 15px;">
                                Arquivos privados para sua análise atual. Não visíveis para outros usuários.
                            </p>
                            <div style="border: 2px dashed var(--glass-border); padding: 20px; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--primary-color)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: var(--text-secondary); margin-bottom: 10px;"></i>
                                <p style="font-size: 0.9rem;">Upload de Arquivos de Apoio</p>
                            </div>
                        </div>

                        <!-- Global Context (Admin) -->
                        <div style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 8px; border: 1px solid var(--glass-border);">
                            <h4 style="color: var(--accent-color); margin-bottom: 10px;"><i class="fas fa-globe"></i> Contexto Global (Admin)</h4>
                            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 15px;">
                                Base de conhecimento oficial da empresa. Visível para todos.
                            </p>
                            <div style="border: 2px dashed var(--glass-border); padding: 20px; text-align: center; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent-color)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                                <i class="fas fa-server" style="font-size: 1.5rem; color: var(--text-secondary); margin-bottom: 10px;"></i>
                                <p style="font-size: 0.9rem;">Gerenciar Base Oficial</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Integration Card -->
                <div class="card glass-panel">
                    <h3><i class="fas fa-plug"></i> Fontes de Dados (APIs)</h3>
                    <p>Adicione novas APIs para alimentar o conhecimento do Assistente IA.</p>
                    <button class="btn" onclick="alert('Funcionalidade de adicionar API em desenvolvimento!')" style="width: 100%; margin-top: 20px;">
                        <i class="fas fa-plus"></i> Adicionar Nova API
                    </button>
                </div>

                <!-- Market Trends -->
                <div class="card glass-panel" style="grid-column: 1 / -1;">
                    <h3><i class="fas fa-chart-line"></i> Análise de Tendências de Mercado</h3>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="marketChart"></canvas>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
