// Vue CALENDAR - Module autonome
return (function() {
    'use strict';

    let calendarDate = new Date();

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

    window.showDayDetail = function(dateStr) {
        const dayData = state.history.find(h => h.date === dateStr);
        if (!dayData) return;
        
        document.getElementById('dayDetailTitle').textContent = 'Détails du ' + dateStr;
        let html = '', totalTasks = 0, completedTasks = 0;
        
        state.routines.forEach(routine => {
            const routineData = dayData.routines[routine.id];
            if (!routineData) return;
            
            html += '<div style="margin-bottom:20px;">';
            html += '<h4 style="font-weight:bold;margin-bottom:10px;display:flex;align-items:center;gap:8px;">';
            html += '<span class="material-symbols-outlined" style="color:#3B82F6;">' + routine.icon + '</span>' + routine.name + '</h4>';
            html += '<div style="margin-left:32px;">';
            
            routine.tasks.forEach(task => {
                totalTasks++;
                const completed = routineData[task.id];
                if (completed) completedTasks++;
                
                html += '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;padding:8px;background:';
                html += (completed ? '#dcfce7' : '#fee2e2') + ';border-radius:6px;">';
                html += '<span class="material-symbols-outlined" style="font-size:20px;color:';
                html += (completed ? '#10B981' : '#EF4444') + ';">' + (completed ? 'check_circle' : 'cancel') + '</span>';
                html += '<span style="color:#000;">' + task.name + '</span></div>';
            });
            
            html += '</div></div>';
        });
        
        const completion = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
        let completionColor = '#71717a';
        if (completion >= 76) completionColor = '#10B981';
        else if (completion >= 51) completionColor = '#F59E0B';
        else if (completion >= 26) completionColor = '#EF4444';
        else if (completion > 0) completionColor = '#3B82F6';
        
        html = '<div style="background:' + completionColor + ';color:white;padding:12px;border-radius:8px;margin-bottom:20px;text-align:center;font-weight:bold;font-size:18px;">Complétion : ' + completion + '%</div>' + html;
        
        document.getElementById('dayDetailContent').innerHTML = html;
        document.getElementById('dayDetailPopup').style.display = 'flex';
        
        setTimeout(() => {
            const popupContent = document.querySelector('#dayDetailPopup .popup-content');
            if (popupContent) {
                popupContent.scrollTop = 0;
                preventPullToRefresh(popupContent);
            }
        }, 100);
    };

    window.closeDayDetail = function() {
        const popup = document.getElementById('dayDetailPopup');
        if (popup) {
            popup.style.display = 'none';
        }
    };

    function renderCalendar() {
        const y = calendarDate.getFullYear(), m = calendarDate.getMonth();
        const first = new Date(y, m, 1), last = new Date(y, m + 1, 0);
        const days = last.getDate(), start = first.getDay();
        const today = new Date();
        const isNextDisabled = (m === today.getMonth() && y === today.getFullYear());
        
        let h = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4" id="calendarContainer">';
        h += '<div class="flex items-center justify-between mb-4">';
        h += '<button id="calPrevBtn" class="text-blue-400"><span class="material-symbols-outlined">chevron_left</span></button>';
        h += '<h3 class="font-bold">' + first.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' }) + '</h3>';
        h += '<button id="calNextBtn" class="text-blue-400' + (isNextDisabled ? ' opacity-50' : '') + '" ' + (isNextDisabled ? 'disabled' : '') + '>';
        h += '<span class="material-symbols-outlined">chevron_right</span></button></div>';
        h += '<div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 text-zinc-500">';
        h += '<div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div></div>';
        h += '<div class="grid grid-cols-7 gap-1">';
        
        for (let i = 0; i < start; i++) h += '<div></div>';
        
        for (let d = 1; d <= days; d++) {
            const dt = new Date(y, m, d), ds = dt.toLocaleDateString('fr-FR');
            const dayData = state.history.find(hh => hh.date === ds);
            let completion = 0;
            
            if (dayData) {
                let total = 0, completed = 0;
                state.routines.forEach(r => r.tasks.forEach(t => {
                    total++;
                    if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) completed++;
                }));
                completion = total > 0 ? completed / total : 0;
            }
            
            let bg = '#18181b', txt = '#a1a1aa';
            if (completion > 0) {
                if (completion >= 0.76) { bg = '#10B981'; txt = '#fff'; }
                else if (completion >= 0.51) { bg = '#F59E0B'; txt = '#fff'; }
                else if (completion >= 0.26) { bg = '#EF4444'; txt = '#fff'; }
                else { bg = '#3B82F6'; txt = '#fff'; }
            }
            
            if (dayData) {
                h += '<div class="calendar-day" style="background-color:' + bg + ';color:' + txt + ';" title="' + Math.round(completion * 100) + '%" data-date="' + ds + '">' + d + '</div>';
            } else {
                h += '<div class="calendar-day" style="background-color:' + bg + ';color:' + txt + ';" title="' + Math.round(completion * 100) + '%">' + d + '</div>';
            }
        }
        
        h += '</div>';
        h += '<div class="mt-3 text-xs text-zinc-500 flex gap-2">';
        h += '<div><span class="inline-block w-3 h-3 rounded" style="background:#3B82F6"></span> 0-25%</div>';
        h += '<div><span class="inline-block w-3 h-3 rounded" style="background:#EF4444"></span> 26-50%</div>';
        h += '<div><span class="inline-block w-3 h-3 rounded" style="background:#F59E0B"></span> 51-75%</div>';
        h += '<div><span class="inline-block w-3 h-3 rounded" style="background:#10B981"></span> 76-100%</div>';
        h += '</div></div>';
        
        document.getElementById('calendarGrid').innerHTML = h;
        
        // Événements navigation
        const prevBtn = document.getElementById('calPrevBtn');
        const nextBtn = document.getElementById('calNextBtn');
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                calendarDate.setMonth(calendarDate.getMonth() - 1);
                renderCalendar();
            });
        }
        
        if (nextBtn && !isNextDisabled) {
            nextBtn.addEventListener('click', () => {
                calendarDate.setMonth(calendarDate.getMonth() + 1);
                renderCalendar();
            });
        }
        
        // Événements jours cliquables
        document.querySelectorAll('[data-date]').forEach(el => {
            el.addEventListener('click', function() {
                window.showDayDetail(this.dataset.date);
            });
        });
        
        // Swipe pour navigation
        const container = document.getElementById('calendarContainer');
        if (container) {
            let touchStartX = 0;
            let touchEndX = 0;
            
            container.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);
            
            container.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 50 && !isNextDisabled) {
                    calendarDate.setMonth(calendarDate.getMonth() + 1);
                    renderCalendar();
                }
                if (touchEndX > touchStartX + 50) {
                    calendarDate.setMonth(calendarDate.getMonth() - 1);
                    renderCalendar();
                }
            }, false);
        }
    }

    function renderHistoryList() {
        const sortedHistory = state.history.slice().sort((a, b) => {
            const dateA = new Date(a.date.split('/').reverse().join('-'));
            const dateB = new Date(b.date.split('/').reverse().join('-'));
            return dateB - dateA;
        });
        
        let html = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
        html += '<h3 class="font-bold mb-3"><span class="material-symbols-outlined mr-2" style="vertical-align:middle;">history</span>Historique complet</h3>';
        
        if (sortedHistory.length === 0) {
            html += '<p class="text-zinc-500 text-center py-8">Aucun historique disponible</p>';
        } else {
            html += '<div class="space-y-2">';
            sortedHistory.forEach(day => {
                let total = 0, completed = 0;
                state.routines.forEach(r => r.tasks.forEach(t => {
                    total++;
                    if (day.routines[r.id] && day.routines[r.id][t.id]) completed++;
                }));
                
                const completion = total > 0 ? Math.round((completed / total) * 100) : 0;
                let bgColor = '#18181b', textColor = '#71717a';
                if (completion >= 76) { bgColor = '#064e3b'; textColor = '#10B981'; }
                else if (completion >= 51) { bgColor = '#78350f'; textColor = '#F59E0B'; }
                else if (completion >= 26) { bgColor = '#7f1d1d'; textColor = '#EF4444'; }
                else if (completion > 0) { bgColor = '#1e3a8a'; textColor = '#3B82F6'; }
                
                html += '<div class="history-day-item p-3 rounded-lg border border-zinc-800" style="background:' + bgColor + ';cursor:pointer;" data-history-date="' + day.date + '">';
                html += '<div class="flex justify-between items-center">';
                html += '<span class="font-semibold" style="color:' + textColor + ';">' + day.date + '</span>';
                html += '<span style="color:' + textColor + ';font-weight:bold;">' + completion + '%</span>';
                html += '</div></div>';
            });
            html += '</div>';
        }
        
        html += '</div>';
        document.getElementById('historyList').innerHTML = html;
        
        // Événements historique
        document.querySelectorAll('[data-history-date]').forEach(el => {
            el.addEventListener('click', function() {
                window.showDayDetail(this.dataset.historyDate);
            });
        });
    }

    // Export du module
    return {
        init: function() {
            renderCalendar();
            renderHistoryList();
        },
        cleanup: function() {
            // Fermer la popup seulement si elle existe
            const popup = document.getElementById('dayDetailPopup');
            if (popup && popup.style.display !== 'none') {
                popup.style.display = 'none';
            }
        }
    };

})();
