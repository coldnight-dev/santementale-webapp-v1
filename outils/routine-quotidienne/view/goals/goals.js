// Vue GOALS - Module autonome
return (function() {
    'use strict';

    function calcStreak() {
        let s = 0;
        const today = new Date();
        for (let i = 0; i < 365; i++) {
            const checkDate = new Date(today);
            checkDate.setDate(today.getDate() - i);
            const ds = checkDate.toLocaleDateString('fr-FR');
            const dayData = state.history.find(h => h.date === ds);
            if (dayData) {
                let dayTotal = 0, dayCompleted = 0;
                state.routines.forEach(r => r.tasks.forEach(t => {
                    dayTotal++;
                    if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) dayCompleted++;
                }));
                if (dayTotal > 0 && dayCompleted / dayTotal >= 0.75) s++;
                else break;
            } else break;
        }
        state.streak = s;
        localStorage.setItem('routineStreak', s.toString());
    }

    window.showBadgeDetail = function(badgeId) {
        const badge = constants.BADGES.find(b => b.id === badgeId);
        if (!badge) return;
        
        const unlocked = state.unlockedBadges.includes(badgeId);
        let progress = 0;
        
        switch (badge.requirement) {
            case 'tasks':
                progress = Math.min(100, Math.round((state.totalTasksCompleted / badge.threshold) * 100));
                break;
            case 'streak':
                progress = Math.min(100, Math.round((state.streak / badge.threshold) * 100));
                break;
            case 'perfect_days':
                progress = Math.min(100, Math.round((state.perfectDaysCount / badge.threshold) * 100));
                break;
        }
        
        let html = '<div style="text-align:center;">';
        html += '<div style="font-size:60px;margin-bottom:15px;' + (unlocked ? '' : 'filter:grayscale(100%);opacity:0.3;') + '">' + badge.icon + '</div>';
        html += '<h3 style="font-size:24px;font-weight:bold;margin-bottom:10px;">' + badge.name + '</h3>';
        html += '<p style="color:#666;margin-bottom:15px;">' + badge.description + '</p>';
        
        if (unlocked) {
            html += '<div style="background:#10B981;color:white;padding:8px 16px;border-radius:8px;display:inline-block;font-weight:bold;">✓ Débloqué</div>';
        } else {
            html += '<div style="margin-top:15px;">';
            html += '<div style="background:#eee;height:20px;border-radius:10px;overflow:hidden;">';
            html += '<div style="background:#3B82F6;height:100%;width:' + progress + '%;transition:width 0.3s;"></div></div>';
            html += '<p style="margin-top:8px;font-size:14px;color:#666;">Progression : ' + progress + '%</p></div>';
        }
        
        html += '</div>';
        document.getElementById('badgeDetailContent').innerHTML = html;
        document.getElementById('badgeDetailPopup').style.display = 'flex';
    };

    window.closeBadgeDetail = function() {
        const popup = document.getElementById('badgeDetailPopup');
        if (popup) {
            popup.style.display = 'none';
        }
    };

    function renderStreak() {
        calcStreak();
        
        let html = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
        html += '<h3 class="font-bold mb-3"><span class="material-symbols-outlined mr-2" style="vertical-align:middle;">local_fire_department</span>Série actuelle</h3>';
        html += '<div class="text-center">';
        html += '<div class="text-6xl font-bold text-orange-500 mb-2' + (state.streak > 0 ? ' streak-pulse' : '') + '">' + state.streak + '</div>';
        html += '<div class="text-zinc-400">jour' + (state.streak > 1 ? 's' : '') + ' à >75%</div>';
        html += '</div></div>';
        
        document.getElementById('streakCard').innerHTML = html;
    }

    function renderAchievements() {
        let html = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
        html += '<h3 class="font-bold mb-3"><span class="material-symbols-outlined mr-2" style="vertical-align:middle;">emoji_events</span>Achievements (' + state.unlockedBadges.length + '/' + constants.BADGES.length + ')</h3>';
        html += '<div class="badge-container">';
        
        constants.BADGES.forEach(badge => {
            const unlocked = state.unlockedBadges.includes(badge.id);
            html += '<div class="badge-item ' + (unlocked ? 'unlocked' : '') + '" data-badge-id="' + badge.id + '">';
            html += '<div class="badge-icon">' + badge.icon + '</div>';
            html += '<div class="badge-name">' + badge.name + '</div>';
            html += '</div>';
        });
        
        html += '</div></div>';
        document.getElementById('achievementsCard').innerHTML = html;
        
        // Attacher événements
        document.querySelectorAll('[data-badge-id]').forEach(el => {
            el.addEventListener('click', function() {
                window.showBadgeDetail(this.dataset.badgeId);
            });
        });
    }

    // Export du module
    return {
        init: function() {
            renderStreak();
            renderAchievements();
        },
        cleanup: function() {
            // Fermer la popup seulement si elle existe
            const popup = document.getElementById('badgeDetailPopup');
            if (popup && popup.style.display !== 'none') {
                popup.style.display = 'none';
            }
        }
    };

})();
