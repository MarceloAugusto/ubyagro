function setupSidebarInteractions() {
    const navLinks = document.querySelectorAll('.nav-links a');
    const settingsToggle = document.getElementById('settingsMenu');
    const settingsItem = settingsToggle ? settingsToggle.parentElement : null;

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    if (settingsToggle && settingsItem) {
        settingsToggle.addEventListener('click', function (e) {
            e.preventDefault();
            settingsItem.classList.toggle('open');
        });
        const activeSubItem = settingsItem.querySelector('.submenu a.active');
        if (activeSubItem) {
            settingsItem.classList.add('open');
        }
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupSidebarInteractions);
} else {
    setupSidebarInteractions();
}

// Chart.js Implementations
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: { color: '#b0bec5' }
        }
    },
    scales: {
        y: {
            grid: { color: 'rgba(255, 255, 255, 0.1)' },
            ticks: { color: '#b0bec5' }
        },
        x: {
            grid: { color: 'rgba(255, 255, 255, 0.1)' },
            ticks: { color: '#b0bec5' }
        }
    }
};

// 1. Trend Chart (Index)
const trendCtx = document.getElementById('trendChart');
if (trendCtx) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Adoção de Biológicos',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#ff8095',
                backgroundColor: 'rgba(100, 255, 218, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: commonOptions
    });
}

// 2. Market Chart (AI Tools)
const marketCtx = document.getElementById('marketChart');
if (marketCtx) {
    new Chart(marketCtx, {
        type: 'bar',
        data: {
            labels: ['Soja', 'Milho', 'Algodão', 'Trigo', 'Café'],
            datasets: [{
                label: 'Previsão de Demanda (Ton)',
                data: [500, 300, 200, 150, 100],
                backgroundColor: [
                    'rgba(0, 77, 64, 0.8)',
                    'rgba(0, 121, 107, 0.8)',
                    'rgba(100, 255, 218, 0.8)',
                    'rgba(38, 166, 154, 0.8)',
                    'rgba(178, 223, 219, 0.8)'
                ],
                borderColor: '#e3002a',
                borderWidth: 1
            }]
        },
        options: commonOptions
    });
}

// 3. Usage Chart (Data)
const usageCtx = document.getElementById('usageChart');
if (usageCtx) {
    new Chart(usageCtx, {
        type: 'doughnut',
        data: {
            labels: ['Bioinseticidas', 'Biofungicidas', 'Bionematicidas'],
            datasets: [{
                data: [45, 30, 25],
                backgroundColor: ['#e3002a', '#ff4d6a', '#ff8095'],
                borderColor: '#121212',
                borderWidth: 2
            }]
        },
        options: {
            ...commonOptions,
            scales: { x: { display: false }, y: { display: false } }
        }
    });
}

// 4. Price Chart (Data)
const priceCtx = document.getElementById('priceChart');
if (priceCtx) {
    new Chart(priceCtx, {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [{
                label: 'Preço Médio (R$/L)',
                data: [45, 48, 52, 50, 55],
                borderColor: '#ff8a65',
                backgroundColor: 'rgba(255, 138, 101, 0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: commonOptions
    });
}

// AI Chat Modal Logic
const aiChatBtn = document.getElementById('aiChatBtn');
const aiChatModal = document.getElementById('aiChatModal');
const closeModal = document.querySelector('.close-modal');
const aiChatForm = document.getElementById('aiChatForm');
const chatBody = document.querySelector('.chat-body');
const scopeNational = document.getElementById('scopeNational');
const scopeInternational = document.getElementById('scopeInternational');
const chatScope = document.querySelector('.chat-scope');

if (aiChatBtn && aiChatModal) {
    aiChatBtn.addEventListener('click', (e) => {
        e.preventDefault();
        aiChatModal.classList.add('active');
    });

    closeModal.addEventListener('click', () => {
        aiChatModal.classList.remove('active');
    });

    aiChatModal.addEventListener('click', (e) => {
        if (e.target === aiChatModal) {
            aiChatModal.classList.remove('active');
        }
    });

    // Toggle selected state for scope chips
    if (chatScope) {
        chatScope.addEventListener('click', (e) => {
            const label = e.target.closest('.pill-toggle');
            if (label) {
                const input = label.querySelector('input');
                if (input) {
                    input.checked = !input.checked;
                    label.classList.toggle('selected', input.checked);
                }
            }
        });
    }

    // Handle Chat Submission (Mock)
    aiChatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const input = aiChatForm.querySelector('input');
        const message = input.value.trim();
        const scopes = [];
        if (scopeNational && scopeNational.checked) scopes.push('national');
        if (scopeInternational && scopeInternational.checked) scopes.push('international');

        if (message) {
            // Add User Message
            addMessage(message, 'user-message');
            input.value = '';

            // Show loading state
            const loadingId = 'loading-' + Date.now();
            const loadingDiv = document.createElement('div');
            loadingDiv.classList.add('message', 'bot-message');
            loadingDiv.id = loadingId;
            loadingDiv.innerHTML = '<p><i class="fas fa-spinner fa-spin"></i> Processando...</p>';
            chatBody.appendChild(loadingDiv);
            chatBody.scrollTop = chatBody.scrollHeight;

            // Call API
            fetch('api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message, scopes: scopes })
            })
                .then(response => response.json())
                .then(data => {
                    // Remove loading
                    const loadingElement = document.getElementById(loadingId);
                    if (loadingElement) loadingElement.remove();

                    if (data.reply) {
                        // Parse Markdown to HTML
                        const htmlReply = marked.parse(data.reply);
                        addMessage(htmlReply, 'bot-message', true);
                    } else if (data.error) {
                        addMessage('Erro: ' + data.error, 'bot-message');
                    }
                })
                .catch(error => {
                    const loadingElement = document.getElementById(loadingId);
                    if (loadingElement) loadingElement.remove();
                    addMessage('Erro de conexão: ' + error.message, 'bot-message');
                });
        }
    });
}

function addMessage(text, className, isHtml = false) {
    const div = document.createElement('div');
    div.classList.add('message', className);
    if (isHtml) {
        div.innerHTML = text;
    } else {
        div.innerHTML = `<p>${text}</p>`;
    }
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
}

