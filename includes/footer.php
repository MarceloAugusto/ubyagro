<footer style="margin-top: var(--spacing-xl); padding-top: var(--spacing-lg); border-top: 1px solid var(--glass-border); color: var(--text-secondary); text-align: center; font-size: 0.9rem;">
    <p>&copy; <?= date('Y') ?> UbyOn. Todos os direitos reservados.</p>
</footer>
<a href="#" class="floating-btn" id="aiChatBtn" title="Assistente IA">
    <i class="fas fa-robot"></i>
</a>

<!-- AI Chat Modal -->
<div id="aiChatModal" class="modal-overlay">
    <div class="modal-content xlarge glass-panel">
        <div class="modal-header">
            <h3><i class="fas fa-robot"></i> Assistente UbyOn</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="chat-body">
            <div class="message bot-message">
                <p>Olá! Sou a IA da UbyOn. Como posso ajudar com sua pesquisa hoje?</p>
            </div>
            <!-- Messages will appear here -->
        </div>
        <div class="chat-footer">
            <div class="chat-scope">
                <label class="pill-toggle sm" for="scopeNational">
                    <input type="checkbox" id="scopeNational" value="national">
                    <span><i class="fas fa-location-dot"></i> Nacional</span>
                </label>
                <label class="pill-toggle sm" for="scopeInternational">
                    <input type="checkbox" id="scopeInternational" value="international">
                    <span><i class="fas fa-globe"></i> Internacional</span>
                </label>
            </div>
            <form id="aiChatForm">
                <input type="text" placeholder="Digite seu prompt aqui..." required>
                <button type="submit" class="btn"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="js/script.js"></script>
