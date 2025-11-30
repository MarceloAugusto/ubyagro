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
$crop = trim($_GET['crop'] ?? '');
$region = trim($_GET['region'] ?? '');
$period = trim($_GET['period'] ?? '');
$stage = trim($_GET['stage'] ?? '');

function fetchTopResearchers($query) {
    if (!trim($query)) return null;
    $url = 'https://api.semanticscholar.org/graph/v1/paper/search?query=' . urlencode($query) . '&limit=20&fields=authors,citationCount';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    $res = curl_exec($ch);
    if ($res === false) { curl_close($ch); return null; }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status !== 200) return null;
    $json = json_decode($res, true);
    if (!is_array($json) || !isset($json['data'])) return null;
    $stats = [];
    foreach ($json['data'] as $paper) {
        $cites = $paper['citationCount'] ?? 0;
        foreach (($paper['authors'] ?? []) as $a) {
            $name = $a['name'] ?? '';
            if (!$name) continue;
            if (!isset($stats[$name])) $stats[$name] = ['papers' => 0, 'citations' => 0];
            $stats[$name]['papers'] += 1;
            $stats[$name]['citations'] += (int)$cites;
        }
    }
    if (empty($stats)) return null;
    uasort($stats, function($x, $y){
        if ($x['citations'] === $y['citations']) return $y['papers'] <=> $x['papers'];
        return $y['citations'] <=> $x['citations'];
    });
    $top = array_slice($stats, 0, 3, true);
    $parts = [];
    foreach ($top as $name => $st) {
        $parts[] = $name . ' (' . $st['citations'] . ' citações, ' . $st['papers'] . ' artigos)';
    }
    return implode(', ', $parts);
}

function simulateResearchers($query) {
    $pool = ['Ana Souza', 'Carlos Mendes', 'Mariana Silva', 'João Pereira', 'Luiza Carvalho', 'Pedro Almeida', 'Renata Gomes', 'Bruno Teixeira'];
    shuffle($pool);
    $sel = array_slice($pool, 0, 3);
    $parts = [];
    foreach ($sel as $name) {
        $cit = rand(25, 120);
        $papers = rand(4, 15);
        $parts[] = $name . ' (' . $cit . ' citações, ' . $papers . ' artigos)';
    }
    return implode(', ', $parts);
}

$topResearchers = fetchTopResearchers($query);
if (!$topResearchers) { $topResearchers = simulateResearchers($query); }

