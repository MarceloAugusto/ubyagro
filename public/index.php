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
            <div style="max-width: 800px; margin: 0 auto; padding-top: 0px; text-align: center;">
                <h1 style="font-family: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'; font-size: clamp(1.6rem, 4.5vw, 2.2rem); font-weight: 800; line-height: 1.1; margin-bottom: 40px;">
                    <span style="color: var(--text-primary);">Explore novas oportunidades</span><br>
                    <span style="color: var(--primary-color);">com dados precisos</span>
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
                        <input type="text" name="q" id="searchInput" placeholder="Ex: Bioestimulantes de microalgas na cultura da soja" 
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
                        <button type="button" id="openFilters" class="pill-toggle sm" style="margin-left:8px; background: transparent; border: none; padding: 0;">
                            <i class="fas fa-sliders"></i> Filtros
                        </button>
                        <input type="hidden" name="crop" id="selectedCrop" value="">
                        <input type="hidden" name="region" id="selectedRegion" value="">
                        <input type="hidden" name="period" id="selectedPeriod" value="">
                        <input type="hidden" name="stage" id="selectedStage" value="">
                    </div>
                    
                    <div style="margin-top: 50px; text-align: left;">
                        <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-weight:600; color: var(--text-primary);">Selecione os tipos de análise:</span>
                            <div style="display:flex; gap:16px; align-items:center;">
                                <a href="#" id="selectAll" style="color: var(--primary-color);">Selecionar todos</a>
                                <a href="#" id="clearAll" style="color: var(--text-secondary);">Limpar</a>
                            </div>
                        </div>
                        <div id="analysisChips" style="display:flex; flex-wrap:wrap; gap:10px;">
                            <button type="button" class="chip" data-value="regulatorio"><i class="fas fa-shield"></i> Regulatório</button>
                            <button type="button" class="chip" data-value="patentes"><i class="fas fa-certificate"></i> Patentes</button>
                            <button type="button" class="chip" data-value="tecnico"><i class="fas fa-flask"></i> Técnico-Científico</button>
                            <button type="button" class="chip" data-value="mercado"><i class="fas fa-chart-line"></i> Mercado</button>
                            <button type="button" class="chip" data-value="estrategia"><i class="fas fa-compass"></i> Estratégia</button>
                            <button type="button" class="chip" data-value="esg"><i class="fas fa-leaf"></i> ESG</button>
                            <button type="button" class="chip" data-value="riscos"><i class="fas fa-triangle-exclamation"></i> Riscos</button>
                            <button type="button" class="chip" data-value="viabilidade"><i class="fas fa-dollar-sign"></i> Viabilidade</button>
                            <button type="button" class="chip" data-value="parcerias"><i class="fas fa-handshake"></i> Parceirias</button>
                            <button type="button" class="chip" data-value="impactos-agronomicos"><i class="fas fa-seedling"></i> Impactos Agronômicos</button>
                            <button type="button" class="chip" data-value="fornecedores"><i class="fas fa-truck"></i> Fornecedores</button>
                        </div>
                        <input type="hidden" name="types" id="selectedTypes" value="">
                    </div>
                </form>

                <div id="filtersModal" class="modal-overlay">
                    <div class="modal-content glass-panel">
                        <div class="modal-header">
                            <h3><i class="fas fa-sliders"></i> Filtros adicionais</h3>
                            <button class="close-modal" id="closeFilters">&times;</button>
                        </div>
                        <div class="chat-body" style="gap:20px;">
                            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; width:100%;">
                                <div>
                                    <span style="display:block; margin-bottom:6px; color: var(--text-secondary); font-weight:500;">Cultura</span>
                                    <select id="filterCrop" style="width:100%; padding:10px 12px; border:1px solid var(--glass-border); border-radius:10px; background:white;">
                                        <option value="">Todas</option>
                                        <option value="soja">Soja</option>
                                        <option value="milho">Milho</option>
                                        <option value="hf">Hortifruti</option>
                                        <option value="cana">Cana-de-açúcar</option>
                                        <option value="cafe">Café</option>
                                        <option value="trigo">Trigo</option>
                                        <option value="algodao">Algodão</option>
                                    </select>
                                </div>
                                <div>
                                    <span style="display:block; margin-bottom:6px; color: var(--text-secondary); font-weight:500;">Região</span>
                                    <select id="filterRegion" style="width:100%; padding:10px 12px; border:1px solid var(--glass-border); border-radius:10px; background:white;">
                                        <option value="">Todas</option>
                                        <option value="sul">Sul</option>
                                        <option value="sudeste">Sudeste</option>
                                        <option value="centro-oeste">Centro-Oeste</option>
                                        <option value="norte">Norte</option>
                                        <option value="nordeste">Nordeste</option>
                                        <option value="america-latina">América Latina</option>
                                        <option value="america-do-norte">América do Norte</option>
                                        <option value="europa">Europa</option>
                                        <option value="asia">Ásia</option>
                                    </select>
                                </div>
                                <div>
                                    <span style="display:block; margin-bottom:6px; color: var(--text-secondary); font-weight:500;">Período de análise</span>
                                    <select id="filterPeriod" style="width:100%; padding:10px 12px; border:1px solid var(--glass-border); border-radius:10px; background:white;">
                                        <option value="">Sem recorte</option>
                                        <option value="2-anos">Últimos 2 anos</option>
                                        <option value="5-anos">Últimos 5 anos</option>
                                        <option value="10-anos">Últimos 10 anos</option>
                                    </select>
                                </div>
                                <div>
                                    <span style="display:block; margin-bottom:6px; color: var(--text-secondary); font-weight:500;">Estágio de desenvolvimento</span>
                                    <select id="filterStage" style="width:100%; padding:10px 12px; border:1px solid var(--glass-border); border-radius:10px; background:white;">
                                        <option value="">Todos</option>
                                        <option value="pesquisa">Pesquisa</option>
                                        <option value="piloto">Piloto</option>
                                        <option value="comercial">Comercial</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chat-footer" style="justify-content:flex-end;">
                            <a href="#" id="clearFilters" style="color: var(--text-secondary); margin-right:auto;">Limpar</a>
                            <button class="btn" id="applyFilters"><i class="fas fa-check"></i> Aplicar filtros</button>
                        </div>
                    </div>
                </div>

                <script>
                    function updateSearchMode(mode) {
                        const input = document.getElementById('searchInput');
                        const icon = document.getElementById('searchIcon');
                        
                        if (mode === 'chat') {
                            input.placeholder = "Pergunte algo para o assistente IA...";
                            icon.className = "fas fa-robot";
                        } else {
                            input.placeholder = "Ex: Bioestimulantes de microalgas na cultura da soja";
                            icon.className = "fas fa-search";
                    }
                    }

                    function handleSearch(e) {
                        const mode = document.querySelector('input[name="search_mode"]:checked').value;
                        const query = document.getElementById('searchInput').value.trim();
                        const scopes = document.getElementById('selectedScopes').value;
                        const types = document.getElementById('selectedTypes').value;
                        const crop = document.getElementById('selectedCrop').value;
                        const region = document.getElementById('selectedRegion').value;
                        const period = document.getElementById('selectedPeriod').value;
                        const stage = document.getElementById('selectedStage').value;

                        if (mode === 'chat') {
                            e.preventDefault();
                            const selectedScopesVal = scopes.split(',').map(function(s){return s.trim();});
                            if (query) {
                                try {
                                    const raw = localStorage.getItem('search_history') || '[]';
                                    const arr = JSON.parse(raw) || [];
                                    arr.push({ q: query, scopes: selectedScopesVal, date: new Date().toISOString(), mode: 'chat' });
                                    localStorage.setItem('search_history', JSON.stringify(arr.slice(-50)));
                                } catch(e){}
                            }
                            if (query) {
                                const modal = document.getElementById('aiChatModal');
                                if (modal) {
                                    modal.classList.add('active');
                                    const chatInput = modal.querySelector('input[type="text"]');
                                    if (chatInput) {
                                        chatInput.value = query;
                                        chatInput.focus();
                                    }
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
                        } else {
                            // Report mode: fetch results and inject into results section
                            e.preventDefault();
                            const results = document.getElementById('searchResults');
                            const resultsBody = document.getElementById('searchResultsBody');
                            if (!results || !resultsBody) return;
                            results.classList.add('loading');
                            resultsBody.innerHTML = '<div class="message bot-message"><p><i class="fas fa-spinner fa-spin"></i> Carregando resultados...</p></div>';
                            try {
                                const raw = localStorage.getItem('search_history') || '[]';
                                const arr = JSON.parse(raw) || [];
                                arr.push({ q: query, scopes: scopes.split(',').map(function(s){return s.trim();}), date: new Date().toISOString(), mode: 'report' });
                                localStorage.setItem('search_history', JSON.stringify(arr.slice(-50)));
                            } catch(e){}
                            const params = new URLSearchParams({ embed: '1', q: query, scopes: scopes, types: types, crop: crop, region: region, period: period, stage: stage });
                            fetch('scout.php?' + params.toString())
                                .then(function(r){ return r.text(); })
                                .then(function(html){
                                    resultsBody.innerHTML = html;
                                    results.classList.remove('loading');
                                    // Scroll into view
                                    results.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                })
                                .catch(function(err){
                                    resultsBody.innerHTML = '<div class="message bot-message"><p>Erro ao carregar resultados: ' + err.message + '</p></div>';
                                    results.classList.remove('loading');
                                });
                        }
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

                    const openFiltersBtn = document.getElementById('openFilters');
                    const filtersModal = document.getElementById('filtersModal');
                    const closeFiltersBtn = document.getElementById('closeFilters');
                    const applyFiltersBtn = document.getElementById('applyFilters');
                    const clearFiltersBtn = document.getElementById('clearFilters');

                    function closeFilters(){ filtersModal.classList.remove('active'); }
                    if (openFiltersBtn) openFiltersBtn.addEventListener('click', function(){ filtersModal.classList.add('active'); });
                    if (closeFiltersBtn) closeFiltersBtn.addEventListener('click', closeFilters);
                    if (filtersModal) filtersModal.addEventListener('click', function(e){ if (e.target === filtersModal) closeFilters(); });
                    if (applyFiltersBtn) applyFiltersBtn.addEventListener('click', function(){
                        const cropSel = document.getElementById('filterCrop');
                        const regionSel = document.getElementById('filterRegion');
                        const periodSel = document.getElementById('filterPeriod');
                        const stageSel = document.getElementById('filterStage');
                        document.getElementById('selectedCrop').value = cropSel ? cropSel.value : '';
                        document.getElementById('selectedRegion').value = regionSel ? regionSel.value : '';
                        document.getElementById('selectedPeriod').value = periodSel ? periodSel.value : '';
                        document.getElementById('selectedStage').value = stageSel ? stageSel.value : '';
                        closeFilters();
                    });
                    if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', function(e){
                        e.preventDefault();
                        const cropSel = document.getElementById('filterCrop');
                        const regionSel = document.getElementById('filterRegion');
                        const periodSel = document.getElementById('filterPeriod');
                        const stageSel = document.getElementById('filterStage');
                        if (cropSel) cropSel.value = '';
                        if (regionSel) regionSel.value = '';
                        if (periodSel) periodSel.value = '';
                        if (stageSel) stageSel.value = '';
                        document.getElementById('selectedCrop').value = '';
                        document.getElementById('selectedRegion').value = '';
                        document.getElementById('selectedPeriod').value = '';
                        document.getElementById('selectedStage').value = '';
                    });
                </script>

                <!-- Results Section -->
                <div id="search-results" style="margin-top: 20px; text-align: left; max-width: 1100px; margin-left: auto; margin-right: auto;">
                    <div id="searchResults" style="background: transparent; border: none; box-shadow: none; padding: 0;">
                        <div id="searchResultsBody" style="padding: 0 0 20px 0;"></div>
                    </div>
                </div>

            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
