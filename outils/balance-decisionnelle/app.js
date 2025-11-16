// ========================================
// BALANCE DÉCISIONNELLE v0.1-alpha
// ========================================

(function() {
    'use strict';

    const MODULE_REVISION = '0.1-alpha';
    const MAX_DECISIONS = 20;
    const XP_PER_DECISION = 20;
    const XP_PER_LEVEL = 100;

    // État global
    window.appState = {
        decisions: [],
        history: [],
        view: 'today',
        currentDate: new Date().toLocaleDateString('fr-FR'),
        streak: 0,
        xp: 0,
        level: 1,
        unlockedBadges: [],
        totalDecisionsCompleted: 0,
        lastSeenVersion: '0',
        isFirstTimeView: false,
        currentViewModule: null
    };

    // Constantes
    window.APP_CONSTANTS = {
        MODULE_REVISION,
        MAX_DECISIONS,
        XP_PER_DECISION,
        XP_PER_LEVEL,
        DECISION_ICONS: [
            'psychology', 'balance', 'gavel', 'handshake', 'lightbulb', 'therapy', 
            'favorite', 'work', 'home', 'school', 'health_and_safety', 'fitness_center',
            'restaurant', 'flight', 'directions_car', 'local_shipping', 'attach_money',
            'savings', 'account_balance', 'shopping_cart', 'family_restroom', 'groups',
            'person', 'face', 'celebration', 'sports_esports', 'music_note', 'movie'
        ],
        BADGES: [
            { id: 'first_decision', name: 'Première Décision', icon: '🎯', description: 'Analyser votre première décision', requirement: 'decisions', threshold: 1 },
            { id: 'wise_thinker', name: 'Penseur Sage', icon: '🧠', description: 'Analyser 10 décisions', requirement: 'decisions', threshold: 10 },
            { id: 'decision_master', name: 'Maître Décisionnaire', icon: '⚖️', description: 'Analyser 50 décisions', requirement: 'decisions', threshold: 50 },
            { id: 'balanced_mind', name: 'Esprit Équilibré', icon: '🎭', description: 'Compléter une balance avec 5+ arguments de chaque côté', requirement: 'balanced', threshold: 1 },
            { id: 'week_streak', name: 'Semaine Réflexive', icon: '🔥', description: '7 jours consécutifs d\'analyse', requirement: 'streak', threshold: 7 },
            { id: 'clarity', name: 'Clarté', icon: '✨', description: 'Prendre 5 décisions avec score >70%', requirement: 'clear_decisions', threshold: 5 },
            { id: 'deep_thinker', name: 'Pensée Profonde', icon: '💡', description: 'Créer une balance avec 10+ arguments totaux', requirement: 'deep_analysis', threshold: 1 }
        ],
        TUTORIAL_STEPS: [
            { target: '.xp-bar', title: 'Système XP & Niveaux', description: "Gagnez 20 XP par décision analysée et progressez dans les niveaux !", anchor: 'bottom' },
            { target: '[data-view="calendar"]', title: 'Calendrier', description: 'Consultez l\'historique de vos décisions et analyses.', anchor: 'bottom' },
            { target: '[data-view="stats"]', title: 'Statistiques', description: 'Visualisez vos patterns de décision et votre évolution.', anchor: 'bottom' },
            { target: '[data-view="goals"]', title: 'Succès', description: 'Débloquez des badges en analysant vos décisions !', anchor: 'bottom' }
        ],
        WHATS_NEW: [
            { icon: '⚖️', title: 'Balance décisionnelle', desc: 'Outil clinique pour peser le pour et le contre de vos décisions' },
            { icon: '🧮', title: 'Calcul automatique', desc: 'Score et poids calculés en temps réel selon vos arguments' },
            { icon: '📊', title: 'Statistiques avancées', desc: 'Suivez vos patterns de décision et votre clarté mentale' },
            { icon: '🎯', title: 'Système de progression', desc: 'XP, niveaux et badges pour suivre votre évolution' },
            { icon: '💾', title: '100% local', desc: 'Toutes vos données restent sur votre appareil' }
        ]
    };

    // Utilitaires
    window.openPopup = function(id) {
        const popup = document.getElementById(id);
        if (popup) {
            popup.style.display = 'flex';
            setTimeout(() => {
                const content = popup.querySelector('.popup-content');
                if (content) {
                    content.scrollTop = 0;
                    preventPullToRefresh(content);
                }
            }, 100);
        }
    };

    window.closePopup = function(id) {
        const popup = document.getElementById(id);
        if (popup) popup.style.display = 'none';
        if (id === 'whatsNewPopup' && window.appState.isFirstTimeView) {
            localStorage.setItem('balanceLastSeenVersion', MODULE_REVISION);
            window.appState.isFirstTimeView = false;
        }
    };

    window.showWhatsNew = function() {
        window.closePopup('helpModalPopup');
        renderWhatsNew(true);
        window.openPopup('whatsNewPopup');
    };

    window.startTutorial = function() {
        window.appState.tutorialStep = 0;
        showTutorialStep();
    };

    window.helpModalPopup = function() {
        window.openPopup('helpModalPopup');
    };

    function showTutorialStep() {
        if (window.appState.tutorialStep >= window.APP_CONSTANTS.TUTORIAL_STEPS.length) {
            closeTutorial();
            return;
        }
        const step = window.APP_CONSTANTS.TUTORIAL_STEPS[window.appState.tutorialStep];
        const target = document.querySelector(step.target);
        if (!target) {
            window.appState.tutorialStep++;
            showTutorialStep();
            return;
        }
        const overlay = document.getElementById('tutorialOverlay');
        const highlight = document.getElementById('tutorialHighlight');
        const tooltip = document.getElementById('tutorialTooltip');
        overlay.style.display = 'block';
        highlight.style.display = 'block';
        const rect = target.getBoundingClientRect();
        highlight.style.left = (rect.left - 5) + 'px';
        highlight.style.top = (rect.top - 5) + 'px';
        highlight.style.width = (rect.width + 10) + 'px';
        highlight.style.height = (rect.height + 10) + 'px';
        let html = '<h3>' + step.title + '</h3><p>' + step.description + '</p><div class="tutorial-buttons">';
        html += '<button class="tutorial-btn skip" onclick="window.skipTutorial()">Passer</button>';
        html += '<button class="tutorial-btn next" onclick="window.nextTutorialStep()">Suivant</button></div>';
        tooltip.innerHTML = html;
        tooltip.style.display = 'block';
        let left = rect.left + rect.width / 2 - 160;
        let top = step.anchor === 'bottom' ? rect.bottom + 20 : rect.top - 180;
        left = Math.max(10, Math.min(left, window.innerWidth - 330));
        top = Math.max(10, top);
        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';
    }

    window.nextTutorialStep = function() {
        window.appState.tutorialStep++;
        showTutorialStep();
    };

    window.skipTutorial = function() {
        closeTutorial();
    };

    function closeTutorial() {
        document.getElementById('tutorialOverlay').style.display = 'none';
        document.getElementById('tutorialHighlight').style.display = 'none';
        document.getElementById('tutorialTooltip').style.display = 'none';
        window.appState.tutorialStep = -1;
        localStorage.setItem('balanceLastSeenVersion', MODULE_REVISION);
    }

    function renderWhatsNew(showClose) {
        let content = window.APP_CONSTANTS.WHATS_NEW.map(item =>
            '<div class="whats-new-item"><div class="whats-new-icon">' + item.icon + '</div>' +
            '<div class="whats-new-text"><h4>' + item.title + '</h4><p>' + item.desc + '</p></div></div>'
        ).join('');
        document.getElementById('whatsNewContent').innerHTML = content;
        document.getElementById('whatsNewCloseBtn').style.display = showClose ? 'block' : 'none';
    }

    function preventPullToRefresh(el) {
        let startY = 0;
        el.addEventListener('touchstart', e => startY = e.touches[0].pageY, { passive: true });
        el.addEventListener('touchmove', e => {
            if (el.scrollTop === 0 && e.touches[0].pageY > startY) e.preventDefault();
        }, { passive: false });
    }

    // XP Bar
    window.renderXPBar = function() {
        const container = document.getElementById('xpBarContainer');
        if (!container) return;
        if (window.appState.view === 'manage') {
            container.innerHTML = '';
            return;
        }
        const xpInLevel = window.appState.xp % XP_PER_LEVEL;
        const xpPercent = (xpInLevel / XP_PER_LEVEL) * 100;
        let html = '<div class="max-w-2xl mx-auto px-4 py-4">';
        html += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
        html += '<div class="flex items-center justify-between mb-2">';
        html += '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-yellow-400" style="font-size:28px;">star</span>';
        html += '<span class="font-bold text-lg">Niveau ' + window.appState.level + '</span></div>';
        html += '<span class="text-sm text-zinc-400">' + xpInLevel + ' / ' + XP_PER_LEVEL + ' XP</span></div>';
        html += '<div class="xp-bar"><div class="xp-fill" style="width:' + xpPercent + '%"></div>';
        html += '<div class="xp-text">' + Math.round(xpPercent) + '%</div></div></div></div>';
        container.innerHTML = html;
    };

    // Chargement des vues
    async function loadView(viewName) {
        const container = document.getElementById('viewContainer');
        container.innerHTML = '<div class="loading-spinner"><svg class="animate-spin h-12 w-12 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-center text-zinc-400 mt-4">Chargement...</p></div>';

        try {
            if (window.appState.currentViewModule && window.appState.currentViewModule.cleanup) {
                window.appState.currentViewModule.cleanup();
            }
            const htmlResp = await fetch(`view/${viewName}/${viewName}.php`);
            if (!htmlResp.ok) throw new Error('Erreur HTML');
            container.innerHTML = await htmlResp.text();
            const cssLink = document.querySelector(`link[href*="${viewName}.css"]`);
            if (cssLink) cssLink.remove();
            const newCss = document.createElement('link');
            newCss.rel = 'stylesheet';
            newCss.href = `view/${viewName}/${viewName}.css?v=${MODULE_REVISION}`;
            document.head.appendChild(newCss);
            const jsResp = await fetch(`view/${viewName}/${viewName}.js?v=${MODULE_REVISION}`);
            if (!jsResp.ok) throw new Error('Erreur JS');
            const scriptFunc = new Function('state', 'constants', await jsResp.text());
            window.appState.currentViewModule = scriptFunc(window.appState, window.APP_CONSTANTS);
            if (window.appState.currentViewModule && window.appState.currentViewModule.init) {
                window.appState.currentViewModule.init();
            }
            window.renderXPBar();
        } catch (e) {
            console.error('Erreur:', e);
            container.innerHTML = '<div class="bg-red-950 border border-red-800 rounded-lg p-4 text-center m-4"><span class="material-symbols-outlined text-red-400" style="font-size:48px;">error</span><p class="text-red-300 mt-2">Erreur de chargement</p><button onclick="location.reload()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg">Recharger</button></div>';
        }
    }

    // Navigation
    function initNavigation() {
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const viewName = tab.dataset.view;
                window.appState.view = viewName;
                localStorage.setItem('balanceCurrentView', viewName);
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                loadView(viewName);
            });
        });
        const savedView = localStorage.getItem('balanceCurrentView') || 'today';
        window.appState.view = savedView;
        const activeTab = document.querySelector(`[data-view="${savedView}"]`);
        if (activeTab) activeTab.classList.add('active');
    }

    // Données
    function loadData() {
        window.appState.decisions = JSON.parse(localStorage.getItem('balanceDecisions') || '[]');
        window.appState.history = JSON.parse(localStorage.getItem('balanceHistory') || '[]');
        window.appState.xp = parseInt(localStorage.getItem('balanceXP') || '0');
        window.appState.level = parseInt(localStorage.getItem('balanceLevel') || '1');
        window.appState.unlockedBadges = JSON.parse(localStorage.getItem('balanceBadges') || '[]');
        window.appState.totalDecisionsCompleted = parseInt(localStorage.getItem('balanceTotalDecisions') || '0');
        window.appState.lastSeenVersion = localStorage.getItem('balanceLastSeenVersion') || '0';
        window.appState.streak = parseInt(localStorage.getItem('balanceStreak') || '0');
    }

    // Init
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            loadData();
            if (!localStorage.getItem('device_uuid')) {
                localStorage.setItem('device_uuid', crypto.randomUUID());
                window.openPopup('privacyPopup');
            }
            let cv = localStorage.getItem('client_version');
            if (!cv) {
                cv = new URLSearchParams(window.location.search).get('v') || '1.web';
                localStorage.setItem('client_version', cv);
            }
            document.getElementById('clientUUID').textContent = 'Client : ' + localStorage.getItem('device_uuid');
            document.getElementById('appVersion').textContent = 'App : ' + cv;
            initNavigation();
            await waitForDependencies();
            await loadView(window.appState.view);
            if (window.appState.lastSeenVersion !== MODULE_REVISION) {
                window.appState.isFirstTimeView = true;
                renderWhatsNew(true);
                window.openPopup('whatsNewPopup');
            }
        } catch (e) {
            console.error('Init error:', e);
        }
    });

    function waitForDependencies() {
        return new Promise(resolve => {
            let attempts = 0;
            const check = () => {
                attempts++;
                const chartReady = typeof Chart !== 'undefined';
                const sortableReady = typeof Sortable !== 'undefined';
                const confettiReady = typeof confetti !== 'undefined';
                if (chartReady && sortableReady && confettiReady) {
                    resolve();
                } else if (attempts >= 100) {
                    resolve();
                } else {
                    setTimeout(check, 100);
                }
            };
            check();
        });
    }

})();
