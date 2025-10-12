<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Journal des Émotions - SanteMentale.org</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding-bottom: 40px; }
        .emotion-card { transition: all 0.3s ease; }
        .emotion-card:active { transform: scale(0.95); }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .popup-content { background: white; color: black; width: 80%; max-width: 400px; padding: 20px; border-radius: 10px; text-align: center; max-height: 80vh; overflow-y: auto; }
        .popup-content p { font-size: 16px; margin-bottom: 10px; }
        .close-btn { padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .username-input { padding: 8px; width: 80%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .calendar-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .calendar-day:hover { transform: scale(1.1); }
    </style>
</head>
<body>
    <div id="app">Chargement...</div>
    <div id="aboutPopup" class="popup"><div class="popup-content"><p>©2025 SanteMentale.org</p><p>API : 0.dev</p><p id="appVersion"></p><p id="clientUUID"></p><button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button></div></div>
    <div id="privacyPopup" class="popup"><div class="popup-content"><p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée. Tout est stocké sur votre appareil.</p><button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button></div></div>
    <div id="usernamePopup" class="popup"><div class="popup-content"><p>Nom à afficher</p><input type="text" id="usernameInput" class="username-input" placeholder="Votre nom"><button class="close-btn" onclick="saveUsername()">Enregistrer</button><button class="close-btn" onclick="closePopup('usernamePopup')">Annuler</button></div></div>
    <div id="advicePopup" class="popup"><div class="popup-content" style="text-align:left;"><h3 style="font-weight:bold;margin-bottom:15px;text-align:center;" id="adviceTitle"></h3><div id="adviceContent"></div><button class="close-btn" onclick="closePopup('advicePopup')" style="margin-top:15px;width:100%;">Fermer</button></div></div>

    <script>
        let state = {
            step: 1, selectedPrimary: null, selectedSecondary: null, context: '', customDate: '', customTime: '', tags: [],
            history: [], view: 'capture', showSuccess: false, deleteConfirm: null, editIndex: null, username: 'visiteur',
            searchQuery: '', filterEmotion: '', calendarDate: new Date(), streak: 0, goalDays: 5, reminderEnabled: false
        };

        const emotionWheel = {
            joie: { color: '#10B981', icon: 'bi-emoji-smile', advice: 'Savourez ce moment ! Partagez votre joie avec quelqu\'un.',
                secondaires: [{ key: 'serein', label: 'Serein', icon: 'bi-cloud-sun' }, { key: 'joyeux', label: 'Joyeux', icon: 'bi-emoji-laughing' },
                { key: 'optimiste', label: 'Optimiste', icon: 'bi-brightness-high' }, { key: 'fier', label: 'Fier', icon: 'bi-award' },
                { key: 'reconnaissant', label: 'Reconnaissant', icon: 'bi-heart' }]},
            tristesse: { color: '#3B82F6', icon: 'bi-emoji-frown', advice: 'C\'est OK. Accordez-vous de la douceur.',
                secondaires: [{ key: 'melancolique', label: 'Mélancolique', icon: 'bi-cloud-drizzle' }, { key: 'seul', label: 'Seul', icon: 'bi-person' },
                { key: 'desespere', label: 'Désespéré', icon: 'bi-emoji-tear' }, { key: 'nostalgique', label: 'Nostalgique', icon: 'bi-clock-history' },
                { key: 'vulnerable', label: 'Vulnérable', icon: 'bi-heart-break' }]},
            colere: { color: '#EF4444', icon: 'bi-emoji-angry', advice: 'Respirez avant de réagir.',
                secondaires: [{ key: 'irrite', label: 'Irrité', icon: 'bi-exclamation-circle' }, { key: 'frustre', label: 'Frustré', icon: 'bi-x-circle' },
                { key: 'furieux', label: 'Furieux', icon: 'bi-fire' }, { key: 'jaloux', label: 'Jaloux', icon: 'bi-eye' },
                { key: 'contrarie', label: 'Contrarié', icon: 'bi-dash-circle' }]},
            peur: { color: '#A78BFA', icon: 'bi-emoji-dizzy', advice: 'Respirez profondément. Ancrez-vous.',
                secondaires: [{ key: 'anxieux', label: 'Anxieux', icon: 'bi-exclamation-triangle' }, { key: 'inquiet', label: 'Inquiet', icon: 'bi-question-circle' },
                { key: 'terrifie', label: 'Terrifié', icon: 'bi-lightning' }, { key: 'nerveux', label: 'Nerveux', icon: 'bi-activity' },
                { key: 'stresse', label: 'Stressé', icon: 'bi-thermometer-high' }]},
            degout: { color: '#78716C', icon: 'bi-emoji-expressionless', advice: 'Cette émotion protège vos limites.',
                secondaires: [{ key: 'repugne', label: 'Répugné', icon: 'bi-x-octagon' }, { key: 'mefiant', label: 'Méfiant', icon: 'bi-shield' },
                { key: 'decu', label: 'Déçu', icon: 'bi-arrow-down-circle' }, { key: 'amer', label: 'Amer', icon: 'bi-droplet' },
                { key: 'mal_a_laise', label: 'Mal à l\'aise', icon: 'bi-emoji-neutral' }]},
            surprise: { color: '#F59E0B', icon: 'bi-emoji-surprise', advice: 'Prenez le temps d\'intégrer.',
                secondaires: [{ key: 'etonne', label: 'Étonné', icon: 'bi-star' }, { key: 'confus', label: 'Confus', icon: 'bi-question-diamond' },
                { key: 'stupefait', label: 'Stupéfait', icon: 'bi-exclamation-diamond' }, { key: 'curieux', label: 'Curieux', icon: 'bi-search' },
                { key: 'interesse', label: 'Intéressé', icon: 'bi-lightbulb' }]}
        };

        const tags = ['#travail', '#famille', '#santé', '#amour', '#amis', '#loisirs', '#argent', '#études'];

        function openPopup(id) { document.getElementById(id).style.display = 'flex'; }
        function closePopup(id) { document.getElementById(id).style.display = 'none'; }
        function openUsernamePopup() { document.getElementById('usernameInput').value = state.username; openPopup('usernamePopup'); }
        function saveUsername() { state.username = document.getElementById('usernameInput').value.trim() || 'visiteur'; localStorage.setItem('username', state.username); closePopup('usernamePopup'); render(); }
        function showAdvice(e) { document.getElementById('adviceTitle').textContent = 'Conseil : ' + e.charAt(0).toUpperCase() + e.slice(1); document.getElementById('adviceContent').innerHTML = '<p style="line-height:1.6;">' + emotionWheel[e].advice + '</p>'; openPopup('advicePopup'); }
        function toggleTag(t) { const i = state.tags.indexOf(t); i > -1 ? state.tags.splice(i, 1) : state.tags.push(t); render(); }
        
        function saveEntry() {
            const now = new Date();
            let d, t, ts;
            if (state.customDate && state.customTime) {
                const dt = new Date(state.customDate + 'T' + state.customTime);
                ts = dt.getTime(); d = dt.toLocaleDateString('fr-FR'); t = dt.toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'});
            } else {
                ts = Date.now(); d = now.toLocaleDateString('fr-FR'); t = now.toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'});
            }
            state.history.unshift({ primary: state.selectedPrimary, secondary: state.selectedSecondary.key, secondaryLabel: state.selectedSecondary.label,
                secondaryIcon: state.selectedSecondary.icon, context: state.context.trim(), tags: [...state.tags], timestamp: ts, date: d, time: t });
            state.history.sort((a,b) => b.timestamp - a.timestamp);
            localStorage.setItem('emotionHistory', JSON.stringify(state.history));
            calcStreak();
            state.showSuccess = true; setTimeout(() => { state.showSuccess = false; render(); }, 3000);
            state.step = 1; state.selectedPrimary = null; state.selectedSecondary = null; state.context = ''; state.customDate = ''; state.customTime = ''; state.tags = [];
            render();
        }
        
        function deleteEntry(i) { state.deleteConfirm = i; render(); }
        function confirmDelete() { state.history.splice(state.deleteConfirm, 1); localStorage.setItem('emotionHistory', JSON.stringify(state.history)); state.deleteConfirm = null; calcStreak(); render(); }
        function cancelDelete() { state.deleteConfirm = null; render(); }
        function editEntry(i) { state.editIndex = i; render(); }
        function saveEdit() {
            const e = state.history[state.editIndex];
            e.context = document.getElementById('editContextInput').value.trim();
            const dv = document.getElementById('editDateInput').value, tv = document.getElementById('editTimeInput').value;
            if (dv && tv) { const nd = new Date(dv + 'T' + tv); e.timestamp = nd.getTime(); e.date = nd.toLocaleDateString('fr-FR'); e.time = nd.toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'}); }
            state.history.sort((a,b) => b.timestamp - a.timestamp);
            localStorage.setItem('emotionHistory', JSON.stringify(state.history));
            state.editIndex = null; render();
        }
        function cancelEdit() { state.editIndex = null; render(); }
        
        function calcStreak() {
            const dates = new Set(); state.history.forEach(e => dates.add(e.date));
            let s = 0; const t = new Date();
            for (let i = 0; i < 365; i++) { const c = new Date(t); c.setDate(t.getDate() - i); if (dates.has(c.toLocaleDateString('fr-FR'))) s++; else break; }
            state.streak = s; localStorage.setItem('emotionStreak', s);
        }
        
        function getStats() {
            const c = {}; state.history.forEach(e => c[e.primary] = (c[e.primary] || 0) + 1);
            return Object.entries(c).map(([n, ct]) => ({ name: n.charAt(0).toUpperCase() + n.slice(1), count: ct, color: emotionWheel[n]?.color }));
        }
        
        function getSecStats() {
            const c = {}; state.history.forEach(e => c[e.secondaryLabel] = (c[e.secondaryLabel] || 0) + 1);
            return Object.entries(c).map(([n, ct]) => ({ name: n, count: ct })).sort((a,b) => b.count - a.count).slice(0, 8);
        }
        
        function getKeywords() {
            const w = {}, stop = ['le','la','les','un','une','des','de','du','à','au','aux','et','ou','est','avec','pour','par','dans','sur','ce','qui','que','il','elle','on','nous','vous','ils','elles','je','tu','mon','ma','mes','ton','ta','tes','son','sa','ses'];
            state.history.forEach(e => {
                if (e.context) {
                    const ws = e.context.toLowerCase().match(/\b\w+\b/g) || [];
                    ws.forEach(word => { if (word.length > 3 && !stop.includes(word)) w[word] = (w[word] || 0) + 1; });
                }
            });
            return Object.entries(w).sort((a,b) => b[1] - a[1]).slice(0, 10);
        }
        
        function getHourly() {
            const h = Array(24).fill(0);
            state.history.forEach(e => { const hr = parseInt(e.time.split(':')[0]); h[hr]++; });
            return h;
        }
        
        function get7Days() {
            const d = [];
            for (let i = 6; i >= 0; i--) {
                const dt = new Date(); dt.setDate(dt.getDate() - i);
                const ds = dt.toLocaleDateString('fr-FR');
                const de = state.history.filter(e => e.date === ds);
                d.push({ date: dt.toLocaleDateString('fr-FR', {day:'numeric',month:'short'}),
                    pos: de.filter(e => e.primary === 'joie' || e.primary === 'surprise').length,
                    neg: de.filter(e => ['tristesse','colere','peur','degout'].includes(e.primary)).length });
            }
            return d;
        }
        
        function checkNegPattern() {
            const l = [];
            for (let i = 0; i < 5; i++) {
                const d = new Date(); d.setDate(d.getDate() - i);
                const ds = d.toLocaleDateString('fr-FR');
                const de = state.history.filter(e => e.date === ds);
                const n = de.filter(e => ['tristesse','colere','peur','degout'].includes(e.primary)).length;
                const p = de.filter(e => ['joie','surprise'].includes(e.primary)).length;
                l.push(n > p && n > 0);
            }
            return l.filter(d => d).length >= 3;
        }
        
        function getFiltered() {
            let f = [...state.history];
            if (state.searchQuery) {
                const q = state.searchQuery.toLowerCase();
                f = f.filter(e => e.context.toLowerCase().includes(q) || e.secondaryLabel.toLowerCase().includes(q) || e.primary.toLowerCase().includes(q) || (e.tags && e.tags.some(t => t.toLowerCase().includes(q))));
            }
            if (state.filterEmotion) f = f.filter(e => e.primary === state.filterEmotion);
            return f;
        }
        
        function exportPDF() {
            const stats = getStats(), kw = getKeywords();
            const html = '<div style="padding:20px;font-family:Arial;"><h1 style="text-align:center;color:#3B82F6;">Journal des Émotions</h1><h2 style="text-align:center;color:#666;">' + state.username + '</h2><p style="text-align:center;color:#999;">Généré le ' + new Date().toLocaleDateString('fr-FR') + '</p><h3 style="color:#3B82F6;margin-top:30px;">📊 Statistiques</h3><p><strong>Total :</strong> ' + state.history.length + '</p><p><strong>Série :</strong> ' + state.streak + ' jours</p><h4 style="color:#666;margin-top:20px;">Émotions</h4>' + stats.map(s => '<p>' + s.name + ': ' + s.count + '×</p>').join('') + '<h4 style="color:#666;margin-top:20px;">Mots-clés</h4><p>' + kw.map(k => k[0]).join(', ') + '</p><h3 style="color:#3B82F6;margin-top:30px;">📝 Entrées récentes</h3>' + state.history.slice(0,20).map(e => '<div style="margin:15px 0;padding:10px;border-left:4px solid ' + emotionWheel[e.primary].color + ';background:#f5f5f5;"><p style="color:#666;font-size:12px;">' + e.date + ' à ' + e.time + '</p><p style="font-weight:bold;color:#333;">' + e.secondaryLabel + '</p>' + (e.context ? '<p style="color:#666;font-style:italic;">"' + e.context + '"</p>' : '') + (e.tags && e.tags.length > 0 ? '<p style="font-size:12px;color:#999;">' + e.tags.join(' ') + '</p>' : '') + '</div>').join('') + '</div>';
            const el = document.createElement('div'); el.innerHTML = html;
            html2pdf().from(el).save('Journal_Emotions_' + state.username + '_' + new Date().toLocaleDateString('fr-FR') + '.pdf');
        }
        
        function exportJSON() {
            const d = { username: state.username, exportDate: new Date().toISOString(), history: state.history, streak: state.streak };
            const b = new Blob([JSON.stringify(d, null, 2)], {type:'application/json'});
            const u = URL.createObjectURL(b), a = document.createElement('a');
            a.href = u; a.download = 'journal_emotions_' + state.username + '_' + new Date().toISOString().split('T')[0] + '.json';
            a.click();
        }
        
        function renderCharts() {
            setTimeout(() => {
                const pc = document.getElementById('primaryChart');
                if (pc) { const s = getStats(); new Chart(pc, { type:'bar', data:{ labels:s.map(x=>x.name), datasets:[{data:s.map(x=>x.count), backgroundColor:s.map(x=>x.color), borderRadius:6}]}, options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{color:'#a1a1aa'},grid:{color:'#27272a'}}, x:{ticks:{color:'#a1a1aa'},grid:{display:false}}}}}); }
                
                const tc = document.getElementById('trendChart');
                if (tc) { const d = get7Days(); new Chart(tc, { type:'line', data:{ labels:d.map(x=>x.date), datasets:[{label:'Positives', data:d.map(x=>x.pos), borderColor:'#10B981', backgroundColor:'rgba(16,185,129,0.1)', tension:0.4, fill:true}, {label:'Négatives', data:d.map(x=>x.neg), borderColor:'#EF4444', backgroundColor:'rgba(239,68,68,0.1)', tension:0.4, fill:true}]}, options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{display:true,labels:{color:'#fff'}}}, scales:{y:{beginAtZero:true,ticks:{color:'#a1a1aa'},grid:{color:'#27272a'}}, x:{ticks:{color:'#a1a1aa'},grid:{color:'#27272a'}}}}}); }
                
                const hc = document.getElementById('hourlyChart');
                if (hc) { const h = getHourly(); new Chart(hc, { type:'line', data:{ labels:Array.from({length:24},(_, i)=>i+'h'), datasets:[{label:'Par heure', data:h, borderColor:'#F59E0B', backgroundColor:'rgba(245,158,11,0.1)', tension:0.4, fill:true}]}, options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{color:'#a1a1aa'},grid:{color:'#27272a'}}, x:{ticks:{color:'#a1a1aa'},grid:{color:'#27272a'}}}}}); }
            }, 100);
        }
        
        function renderCal() {
            const y = state.calendarDate.getFullYear(), m = state.calendarDate.getMonth();
            const first = new Date(y, m, 1), last = new Date(y, m + 1, 0);
            const days = last.getDate(), start = first.getDay();
            const ebd = {};
            state.history.forEach(e => { if (!ebd[e.date]) ebd[e.date] = []; ebd[e.date].push(e); });
            
            let h = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
            h += '<div class="flex items-center justify-between mb-4">';
            h += '<button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()-1);render();" class="text-blue-400"><i class="bi bi-chevron-left"></i></button>';
            h += '<h3 class="font-bold">' + first.toLocaleDateString('fr-FR', {month:'long',year:'numeric'}) + '</h3>';
            h += '<button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()+1);render();" class="text-blue-400"><i class="bi bi-chevron-right"></i></button>';
            h += '</div><div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 text-zinc-500"><div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div></div>';
            h += '<div class="grid grid-cols-7 gap-1">';
            for (let i = 0; i < start; i++) h += '<div></div>';
            for (let d = 1; d <= days; d++) {
                const dt = new Date(y, m, d), ds = dt.toLocaleDateString('fr-FR'), de = ebd[ds] || [];
                let bg = '#18181b', txt = '#a1a1aa';
                if (de.length > 0) { bg = emotionWheel[de[0].primary].color; txt = '#fff'; }
                h += '<div class="calendar-day text-sm font-semibold" style="background-color:' + bg + ';color:' + txt + ';" title="' + de.length + ' émotion(s)">' + d + '</div>';
            }
            h += '</div></div>';
            return h;
        }
        
        function updateChar() { const t = document.getElementById('contextInput'), c = document.getElementById('charCount'); if (t && c) { c.textContent = t.value.length + '/200'; c.style.color = t.value.length > 180 ? '#EF4444' : '#a1a1aa'; }}
        function updateEditChar() { const t = document.getElementById('editContextInput'), c = document.getElementById('editCharCount'); if (t && c) { c.textContent = t.value.length + '/200'; c.style.color = t.value.length > 180 ? '#EF4444' : '#a1a1aa'; }}
        
        function render() {
            const app = document.getElementById('app');
            let h = '<div class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-10"><div class="max-w-2xl mx-auto px-4 py-4"><h1 class="text-xl font-bold"><i class="bi bi-journal-heart-fill mr-2"></i>Journal des Émotions</h1></div></div>';
            h += '<div class="bg-zinc-900 border-b border-zinc-800"><div class="max-w-2xl mx-auto px-4"><div class="flex gap-1 overflow-x-auto">';
            h += '<button onclick="state.view=\'capture\';render();" class="py-3 px-4 font-semibold whitespace-nowrap ' + (state.view==='capture'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-bullseye mr-2"></i>Identifier</button>';
            h += '<button onclick="state.view=\'stats\';render();" class="py-3 px-4 font-semibold whitespace-nowrap ' + (state.view==='stats'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-bar-chart-fill mr-2"></i>Analyse</button>';
            h += '<button onclick="state.view=\'journal\';render();" class="py-3 px-4 font-semibold whitespace-nowrap ' + (state.view==='journal'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-journal-text mr-2"></i>Journal</button>';
            h += '<button onclick="state.view=\'calendar\';render();" class="py-3 px-4 font-semibold whitespace-nowrap ' + (state.view==='calendar'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-calendar-month mr-2"></i>Calendrier</button>';
            h += '<button onclick="state.view=\'goals\';render();" class="py-3 px-4 font-semibold whitespace-nowrap ' + (state.view==='goals'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-trophy mr-2"></i>Objectifs</button>';
            h += '</div></div></div><div class="max-w-2xl mx-auto px-4 py-6">';
            
            if (state.showSuccess) h += '<div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2"><i class="bi bi-check-circle-fill text-xl"></i><span class="font-semibold">Émotion enregistrée !</span></div>';
            
            if (state.deleteConfirm !== null) h += '<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 max-w-sm mx-4"><p class="text-white mb-4">Supprimer cette entrée ?</p><div class="flex gap-2"><button onclick="cancelDelete()" class="flex-1 py-2 bg-zinc-800 text-white rounded-lg">Annuler</button><button onclick="confirmDelete()" class="flex-1 py-2 bg-red-600 text-white rounded-lg">Supprimer</button></div></div></div>';
            
            if (state.editIndex !== null) {
                const e = state.history[state.editIndex], dt = new Date(e.timestamp);
                const dv = dt.toISOString().split('T')[0], tv = dt.toTimeString().split(' ')[0].substring(0,5);
                h += '<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 max-w-md w-full max-h-[80vh] overflow-y-auto"><h3 class="text-white font-bold mb-4">Modifier</h3>';
                h += '<div class="mb-4"><div class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold border-2" style="border-color:' + emotionWheel[e.primary].color + ';background-color:' + emotionWheel[e.primary].color + '20"><i class="bi ' + e.secondaryIcon + '"></i><span>' + e.secondaryLabel + '</span></div></div>';
                h += '<div class="mb-3 grid grid-cols-2 gap-3"><div><label class="block text-sm font-bold mb-2 text-zinc-300">Date</label><input type="date" id="editDateInput" value="' + dv + '" class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white"></div>';
                h += '<div><label class="block text-sm font-bold mb-2 text-zinc-300">Heure</label><input type="time" id="editTimeInput" value="' + tv + '" class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white"></div></div>';
                h += '<label class="block text-sm font-bold mb-2 text-zinc-300">Contexte</label><textarea id="editContextInput" class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-lg text-white" rows="3" maxlength="200" oninput="updateEditChar()">' + e.context + '</textarea>';
                h += '<div class="text-right text-sm text-zinc-500 mt-1" id="editCharCount">' + e.context.length + '/200</div>';
                h += '<div class="flex gap-2 mt-4"><button onclick="cancelEdit()" class="flex-1 py-2 bg-zinc-800 text-white rounded-lg">Annuler</button><button onclick="saveEdit()" class="flex-1 py-2 bg-blue-600 text-white rounded-lg">Enregistrer</button></div></div></div>';
            }
            
            if (state.view === 'capture') {
                h += '<div class="space-y-4"><div class="flex items-center gap-2 text-sm text-zinc-500">';
                h += '<span class="' + (state.step>=1?'text-blue-400 font-semibold':'') + '">Émotion</span><i class="bi bi-arrow-right text-xs"></i>';
                h += '<span class="' + (state.step>=2?'text-blue-400 font-semibold':'') + '">Nuance</span><i class="bi bi-arrow-right text-xs"></i>';
                h += '<span class="' + (state.step>=3?'text-blue-400 font-semibold':'') + '">Détails</span></div>';
                
                if (state.step === 1) {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h2 class="font-bold mb-3">Quelle émotion ressentez-vous ?</h2><div class="grid grid-cols-2 gap-3">';
                    Object.entries(emotionWheel).forEach(([k, d]) => {
                        h += '<button onclick="state.selectedPrimary=\'' + k + '\';state.step=2;render();" class="emotion-card p-4 rounded-lg border-2 border-zinc-800 hover:border-blue-500 bg-zinc-950">';
                        h += '<i class="bi ' + d.icon + ' text-4xl mb-2" style="color:' + d.color + '"></i><div class="font-bold capitalize text-zinc-200">' + k + '</div></button>';
                    });
                    h += '</div></div>';
                } else if (state.step === 2 && state.selectedPrimary) {
                    const p = emotionWheel[state.selectedPrimary];
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><button onclick="state.step=1;render();" class="text-blue-400 mb-3 text-sm"><i class="bi bi-arrow-left mr-1"></i>Retour</button>';
                    h += '<div class="flex items-center gap-3 mb-4"><i class="bi ' + p.icon + ' text-3xl" style="color:' + p.color + '"></i><h2 class="font-bold capitalize">' + state.selectedPrimary + ' → Précisez</h2></div><div class="space-y-2">';
                    p.secondaires.forEach(em => {
                        h += '<button onclick=\'state.selectedSecondary=' + JSON.stringify(em) + ';state.step=3;render();\' class="emotion-card w-full p-3 rounded-lg border-2 border-zinc-800 hover:border-blue-500 bg-zinc-950 text-left flex items-center gap-3">';
                        h += '<i class="bi ' + em.icon + ' text-2xl" style="color:' + p.color + '"></i><span class="font-semibold text-zinc-200">' + em.label + '</span></button>';
                    });
                    h += '</div></div>';
                } else if (state.step === 3 && state.selectedSecondary) {
                    const p = emotionWheel[state.selectedPrimary], now = new Date();
                    const dd = now.toISOString().split('T')[0], dt = now.toTimeString().split(' ')[0].substring(0,5);
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><button onclick="state.step=2;render();" class="text-blue-400 mb-3 text-sm"><i class="bi bi-arrow-left mr-1"></i>Retour</button>';
                    h += '<div class="mb-4"><div class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold border-2" style="border-color:' + p.color + ';background-color:' + p.color + '20">';
                    h += '<i class="bi ' + state.selectedSecondary.icon + '"></i><span>' + state.selectedSecondary.label + '</span></div>';
                    h += '<button onclick="showAdvice(\'' + state.selectedPrimary + '\')" class="ml-3 text-blue-400 text-sm hover:text-blue-300"><i class="bi bi-lightbulb"></i> Conseil</button></div>';
                    h += '<div class="mb-4 grid grid-cols-2 gap-3"><div><label class="block text-sm font-bold mb-2 text-zinc-300">Date</label><input type="date" id="dateInput" value="' + (state.customDate||dd) + '" class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white" onchange="state.customDate=this.value"></div>';
                    h += '<div><label class="block text-sm font-bold mb-2 text-zinc-300">Heure</label><input type="time" id="timeInput" value="' + (state.customTime||dt) + '" class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white" onchange="state.customTime=this.value"></div></div>';
                    h += '<label class="block font-bold mb-2">Que s\'est-il passé ?</label><textarea id="contextInput" placeholder="Ex: Dispute avec un collègue..." class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-lg text-white placeholder-zinc-600" rows="3" maxlength="200" oninput="updateChar()">' + state.context + '</textarea>';
                    h += '<div class="text-right text-sm text-zinc-500 mt-1" id="charCount">0/200</div>';
                    h += '<label class="block font-bold mb-2 mt-4">Tags (optionnel)</label><div class="flex flex-wrap gap-2">';
                    tags.forEach(t => h += '<button onclick="toggleTag(\'' + t + '\')" class="px-3 py-1 rounded-full text-sm ' + (state.tags.includes(t)?'bg-blue-600 text-white':'bg-zinc-800 text-zinc-400') + ' hover:bg-blue-500 hover:text-white">' + t + '</button>');
                    h += '</div><div class="flex gap-2 mt-4"><button onclick="state.step=1;state.selectedPrimary=null;state.selectedSecondary=null;state.context=\'\';state.customDate=\'\';state.customTime=\'\';state.tags=[];render();" class="flex-1 py-3 bg-zinc-800 text-white font-bold rounded-lg">Annuler</button>';
                    h += '<button onclick="state.context=document.getElementById(\'contextInput\').value;state.customDate=document.getElementById(\'dateInput\').value;state.customTime=document.getElementById(\'timeInput\').value;saveEntry();" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-lg">Enregistrer</button></div></div>';
                }
                h += '<div class="bg-blue-950 border border-blue-900 rounded-lg p-4 text-sm text-blue-200"><i class="bi bi-lightbulb mr-2"></i><strong>Conseil :</strong> Identifier vos émotions aide à mieux les gérer.</div></div>';
            } else if (state.view === 'stats') {
                if (state.history.length === 0) {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 text-center text-zinc-500"><i class="bi bi-graph-up text-4xl mb-3 opacity-30"></i><p>Aucune donnée</p></div>';
                } else {
                    const pos = state.history.filter(e => e.primary==='joie'||e.primary==='surprise').length;
                    const neg = state.history.filter(e => ['tristesse','colere','peur','degout'].includes(e.primary)).length;
                    const kw = getKeywords(), hasneg = checkNegPattern();
                    h += '<div class="space-y-4">';
                    if (hasneg) h += '<div class="bg-red-950 border border-red-900 rounded-lg p-4 text-sm text-red-200"><i class="bi bi-exclamation-triangle mr-2"></i><strong>Attention :</strong> Beaucoup d\'émotions négatives récemment. Pensez à en parler.</div>';
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-graph-up mr-2"></i>Résumé</h3><div class="grid grid-cols-3 gap-3">';
                    h += '<div class="text-center p-3 bg-zinc-950 rounded-lg border border-zinc-800"><div class="text-2xl font-bold">' + state.history.length + '</div><div class="text-xs text-zinc-500">Total</div></div>';
                    h += '<div class="text-center p-3 bg-green-950 rounded-lg border border-green-900"><div class="text-2xl font-bold text-green-400">' + pos + '</div><div class="text-xs text-zinc-500">Positives</div></div>';
                    h += '<div class="text-center p-3 bg-red-950 rounded-lg border border-red-900"><div class="text-2xl font-bold text-red-400">' + neg + '</div><div class="text-xs text-zinc-500">Négatives</div></div></div></div>';
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-palette mr-2"></i>Émotions</h3><div style="height:200px;"><canvas id="primaryChart"></canvas></div></div>';
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-search mr-2"></i>Nuances fréquentes</h3><div class="space-y-2">';
                    getSecStats().forEach(s => h += '<div class="flex items-center justify-between p-2 bg-zinc-950 rounded border border-zinc-800"><span class="text-sm font-semibold text-zinc-300">' + s.name + '</span><span class="text-sm text-zinc-500">×' + s.count + '</span></div>');
                    h += '</div></div><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-clock mr-2"></i>Par heure</h3><div style="height:180px;"><canvas id="hourlyChart"></canvas></div></div>';
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-graph-down mr-2"></i>Tendance (7j)</h3><div style="height:180px;"><canvas id="trendChart"></canvas></div></div>';
                    if (kw.length > 0) {
                        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-chat-quote mr-2"></i>Mots-clés</h3><div class="flex flex-wrap gap-2">';
                        kw.forEach(([w,c]) => h += '<div class="px-3 py-1 bg-zinc-950 border border-zinc-800 rounded-full text-sm"><span class="text-zinc-300">' + w + '</span><span class="text-zinc-600 ml-1">×' + c + '</span></div>');
                        h += '</div></div>';
                    }
                    h += '<div class="flex gap-2"><button onclick="exportPDF()" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-lg flex items-center justify-center gap-2"><i class="bi bi-file-pdf"></i> PDF</button>';
                    h += '<button onclick="exportJSON()" class="flex-1 py-3 bg-zinc-700 text-white font-bold rounded-lg flex items-center justify-center gap-2"><i class="bi bi-file-code"></i> JSON</button></div></div>';
                    renderCharts();
                }
            } else if (state.view === 'journal') {
                h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                h += '<input type="text" placeholder="Rechercher..." class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white mb-3" value="' + state.searchQuery + '" oninput="state.searchQuery=this.value;render()">';
                h += '<div class="flex gap-2 flex-wrap"><button onclick="state.filterEmotion=\'\';render();" class="px-3 py-1 rounded-full text-sm ' + (!state.filterEmotion?'bg-blue-600 text-white':'bg-zinc-800 text-zinc-400') + '">Toutes</button>';
                Object.keys(emotionWheel).forEach(e => h += '<button onclick="state.filterEmotion=\'' + e + '\';render();" class="px-3 py-1 rounded-full text-sm ' + (state.filterEmotion===e?'bg-blue-600 text-white':'bg-zinc-800 text-zinc-400') + '">' + e.charAt(0).toUpperCase() + e.slice(1) + '</button>');
                h += '</div></div>';
                const filt = getFiltered();
                if (filt.length === 0) {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 text-center text-zinc-500"><i class="bi bi-book-open text-4xl mb-3 opacity-30"></i><p>' + (state.searchQuery||state.filterEmotion?'Aucun résultat':'Votre journal est vide') + '</p></div>';
                } else {
                    h += '<div class="space-y-3">';
                    filt.forEach(e => {
                        const idx = state.history.indexOf(e), c = emotionWheel[e.primary].color;
                        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 relative" style="border-left:4px solid ' + c + '">';
                        h += '<div class="absolute top-3 right-3 flex gap-2"><button onclick="editEntry(' + idx + ')" class="text-zinc-600 hover:text-blue-400 hover:bg-blue-950 rounded-full p-2"><i class="bi bi-pencil"></i></button>';
                        h += '<button onclick="deleteEntry(' + idx + ')" class="text-zinc-600 hover:text-red-400 hover:bg-red-950 rounded-full p-2"><i class="bi bi-trash"></i></button></div>';
                        h += '<div class="flex items-start justify-between mb-2 pr-20"><div><div class="flex items-center gap-2 mb-1"><i class="bi ' + e.secondaryIcon + '" style="color:' + c + '"></i><span class="font-bold">' + e.secondaryLabel + '</span></div>';
                        h += '<div class="text-xs text-zinc-500 capitalize">' + e.primary + '</div></div><div class="text-xs text-zinc-500 text-right">' + e.date + '<br>' + e.time + '</div></div>';
                        if (e.context) h += '<p class="text-sm text-zinc-400 mt-2 italic">"' + e.context + '"</p>';
                        if (e.tags && e.tags.length > 0) h += '<div class="mt-2 flex flex-wrap gap-1">' + e.tags.map(t => '<span class="px-2 py-1 bg-blue-600 text-white rounded-full text-xs">' + t + '</span>').join('') + '</div>';
                        h += '</div>';
                    });
                    h += '</div>';
                }
                h += '</div>';
            } else if (state.view === 'calendar') {
                h += renderCal();
            } else if (state.view === 'goals') {
                const wk = state.history.filter(e => { const d = new Date(e.timestamp), w = new Date(); w.setDate(w.getDate()-7); return d >= w; }).length;
                const prog = Math.min((wk/state.goalDays)*100, 100);
                h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-fire mr-2"></i>Série actuelle</h3><div class="text-center">';
                h += '<div class="text-6xl font-bold text-yellow-500 mb-2">' + state.streak + '</div><div class="text-zinc-400">jour' + (state.streak>1?'s':'') + ' consécutif' + (state.streak>1?'s':'') + '</div></div></div>';
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-trophy mr-2"></i>Objectif hebdomadaire</h3><div class="mb-3">';
                h += '<div class="flex items-center justify-between mb-2"><span class="text-zinc-400">Cette semaine</span><span class="font-bold">' + wk + ' / ' + state.goalDays + '</span></div>';
                h += '<div class="w-full bg-zinc-800 rounded-full h-4 overflow-hidden"><div class="bg-blue-600 h-full transition-all" style="width:' + prog + '%"></div></div></div>';
                h += '<div class="flex gap-2"><button onclick="state.goalDays=Math.max(1,state.goalDays-1);localStorage.setItem(\'goalDays\',state.goalDays);render();" class="px-4 py-2 bg-zinc-800 text-white rounded-lg"><i class="bi bi-dash"></i></button>';
                h += '<div class="flex-1 text-center py-2 bg-zinc-950 rounded-lg">Objectif: ' + state.goalDays + ' jour' + (state.goalDays>1?'s':'') + '</div>';
                h += '<button onclick="state.goalDays=Math.min(7,state.goalDays+1);localStorage.setItem(\'goalDays\',state.goalDays);render();" class="px-4 py-2 bg-zinc-800 text-white rounded-lg"><i class="bi bi-plus"></i></button></div></div>';
                h += '<div class="bg-blue-950 border border-blue-900 rounded-lg p-4 text-sm text-blue-200"><i class="bi bi-star mr-2"></i><strong>Conseil :</strong> La régularité compte plus que la quantité.</div></div>';
            }
            
            h += '</div><div style="margin-top:40px;padding-bottom:40px;text-align:center;"><a href="/v1/outils/" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-lg" style="text-decoration:none;"><i class="bi bi-arrow-left"></i> Retour</a>';
            h += '<p style="margin-top:30px;color:#555;font-size:14px;line-height:1.8;"><svg style="width:14px;height:14px;margin-right:5px;display:inline-block;vertical-align:middle;" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span style="color:#ddd;cursor:pointer;" onclick="openUsernamePopup()">' + state.username + '</span><br>v<span id="footerVersion"></span> • Accès anticipé<br><a onclick="openPopup(\'aboutPopup\')" style="color:#0d47a1;cursor:pointer;">À propos</a> • <a onclick="openPopup(\'privacyPopup\')" style="color:#0d47a1;cursor:pointer;">Confidentialité</a><br/><span style="color:#161616;">©2025 SanteMentale.org</span></p></div></div>';
            
            app.innerHTML = h;
            document.getElementById('footerVersion').textContent = localStorage.getItem('client_version') || '1.web';
            if (state.step === 3) updateChar();
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            try {
                state.history = JSON.parse(localStorage.getItem('emotionHistory') || '[]');
                state.username = localStorage.getItem('username') || 'visiteur';
                state.goalDays = parseInt(localStorage.getItem('goalDays')) || 5;
                
                if (!localStorage.getItem('device_uuid')) {
                    localStorage.setItem('device_uuid', crypto.randomUUID());
                    openPopup('privacyPopup');
                }
                
                let cv = localStorage.getItem('client_version');
                if (!cv) {
                    const up = new URLSearchParams(window.location.search), vfu = up.get('v');
                    cv = vfu || '1.web';
                    localStorage.setItem('client_version', cv);
                }
                
                document.getElementById('clientUUID').textContent = 'Client : ' + localStorage.getItem('device_uuid');
                document.getElementById('appVersion').textContent = 'App : ' + cv;
                
                calcStreak();
                render();
            } catch(e) {
                console.error('Erreur:', e);
                document.getElementById('app').innerHTML = '<div style="color:red;padding:20px;">Erreur: ' + e.message + '</div>';
            }
        });
    </script>
</body>
</html>
