<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Journal de Gratitude - SanteMentale.org</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding-bottom: 40px; }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .popup-content { background: white; color: black; width: 80%; max-width: 400px; padding: 20px; border-radius: 10px; text-align: center; max-height: 80vh; overflow-y: auto; }
        .popup-content p { font-size: 16px; margin-bottom: 10px; }
        .close-btn { padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .username-input { padding: 8px; width: 80%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .calendar-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 600; }
        .calendar-day:hover { transform: scale(1.1); }
    </style>
</head>
<body>
    <div id="app">Chargement...</div>
    <div id="aboutPopup" class="popup"><div class="popup-content"><p>©2025 SanteMentale.org</p><p>API : 0.dev</p><p id="appVersion"></p><p id="clientUUID"></p><button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button></div></div>
    <div id="privacyPopup" class="popup"><div class="popup-content"><p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée. Tout est stocké sur votre appareil.</p><button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button></div></div>
    <div id="usernamePopup" class="popup"><div class="popup-content"><p>Nom à afficher sur les rapports PDF</p><input type="text" id="usernameInput" class="username-input" placeholder="Votre nom"><button class="close-btn" onclick="saveUsername()">Enregistrer</button><button class="close-btn" onclick="closePopup('usernamePopup')">Annuler</button></div></div>

    <script>
        const MODULE_REVISION = 1;
        let state = { gratitude1: '', gratitude2: '', gratitude3: '', customDate: '', history: [], view: 'capture', showSuccess: false, deleteConfirm: null, editIndex: null, username: 'visiteur', searchQuery: '', calendarDate: new Date(), streak: 0, goalDays: 5 };

        function openPopup(id) { document.getElementById(id).style.display = 'flex'; }
        function closePopup(id) { document.getElementById(id).style.display = 'none'; }
        function openUsernamePopup() { document.getElementById('usernameInput').value = state.username; openPopup('usernamePopup'); }
        function saveUsername() { state.username = document.getElementById('usernameInput').value.trim() || 'visiteur'; localStorage.setItem('username', state.username); closePopup('usernamePopup'); render(); }
        
        function saveEntry() {
            const g1 = document.getElementById('g1').value.trim();
            const g2 = document.getElementById('g2').value.trim();
            const g3 = document.getElementById('g3').value.trim();
            if (!g1 && !g2 && !g3) { alert('Veuillez entrer au moins une gratitude.'); return; }
            const now = new Date();
            let d, ts;
            if (state.customDate) { const dt = new Date(state.customDate); ts = dt.getTime(); d = dt.toLocaleDateString('fr-FR'); }
            else { ts = Date.now(); d = now.toLocaleDateString('fr-FR'); }
            const entry = { gratitudes: [g1, g2, g3].filter(g => g), timestamp: ts, date: d };
            const existingIdx = state.history.findIndex(e => e.date === d);
            if (existingIdx > -1) { if (confirm('Une entrée existe déjà pour cette date. Remplacer ?')) state.history[existingIdx] = entry; else return; }
            else state.history.unshift(entry);
            state.history.sort((a,b) => b.timestamp - a.timestamp);
            localStorage.setItem('gratitudeHistory', JSON.stringify(state.history));
            calcStreak();
            state.showSuccess = true; setTimeout(() => { state.showSuccess = false; render(); }, 3000);
            state.gratitude1 = ''; state.gratitude2 = ''; state.gratitude3 = ''; state.customDate = ''; render();
        }
        
        function deleteEntry(i) { state.deleteConfirm = i; render(); }
        function confirmDelete() { state.history.splice(state.deleteConfirm, 1); localStorage.setItem('gratitudeHistory', JSON.stringify(state.history)); state.deleteConfirm = null; calcStreak(); render(); }
        function cancelDelete() { state.deleteConfirm = null; render(); }
        function editEntry(i) { state.editIndex = i; render(); }
        function saveEdit() {
            const e = state.history[state.editIndex];
            const g1 = document.getElementById('edit1').value.trim();
            const g2 = document.getElementById('edit2').value.trim();
            const g3 = document.getElementById('edit3').value.trim();
            e.gratitudes = [g1, g2, g3].filter(g => g);
            const dv = document.getElementById('editDateInput').value;
            if (dv) { const nd = new Date(dv); e.timestamp = nd.getTime(); e.date = nd.toLocaleDateString('fr-FR'); }
            state.history.sort((a,b) => b.timestamp - a.timestamp);
            localStorage.setItem('gratitudeHistory', JSON.stringify(state.history));
            state.editIndex = null; render();
        }
        function cancelEdit() { state.editIndex = null; render(); }
        
        function calcStreak() {
            const dates = new Set(); state.history.forEach(e => dates.add(e.date));
            let s = 0; const t = new Date();
            for (let i = 0; i < 365; i++) { const c = new Date(t); c.setDate(t.getDate() - i); if (dates.has(c.toLocaleDateString('fr-FR'))) s++; else break; }
            state.streak = s; localStorage.setItem('gratitudeStreak', s);
        }
        
        function get7Days() {
            const d = [];
            for (let i = 6; i >= 0; i--) {
                const dt = new Date(); dt.setDate(dt.getDate() - i);
                const ds = dt.toLocaleDateString('fr-FR');
                const de = state.history.find(e => e.date === ds);
                d.push({ date: dt.toLocaleDateString('fr-FR', {day:'numeric',month:'short'}), count: de ? de.gratitudes.length : 0 });
            }
            return d;
        }
        
        function getMonthlyStats() {
            const m = {};
            state.history.forEach(e => {
                const dt = new Date(e.timestamp);
                const month = dt.toLocaleDateString('fr-FR', {month:'long',year:'numeric'});
                m[month] = (m[month] || 0) + 1;
            });
            return Object.entries(m).sort((a,b) => b[1] - a[1]).slice(0, 6);
        }
        
        function getKeywords() {
            const w = {}, stop = ['je','suis','pour','que','qui','mon','ma','mes','son','sa','ses','le','la','les','un','une','des','de','du','à','au','aux','et','ou','est','avec','dans','sur','ce','il','elle','on','nous','vous','ils','elles','tu','ton','ta','tes','été','avoir','être','faire'];
            state.history.forEach(e => {
                e.gratitudes.forEach(g => {
                    const ws = g.toLowerCase().match(/\b\w+\b/g) || [];
                    ws.forEach(word => { if (word.length > 3 && !stop.includes(word)) w[word] = (w[word] || 0) + 1; });
                });
            });
            return Object.entries(w).sort((a,b) => b[1] - a[1]).slice(0, 15);
        }
        
        function getFiltered() {
            let f = [...state.history];
            if (state.searchQuery) { const q = state.searchQuery.toLowerCase(); f = f.filter(e => e.gratitudes.some(g => g.toLowerCase().includes(q))); }
            return f;
        }
        
        function exportPDF() {
            const kw = getKeywords();
            const html = '<div style="padding:20px;font-family:Arial;"><h1 style="text-align:center;color:#10B981;">Journal de Gratitude</h1><h2 style="text-align:center;color:#666;">' + state.username + '</h2><p style="text-align:center;color:#999;">Généré le ' + new Date().toLocaleDateString('fr-FR') + '</p><h3 style="color:#10B981;margin-top:30px;">📊 Statistiques</h3><p><strong>Total :</strong> ' + state.history.length + ' entrées</p><p><strong>Série :</strong> ' + state.streak + ' jours</p><h4 style="color:#666;margin-top:20px;">Thèmes fréquents</h4><p>' + kw.map(k => k[0]).join(', ') + '</p><h3 style="color:#10B981;margin-top:30px;">📝 Entrées récentes</h3>' + state.history.slice(0,30).map(e => '<div style="margin:15px 0;padding:10px;border-left:4px solid #10B981;background:#f5f5f5;"><p style="color:#666;font-size:12px;">' + e.date + '</p>' + e.gratitudes.map((g,i) => '<p style="color:#333;margin:5px 0;"><strong>' + (i+1) + '.</strong> ' + g + '</p>').join('') + '</div>').join('') + '</div>';
            const el = document.createElement('div'); el.innerHTML = html;
            html2pdf().from(el).save('Journal_Gratitude_' + state.username + '_' + new Date().toISOString().split('T')[0] + '.pdf');
        }
        
        function exportJSON() {
            const d = { username: state.username, exportDate: new Date().toISOString(), history: state.history, streak: state.streak };
            const b = new Blob([JSON.stringify(d, null, 2)], {type:'application/json'});
            const u = URL.createObjectURL(b), a = document.createElement('a');
            a.href = u; a.download = 'journal_gratitude_' + state.username + '_' + new Date().toISOString().split('T')[0] + '.json';
            a.click();
        }
        
        function renderCharts() {
            setTimeout(() => {
                const tc = document.getElementById('trendChart');
                if (tc) { const d = get7Days(); new Chart(tc, { type:'bar', data:{ labels:d.map(x=>x.date), datasets:[{label:'Gratitudes', data:d.map(x=>x.count), backgroundColor:'#10B981', borderRadius:6}]}, options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{color:'#a1a1aa'},grid:{color:'#27272a'}}, x:{ticks:{color:'#a1a1aa'},grid:{display:false}}}}}); }
            }, 100);
        }
        
        function renderCal() {
            const y = state.calendarDate.getFullYear(), m = state.calendarDate.getMonth();
            const first = new Date(y, m, 1), last = new Date(y, m + 1, 0);
            const days = last.getDate(), start = first.getDay();
            const ebd = {}; state.history.forEach(e => ebd[e.date] = e.gratitudes.length);
            let h = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
            h += '<div class="flex items-center justify-between mb-4">';
            h += '<button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()-1);render();" class="text-blue-400"><i class="bi bi-chevron-left"></i></button>';
            h += '<h3 class="font-bold">' + first.toLocaleDateString('fr-FR', {month:'long',year:'numeric'}) + '</h3>';
            h += '<button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()+1);render();" class="text-blue-400"><i class="bi bi-chevron-right"></i></button>';
            h += '</div><div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 text-zinc-500"><div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div></div>';
            h += '<div class="grid grid-cols-7 gap-1">';
            for (let i = 0; i < start; i++) h += '<div></div>';
            for (let d = 1; d <= days; d++) {
                const dt = new Date(y, m, d), ds = dt.toLocaleDateString('fr-FR');
                const count = ebd[ds] || 0;
                let bg = '#18181b', txt = '#a1a1aa';
                if (count > 0) { bg = count >= 3 ? '#10B981' : count === 2 ? '#F59E0B' : '#3B82F6'; txt = '#fff'; }
                h += '<div class="calendar-day" style="background-color:' + bg + ';color:' + txt + ';" title="' + count + ' gratitude(s)">' + d + '</div>';
            }
            h += '</div><div class="mt-3 text-xs text-zinc-500 flex gap-3"><div><span class="inline-block w-3 h-3 rounded" style="background:#3B82F6"></span> 1</div><div><span class="inline-block w-3 h-3 rounded" style="background:#F59E0B"></span> 2</div><div><span class="inline-block w-3 h-3 rounded" style="background:#10B981"></span> 3</div></div></div>';
            return h;
        }
        
        function updateChar(n) { const t = document.getElementById('g' + n), c = document.getElementById('c' + n); if (t && c) { c.textContent = t.value.length + '/200'; c.style.color = t.value.length > 180 ? '#EF4444' : '#a1a1aa'; }}
        function updateEditChar(n) { const t = document.getElementById('edit' + n), c = document.getElementById('ec' + n); if (t && c) { c.textContent = t.value.length + '/200'; c.style.color = t.value.length > 180 ? '#EF4444' : '#a1a1aa'; }}
        
        function render() {
            const app = document.getElementById('app');
            let h = '<div class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-10"><div class="max-w-2xl mx-auto px-4 py-4"><h1 class="text-xl font-bold"><i class="bi bi-heart-fill mr-2" style="color:#10B981;"></i>Journal de Gratitude</h1></div></div>';
            h += '<div class="bg-zinc-900 border-b border-zinc-800"><div class="max-w-2xl mx-auto px-4"><div class="flex gap-1 justify-around">';
            h += '<button onclick="state.view=\'capture\';render();" class="py-4 px-3 transition-all ' + (state.view==='capture'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-pencil-square" style="font-size:24px;"></i></button>';
            h += '<button onclick="state.view=\'stats\';render();" class="py-4 px-3 transition-all ' + (state.view==='stats'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-bar-chart-fill" style="font-size:24px;"></i></button>';
            h += '<button onclick="state.view=\'journal\';render();" class="py-4 px-3 transition-all ' + (state.view==='journal'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-journal-text" style="font-size:24px;"></i></button>';
            h += '<button onclick="state.view=\'calendar\';render();" class="py-4 px-3 transition-all ' + (state.view==='calendar'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-calendar-month" style="font-size:24px;"></i></button>';
            h += '<button onclick="state.view=\'goals\';render();" class="py-4 px-3 transition-all ' + (state.view==='goals'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><i class="bi bi-trophy" style="font-size:24px;"></i></button>';
            h += '</div></div></div><div class="max-w-2xl mx-auto px-4 py-6">';
            
            if (state.showSuccess) h += '<div class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50"><i class="bi bi-check-circle-fill text-xl mr-2"></i>Gratitude enregistrée !</div>';
            if (state.deleteConfirm !== null) h += '<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 max-w-sm mx-4"><p class="text-white mb-4">Supprimer cette entrée ?</p><div class="flex gap-2"><button onclick="cancelDelete()" class="flex-1 py-2 bg-zinc-800 text-white rounded-lg">Annuler</button><button onclick="confirmDelete()" class="flex-1 py-2 bg-red-600 text-white rounded-lg">Supprimer</button></div></div></div>';
            
            if (state.editIndex !== null) {
                const e = state.history[state.editIndex], dt = new Date(e.timestamp), dv = dt.toISOString().split('T')[0];
                h += '<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 max-w-md w-full max-h-[80vh] overflow-y-auto"><h3 class="text-white font-bold mb-4">Modifier</h3>';
                h += '<div class="mb-3"><label class="block text-sm font-bold mb-2 text-zinc-300">Date</label><input type="date" id="editDateInput" value="' + dv + '" class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white"></div>';
                for (let i = 0; i < 3; i++) {
                    const val = e.gratitudes[i] || '';
                    h += '<label class="block text-sm font-bold mb-2 text-zinc-300">Gratitude ' + (i+1) + '</label><textarea id="edit' + (i+1) + '" class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-lg text-white mb-1" rows="2" maxlength="200" oninput="updateEditChar(' + (i+1) + ')">' + val + '</textarea>';
                    h += '<div class="text-right text-sm text-zinc-500 mb-3" id="ec' + (i+1) + '">' + val.length + '/200</div>';
                }
                h += '<div class="flex gap-2 mt-4"><button onclick="cancelEdit()" class="flex-1 py-2 bg-zinc-800 text-white rounded-lg">Annuler</button><button onclick="saveEdit()" class="flex-1 py-2 bg-blue-600 text-white rounded-lg">Enregistrer</button></div></div></div>';
            }
            
            if (state.view === 'capture') {
                const now = new Date(), dd = now.toISOString().split('T')[0];
                h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                h += '<h2 class="font-bold mb-4 text-lg">Pour quoi êtes-vous reconnaissant(e) aujourd\'hui ?</h2>';
                h += '<div class="mb-3"><label class="block text-sm font-bold mb-2 text-zinc-300">Date</label><input type="date" id="dateInput" value="' + (state.customDate||dd) + '" class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white" onchange="state.customDate=this.value"></div>';
                for (let i = 1; i <= 3; i++) {
                    h += '<label class="block font-bold mb-2 text-green-400">Gratitude ' + i + '</label>';
                    h += '<textarea id="g' + i + '" placeholder="Ex: Un moment avec un ami, un beau coucher de soleil..." class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-lg text-white placeholder-zinc-600 mb-1" rows="2" maxlength="200" oninput="updateChar(' + i + ')"></textarea>';
                    h += '<div class="text-right text-sm text-zinc-500 mb-4" id="c' + i + '">0/200</div>';
                }
                h += '<button onclick="saveEntry();" class="w-full py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 flex items-center justify-center gap-2"><i class="bi bi-heart-fill"></i> Enregistrer</button>';
                h += '</div><div class="bg-green-950 border border-green-900 rounded-lg p-4 text-sm text-green-200"><i class="bi bi-lightbulb mr-2"></i><strong>Conseil :</strong> Pratiquer la gratitude quotidiennement améliore le bien-être.</div></div>';
            } else if (state.view === 'stats') {
                if (state.history.length === 0) {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 text-center text-zinc-500"><i class="bi bi-graph-up text-4xl mb-3 opacity-30"></i><p>Aucune donnée</p></div>';
                } else {
                    const total = state.history.reduce((sum, e) => sum + e.gratitudes.length, 0);
                    const kw = getKeywords(), monthly = getMonthlyStats();
                    h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-graph-up mr-2"></i>Résumé</h3><div class="grid grid-cols-2 gap-3">';
                    h += '<div class="text-center p-3 bg-zinc-950 rounded-lg border border-zinc-800"><div class="text-2xl font-bold">' + state.history.length + '</div><div class="text-xs text-zinc-500">Entrées</div></div>';
                    h += '<div class="text-center p-3 bg-green-950 rounded-lg border border-green-900"><div class="text-2xl font-bold text-green-400">' + total + '</div><div class="text-xs text-zinc-500">Gratitudes</div></div></div></div>';
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-bar-chart mr-2"></i>7 derniers jours</h3><div style="height:200px;"><canvas id="trendChart"></canvas></div></div>';
                    if (kw.length > 0) {
                        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-chat-quote mr-2"></i>Thèmes fréquents</h3><div class="flex flex-wrap gap-2">';
                        kw.forEach(([w,c]) => h += '<div class="px-3 py-1 bg-zinc-950 border border-zinc-800 rounded-full text-sm"><span class="text-zinc-300">' + w + '</span><span class="text-zinc-600 ml-1">×' + c + '</span></div>');
                        h += '</div></div>';
                    }
                    if (monthly.length > 0) {
                        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-calendar-check mr-2"></i>Par mois</h3><div class="space-y-2">';
                        monthly.forEach(([m,c]) => h += '<div class="flex items-center justify-between p-2 bg-zinc-950 rounded border border-zinc-800"><span class="text-sm text-zinc-300">' + m + '</span><span class="text-sm text-zinc-500">×' + c + '</span></div>');
                        h += '</div></div>';
                    }
                    h += '<div class="flex gap-2"><button onclick="exportPDF()" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-lg flex items-center justify-center gap-2"><i class="bi bi-file-pdf"></i> PDF</button>';
                    h += '<button onclick="exportJSON()" class="flex-1 py-3 bg-zinc-700 text-white font-bold rounded-lg flex items-center justify-center gap-2"><i class="bi bi-file-code"></i> JSON</button></div></div>';
                    renderCharts();
                }
            } else if (state.view === 'journal') {
                h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                h += '<input type="text" placeholder="Rechercher..." class="w-full p-2 bg-zinc-950 border border-zinc-800 rounded-lg text-white" value="' + state.searchQuery + '" oninput="state.searchQuery=this.value;render()"></div>';
                const filt = getFiltered();
                if (filt.length === 0) {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 text-center text-zinc-500"><i class="bi bi-book-open text-4xl mb-3 opacity-30"></i><p>' + (state.searchQuery?'Aucun résultat':'Votre journal est vide') + '</p></div>';
                } else {
                    h += '<div class="space-y-3">';
                    filt.forEach(e => {
                        const idx = state.history.indexOf(e);
                        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 relative" style="border-left:4px solid #10B981">';
                        h += '<div class="absolute top-3 right-3 flex gap-2"><button onclick="editEntry(' + idx + ')" class="text-zinc-600 hover:text-blue-400 hover:bg-blue-950 rounded-full p-2"><i class="bi bi-pencil"></i></button>';
                        h += '<button onclick="deleteEntry(' + idx + ')" class="text-zinc-600 hover:text-red-400 hover:bg-red-950 rounded-full p-2"><i class="bi bi-trash"></i></button></div>';
                        h += '<div class="flex items-center justify-between mb-3 pr-20"><div class="font-bold text-green-400">' + e.date + '</div><div class="text-xs text-zinc-500">' + e.gratitudes.length + ' gratitude' + (e.gratitudes.length>1?'s':'') + '</div></div>';
                        h += '<div class="space-y-2">';
                        e.gratitudes.forEach((g, i) => h += '<div class="flex gap-2 items-start"><span class="text-green-500 font-bold">' + (i+1) + '.</span><p class="text-sm text-zinc-300 flex-1">' + g + '</p></div>');
                        h += '</div></div>';
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
                h += '<div class="text-6xl font-bold text-green-500 mb-2">' + state.streak + '</div><div class="text-zinc-400">jour' + (state.streak>1?'s':'') + ' consécutif' + (state.streak>1?'s':'') + '</div></div></div>';
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><i class="bi bi-trophy mr-2"></i>Objectif hebdomadaire</h3><div class="mb-3">';
                h += '<div class="flex items-center justify-between mb-2"><span class="text-zinc-400">Cette semaine</span><span class="font-bold">' + wk + ' / ' + state.goalDays + '</span></div>';
                h += '<div class="w-full bg-zinc-800 rounded-full h-4 overflow-hidden"><div class="bg-green-600 h-full transition-all" style="width:' + prog + '%"></div></div></div>';
                h += '<div class="flex gap-2"><button onclick="state.goalDays=Math.max(1,state.goalDays-1);localStorage.setItem(\'gratitudeGoalDays\',state.goalDays);render();" class="px-4 py-2 bg-zinc-800 text-white rounded-lg"><i class="bi bi-dash"></i></button>';
                h += '<div class="flex-1 text-center py-2 bg-zinc-950 rounded-lg">Objectif: ' + state.goalDays + ' jour' + (state.goalDays>1?'s':'') + '</div>';
                h += '<button onclick="state.goalDays=Math.min(7,state.goalDays+1);localStorage.setItem(\'gratitudeGoalDays\',state.goalDays);render();" class="px-4 py-2 bg-zinc-800 text-white rounded-lg"><i class="bi bi-plus"></i></button></div></div>';
                h += '<div class="bg-green-950 border border-green-900 rounded-lg p-4 text-sm text-green-200"><i class="bi bi-star mr-2"></i><strong>Astuce :</strong> Visez 3 gratitudes par jour pour maximiser les bénéfices !</div></div>';
            }
            
            h += '</div><div style="margin-top:40px;padding-bottom:40px;text-align:center;"><a href="/v1/outils/?v=1.0&msg=patched" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-lg" style="text-decoration:none;"><i class="bi bi-arrow-left"></i> Retour</a>';
            h += '<p style="margin-top:30px;color:#555;font-size:14px;line-height:1.8;"><svg style="width:14px;height:14px;margin-right:5px;display:inline-block;vertical-align:middle;" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span style="color:#ddd;cursor:pointer;" onclick="openUsernamePopup()">' + state.username + '</span><br>v<span id="footerVersion"></span>.' + MODULE_REVISION + ' • Accès anticipé<br><a onclick="openPopup(\'aboutPopup\')" style="color:#0d47a1;cursor:pointer;">À propos</a> • <a onclick="openPopup(\'privacyPopup\')" style="color:#0d47a1;cursor:pointer;">Confidentialité</a><br/><span style="color:#161616;">©2025 SanteMentale.org</span></p></div></div>';
            
            app.innerHTML = h;
            document.getElementById('footerVersion').textContent = localStorage.getItem('client_version') || '1.web';
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            try {
                state.history = JSON.parse(localStorage.getItem('gratitudeHistory') || '[]');
                state.username = localStorage.getItem('username') || 'visiteur';
                state.goalDays = parseInt(localStorage.getItem('gratitudeGoalDays')) || 5;
                
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
                document.getElementById('appVersion').textContent = 'App : ' + cv + '.' + MODULE_REVISION;
                
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