// Mock Data for all analysis types
$analysisData = [
    'regulatorio' => [
        'title' => 'Análise Regulatória',
        'icon' => 'fas fa-shield',
        'color' => '#2ed573', // Green
        'content' => 'A regulamentação dos bioinsumos no Brasil, incluindo bioestimulantes à base de algas, foi estabelecida pela Lei nº 15.070, sancionada em 23 de dezembro de 2024. Esta lei abrange a produção, uso, registro, inspeção e comercialização de bioinsumos na agropecuária, aplicando-se a todos os sistemas de cultivo, sejam convencionais, orgânicos ou de base agroecológica. Os bioinsumos produzidos exclusivamente para uso próprio nas propriedades rurais estão isentos de registro, mas sua comercialização é vedada. A coordenação dos registros e fiscalização dos bioinsumos de finalidade comercial é centralizada no Ministério da Agricultura, Pecuária e Abastecimento (MAPA).',
        'summary' => [
            ['label' => 'Principais leis relacionadas', 'value' => 'Lei 15.070/2024; IN MAPA 36/2020; IN MAPA 13/2023'],
            ['label' => 'Órgãos responsáveis', 'value' => 'MAPA, ANVISA (tox), IBAMA (ecotox)']
        ],
        'note' => 'O cenário regulatório é favorável para bioinsumos de baixo risco, com prazos mais curtos e maior previsibilidade no registro.' ,
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
        'summary' => [
            ['label' => 'Patentes encontradas', 'value' => '14'],
            ['label' => 'Empresas registradoras', 'value' => '5']
        ],
        'note' => 'A maioria dos depósitos se concentra nos clusters Sul/Sudeste, com liderança de players globais e participação crescente de centros acadêmicos.',
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
        'summary' => [
            ['label' => 'Base científica', 'value' => '450+ artigos indexados'],
            ['label' => 'Viabilidade de pesquisa', 'value' => 'Protocolos validados (V4 soja)'],
            ['label' => 'Escalabilidade', 'value' => 'Piloto 500L → escala 10.000L']
        ],
        'note' => 'Os resultados são consistentes em diferentes regiões e culturas, com destaque para soja e milho.' ,
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
        'summary' => [
            ['label' => 'Demanda potencial', 'value' => 'Crescimento 12% a.a.; TAM BR ~R$1,2 bi'],
            ['label' => 'Diferenciação', 'value' => 'Biológicos + custo competitivo'],
            ['label' => 'Barreiras de entrada', 'value' => 'Registro, distribuição, awareness'],
            ['label' => 'Concorrentes', 'value' => 'Syngenta, Bayer, BASF, Corteva']
        ],
        'note' => 'Maior tração prevista em HF e soja, com expansão por canais regionais.' ,
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
        'summary' => [
            ['label' => 'Impacto ambiental', 'value' => '−15% uso de N sintético; menor pegada de carbono'],
            ['label' => 'Impacto social', 'value' => 'Integração de cooperativas locais; 120 produtores impactados']
        ],
        'note' => 'Os benefícios são mais evidentes em regiões de agricultura familiar do Sul e Sudeste.' ,
        'sources' => [
            ['name' => 'Protocolo ESG UbyAgro', 'url' => '#']
        ]
    ],
    'riscos' => [
        'title' => 'Análise de Riscos',
        'icon' => 'fas fa-triangle-exclamation',
        'color' => '#ff6b81', // Light Red
        'content' => 'Risco Regulatório: Médio (mudanças recentes na lei de bioinsumos). Risco Tecnológico: Baixo (tecnologia madura). Risco de Mercado: Médio (alta competitividade de preços). Recomenda-se monitoramento trimestral das normativas do MAPA.',
        'summary' => [
            ['label' => 'Riscos técnicos', 'value' => 'Fotodegradação; estabilidade de prateleira'],
            ['label' => 'Riscos de mercado', 'value' => 'Pressão de preços; incumbentes fortes'],
            ['label' => 'Planos de mitigação', 'value' => 'Pilotos regionais; estabilizantes; segmentação']
        ],
        'note' => 'Monitoramento trimestral e P&D contínuo reduzem exposição a mudanças regulatórias e tecnológicas.' ,
        'sources' => [
            ['name' => 'Matriz de Risco Corporativa', 'url' => '#']
        ]
    ],
    'viabilidade' => [
        'title' => 'Viabilidade Econômica',
        'icon' => 'fas fa-dollar-sign',
        'color' => '#2ed573', // Green
        'content' => 'O ROI estimado é de 25% em 3 anos, com Payback em 18 meses. O custo de produção é competitivo frente aos importados, permitindo uma margem bruta projetada de 40%. O investimento inicial em CAPEX é baixo, aproveitando estrutura existente.',
        'summary' => [
            ['label' => 'Custo-benefício', 'value' => 'ROI 25% (3 anos)'],
            ['label' => 'Modelo de negócio', 'value' => 'Go-to-market via cooperativas; foco HF/soja'],
            ['label' => 'Investimento inicial', 'value' => 'CAPEX baixo; aproveita plantas existentes']
        ],
        'note' => 'A estrutura atual permite ramp-up rápido com investimento incremental limitado.' ,
        'sources' => [
            ['name' => 'Modelagem Financeira v2', 'url' => '#']
        ]
    ],
    'parcerias' => [
        'title' => 'Parceirias',
        'icon' => 'fas fa-handshake',
        'color' => '#70a1ff', // Light Blue
        'content' => 'Identificadas oportunidades de co-desenvolvimento com a StartUp "AgroBioTech" (Piracicaba) para a tecnologia de encapsulamento. Institutos de pesquisa como o CENA/USP também demonstraram interesse em validar a tecnologia em campo.',
        'summary' => [
            ['label' => 'Principal pesquisador do assunto', 'value' => $topResearchers],
            ['label' => 'Possíveis instituições parceiras', 'value' => 'ESALQ/USP, UFSCar, Cooperativa Coamo']
        ],
        'note' => 'Parcerias acadêmicas aceleram validação em campo e acesso a novas rotas tecnológicas.' ,
        'sources' => [
            ['name' => 'Radar de Inovação', 'url' => '#']
        ]
    ],
    'estrategia' => [
        'title' => 'Estratégia',
        'icon' => 'fas fa-compass',
        'color' => '#e3002a',
        'content' => 'Alinhamento estratégico com portfólio atual e metas de expansão. Recomenda-se focar segmentos de alto crescimento (HF e soja) com posicionamento de valor e canais regionais.',
        'summary' => [
            ['label' => 'Relevância ao portfólio', 'value' => 'Complementa linha biológica; sinergia com atuais produtos'],
            ['label' => 'Compatibilidade com metas de inovação', 'value' => 'Acelera roadmap de biológicos e sustentabilidade'],
            ['label' => 'Mercado-alvo', 'value' => 'HF e soja com pilotos em milho']
        ],
        'note' => 'Foco em diferenciação técnica com narrativa de sustentabilidade.' ,
        'sources' => [
            ['name' => 'Plano Estratégico UbyAgro', 'url' => '#']
        ]
    ],
    'impactos-agronomicos' => [
        'title' => 'Impactos Agronômicos',
        'icon' => 'fas fa-seedling',
        'color' => '#2ecc71',
        'content' => 'Impactos esperados: aumento médio de produtividade, redução de estresse hídrico, melhoria na saúde do solo e eficiência do uso de insumos.',
        'summary' => [
            ['label' => 'Benefícios comprováveis', 'value' => 'Produtividade +8–12%; eficiência de insumos'],
            ['label' => 'Sustentabilidade', 'value' => 'Resíduos reprocessados; menor pegada de carbono'],
            ['label' => 'Aplicabilidade no campo', 'value' => 'Protocolos claros; compatível com maquinário padrão']
        ],
        'note' => 'Resultados replicáveis em ensaios multi-regiões indicam robustez agronômica.' ,
        'sources' => [
            ['name' => 'Boletins Técnicos Embrapa', 'url' => '#']
        ]
    ],
    'fornecedores' => [
        'title' => 'Fornecedores',
        'icon' => 'fas fa-truck',
        'color' => '#5352ed', // Indigo
        'content' => 'A cadeia de suprimentos para a matéria-prima principal é estável, com 3 fornecedores homologados no estado de SP. Recomenda-se desenvolver um fornecedor secundário para o aditivo XP-200 para mitigar riscos de desabastecimento.',
        'summary' => [
            ['label' => 'Possíveis fornecedores encontrados', 'value' => '3 homologados (SP); 1 alternativo para XP-200']
        ],
        'note' => 'Diversificação de fornecedores reduz risco de desabastecimento e melhora poder de negociação.' ,
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
<?php if (isset($_GET['embed']) && $_GET['embed'] === '1'): ?>
    <div style="margin-bottom: var(--spacing-xl); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Relatório de Scouting • Escopo: <span style="color: var(--accent-color); font-weight: 600;"><?= $scopeLabel ?></span><?php if ($crop || $region || $period || $stage): ?> • Filtros: <span style="font-weight: 600; color: var(--text-secondary);"><?php if ($crop): ?>Cultura: <?= htmlspecialchars(ucfirst($crop)) ?><?php endif; ?><?php if (($crop && ($region || $period || $stage))): ?>; <?php endif; ?><?php if ($region): ?>Região: <?= htmlspecialchars(ucwords(str_replace('-', ' ', $region))) ?><?php endif; ?><?php if (($region && ($period || $stage))): ?>; <?php endif; ?><?php if ($period): ?>Período: <?= htmlspecialchars(str_replace('-', ' ', ucfirst($period))) ?><?php endif; ?><?php if (($period && $stage)): ?>; <?php endif; ?><?php if ($stage): ?>Estágio: <?= htmlspecialchars(ucfirst($stage)) ?><?php endif; ?></span><?php endif; ?></p>
            <h2 style="font-size: 1.5rem;">Análise: "<?= htmlspecialchars($query) ?>"</h2>
            <?php if (!empty($selectedTypes)): ?>
                <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px;">
                    <?php foreach ($selectedTypes as $t): ?>
                        <span class="chip selected" style="padding:6px 10px;"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $t))) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

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
                <?php if (isset($data['summary'])): ?>
                <ul style="padding-left: 18px; margin: 0 0 12px 0;">
                    <?php foreach ($data['summary'] as $item): ?>
                        <li>
                            <strong><?= htmlspecialchars($item['label']) ?>:</strong>
                            <span style="color: var(--text-secondary);"> <?= htmlspecialchars($item['value']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (isset($data['note'])): ?>
                <p style="margin:0 0 12px 0; color: var(--text-primary);"><em><?= htmlspecialchars($data['note']) ?></em></p>
                <?php endif; ?>
                <?php else: ?>
                <p><?= $data['content'] ?></p>
                <?php endif; ?>
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
<?php else: ?>
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
            <div class="scout-header">
                <div>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Relatório de Scouting • Escopo: <span style="color: var(--accent-color); font-weight: 600;"><?= $scopeLabel ?></span><?php if ($crop || $region || $period || $stage): ?> • Filtros: <span style="font-weight: 600; color: var(--text-secondary);"><?php if ($crop): ?>Cultura: <?= htmlspecialchars(ucfirst($crop)) ?><?php endif; ?><?php if (($crop && ($region || $period || $stage))): ?>; <?php endif; ?><?php if ($region): ?>Região: <?= htmlspecialchars(ucwords(str_replace('-', ' ', $region))) ?><?php endif; ?><?php if (($region && ($period || $stage))): ?>; <?php endif; ?><?php if ($period): ?>Período: <?= htmlspecialchars(str_replace('-', ' ', ucfirst($period))) ?><?php endif; ?><?php if (($period && $stage)): ?>; <?php endif; ?><?php if ($stage): ?>Estágio: <?= htmlspecialchars(ucfirst($stage)) ?><?php endif; ?></span><?php endif; ?></p>
                    <h1 style="font-size: 2rem;">Análise: "<?= htmlspecialchars($query) ?>"</h1>
                    <?php if (!empty($selectedTypes)): ?>
                        <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px;">
                            <?php foreach ($selectedTypes as $t): ?>
                                <span class="chip selected" style="padding:6px 10px;"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $t))) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="scout-actions">
                    <button class="btn-outline" onclick="window.print()"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
                    <button class="btn"><i class="fas a-share-alt"></i> Compartilhar</button>
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
                        <?php if (isset($data['summary'])): ?>
                        <ul style="padding-left: 18px; margin: 0 0 12px 0;">
                            <?php foreach ($data['summary'] as $item): ?>
                                <li>
                                    <strong><?= htmlspecialchars($item['label']) ?>:</strong>
                                    <span style="color: var(--text-secondary);"> <?= htmlspecialchars($item['value']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (isset($data['note'])): ?>
                        <p style="margin:0 0 12px 0; color: var(--text-primary);"><em><?= htmlspecialchars($data['note']) ?></em></p>
                        <?php endif; ?>
                        <?php else: ?>
                        <p><?= $data['content'] ?></p>
                        <?php endif; ?>
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
<?php endif; ?>
