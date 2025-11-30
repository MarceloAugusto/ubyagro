<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="img/logo.png" alt="UbyOn Logo" class="logo-img" style="width: 190px;">
    </div>
    <ul class="nav-links">
        <li>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-search"></i> Explorar (Scouting)
            </a>
        </li>
        <li>
            <a href="scout.php" class="<?= basename($_SERVER['PHP_SELF']) == 'scout.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> Análises Recentes
            </a>
        </li>
        <li>
            <a href="ai-tools.php" class="<?= basename($_SERVER['PHP_SELF']) == 'ai-tools.php' ? 'active' : '' ?>">
                <i class="fas fa-brain"></i> Inteligência & RAG
            </a>
        </li>
        <li>
            <a href="research.php" class="<?= basename($_SERVER['PHP_SELF']) == 'research.php' ? 'active' : '' ?>">
                <i class="fas fa-book-open"></i> Pesquisa & Artigos
            </a>
        </li>
        <li>
            <a href="data.php" class="<?= basename($_SERVER['PHP_SELF']) == 'data.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Dados Agronômicos
            </a>
        </li>
        <li>
            <a href="patents.php" class="<?= basename($_SERVER['PHP_SELF']) == 'patents.php' ? 'active' : '' ?>">
                <i class="fas fa-certificate"></i> Patentes
            </a>
        </li>
        <li>
            <a href="safety.php" class="<?= basename($_SERVER['PHP_SELF']) == 'safety.php' ? 'active' : '' ?>">
                <i class="fas fa-shield-alt"></i> Segurança (FISPQ)
            </a>
        </li>
    </ul>
</aside>
