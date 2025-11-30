<div class="top-bar">
    <div class="page-title">
        <h1><?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?></h1>
    </div>
    
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" placeholder="Pesquisar...">
    </div>

    <div class="user-profile">
        <div class="notifications">
            <i class="fas fa-bell" style="color: var(--text-secondary); cursor: pointer;"></i>
        </div>
        <div class="avatar">
            U
        </div>
        <span style="font-weight: 500;">Usuário P&D</span>
    </div>
</div>
