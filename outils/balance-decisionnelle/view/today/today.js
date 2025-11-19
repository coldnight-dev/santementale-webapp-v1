// Vue TODAY - Mes Décisions

return (function(state, constants) {
    
    let selectedIcon = 'psychology';
    let currentDecisionId = null;

    function init() {
        renderDecisions();
        setupEventListeners();
        renderIconSelector();
    }

    function setupEventListeners() {
        const addBtn = document.getElementById('addDecisionBtn');
        if (addBtn) {
            addBtn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                selectedIcon = 'psychology';
                document.getElementById('decisionTitle').value = '';
                document.getElementById('decisionImportance').value = 5;
                document.getElementById('importanceDisplay').textContent = 5;
                renderIconSelector();
                window.openPopup('newDecisionPopup');
            };
        }

        const importanceSlider = document.getElementById('decisionImportance');
        if (importanceSlider) {
            importanceSlider.oninput = (e) => {
                document.getElementById('importanceDisplay').textContent = e.target.value;
            };
        }

        const form = document.getElementById('newDecisionForm');
        if (form) {
            form.onsubmit = (e) => {
                e.preventDefault();
                createDecision();
            };
        }
    }
    }

    function renderIconSelector() {
        const container = document.getElementById('iconSelector');
        container.innerHTML = constants.DECISION_ICONS.map(icon => 
            `<div class="icon-item ${icon === selectedIcon ? 'selected' : ''}" data-icon="${icon}">
                <span class="material-symbols-outlined">${icon}</span>
            </div>`
        ).join('');
        
        container.querySelectorAll('.icon-item').forEach(el => {
            el.onclick = () => {
                selectedIcon = el.dataset.icon;
                renderIconSelector();
            };
        });
    }

    function createDecision() {
        const title = document.getElementById('decisionTitle').value.trim();
        const importance = parseInt(document.getElementById('decisionImportance').value);
        
        if (!title) return;

        const decision = {
            id: 'dec_' + Date.now(),
            title: title,
            icon: selectedIcon,
            importance: importance,
            createdAt: new Date().toISOString(),
            prosArgs: [],
            consArgs: [],
            status: 'active', // active, completed, archived
            finalChoice: null, // 'pros' ou 'cons'
            confidence: 0
        };

        state.decisions.push(decision);
        saveDecisions();
        renderDecisions();
        window.closePopup('newDecisionPopup');
        
        // Notification
        showNotification('✅ Décision créée !');
    }

    function renderDecisions() {
        const container = document.getElementById('decisionsContainer');
        const activeDecisions = state.decisions.filter(d => d.status === 'active');
        
        if (activeDecisions.length === 0) {
            container.innerHTML = `
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 text-center">
                    <span class="material-symbols-outlined text-zinc-600" style="font-size:64px;">psychology</span>
                    <p class="text-zinc-400 mt-4">Aucune décision en cours</p>
                    <p class="text-zinc-500 text-sm mt-2">Créez votre première balance décisionnelle</p>
                </div>
            `;
            return;
        }

        container.innerHTML = activeDecisions.map(dec => {
            const score = calculateDecisionScore(dec);
            const prosCount = dec.prosArgs.length;
            const consCount = dec.consArgs.length;
            
            return `
                <div class="decision-card bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4 cursor-pointer hover:border-blue-600 transition" data-id="${dec.id}">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-400" style="font-size:32px;">${dec.icon}</span>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-2">${dec.title}</h3>
                            <div class="flex items-center gap-4 text-sm text-zinc-400">
                                <span>💚 ${prosCount} pour</span>
                                <span>❤️ ${consCount} contre</span>
                                <span>⭐ Importance ${dec.importance}/10</span>
                            </div>
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span>Tendance actuelle</span>
                                    <span class="font-bold">${score.label}</span>
                                </div>
                                <div class="w-full h-2 bg-zinc-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-green-500 to-blue-500" style="width:${score.percent}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        // Event listeners
        container.querySelectorAll('.decision-card').forEach(card => {
            card.onclick = () => openDecisionDetail(card.dataset.id);
        });
    }

    function calculateDecisionScore(decision) {
        const prosTotal = decision.prosArgs.reduce((sum, arg) => sum + arg.weight, 0);
        const consTotal = decision.consArgs.reduce((sum, arg) => sum + arg.weight, 0);
        const total = prosTotal + consTotal;
        
        if (total === 0) {
            return { percent: 50, label: 'Non analysé', score: 0 };
        }
        
        const percent = (prosTotal / total) * 100;
        let label = '';
        
        if (percent >= 70) label = 'Fortement Pour';
        else if (percent >= 60) label = 'Plutôt Pour';
        else if (percent >= 40) label = 'Indécis';
        else if (percent >= 30) label = 'Plutôt Contre';
        else label = 'Fortement Contre';
        
        return { percent: Math.round(percent), label, score: percent };
    }

    function openDecisionDetail(decisionId) {
        currentDecisionId = decisionId;
        const decision = state.decisions.find(d => d.id === decisionId);
        if (!decision) return;

        document.getElementById('detailTitle').textContent = decision.title;
        renderDecisionDetail(decision);
        window.openPopup('decisionDetailPopup');
    }

    function renderDecisionDetail(decision) {
        const score = calculateDecisionScore(decision);
        const container = document.getElementById('decisionDetailContent');
        
        let html = `
            <!-- Score global -->
            <div class="bg-zinc-900 rounded-lg p-4 mb-4">
                <div class="text-center mb-3">
                    <div class="text-4xl font-bold mb-1">${score.percent}%</div>
                    <div class="text-zinc-400">${score.label}</div>
                </div>
                <div class="w-full h-3 bg-zinc-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-500 to-blue-500" style="width:${score.percent}%"></div>
                </div>
            </div>

            <!-- Arguments POUR -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500">thumb_up</span>
                        Arguments POUR (${decision.prosArgs.length})
                    </h3>
                    <button onclick="window.addArgument('pros')" class="px-3 py-1 bg-green-600 rounded text-sm">+ Ajouter</button>
                </div>
                <div id="prosArgsContainer">
                    ${decision.prosArgs.length === 0 ? '<p class="text-zinc-500 text-sm">Aucun argument pour le moment</p>' : 
                        decision.prosArgs.map((arg, idx) => `
                            <div class="bg-zinc-800 rounded-lg p-3 mb-2">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="flex-1">${arg.text}</p>
                                    <button onclick="window.removeArgument('pros', ${idx})" class="text-red-500 ml-2">
                                        <span class="material-symbols-outlined" style="font-size:20px;">delete</span>
                                    </button>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span>Poids:</span>
                                    <input type="range" min="1" max="10" value="${arg.weight}" 
                                        onchange="window.updateArgWeight('pros', ${idx}, this.value)" 
                                        class="flex-1">
                                    <span class="font-bold w-8 text-right">${arg.weight}/10</span>
                                </div>
                            </div>
                        `).join('')
                    }
                </div>
            </div>

            <!-- Arguments CONTRE -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-500">thumb_down</span>
                        Arguments CONTRE (${decision.consArgs.length})
                    </h3>
                    <button onclick="window.addArgument('cons')" class="px-3 py-1 bg-red-600 rounded text-sm">+ Ajouter</button>
                </div>
                <div id="consArgsContainer">
                    ${decision.consArgs.length === 0 ? '<p class="text-zinc-500 text-sm">Aucun argument pour le moment</p>' : 
                        decision.consArgs.map((arg, idx) => `
                            <div class="bg-zinc-800 rounded-lg p-3 mb-2">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="flex-1">${arg.text}</p>
                                    <button onclick="window.removeArgument('cons', ${idx})" class="text-red-500 ml-2">
                                        <span class="material-symbols-outlined" style="font-size:20px;">delete</span>
                                    </button>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span>Poids:</span>
                                    <input type="range" min="1" max="10" value="${arg.weight}" 
                                        onchange="window.updateArgWeight('cons', ${idx}, this.value)" 
                                        class="flex-1">
                                    <span class="font-bold w-8 text-right">${arg.weight}/10</span>
                                </div>
                            </div>
                        `).join('')
                    }
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 mt-6">
                <button onclick="window.completeDecision()" class="flex-1 p-3 bg-blue-600 rounded-lg font-bold">
                    Finaliser
                </button>
                <button onclick="window.archiveDecision()" class="px-4 py-3 bg-zinc-700 rounded-lg">
                    <span class="material-symbols-outlined">archive</span>
                </button>
            </div>
        `;
        
        container.innerHTML = html;
    }

    // Fonctions globales pour les actions
    window.addArgument = function(type) {
        const text = prompt(`Nouvel argument ${type === 'pros' ? 'POUR' : 'CONTRE'} :`);
        if (!text || !text.trim()) return;
        
        const decision = state.decisions.find(d => d.id === currentDecisionId);
        const arg = { text: text.trim(), weight: 5, createdAt: new Date().toISOString() };
        
        if (type === 'pros') decision.prosArgs.push(arg);
        else decision.consArgs.push(arg);
        
        saveDecisions();
        renderDecisionDetail(decision);
        renderDecisions();
    };

    window.removeArgument = function(type, index) {
        if (!confirm('Supprimer cet argument ?')) return;
        const decision = state.decisions.find(d => d.id === currentDecisionId);
        if (type === 'pros') decision.prosArgs.splice(index, 1);
        else decision.consArgs.splice(index, 1);
        saveDecisions();
        renderDecisionDetail(decision);
        renderDecisions();
    };

    window.updateArgWeight = function(type, index, weight) {
        const decision = state.decisions.find(d => d.id === currentDecisionId);
        if (type === 'pros') decision.prosArgs[index].weight = parseInt(weight);
        else decision.consArgs[index].weight = parseInt(weight);
        saveDecisions();
        renderDecisionDetail(decision);
        renderDecisions();
    };

    window.completeDecision = function() {
        const decision = state.decisions.find(d => d.id === currentDecisionId);
        const score = calculateDecisionScore(decision);
        decision.finalChoice = score.percent >= 50 ? 'pros' : 'cons';
        decision.confidence = Math.abs(score.percent - 50) * 2;
        decision.status = 'completed';
        decision.completedAt = new Date().toISOString();
        
        // XP et progression
        state.xp += constants.XP_PER_DECISION;
        state.totalDecisionsCompleted++;
        
        // Level up
        const newLevel = Math.floor(state.xp / constants.XP_PER_LEVEL) + 1;
        if (newLevel > state.level) {
            state.level = newLevel;
            confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
        }
        
        // Historique
        addToHistory(decision);
        checkBadges();
        saveAll();
        
        window.closePopup('decisionDetailPopup');
        renderDecisions();
        window.renderXPBar();
        showNotification('🎉 Décision finalisée ! +' + constants.XP_PER_DECISION + ' XP');
    };

    window.archiveDecision = function() {
        if (!confirm('Archiver cette décision ?')) return;
        const decision = state.decisions.find(d => d.id === currentDecisionId);
        decision.status = 'archived';
        saveDecisions();
        window.closePopup('decisionDetailPopup');
        renderDecisions();
    };

    function addToHistory(decision) {
        const today = state.currentDate;
        let dayHistory = state.history.find(h => h.date === today);
        if (!dayHistory) {
            dayHistory = { date: today, decisions: [] };
            state.history.push(dayHistory);
        }
        dayHistory.decisions.push({
            id: decision.id,
            title: decision.title,
            score: calculateDecisionScore(decision).percent,
            finalChoice: decision.finalChoice
        });
    }

    function checkBadges() {
        // Logic badges - simplifié
        const badges = constants.BADGES;
        badges.forEach(badge => {
            if (state.unlockedBadges.includes(badge.id)) return;
            let unlock = false;
            
            if (badge.requirement === 'decisions' && state.totalDecisionsCompleted >= badge.threshold) unlock = true;
            
            if (unlock) {
                state.unlockedBadges.push(badge.id);
                showNotification(`🏆 Badge débloqué : ${badge.name}`);
            }
        });
    }

    function saveDecisions() {
        localStorage.setItem('balanceDecisions', JSON.stringify(state.decisions));
    }

    function saveAll() {
        saveDecisions();
        localStorage.setItem('balanceHistory', JSON.stringify(state.history));
        localStorage.setItem('balanceXP', state.xp.toString());
        localStorage.setItem('balanceLevel', state.level.toString());
        localStorage.setItem('balanceBadges', JSON.stringify(state.unlockedBadges));
        localStorage.setItem('balanceTotalDecisions', state.totalDecisionsCompleted.toString());
    }

    function showNotification(message) {
        const notif = document.createElement('div');
        notif.textContent = message;
        notif.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%);background:#3b82f6;color:#fff;padding:12px 24px;border-radius:8px;z-index:10000;font-weight:bold;';
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 3000);
    }

    function cleanup() {
        // Nettoyage si nécessaire
    }

    return { init, cleanup };
    
});
