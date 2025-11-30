<?php
$pageTitle = 'Detalhes da Análise';
require_once '../config/db.php';

$query = $_GET['q'] ?? '';
$type = $_GET['type'] ?? '';

// Mock Data (Shared structure with scout.php, but with added 'details' section)
$analysisData = [
    'regulatorio' => [
        'title' => 'Análise Regulatória',
        'icon' => 'fas fa-shield',
        'color' => '#2ed573', // Green
        'content' => 'A regulamentação dos bioinsumos no Brasil, incluindo bioestimulantes à base de algas, foi estabelecida pela Lei nº 15.070, sancionada em 23 de dezembro de 2024.',
        'details' => [
            'Marco Legal' => 'Lei nº 15.070/2024 institui o marco legal dos bioinsumos, definindo regras para produção comercial e para uso próprio (on farm).',
            'Registro' => 'O registro de produtos comerciais deve ser feito junto ao MAPA. O processo foi simplificado para produtos de baixo risco, com prazo médio de aprovação reduzido de 24 para 8 meses.',
            'Exigências Técnicas' => 'É necessário apresentar dossiê toxicológico (ANVISA) e ecotoxicológico (IBAMA), além de testes de eficiência agronômica em pelo menos 3 safras/regiões diferentes.',
            'Classificação' => 'O produto pesquisado enquadra-se na Categoria III (Bioestimulante Misto), isento de receituário agronômico para venda.',
        ],
        'sources' => [
            ['name' => 'Senado Federal - Lei 15.070', 'url' => 'https://www12.senado.leg.br'],
            ['name' => 'MAPA - Bioinsumos', 'url' => 'https://www.gov.br/agricultura/pt-br'],
            ['name' => 'Diário Oficial da União', 'url' => '#']
        ]
    ],
    'patentes' => [
        'title' => 'Análise de Patentes',
        'icon' => 'fas fa-certificate',
        'color' => '#1e90ff', // Blue
        'content' => 'Identificadas 14 patentes ativas relacionadas à molécula pesquisada nos últimos 5 anos. A análise de anterioridade sugere liberdade de operação (FTO) parcial.',
        'details' => [
            'FTO (Freedom to Operate)' => 'Risco moderado devido à patente BR1120200056 (BASF) que reivindica o processo de extração enzimática. Recomendamos rota alternativa via hidrólise ácida.',
            'Patentes Expirando' => 'Duas patentes chave de formulação expiram em 2026, abrindo janela para genéricos.',
            'Oportunidades' => 'Não foram encontradas anterioridades para a combinação da molécula com aminoácidos de cadeia ramificada, sugerindo patenteabilidade de nova formulação.',
            'Principais Titulares' => 'BASF (40%), Syngenta (25%), Embrapa (10%), Outros (25%).'
        ],
        'sources' => [
            ['name' => 'Google Patents', 'url' => 'https://patents.google.com'],
            ['name' => 'INPI Busca Web', 'url' => 'https://www.gov.br/inpi/pt-br'],
            ['name' => 'Espacenet', 'url' => '#']
        ]
    ],
    'tecnico' => [
        'title' => 'Técnico-Científico',
        'icon' => 'fas fa-flask',
        'color' => '#a55eea', // Purple
        'content' => 'A base científica é robusta, com mais de 450 artigos indexados. Estudos recentes demonstram eficácia superior em produtividade.',
        'details' => [
            'Eficácia Agronômica' => 'Meta-análise de 25 estudos mostra ganho médio de produtividade de 12% em soja e 8% em milho.',
            'Mecanismo de Ação' => 'Atua na regulação estomática e no sistema antioxidante da planta, mitigando estresse hídrico.',
            'Estabilidade' => 'A molécula é sensível à fotodegradação (meia-vida de 48h sob UV). Necessário uso de protetores solares na formulação.',
            'Compatibilidade' => 'Compatível com a maioria dos fungicidas do mercado, exceto aqueles à base de cobre.'
        ],
        'sources' => [
            ['name' => 'SciELO', 'url' => 'https://scielo.br'],
            ['name' => 'Embrapa Solos', 'url' => 'https://www.embrapa.br/solos'],
            ['name' => 'ScienceDirect', 'url' => '#']
        ]
    ],
    'analise-tecnica' => [
        'title' => 'Análise Técnica',
        'icon' => 'fas fa-clipboard-list',
        'color' => '#ffa502', // Orange
        'content' => 'A viabilidade técnica de produção em escala industrial requer ajustes na linha de fermentação atual.',
        'details' => [
            'Escalabilidade' => 'Processo validado em escala piloto (500L). Scale-up para 10.000L requer otimização de aeração (kLa).',
            'Custos Industriais' => 'OPEX estimado em R$ 12,50/L. Principal driver de custo é o substrato de carbono.',
            'Shelf-life' => 'Testes acelerados indicam estabilidade de 18 meses a 25°C com uso de conservantes (Benzoato de Sódio).',
            'Controle de Qualidade' => 'Método analítico por HPLC já validado internamente.'
        ],
        'sources' => [
            ['name' => 'Relatórios Internos P&D', 'url' => '#'],
            ['name' => 'Laudos de Laboratório Terceiro', 'url' => '#']
        ]
    ],
    'mercado' => [
        'title' => 'Análise de Mercado',
        'icon' => 'fas fa-chart-line',
        'color' => '#ff4757', // Red
        'content' => 'O mercado global cresce a um CAGR de 12%. No Brasil, demanda impulsionada pela safrinha de milho.',
        'details' => [
            'Tamanho do Mercado (TAM)' => 'Estimado em R$ 1.2 Bilhões no Brasil (2024).',
            'Segmentação' => 'Soja (45%), Milho (30%), HF (15%), Outros (10%).',
            'Tendências' => 'Crescente adoção de biológicos por grandes grupos agrícolas para redução da pegada de carbono.',
            'Concorrência' => 'Alta concentração. Top 5 players detêm 60% do market share. Preço médio ao produtor: R$ 45,00/L.'
        ],
        'sources' => [
            ['name' => 'AgroPages', 'url' => 'https://news.agropages.com'],
            ['name' => 'Mordor Intelligence', 'url' => 'https://www.mordorintelligence.com'],
            ['name' => 'Abisolo', 'url' => '#']
        ]
    ],
    'esg' => [
        'title' => 'Análise ESG',
        'icon' => 'fas fa-leaf',
        'color' => '#26de81', // Light Green
        'content' => 'Produto classifica-se como Bioinsumo de Classe A, contribuindo para metas de descarbonização.',
        'details' => [
            'Ambiental (E)' => 'Redução de 15% no uso de fertilizantes nitrogenados sintéticos. Embalagens recicláveis (Campo Limpo).',
            'Social (S)' => 'Matéria-prima proveniente de cooperativas de agricultura familiar no Nordeste.',
            'Governança (G)' => 'Rastreabilidade total da cadeia via Blockchain, auditável por terceiros.',
            'ODS (ONU)' => 'Contribui para ODS 2 (Fome Zero) e ODS 12 (Consumo e Produção Responsáveis).'
        ],
        'sources' => [
            ['name' => 'Protocolo ESG UbyAgro', 'url' => '#'],
            ['name' => 'Pacto Global', 'url' => '#']
        ]
    ],
    'riscos' => [
        'title' => 'Análise de Riscos',
        'icon' => 'fas fa-triangle-exclamation',
        'color' => '#ff6b81', // Light Red
        'content' => 'Riscos monitorados em níveis aceitáveis, com atenção ao cenário regulatório.',
        'details' => [
            'Regulatório' => 'Médio. Possível revisão de critérios para "bioestimulantes" pelo MAPA em 2025.',
            'Suprimentos' => 'Baixo. Contratos de longo prazo firmados com fornecedores de extrato de algas.',
            'Financeiro' => 'Médio. Exposição cambial na importação de aditivos (15% do custo).',
            'Reputacional' => 'Baixo. Histórico limpo de incidentes ambientais.'
        ],
        'sources' => [
            ['name' => 'Matriz de Risco Corporativa', 'url' => '#']
        ]
    ],
    'viabilidade' => [
        'title' => 'Viabilidade Econômica',
        'icon' => 'fas fa-dollar-sign',
        'color' => '#2ed573', // Green
        'content' => 'Indicadores financeiros sólidos sugerem aprovação do projeto.',
        'details' => [
            'VPL (Valor Presente Líquido)' => 'R$ 15.4 Milhões (Taxa de desconto 12%).',
            'TIR (Taxa Interna de Retorno)' => '28.5%.',
            'Payback' => '18 meses (descontado).',
            'Ponto de Equilíbrio' => '25.000 Litros/ano (12% da capacidade instalada).'
        ],
        'sources' => [
            ['name' => 'Modelagem Financeira v2', 'url' => '#']
        ]
    ],
    'parcerias' => [
        'title' => 'Parceirias',
        'icon' => 'fas fa-handshake',
        'color' => '#70a1ff', // Light Blue
        'content' => 'Mapeamento de parceiros estratégicos para aceleração do go-to-market.',
        'details' => [
            'Academia' => 'ESALQ/USP (Testes de campo), UFSCar (Desenvolvimento de formulação).',
            'Startups' => 'AgroBioTech (Nanoencapsulamento), SoluTech (Sensores de solo).',
            'Comercial' => 'Cooperativa Coamo (Distribuição no PR), Coplana (SP).',
            'Fomento' => 'Edital FINEP 2024 (Subvenção econômica para inovação).'
        ],
        'sources' => [
            ['name' => 'Radar de Inovação', 'url' => '#']
        ]
    ],
    'estrategia' => [
        'title' => 'Estratégia',
        'icon' => 'fas fa-compass',
        'color' => '#e3002a',
        'content' => 'Diretrizes de posicionamento competitivo, segmentação de mercado e priorização de portfólio.',
        'details' => [
            'Posicionamento' => 'Foco em diferenciação técnica com narrativa de sustentabilidade.',
            'Segmentos' => 'HF e soja como âncoras, com pilotos em milho.',
            'Canais' => 'Parcerias com cooperativas regionais e distribuidores chave.'
        ],
        'sources' => [
            ['name' => 'Plano Estratégico UbyAgro', 'url' => '#']
        ]
    ],
    'impactos-agronomicos' => [
        'title' => 'Impactos Agronômicos',
        'icon' => 'fas fa-seedling',
        'color' => '#2ecc71',
        'content' => 'Efeitos esperados em produtividade, qualidade e resiliência das culturas.',
        'details' => [
            'Produtividade' => 'Ganhos médios em ensaios controlados superiores a 8–12%.',
            'Resiliência' => 'Mitigação de estresse hídrico e fortalecimento radicular.',
            'Solo' => 'Melhora de indicadores de saúde do solo em áreas tratadas.'
        ],
        'sources' => [
            ['name' => 'Boletins Técnicos Embrapa', 'url' => '#']
        ]
    ],
    'fornecedores' => [
        'title' => 'Fornecedores',
        'icon' => 'fas fa-truck',
        'color' => '#5352ed', // Indigo
        'content' => 'Cadeia de suprimentos mapeada e qualificada.',
        'details' => [
            'Matéria-prima A' => 'Fornecedor A (SP) - Capacidade 50t/mês. Certificado ISO 9001.',
            'Matéria-prima B' => 'Importado (China) - Lead time de 45 dias. Estoque de segurança de 3 meses.',
            'Embalagens' => 'Plastipak (PR) - Contrato guarda-chuva vigente.',
            'Logística' => 'Transportadora JSL - Rota dedicada SP-MT.'
        ],
        'sources' => [
            ['name' => 'Base de Compras', 'url' => '#']
        ]
    ]
];

