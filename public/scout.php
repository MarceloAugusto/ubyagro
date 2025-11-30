<?php
$pageTitle = 'Resultado da Análise';
require_once '../config/db.php';

$query = $_GET['q'] ?? '';
$scopesParam = $_GET['scopes'] ?? '';
$scopes = array_filter(array_map('trim', explode(',', $scopesParam)));
if (empty($scopes)) {
    $scope = $_GET['scope'] ?? 'national';
    $scopes = [$scope];
}
$scopeLabel = implode(' + ', array_map(function($s){ return $s === 'national' ? 'Nacional (BR)' : 'Internacional'; }, $scopes));
$typesParam = $_GET['types'] ?? '';
$selectedTypes = array_filter(array_map('trim', explode(',', $typesParam)));

// Mock Data for all analysis types
$analysisData = [
    'regulatorio' => [
        'title' => 'Análise Regulatória',
        'icon' => 'fas fa-shield',
        'color' => '#2ed573', // Green
        'content' => 'A regulamentação dos bioinsumos no Brasil, incluindo bioestimulantes à base de algas, foi estabelecida pela Lei nº 15.070, sancionada em 23 de dezembro de 2024. Esta lei abrange a produção, uso, registro, inspeção e comercialização de bioinsumos na agropecuária, aplicando-se a todos os sistemas de cultivo, sejam convencionais, orgânicos ou de base agroecológica. Os bioinsumos produzidos exclusivamente para uso próprio nas propriedades rurais estão isentos de registro, mas sua comercialização é vedada. A coordenação dos registros e fiscalização dos bioinsumos de finalidade comercial é centralizada no Ministério da Agricultura, Pecuária e Abastecimento (MAPA).',
        'sources' => [
            ['name' => 'www12.senado.leg.br', 'url' => 'https://www12.senado.leg.br'],
            ['name' => 'gov.br/mapa', 'url' => 'https://www.gov.br/agricultura/pt-br']
        ]
    ],
    'patentes' => [
        'title' => 'Análise de Patentes',
        'icon' => 'fas fa-certificate',
        'color' => '#1e90ff', // Blue
        'content' => 'Identificadas 14 patentes ativas relacionadas à molécula pesquisada nos últimos 5 anos. A análise de anterioridade sugere liberdade de operação (FTO) parcial, com atenção especial a duas patentes da BASF que expiram em 2026. Existem oportunidades para patenteamento de novas formulações combinadas com aditivos de liberação lenta.',
        'sources' => [
            ['name' => 'Google Patents', 'url' => 'https://patents.google.com'],
            ['name' => 'INPI', 'url' => 'https://www.gov.br/inpi/pt-br']
        ]
    ],
    'tecnico' => [
        'title' => 'Técnico-Científico',
        'icon' => 'fas fa-flask',
        'color' => '#a55eea', // Purple
        'content' => 'A base científica é robusta, com mais de 450 artigos indexados. Estudos recentes da Embrapa (2023) demonstram eficácia de 15% superior em produtividade de soja quando aplicado no estágio V4. A estabilidade da formulação é um ponto crítico abordado em 30% das publicações recentes.',
        'sources' => [
            ['name' => 'SciELO', 'url' => 'https://scielo.br'],
            ['name' => 'Embrapa Solos', 'url' => 'https://www.embrapa.br/solos']
        ]
    ],
    'analise-tecnica' => [
        'title' => 'Análise Técnica',
        'icon' => 'fas fa-clipboard-list',
        'color' => '#ffa502', // Orange
        'content' => 'A viabilidade técnica de produção em escala industrial requer ajustes na linha de fermentação atual. O ingrediente ativo demonstra alta compatibilidade com os adjuvantes padrão da UbyAgro, mas testes de prateleira indicam necessidade de estabilizantes adicionais para shelf-life superior a 12 meses.',
        'sources' => [
            ['name' => 'Relatórios Internos P&D', 'url' => '#']
        ]
    ],
    'mercado' => [
        'title' => 'Análise de Mercado',
        'icon' => 'fas fa-chart-line',
        'color' => '#ff4757', // Red
        'content' => 'O mercado global para esta classe de produtos cresce a um CAGR de 12%. No Brasil, a demanda é impulsionada pela safrinha de milho. Principais concorrentes (Syngenta, Bayer) já possuem produtos posicionados, mas há uma lacuna de mercado para soluções de baixo custo focadas em agricultura familiar e HF.',
        'sources' => [
            ['name' => 'AgroPages', 'url' => 'https://news.agropages.com'],
            ['name' => 'Mordor Intelligence', 'url' => 'https://www.mordorintelligence.com']
        ]
    ],
    'esg' => [
        'title' => 'Análise ESG',
        'icon' => 'fas fa-leaf',
        'color' => '#26de81', // Light Green
        'content' => 'O produto classifica-se como Bioinsumo de Classe A, contribuindo positivamente para metas de descarbonização. O processo produtivo utiliza resíduos da indústria sucroalcooleira, alinhando-se com princípios de economia circular. Não há passivos ambientais significativos identificados.',
        'sources' => [
            ['name' => 'Protocolo ESG UbyAgro', 'url' => '#']
        ]
    ],
    'riscos' => [
        'title' => 'Análise de Riscos',
        'icon' => 'fas fa-triangle-exclamation',
        'color' => '#ff6b81', // Light Red
        'content' => 'Risco Regulatório: Médio (mudanças recentes na lei de bioinsumos). Risco Tecnológico: Baixo (tecnologia madura). Risco de Mercado: Médio (alta competitividade de preços). Recomenda-se monitoramento trimestral das normativas do MAPA.',
        'sources' => [
            ['name' => 'Matriz de Risco Corporativa', 'url' => '#']
        ]
    ],
    'viabilidade' => [
        'title' => 'Viabilidade Econômica',
        'icon' => 'fas fa-dollar-sign',
        'color' => '#2ed573', // Green
        'content' => 'O ROI estimado é de 25% em 3 anos, com Payback em 18 meses. O custo de produção é competitivo frente aos importados, permitindo uma margem bruta projetada de 40%. O investimento inicial em CAPEX é baixo, aproveitando estrutura existente.',
        'sources' => [
            ['name' => 'Modelagem Financeira v2', 'url' => '#']
        ]
    ],
    'parcerias' => [
        'title' => 'Possíveis Parcerias',
        'icon' => 'fas fa-handshake',
        'color' => '#70a1ff', // Light Blue
        'content' => 'Identificadas oportunidades de co-desenvolvimento com a StartUp "AgroBioTech" (Piracicaba) para a tecnologia de encapsulamento. Institutos de pesquisa como o CENA/USP também demonstraram interesse em validar a tecnologia em campo.',
        'sources' => [
            ['name' => 'Radar de Inovação', 'url' => '#']
        ]
    ],
    'fornecedores' => [
        'title' => 'Fornecedores',
        'icon' => 'fas fa-truck',
        'color' => '#5352ed', // Indigo
        'content' => 'A cadeia de suprimentos para a matéria-prima principal é estável, com 3 fornecedores homologados no estado de SP. Recomenda-se desenvolver um fornecedor secundário para o aditivo XP-200 para mitigar riscos de desabastecimento.',
        'sources' => [
            ['name' => 'Base de Compras', 'url' => '#']
        ]
    ]
];

