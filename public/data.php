<?php
$pageTitle = 'Dados e Integrações';
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Dados e Integrações</title>
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

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-md);">
                <h2 style="font-size: 1.5rem; margin: 0;">Dados e Integrações</h2>
                <a href="ai-tools.php" class="btn-outline"><i class="fas fa-plug"></i> Gerenciar APIs</a>
            </div>

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
                            <h3><i class="fas fa-chart-line"></i> Kynetec:</h3>
                            <p>Inteligência e dados de mercado agro</p>
                        </div>
                        <a href="https://www.kynetec.com/" target="_blank" class="btn">Acessar</a>
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

            <div class="research-grid">
                <div class="glass-panel">
                    <h3 style="margin-bottom: var(--spacing-md); font-size: 1rem;">Adicionar Série de Dados</h3>
                    <form>
                        <div id="uploadDropzone" class="upload-dropzone">
                            <div style="text-align: center;">
                                <i class="fas fa-upload" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <p style="margin-top: 8px; color: var(--text-secondary);">Arraste e solte um arquivo aqui</p>
                                <p style="font-size: 0.85rem; color: var(--text-secondary);">ou</p>
                                <button type="button" class="btn-outline" id="browseBtn">Escolher arquivo</button>
                                <input type="file" id="dataFileInput" accept=".csv,.xlsx,.json" style="display: none;">
                                <div id="selectedFileName" style="margin-top: 10px; font-size: 0.9rem; color: var(--text-primary);"></div>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px;">
                            <div>
                                <label style="display:block; margin-bottom:6px; color: var(--text-secondary);">Fonte</label>
                                <select class="search-input" style="width:100%; color: var(--text-primary); background: var(--surface-light);">
                                    <option>Kynetec</option>
                                    <option>CONAB</option>
                                    <option>Agrofit</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; color: var(--text-secondary);">Categoria</label>
                                <select class="search-input" style="width:100%; color: var(--text-primary); background: var(--surface-light);">
                                    <option>Mercado</option>
                                    <option>Produção</option>
                                    <option>Registro</option>
                                </select>
                            </div>
                        </div>
                        <div style="margin-top: 12px; display:flex; gap:10px;">
                            <button type="button" class="btn" style="flex:0 0 auto;">Adicionar</button>
                            <button type="button" class="btn-outline" style="flex:0 0 auto;" id="clearSelection">Limpar</button>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="glass-panel" style="padding: var(--spacing-lg);">
                        <h3 style="margin-bottom: var(--spacing-md); font-size: 1rem;">Séries e Indicadores</h3>
                        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px;">
                            <li>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <span class="chip selected" style="padding:6px 10px;">Kynetec</span>
                                        <h4 style="margin:6px 0; font-size:1rem;">Demanda por Biológicos (Soja)</h4>
                                        <p style="font-size:0.9rem; color: var(--text-secondary);">Brasil • 2023–2024</p>
                                    </div>
                                    <a href="https://www.kynetec.com/" target="_blank" class="btn-outline" style="padding:6px 12px; font-size:0.85rem;">Ver</a>
                                </div>
                            </li>
                            <li>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <span class="chip selected" style="padding:6px 10px;">CONAB</span>
                                        <h4 style="margin:6px 0; font-size:1rem;">Safras e Estoques (Milho)</h4>
                                        <p style="font-size:0.9rem; color: var(--text-secondary);">Brasil • 2024</p>
                                    </div>
                                    <a href="https://www.conab.gov.br/" target="_blank" class="btn-outline" style="padding:6px 12px; font-size:0.85rem;">Ver</a>
                                </div>
                            </li>
                            <li>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <span class="chip selected" style="padding:6px 10px;">Agrofit</span>
                                        <h4 style="margin:6px 0; font-size:1rem;">Registros por Cultura</h4>
                                        <p style="font-size:0.9rem; color: var(--text-secondary);">Brasil • Atuais</p>
                                    </div>
                                    <a href="https://agrofit.agricultura.gov.br/agrofit_cons/principal_agrofit_cons" target="_blank" class="btn-outline" style="padding:6px 12px; font-size:0.85rem;">Ver</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var dropzone = document.getElementById('uploadDropzone');
            var fileInput = document.getElementById('dataFileInput');
            var browseBtn = document.getElementById('browseBtn');
            var nameLabel = document.getElementById('selectedFileName');
            var clearBtn = document.getElementById('clearSelection');

            function setFile(file) {
                if (!file) return;
                nameLabel.textContent = file.name;
            }

            ['dragenter','dragover'].forEach(function(evt){
                dropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); dropzone.classList.add('dragover'); });
            });
            ['dragleave','drop'].forEach(function(evt){
                dropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); dropzone.classList.remove('dragover'); });
            });
            dropzone.addEventListener('drop', function(e){
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                    fileInput.files = e.dataTransfer.files;
                    setFile(e.dataTransfer.files[0]);
                }
            });
            browseBtn.addEventListener('click', function(){ fileInput.click(); });
            fileInput.addEventListener('change', function(){ setFile(fileInput.files[0]); });
            clearBtn.addEventListener('click', function(){ nameLabel.textContent=''; fileInput.value=''; });
        });
    </script>
</body>
</html>
