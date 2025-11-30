<?php
$pageTitle = 'Visão estratégica';
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Visão estratégica</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="content-wrapper">
                <div class="page-header">
                    <h1><i class="fas fa-chart-line"></i> Visão estratégica</h1>
                    <p class="subtitle">Visão geral do agronegócio e inteligência competitiva</p>
                </div>

                <!-- First Row: News and Trends -->
                <div class="dashboard-grid grid-2" style="margin-bottom: 30px;">
                    <!-- Recent News Card -->
                    <div class="card glass-panel">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3><i class="fas fa-newspaper"></i> Notícias Recentes</h3>
                            <a href="#" style="color: var(--primary-color); font-size: 0.9rem;">Ver todas</a>
                        </div>
                        <div class="news-list">
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=400&h=300&fit=crop" alt="Bioestimulantes">
                                </div>
                                <div class="news-content">
                                    <h4>Nova tecnologia de bioestimulantes aprovada</h4>
                                    <p>Ministério da Agricultura aprova uso de novo composto para soja</p>
                                    <span class="news-time"><i class="fas fa-clock"></i> Há 2 horas</span>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=400&h=300&fit=crop" alt="Biológicos">
                                </div>
                                <div class="news-content">
                                    <h4>Mercado de biológicos cresce 25% no Brasil</h4>
                                    <p>Setor registra expansão acima da média global</p>
                                    <span class="news-time"><i class="fas fa-clock"></i> Há 5 horas</span>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=400&h=300&fit=crop" alt="Pesquisa">
                                </div>
                                <div class="news-content">
                                    <h4>Pesquisa revela eficácia de peptídeos</h4>
                                    <p>Estudo da Embrapa mostra resultados promissores</p>
                                    <span class="news-time"><i class="fas fa-clock"></i> Há 1 dia</span>
                                </div>
                            </div>
                            <div class="news-item">
                                <div class="news-image">
                                    <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=400&h=300&fit=crop" alt="Exportações">
                                </div>
                                <div class="news-content">
                                    <h4>Exportações de defensivos aumentam 18%</h4>
                                    <p>Primeiro trimestre supera expectativas do setor</p>
                                    <span class="news-time"><i class="fas fa-clock"></i> Há 2 dias</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trends Card -->
                    <div class="card glass-panel">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3><i class="fas fa-fire"></i> Tendências</h3>
                            <a href="#" style="color: var(--primary-color); font-size: 0.9rem;">Explorar</a>
                        </div>
                        <div class="trends-list">
                            <div class="trend-item">
                                <div class="trend-rank" style="background: linear-gradient(135deg, #e3002a, #ff4d6a);">1</div>
                                <div class="trend-content">
                                    <h4>Bionematicidas</h4>
                                    <div class="trend-stats">
                                        <span class="trend-growth"><i class="fas fa-arrow-up"></i> +45%</span>
                                        <span class="trend-searches">12.5k buscas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="trend-item">
                                <div class="trend-rank" style="background: linear-gradient(135deg, #ff4d6a, #ff8095);">2</div>
                                <div class="trend-content">
                                    <h4>RNA Interferência</h4>
                                    <div class="trend-stats">
                                        <span class="trend-growth"><i class="fas fa-arrow-up"></i> +38%</span>
                                        <span class="trend-searches">9.8k buscas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="trend-item">
                                <div class="trend-rank" style="background: linear-gradient(135deg, #ff8095, #ffb3c1);">3</div>
                                <div class="trend-content">
                                    <h4>Microbioma do Solo</h4>
                                    <div class="trend-stats">
                                        <span class="trend-growth"><i class="fas fa-arrow-up"></i> +32%</span>
                                        <span class="trend-searches">8.2k buscas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="trend-item">
                                <div class="trend-rank" style="background: linear-gradient(135deg, #ffb3c1, #ffd4db);">4</div>
                                <div class="trend-content">
                                    <h4>Agricultura Regenerativa</h4>
                                    <div class="trend-stats">
                                        <span class="trend-growth"><i class="fas fa-arrow-up"></i> +28%</span>
                                        <span class="trend-searches">7.1k buscas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="trend-item">
                                <div class="trend-rank" style="background: linear-gradient(135deg, #ffd4db, #ffe8ed);">5</div>
                                <div class="trend-content">
                                    <h4>Bioestimulantes Foliares</h4>
                                    <div class="trend-stats">
                                        <span class="trend-growth"><i class="fas fa-arrow-up"></i> +25%</span>
                                        <span class="trend-searches">6.5k buscas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Visualizations (from Data page) -->
                <div class="dashboard-grid grid-2" style="margin-bottom: 30px;">
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

                <!-- Second Row: Map -->
                <div class="card glass-panel" style="padding: 0; overflow: hidden;">
                    <div class="card-header" style="padding: 20px 30px; border-bottom: 1px solid var(--glass-border);">
                        <h3><i class="fas fa-map-marked-alt"></i> Mapa de Atividades Agronômicas</h3>
                        <p style="margin: 5px 0 0 0; color: var(--text-secondary); font-size: 0.9rem;">Distribuição geográfica de pesquisas e inovações</p>
                    </div>
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>

    <style>
        .news-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .news-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-radius: 12px;
            background: var(--surface-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .news-item:hover {
            background: rgba(227, 0, 42, 0.05);
            transform: translateX(5px);
        }

        .news-image {
            width: 120px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-item:hover .news-image img {
            transform: scale(1.1);
        }

        .news-content h4 {
            margin: 0 0 5px 0;
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .news-content p {
            margin: 0 0 8px 0;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .news-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trends-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .trend-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 12px;
            background: var(--surface-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .trend-item:hover {
            background: rgba(227, 0, 42, 0.05);
            transform: scale(1.02);
        }

        .trend-rank {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .trend-content {
            flex: 1;
        }

        .trend-content h4 {
            margin: 0 0 8px 0;
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .trend-stats {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
        }

        .trend-growth {
            color: #4caf50;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trend-searches {
            color: var(--text-secondary);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-wrapper {
            padding: 0;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }
        @media (max-width: 768px) {
            
            .news-list {
                gap: 10px;
            }
            .news-item {
                padding: 10px;
                gap: 10px;
                flex-direction: row;
                align-items: center;
            }
            .news-image {
                width: 80px;
                height: 56px;
                border-radius: 8px;
            }
            .news-content h4 {
                font-size: 0.95rem;
                margin: 0;
            }
            .news-content p {
                display: none;
            }
            .news-time {
                font-size: 0.7rem;
            }
            .page-header h1 {
                font-size: 1.6rem;
            }
            #map {
                height: 340px;
            }
        }
    </style>

    <script>
        const map = L.map('map').setView([-14.2350, -51.9253], 4);
        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors', maxZoom: 18 }).addTo(map);

        const locations = [
            { lat: -15.7801, lng: -47.9292, name: 'Brasília - Embrapa Sede', type: 'Pesquisa' },
            { lat: -23.5505, lng: -46.6333, name: 'São Paulo - Centro de Inovação', type: 'Inovação' },
            { lat: -19.9167, lng: -43.9345, name: 'Belo Horizonte - Hub Agro', type: 'Desenvolvimento' },
            { lat: -25.4284, lng: -49.2733, name: 'Curitiba - Polo Tecnológico', type: 'Tecnologia' },
            { lat: -30.0346, lng: -51.2177, name: 'Porto Alegre - Centro Sul', type: 'Pesquisa' },
            { lat: -3.7172, lng: -38.5433, name: 'Fortaleza - Nordeste Agro', type: 'Desenvolvimento' },
            { lat: -12.9714, lng: -38.5014, name: 'Salvador - Biotech BA', type: 'Biotecnologia' },
            { lat: -8.0476, lng: -34.8770, name: 'Recife - AgriTech PE', type: 'Inovação' }
        ];

        const markerColors = {
            'Pesquisa': '#e3002a',
            'Inovação': '#ff4d6a',
            'Desenvolvimento': '#4caf50',
            'Tecnologia': '#2196f3',
            'Biotecnologia': '#ff9800'
        };

        const markersLayer = L.layerGroup();
        locations.forEach(loc => {
            const color = markerColors[loc.type] || '#e3002a';
            L.circleMarker([loc.lat, loc.lng], {
                radius: 7,
                color: '#ffffff',
                weight: 2,
                fillColor: color,
                fillOpacity: 1
            }).bindPopup(`<b>${loc.name}</b><br>${loc.type}`).addTo(markersLayer);
        });
        markersLayer.addTo(map);

        function rand(min, max) { return Math.random() * (max - min) + min; }
        function randomPolygon(centerLat, centerLng, sizeKm) {
            const pts = [];
            for (let i = 0; i < 6; i++) {
                const ang = (Math.PI * 2 * i) / 6;
                const dLat = (sizeKm / 111) * Math.cos(ang);
                const dLng = (sizeKm / (111 * Math.cos(centerLat * Math.PI / 180))) * Math.sin(ang);
                pts.push([centerLat + dLat, centerLng + dLng]);
            }
            return pts;
        }

        const landUseLayer = L.layerGroup();
        for (let i = 0; i < 8; i++) {
            const lat = rand(-27, -3);
            const lng = rand(-70, -40);
            const poly = L.polygon(randomPolygon(lat, lng, 120), {
                color: '#b30020',
                weight: 1,
                fillColor: '#e3002a',
                fillOpacity: 0.15
            }).bindTooltip('Uso de solo');
            poly.addTo(landUseLayer);
        }

        const producersLayer = L.layerGroup();
        for (let i = 0; i < 40; i++) {
            const lat = rand(-30, 5);
            const lng = rand(-70, -35);
            L.circleMarker([lat, lng], {
                radius: 5,
                color: '#2d3436',
                weight: 1,
                fillColor: '#ff9800',
                fillOpacity: 0.8
            }).bindTooltip('Produtor registrado').addTo(producersLayer);
        }

        const influenceLayer = L.layerGroup();
        locations.forEach(loc => {
            L.circle([loc.lat, loc.lng], {
                radius: 120000,
                color: '#2196f3',
                weight: 1,
                fillColor: '#2196f3',
                fillOpacity: 0.15
            }).addTo(influenceLayer);
        });

        const overlays = {
            'Uso de solo': landUseLayer,
            'Produtores registrados': producersLayer,
            'Zona de influência': influenceLayer,
            'Centros': markersLayer
        };
        L.control.layers(null, overlays, { collapsed: false }).addTo(map);
    </script>
</body>
</html>