// Filter types to display
$displayTypes = [];
if (empty($selectedTypes)) {
    // Default if none selected: show main pillars
    $displayTypes = ['regulatorio', 'patentes', 'tecnico', 'mercado'];
} else {
    $displayTypes = $selectedTypes;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Scouting: <?= htmlspecialchars($query) ?></title>
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

            <!-- Header with Search Context -->
            <div style="margin-bottom: var(--spacing-xl); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Relatório de Scouting • Escopo: <span style="color: var(--accent-color); font-weight: 600;"><?= $scopeLabel ?></span></p>
                    <h1 style="font-size: 2rem;">Análise: "<?= htmlspecialchars($query) ?>"</h1>
                    <?php if (!empty($selectedTypes)): ?>
                        <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px;">
                            <?php foreach ($selectedTypes as $t): ?>
                                <span class="chip selected" style="padding:6px 10px;"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $t))) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="btn-outline" onclick="window.print()"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
                    <button class="btn"><i class="fas fa-share-alt"></i> Compartilhar</button>
                </div>
            </div>

            <!-- Executive Summary -->
            <div class="card glass-panel" style="margin-bottom: var(--spacing-xl); border-left: 4px solid var(--primary-color);">
                <h3><i class="fas fa-clipboard-check"></i> Resumo Executivo (IA)</h3>
                <p style="line-height: 1.6; color: var(--text-primary);">
                    A análise preliminar para <strong><?= htmlspecialchars($query) ?></strong> indica um potencial <strong style="color: #4ecdc4;">ALTO</strong> de viabilidade técnica, mas com barreiras regulatórias moderadas no cenário <?= $scopeLabel ?>. 
                    Observa-se um crescimento de demanda de 12% a.a., impulsionado pela busca por soluções sustentáveis. 
                    <br><br>
                    <strong>Recomendação:</strong> Aprofundar estudos de formulação e iniciar pré-consultas regulatórias.
                </p>
            </div>

            <!-- Analysis Accordions -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($displayTypes as $type): 
                    $data = $analysisData[$type] ?? null;
                    if (!$data) continue;
                ?>
                <div class="accordion-card glass-panel expanded" style="border-left: 4px solid <?= $data['color'] ?>;">
                    <div class="accordion-header">
                        <div class="accordion-title" style="color: <?= $data['color'] ?>;">
                            <i class="<?= $data['icon'] ?>"></i>
                            <h3><?= $data['title'] ?></h3>
                        </div>
                        <a href="detail.php?type=<?= $type ?>&q=<?= urlencode($query) ?>" class="btn-details">
                            <i class="fas fa-eye"></i> Ver detalhes
                        </a>
                    </div>
                    <div class="accordion-content">
                        <p><?= $data['content'] ?></p>
                        <div class="sources-list">
                            <span class="sources-label"><i class="fas fa-book-open"></i> FONTES</span>
                            <?php foreach ($data['sources'] as $source): ?>
                                <a href="<?= $source['url'] ?>" target="_blank" class="source-tag">
                                    <i class="fas fa-link"></i> <?= $source['name'] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>

    <script>
        // Mock Chart for Market Share
        const chartCanvas = document.getElementById('marketShareChart');
        if (chartCanvas) {
            const ctx = chartCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Concorrente A', 'Concorrente B', 'Outros', 'Lacuna de Mercado'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { color: '#a0a0a0' }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