$currentData = $analysisData[$type] ?? null;

if (!$currentData) {
    // Handle invalid type
    header("Location: scout.php?q=" . urlencode($query));
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Detalhes: <?= htmlspecialchars($currentData['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <!-- Breadcrumb / Back Action -->
            <div style="margin-bottom: 20px;">
                <a href="scout.php?q=<?= urlencode($query) ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-secondary); text-decoration: none; font-weight: 500; transition: color 0.2s;">
                    <i class="fas fa-arrow-left"></i> Voltar para resultados
                </a>
            </div>

            <!-- Main Title Card -->
            <div class="card glass-panel" style="margin-bottom: 30px; border-left: 6px solid <?= $currentData['color'] ?>; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 10rem; opacity: 0.05; color: <?= $currentData['color'] ?>;">
                    <i class="<?= $currentData['icon'] ?>"></i>
                </div>
                <a href="#" class="btn" style="position: absolute; top: 16px; right: 16px; z-index: 2;">
                    <i class="fas fa-download"></i> Baixar Relatório
                </a>
                
                <div style="display: flex; align-items: center; gap: 20px; position: relative; z-index: 1;">
                    <div style="width: 60px; height: 60px; border-radius: 12px; background: <?= $currentData['color'] ?>20; display: flex; align-items: center; justify-content: center;">
                        <i class="<?= $currentData['icon'] ?>" style="font-size: 1.8rem; color: <?= $currentData['color'] ?>;"></i>
                    </div>
                    <div>
                        <p style="margin:0; color: var(--text-secondary); font-size: 0.9rem;">Análise Detalhada</p>
                        <h1 style="margin: 5px 0 0 0; font-size: 2rem; color: var(--text-primary);"><?= $currentData['title'] ?></h1>
                    </div>
                </div>
                
                <div style="margin-top: 25px; padding-top: 25px; border-top: 1px solid rgba(0,0,0,0.05);">
                    <p style="font-size: 1.1rem; line-height: 1.6; color: var(--text-primary); max-width: 900px;">
                        <?= $currentData['content'] ?>
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Left Column: Deep Dive Details -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="card glass-panel">
                        <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; color: var(--text-primary);">
                            <i class="fas fa-search-plus" style="color: var(--accent-color);"></i> Pontos Chave
                        </h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php if (isset($currentData['details'])): ?>
                                <?php foreach ($currentData['details'] as $key => $value): ?>
                                <div style="padding: 15px; background: rgba(255,255,255,0.5); border-radius: 8px; border: 1px solid rgba(0,0,0,0.03);">
                                    <strong style="display: block; color: <?= $currentData['color'] ?>; margin-bottom: 6px; font-size: 0.95rem;"><?= $key ?></strong>
                                    <span style="color: var(--text-secondary); line-height: 1.5;"><?= $value ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="color: var(--text-secondary);">Detalhes adicionais não disponíveis no momento.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sources & Metadata -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Sources Card -->
                    <div class="card glass-panel">
                        <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-primary);">
                            <i class="fas fa-link" style="color: var(--text-secondary); font-size: 0.9rem;"></i> Fontes Utilizadas
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <?php foreach ($currentData['sources'] as $source): ?>
                                <a href="<?= $source['url'] ?>" target="_blank" class="source-tag" style="justify-content: space-between; width: 100%; box-sizing: border-box;">
                                    <span><i class="fas fa-external-link-alt" style="margin-right: 8px; font-size: 0.8rem;"></i> <?= $source['name'] ?></span>
                                    <i class="fas fa-chevron-right" style="font-size: 0.7rem; opacity: 0.5;"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- AI Confidence Card (Mock) -->
                    <div class="card glass-panel">
                        <h3 style="margin-bottom: 10px; font-size: 1rem; color: var(--text-secondary);">Confiabilidade da Análise</h3>
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                            <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">94%</div>
                            <div style="flex: 1; height: 6px; background: rgba(0,0,0,0.05); border-radius: 3px; overflow: hidden;">
                                <div style="width: 94%; height: 100%; background: var(--primary-color);"></div>
                            </div>
                        </div>
                        <p style="font-size: 0.8rem; color: var(--text-secondary);">Baseado em cruzamento de dados de <?= count($currentData['sources']) + 3 ?> fontes verificadas.</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    
                </div>
            </div>

            <div class="card glass-panel" style="margin-top: var(--spacing-xl); border-left: 4px solid <?= $currentData['color'] ?>;">
                <h3 style="display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-clipboard"></i> Resumo
                </h3>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <p style="line-height:1.7; color: var(--text-primary);">
                        Esta análise de <strong><?= htmlspecialchars($currentData['title']) ?></strong> para "<?= htmlspecialchars($query) ?>" consolida evidências regulatórias, científicas e de mercado para apoiar a tomada de decisão. Em síntese: <?= htmlspecialchars($currentData['content']) ?>. O escopo e as fontes selecionadas fornecem contexto suficiente para estimar riscos e oportunidades no curto e médio prazo.
                    </p>
                    <p style="line-height:1.7; color: var(--text-primary);">
                        Com base nos pontos chave apresentados, recomenda-se avançar com validações direcionadas e alinhadas ao portfólio atual, priorizando regiões de maior tração e parcerias institucionais. O conjunto de fontes consultadas (<?= count($currentData['sources']) ?> principais) sustenta a consistência dos resultados e indica caminhos de diferenciação.
                    </p>
                    <p style="line-height:1.7; color: var(--text-primary);">
                        Próximos passos incluem aprofundar <em>due diligence</em> técnica e regulatória, consolidar métricas de desempenho em campo e estruturar o posicionamento competitivo para o mercado-alvo, considerando cronograma e recursos necessários.
                    </p>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>
</body>
</html>
