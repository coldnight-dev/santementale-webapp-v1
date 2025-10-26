// ========================================
// ROUTINES v0.12-alpha - Gestionnaire App
// ========================================

(function() {
    'use strict';

    const MODULE_REVISION = '0.12-alpha';
    const MAX_ROUTINES = 10;
    const MAX_TASKS = 25;
    const XP_PER_TASK = 10;
    const XP_PER_LEVEL = 100;

    // État global partagé entre toutes les vues
    window.appState = {
        routines: [],
        history: [],
        view: 'today',
        currentDate: new Date().toLocaleDateString('fr-FR'),
        streak: 0,
        xp: 0,
        level: 1,
        unlockedBadges: [],
        totalTasksCompleted: 0,
        perfectDaysCount: 0,
        lastSeenVersion: '0',
        isFirstTimeView: false,
        currentViewModule: null
    };

    // Constants exportées globalement
    window.APP_CONSTANTS = {
        MODULE_REVISION,
        MAX_ROUTINES,
        MAX_TASKS,
        XP_PER_TASK,
        XP_PER_LEVEL,
        ICONS: [
            'wb_sunny', 'nightlight', 'favorite', 'local_cafe', 'book', 'fitness_center', 'water_drop',
            'music_note', 'brush', 'phone', 'email', 'shopping_cart', 'nature', 'laptop',
            'sports_esports', 'local_dining', 'psychology'
        ],
        TASK_ICONS: [
            'self_improvement', 'spa', 'favorite_border', 'local_cafe', 'restaurant', 'water_drop',
            'fitness_center', 'directions_run', 'bedtime', 'alarm', 'book', 'edit', 'music_note',
            'brush', 'palette', 'shower', 'pool', 'surfing', 'hiking',
            'downhill_skiing', 'skateboarding', 'directions_bike', 'electric_bike', 'electric_scooter', 'directions_walk',
            'kayaking', 'kitesurfing', 'paragliding', 'sailing', 'scuba_diving',
            'sports_baseball', 'sports_basketball', 'sports_cricket', 'sports_football', 'sports_golf',
            'sports_hockey', 'sports_mma', 'sports_rugby', 'sports_soccer', 'sports_tennis', 'sports_volleyball',
            'self_improvement', 'spa', 'healing', 'psychology', 'mood', 'sentiment_satisfied', 'sentiment_very_satisfied',
            'energy_savings_leaf', 'eco', 'local_florist', 'park', 'forest', 'grass', 'yard',
            'coffee', 'lunch_dining', 'dinner_dining', 'breakfast_dining', 'ramen_dining', 'fastfood',
            'bakery_dining', 'liquor', 'icecream', 'cake', 'cookie', 'egg_alt', 'nutrition',
            'shower', 'bathtub', 'bathroom', 'face', 'face_retouching_natural', 'cleaning_services',
            'wash', 'soap', 'sanitizer', 'vaccines', 'thermostat', 'medication', 'health_and_safety',
            'visibility', 'eyeglasses', 'hearing', 'accessible', 'pregnant_woman', 'child_care',
            'library_books', 'menu_book', 'auto_stories', 'article', 'description', 'note',
            'draw', 'create', 'design_services', 'colorize', 'photo_camera', 'videocam', 'mic',
            'headphones', 'speaker', 'piano'
        ],
        ICONS_PER_PAGE: 24,
        BADGES: [
            { id: 'first_step', name: 'Premier Pas', icon: '🎯', description: 'Compléter votre première tâche', requirement: 'tasks', threshold: 1 },
            { id: 'dedicated', name: 'Dévoué', icon: '💪', description: 'Compléter 50 tâches', requirement: 'tasks', threshold: 50 },
            { id: 'champion', name: 'Champion', icon: '🏆', description: 'Compléter 200 tâches', requirement: 'tasks', threshold: 200 },
            { id: 'legend', name: 'Légende', icon: '⭐', description: 'Compléter 500 tâches', requirement: 'tasks', threshold: 500 },
            { id: 'week_streak', name: 'Hebdomadaire', icon: '🔥', description: '7 jours de suite à >75%', requirement: 'streak', threshold: 7 },
            { id: 'month_streak', name: 'Mensuel', icon: '💎', description: '30 jours de suite à >75%', requirement: 'streak', threshold: 30 },
            { id: 'perfect_day', name: 'Journée Parfaite', icon: '✨', description: 'Compléter 100% des tâches en une journée', requirement: 'perfect_days', threshold: 1 },
            { id: 'early_bird', name: 'Lève-tôt', icon: '🌅', description: 'Compléter une routine du matin', requirement: 'morning_routines', threshold: 1 },
            { id: 'night_owl', name: 'Couche-tard', icon: '🌙', description: 'Compléter une routine du soir', requirement: 'evening_routines', threshold: 1 },
            { id: 'organizer', name: 'Organisateur', icon: '📋', description: 'Créer 3 routines personnalisées', requirement: 'custom_routines', threshold: 3 }
        ],
        TUTORIAL_STEPS: [
            { target: '.xp-bar', title: 'Système XP & Niveaux', description: "Gagnez de l'expérience en complétant des tâches et débloquez des niveaux ! Chaque tâche complétée = +10 XP.", anchor: 'bottom' },
            { target: '[data-view="calendar"]', title: 'Onglet Calendrier', description: 'Visualisez votre historique complet et consultez vos performances passées.', anchor: 'bottom' },
            { target: '[data-view="stats"]', title: 'Onglet Statistiques', description: 'Consultez votre progression, votre série de jours et le temps total investi dans vos routines.', anchor: 'bottom' },
            { target: '[data-view="goals"]', title: 'Onglet Achievements', description: 'Débloquez des badges exclusifs en atteignant des objectifs. 10 achievements à conquérir !', anchor: 'bottom' },
            { target: '[onclick*="helpModalPopup"] span', title: "Besoin d'aide ?", description: "Cliquez sur l'icône d'aide pour accéder au menu des nouveautés ou relancer le tutoriel à tout moment.", anchor: 'bottom' }
        ],
        WHATS_NEW: [
            { icon: '🎨', title: '100 icônes disponibles', desc: 'Pagination carousel avec swipe pour vos tâches' },
            { icon: '🔧', title: 'Interface optimisée', desc: 'Icônes régulières, tailles ajustées, navigation améliorée' },
            { icon: '📅', title: 'Calendrier perfectionné', desc: 'Détails cliquables, scroll fluide, bouton fermer' },
            { icon: '🐛', title: 'Corrections de bugs', desc: 'Animations nettoyées, affichages unifiés' },
            { icon: '✨', title: 'Expérience tactile', desc: 'Meilleure gestion du scroll et des interactions' }
        ]
    };

    // Utilitaires globaux
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
        if (popup) {
            popup.style.display = 'none';
        }
        if (id === 'whatsNewPopup' && window.appState.isFirstTimeView) {
            localStorage.setItem('routineLastSeenVersion', MODULE_REVISION);
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
        let tooltipHTML = '<h3>' + step.title + '</h3><p>' + step.description + '</p><div class="tutorial-buttons">';
        tooltipHTML += '<button class="tutorial-btn skip" onclick="window.skipTutorial()">Passer</button>';
        tooltipHTML += '<button class="tutorial-btn next" onclick="window.nextTutorialStep()">Suivant</button></div>';
        tooltip.innerHTML = tooltipHTML;
        tooltip.style.display = 'block';
        let tooltipLeft = rect.left + rect.width / 2 - 160;
        let tooltipTop = step.anchor === 'bottom' ? rect.bottom + 20 : rect.top - 180;
        tooltipLeft = Math.max(10, Math.min(tooltipLeft, window.innerWidth - 330));
        tooltipTop = Math.max(10, tooltipTop);
        tooltip.style.left = tooltipLeft + 'px';
        tooltip.style.top = tooltipTop + 'px';
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
        localStorage.setItem('routineLastSeenVersion', MODULE_REVISION);
    }

    function renderWhatsNew(showClose) {
        let content = window.APP_CONSTANTS.WHATS_NEW.map(item =>
            '<div class="whats-new-item"><div class="whats-new-icon">' + item.icon + '</div>' +
            '<div class="whats-new-text"><h4>' + item.title + '</h4><p>' + item.desc + '</p></div></div>'
        ).join('');
        document.getElementById('whatsNewContent').innerHTML = content;
        document.getElementById('whatsNewCloseBtn').style.display = showClose ? 'block' : 'none';
    }

    function preventPullToRefresh(element) {
        let startY = 0;
        element.addEventListener('touchstart', function(e) {
            startY = e.touches[0].pageY;
        }, { passive: true });
        element.addEventListener('touchmove', function(e) {
            const y = e.touches[0].pageY;
            if (element.scrollTop === 0 && y > startY) {
                e.preventDefault();
            }
        }, { passive: false });
    }

    // Gestion XP globale
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
        html += '<div class="flex items-center justify-between mb-2" style="position:relative;z-index:1;">';
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
        
        // Afficher le loader
        container.innerHTML = '<div class="loading-spinner"><svg class="animate-spin h-12 w-12 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-center text-zinc-400 mt-4">Chargement...</p></div>';

        try {
            // Nettoyer le module précédent
            if (window.appState.currentViewModule && window.appState.currentViewModule.cleanup) {
                window.appState.currentViewModule.cleanup();
            }

            // Charger le HTML
            const htmlResponse = await fetch(`view/${viewName}/${viewName}.php`);
            if (!htmlResponse.ok) throw new Error('Erreur chargement HTML');
            const htmlContent = await htmlResponse.text();
            container.innerHTML = htmlContent;

            // Charger le CSS
            const cssLink = document.querySelector(`link[href*="${viewName}.css"]`);
            if (cssLink) cssLink.remove();
            const newCssLink = document.createElement('link');
            newCssLink.rel = 'stylesheet';
            newCssLink.href = `view/${viewName}/${viewName}.css?v=${MODULE_REVISION}`;
            document.head.appendChild(newCssLink);

            // Charger le JS
            const scriptResponse = await fetch(`view/${viewName}/${viewName}.js?v=${MODULE_REVISION}`);
            if (!scriptResponse.ok) throw new Error('Erreur chargement JS');
            const scriptContent = await scriptResponse.text();
            
            // Exécuter le script dans un contexte isolé
            const scriptFunc = new Function('state', 'constants', scriptContent);
            window.appState.currentViewModule = scriptFunc(window.appState, window.APP_CONSTANTS);

            // Initialiser la vue
            if (window.appState.currentViewModule && window.appState.currentViewModule.init) {
                window.appState.currentViewModule.init();
            }

            // Mettre à jour la barre XP
            window.renderXPBar();

        } catch (error) {
            console.error('Erreur chargement vue:', error);
            container.innerHTML = '<div class="bg-red-950 border border-red-800 rounded-lg p-4 text-center"><span class="material-symbols-outlined text-red-400" style="font-size:48px;">error</span><p class="text-red-300 mt-2">Erreur de chargement de la vue</p><button onclick="location.reload()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg">Recharger</button></div>';
        }
    }

    // Initialisation de la navigation
    function initNavigation() {
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const viewName = tab.dataset.view;
                window.appState.view = viewName;
                localStorage.setItem('routineCurrentView', viewName);
                
                // Mise à jour visuelle des tabs
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Charger la vue
                loadView(viewName);
            });
        });

        // Activer la vue par défaut
        const savedView = localStorage.getItem('routineCurrentView') || 'today';
        window.appState.view = savedView;
        const activeTab = document.querySelector(`[data-view="${savedView}"]`);
        if (activeTab) activeTab.classList.add('active');
    }

    // Initialisation des données
    function initDefaultRoutines() {
        return [
            {
                id: 'morning', name: 'Routine Matin', icon: 'wb_sunny', tasks: [
                    { id: 't1', name: 'Se réveiller', icon: 'bedtime', duration: 0, completed: false },
                    { id: 't2', name: "Boire un verre d'eau", icon: 'water_drop', duration: 2, completed: false },
                    { id: 't3', name: 'Petit déjeuner', icon: 'local_cafe', duration: 20, completed: false },
                    { id: 't4', name: 'Hygiène', icon: 'shower', duration: 15, completed: false }
                ]
            },
            {
                id: 'day', name: 'Routine Journée', icon: 'laptop', tasks: [
                    { id: 't5', name: 'Planifier la journée', icon: 'edit', duration: 10, completed: false },
                    { id: 't6', name: 'Travail/Études', icon: 'laptop', duration: 240, completed: false },
                    { id: 't7', name: 'Pause déjeuner', icon: 'restaurant', duration: 45, completed: false },
                    { id: 't8', name: 'Activité physique', icon: 'fitness_center', duration: 30, completed: false }
                ]
            },
            {
                id: 'evening', name: 'Routine Soir', icon: 'nightlight', tasks: [
                    { id: 't9', name: 'Dîner', icon: 'restaurant', duration: 30, completed: false },
                    { id: 't10', name: 'Moment détente', icon: 'book', duration: 60, completed: false },
                    { id: 't11', name: 'Préparer le lendemain', icon: 'edit', duration: 15, completed: false },
                    { id: 't12', name: 'Coucher', icon: 'bedtime', duration: 0, completed: false }
                ]
            }
        ];
    }

    function loadData() {
        const savedRoutines = localStorage.getItem('routines');
        if (savedRoutines) {
            window.appState.routines = JSON.parse(savedRoutines);
        } else {
            window.appState.routines = initDefaultRoutines();
            localStorage.setItem('routines', JSON.stringify(window.appState.routines));
        }

        window.appState.history = JSON.parse(localStorage.getItem('routineHistory') || '[]');
        window.appState.xp = parseInt(localStorage.getItem('routineXP') || '0');
        window.appState.level = parseInt(localStorage.getItem('routineLevel') || '1');
        window.appState.unlockedBadges = JSON.parse(localStorage.getItem('routineBadges') || '[]');
        window.appState.totalTasksCompleted = parseInt(localStorage.getItem('routineTotalTasks') || '0');
        window.appState.perfectDaysCount = parseInt(localStorage.getItem('routinePerfectDays') || '0');
        window.appState.lastSeenVersion = localStorage.getItem('routineLastSeenVersion') || '0';
        window.appState.streak = parseInt(localStorage.getItem('routineStreak') || '0');

        // Restaurer l'état des tâches du jour
        const today = window.appState.currentDate;
        let dayHistory = window.appState.history.find(h => h.date === today);
        if (dayHistory) {
            window.appState.routines.forEach(r => r.tasks.forEach(t => {
                t.completed = !!(dayHistory.routines[r.id] && dayHistory.routines[r.id][t.id]);
            }));
        } else {
            window.appState.routines.forEach(r => r.tasks.forEach(t => t.completed = false));
        }
    }

    // Initialisation au chargement
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            // Charger les données
            loadData();

            // Initialiser UUID
            if (!localStorage.getItem('device_uuid')) {
                localStorage.setItem('device_uuid', crypto.randomUUID());
                window.openPopup('privacyPopup');
            }

            // Version client
            let cv = localStorage.getItem('client_version');
            if (!cv) {
                const up = new URLSearchParams(window.location.search);
                cv = up.get('v') || '1.web';
                localStorage.setItem('client_version', cv);
            }

            // Afficher infos
            document.getElementById('clientUUID').textContent = 'Client : ' + localStorage.getItem('device_uuid');
            document.getElementById('appVersion').textContent = 'App : ' + cv;

            // Initialiser navigation
            initNavigation();

            // S'assurer que les dépendances CDN sont chargées
            await waitForDependencies();

            // Charger la vue initiale
            await loadView(window.appState.view);

            // Vérifier si première visite
            if (window.appState.lastSeenVersion !== MODULE_REVISION) {
                window.appState.isFirstTimeView = true;
                renderWhatsNew(true);
                window.openPopup('whatsNewPopup');
            }

        } catch (e) {
            console.error('Erreur initialisation:', e);
            alert('Une erreur s\'est produite lors du chargement.');
        }
    });

    // Attendre que les dépendances CDN soient chargées
    function waitForDependencies() {
        return new Promise((resolve) => {
            let attempts = 0;
            const maxAttempts = 100; // 10 secondes max
            
            const checkDependencies = () => {
                attempts++;
                
                // Vérifier que Chart.js, Sortable et Confetti sont chargés
                const chartReady = typeof Chart !== 'undefined';
                const sortableReady = typeof Sortable !== 'undefined';
                const confettiReady = typeof confetti !== 'undefined';
                
                if (chartReady && sortableReady && confettiReady) {
                    console.log('✅ Toutes les dépendances CDN sont chargées');
                    resolve();
                } else if (attempts >= maxAttempts) {
                    console.warn('⚠️ Timeout en attendant les dépendances CDN. Chart:', chartReady, 'Sortable:', sortableReady, 'Confetti:', confettiReady);
                    resolve(); // Continuer quand même
                } else {
                    if (attempts % 10 === 0) {
                        console.log('⏳ Attente dépendances... Tentative', attempts, '- Chart:', chartReady, 'Sortable:', sortableReady, 'Confetti:', confettiReady);
                    }
                    setTimeout(checkDependencies, 100);
                }
            };
            
            checkDependencies();
        });
    }

})();
