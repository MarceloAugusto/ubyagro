<?php
$pageTitle = 'Explorar (Scouting)';
require_once '../config/db.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Explorar</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <!-- Search Engine Interface -->
            <div style="max-width: 800px; margin: 0 auto; padding-top: 50px; text-align: center;">
                <h1 style="font-size: 3rem; margin-bottom: 30px; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    O que você quer descobrir hoje?
                </h1>
                
                <form id="searchForm" action="scout.php" method="GET" style="position: relative; margin-bottom: 30px;" onsubmit="handleSearch(event)">
                    <!-- Mode Toggle -->
                    <div style="display: flex; justify-content: flex-start; gap: 10px; margin-bottom: 10px; align-items: center; font-size: 0.95rem;">
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px; color: var(--text-secondary);">
                            <input type="radio" name="search_mode" value="report" checked onchange="updateSearchMode(this.value)" style="accent-color: var(--primary-color);">
                            <span><i class="fas fa-file-alt"></i> Gerar Relatório</span>
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px; color: var(--text-secondary);">
                            <input type="radio" name="search_mode" value="chat" onchange="updateSearchMode(this.value)" style="accent-color: var(--accent-color);">
                            <span><i class="fas fa-comments"></i> Chat com IA</span>
                        </label>
                    </div>

                    <div style="position: relative;">
                        <i id="searchIcon" class="fas fa-search" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 1.2rem;"></i>
                        <input type="text" name="q" id="searchInput" placeholder="Pesquise por moléculas, ingredientes ativos, concorrentes..." 
                               style="width: 100%; padding: 20px 140px 20px 55px; border-radius: 30px; border: 1px solid var(--glass-border); background: var(--surface-light); color: var(--text-primary); font-size: 1.1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); outline: none; transition: all 0.3s;">
                        <button type="submit" class="search-action" style="position: absolute; right: 6px; top: 6px; height: 48px; padding: 0 24px; border-radius: 24px; border: none; background: linear-gradient(180deg, var(--primary-color), var(--secondary-color)); color: #fff; font-weight: 600; box-shadow: var(--shadow-sm);">Analisar</button>
                    </div>

                    <div id="scopeChips" style="display:flex; gap:8px; margin-top: 10px; justify-content: flex-start; text-align: left;">
                        <label class="pill-toggle sm selected">
                            <input type="checkbox" value="national" checked>
                            <span><i class="fas fa-location-dot"></i> Nacional</span>
                        </label>
                        <label class="pill-toggle sm">
                            <input type="checkbox" value="international">
                            <span><i class="fas fa-globe"></i> Internacional</span>
                        </label>
                        <input type="hidden" name="scopes" id="selectedScopes" value="national">
                    </div>
                    
                    <div style="margin-top: 24px; text-align: left;">
                        <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-weight:600; color: var(--text-primary);">Selecione os tipos de análise:</span>
                            <div style="display:flex; gap:16px;">
                                <a href="#" id="selectAll" style="color: var(--primary-color);">Selecionar todos</a>
                                <a href="#" id="clearAll" style="color: var(--text-secondary);">Limpar</a>
                            </div>
                        </div>
                        <div id="analysisChips" style="display:flex; flex-wrap:wrap; gap:10px;">
                            <button type="button" class="chip" data-value="regulatorio"><i class="fas fa-shield"></i> Regulatório</button>
                            <button type="button" class="chip" data-value="patentes"><i class="fas fa-certificate"></i> Patentes</button>
                            <button type="button" class="chip" data-value="tecnico"><i class="fas fa-flask"></i> Técnico-Científico</button>
                            <button type="button" class="chip" data-value="analise-tecnica"><i class="fas fa-clipboard-list"></i> Análise Técnica</button>
                            <button type="button" class="chip" data-value="mercado"><i class="fas fa-chart-line"></i> Mercado</button>
                            <button type="button" class="chip" data-value="esg"><i class="fas fa-leaf"></i> ESG</button>
                            <button type="button" class="chip" data-value="riscos"><i class="fas fa-triangle-exclamation"></i> Riscos</button>
                            <button type="button" class="chip" data-value="viabilidade"><i class="fas fa-dollar-sign"></i> Viabilidade</button>
                            <button type="button" class="chip" data-value="parcerias"><i class="fas fa-handshake"></i> Possíveis Parcerias</button>
                            <button type="button" class="chip" data-value="fornecedores"><i class="fas fa-truck"></i> Fornecedores</button>
                        </div>
                        <input type="hidden" name="types" id="selectedTypes" value="">
                    </div>
                </form>

                <script>
                    function updateSearchMode(mode) {
                        const input = document.getElementById('searchInput');
                        const icon = document.getElementById('searchIcon');
                        
                        if (mode === 'chat') {
                            input.placeholder = "Pergunte algo para o assistente IA...";
                            icon.className = "fas fa-robot";
                        } else {
                            input.placeholder = "Pesquise por moléculas, ingredientes ativos, concorrentes...";
                            icon.className = "fas fa-search";
                    }
                    }

                    function handleSearch(e) {
                        const mode = document.querySelector('input[name="search_mode"]:checked').value;
                        if (mode === 'chat') {
                            e.preventDefault();
                            const query = document.getElementById('searchInput').value;
                            // sync scopes to chat modal toggles
                            const selectedScopesVal = document.getElementById('selectedScopes').value.split(',').map(function(s){return s.trim();});
                            if (query.trim()) {
                                // Open Modal
                                const modal = document.getElementById('aiChatModal');
                                if (modal) {
                                    modal.classList.add('active');
                                    // Populate chat input if possible, or send message immediately
                                    // For now, let's just focus the input in the modal or simulate a message
                                    const chatInput = modal.querySelector('input[type="text"]');
                                    if (chatInput) {
                                        chatInput.value = query;
                                        chatInput.focus();
                                    }
                                    // set chat scope checkboxes accordingly
                                    const chatNational = modal.querySelector('#scopeNational');
                                    const chatInternational = modal.querySelector('#scopeInternational');
                                    if (chatNational) {
                                        chatNational.checked = selectedScopesVal.includes('national');
                                        chatNational.closest('.pill-toggle')?.classList.toggle('selected', chatNational.checked);
                                    }
                                    if (chatInternational) {
                                        chatInternational.checked = selectedScopesVal.includes('international');
                                        chatInternational.closest('.pill-toggle')?.classList.toggle('selected', chatInternational.checked);
                                    }
                                }
                            }
                        }
                        // If report, let the form submit naturally to scout.php
                    }
                    // Scope chips logic
                    const scopeChips = document.getElementById('scopeChips');
                    const scopesInput = document.getElementById('selectedScopes');
                    function updateScopes(){
                        const values = Array.from(scopeChips.querySelectorAll('input[type="checkbox"]'))
                            .filter(function(i){return i.checked;})
                            .map(function(i){return i.value});
                        scopesInput.value = values.join(',');
                    }
                    scopeChips.addEventListener('click', function(e){
                        const label = e.target.closest('.pill-toggle');
                        if (label){
                            const input = label.querySelector('input');
                            if (input){
                                input.checked = !input.checked;
                                label.classList.toggle('selected', input.checked);
                                updateScopes();
                            }
                        }
                    });
                    const chips = document.getElementById('analysisChips');
                    const typesInput = document.getElementById('selectedTypes');
                    function updateTypes(){
                        const values = Array.from(chips.querySelectorAll('.chip.selected')).map(function(c){return c.getAttribute('data-value')});
                        typesInput.value = values.join(',');
                    }
                    chips.addEventListener('click', function(e){
                        if (e.target.closest('.chip')){
                            const chip = e.target.closest('.chip');
                            chip.classList.toggle('selected');
                            updateTypes();
                        }
                    });
                    document.getElementById('selectAll').addEventListener('click', function(e){
                        e.preventDefault();
                        chips.querySelectorAll('.chip').forEach(function(c){c.classList.add('selected')});
                        updateTypes();
                    });
                    document.getElementById('clearAll').addEventListener('click', function(e){
                        e.preventDefault();
                        chips.querySelectorAll('.chip').forEach(function(c){c.classList.remove('selected')});
                        updateTypes();
                    });
                </script>

            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
