/* ===== calendar.js ===== */
return (function(state, constants) {
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();
    
    function init() {
        setupListeners();
        renderCalendar();
    }
    
    function setupListeners() {
        document.getElementById('prevMonth').onclick = () => {
            currentMonth--;
            if (currentMonth < 0) { currentMonth = 11; currentYear--; }
            renderCalendar();
        };
        document.getElementById('nextMonth').onclick = () => {
            currentMonth++;
            if (currentMonth > 11) { currentMonth = 0; currentYear++; }
            renderCalendar();
        };
    }
    
    function renderCalendar() {
        const monthNames = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
        document.getElementById('currentMonth').textContent = monthNames[currentMonth] + ' ' + currentYear;
        
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const today = new Date();
        
        let html = '<div class="grid grid-cols-7 gap-2 mb-2">';
        ['D','L','M','M','J','V','S'].forEach(d => html += `<div class="text-center text-xs text-zinc-500 font-bold">${d}</div>`);
        html += '</div><div class="grid grid-cols-7 gap-2">';
        
        for (let i = 0; i < firstDay; i++) html += '<div></div>';
        
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentYear, currentMonth, day).toLocaleDateString('fr-FR');
            const dayHistory = state.history.find(h => h.date === date);
            const decisionsCount = dayHistory ? dayHistory.decisions.length : 0;
            const isToday = day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear();
            
            html += `<div class="calendar-day ${isToday ? 'today' : ''} ${decisionsCount > 0 ? 'has-data' : ''}" data-date="${date}">
                <div class="text-center">${day}</div>
                ${decisionsCount > 0 ? `<div class="day-indicator">${decisionsCount}</div>` : ''}
            </div>`;
        }
        
        html += '</div>';
        document.getElementById('calendarGrid').innerHTML = html;
        
        document.querySelectorAll('.calendar-day').forEach(el => {
            el.onclick = () => showDayDetail(el.dataset.date);
        });
    }
    
    function showDayDetail(date) {
        const dayHistory = state.history.find(h => h.date === date);
        const container = document.getElementById('dayDetail');
        
        if (!dayHistory || dayHistory.decisions.length === 0) {
            container.innerHTML = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 text-center text-zinc-500">Aucune décision ce jour</div>';
            return;
        }
        
        let html = `<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">
            <h3 class="font-bold mb-3">📅 ${date}</h3>
            ${dayHistory.decisions.map(d => `
                <div class="bg-zinc-800 rounded-lg p-3 mb-2">
                    <div class="font-bold">${d.title}</div>
                    <div class="text-sm text-zinc-400 mt-1">Score: ${d.score}% • Choix: ${d.finalChoice === 'pros' ? '✅ Pour' : '❌ Contre'}</div>
                </div>
            `).join('')}
        </div>`;
        
        container.innerHTML = html;
    }
    
    return { init };
});
