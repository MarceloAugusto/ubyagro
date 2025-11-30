<?php
$pageTitle = 'Histórico';
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbyOn - Histórico</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="card glass-panel" style="display:flex; align-items:center; gap:16px;">
                <div class="history-icon" style="width:64px; height:64px; border-radius:16px; background: rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-clock-rotate-left" style="font-size:1.4rem; color: var(--primary-color);"></i>
                </div>
                <div>
                    <h1 style="margin:0;">Histórico</h1>
                    <p style="margin:4px 0 0 0; color: var(--text-secondary);">Suas pesquisas anteriores</p>
                </div>
            </div>

            <div id="historyList" class="history-list"></div>

            <?php include '../includes/footer.php'; ?>
        </main>
    </div>

    <script>
        function formatDate(dateStr){
            try{
                const d = new Date(dateStr);
                const dia = new Intl.DateTimeFormat('pt-BR', { day: '2-digit' }).format(d);
                const mesNome = new Intl.DateTimeFormat('pt-BR', { month: 'long' }).format(d);
                const horaMin = new Intl.DateTimeFormat('pt-BR', { hour: '2-digit', minute: '2-digit' }).format(d);
                return dia + ' de ' + mesNome + ' às ' + horaMin;
            }catch(e){
                return dateStr;
            }
        }

        function scopeLabel(scopes){
            if (!scopes || !scopes.length) return '';
            if (scopes.length === 2) return 'Nacional + Internacional';
            return scopes[0] === 'national' ? 'Nacional' : 'Internacional';
        }

        function renderHistory(){
            const el = document.getElementById('historyList');
            const raw = localStorage.getItem('search_history') || '[]';
            let items = [];
            try{ items = JSON.parse(raw) || []; }catch(e){ items = []; }
            if (!items.length){
                el.innerHTML = '<div class="card glass-panel"><p style="margin:0; color: var(--text-secondary);">Nenhuma pesquisa recente.</p></div>';
                return;
            }
            const html = items.slice().reverse().map(function(it){
                const label = scopeLabel(it.scopes || []);
                const dateTxt = formatDate(it.date);
                const scopesParam = encodeURIComponent((it.scopes||[]).join(','));
                const qParam = encodeURIComponent(it.q || '');
                const href = 'scout.php?q=' + qParam + '&scopes=' + scopesParam;
                return (
                    '<a class="history-item" href="' + href + '" style="display:flex; align-items:center; gap:16px; background:white; border-radius:20px; padding:16px 20px; box-shadow: var(--shadow-sm); text-decoration:none; color:inherit;">'
                    + '<div class="history-badge" style="width:56px; height:56px; border-radius:16px; background: rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:center;">'
                    + '<i class="fas fa-location-dot" style="color: var(--text-secondary);"></i>'
                    + '</div>'
                    + '<div style="flex:1;">'
                    + '<div style="font-weight:600;">' + (it.q || '') + '</div>'
                    + '<div style="display:flex; align-items:center; gap:10px; margin-top:6px;">'
                    + (label ? '<span class="chip selected" style="padding:4px 10px; font-size:0.8rem;">' + label + '</span>' : '')
                    + '<span style="color: var(--text-secondary); font-size:0.9rem;">' + dateTxt + '</span>'
                    + '</div>'
                    + '</div>'
                    + '<div style="color:#9aa0a6;"><i class="fas fa-chevron-right"></i></div>'
                    + '</a>'
                );
            }).join('');
            el.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', renderHistory);
    </script>
</body>
</html>
