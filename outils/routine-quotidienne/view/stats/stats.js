// Vue STATS - Module autonome
return (function() {
    'use strict';

    let statsChart = null;

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

    function renderChart() {
        setTimeout(() => {
            const canvas = document.getElementById('statsChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            if (statsChart) statsChart.destroy();
            
            const labels = [], data = [];
            const today = new Date();
            for (let i = 6; i >= 0; i--) {
                const d = new Date(today);
                d.setDate(today.getDate() - i);
                labels.push(d.toLocaleDateString('fr-FR', { weekday: 'short' }));
                const dateStr = d.toLocaleDateString('fr-FR');
                const dayData = state.history.find(h => h.date === dateStr);
                if (dayData) {
                    let total = 0, completed = 0;
                    state.routines.forEach(r => r.tasks.forEach(t => {
                        total++;
                        if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) completed++;
                    }));
                    data.push(total > 0 ? Math.round((completed / total) * 100) : 0);
                } else data.push(0);
            }
            
            statsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Progression (%)',
                        data: data,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            max: 100, 
                            ticks: { color: '#71717a' }, 
                            grid: { color: '#27272a' } 
                        },
                        x: { 
                            ticks: { color: '#71717a' }, 
                            grid: { color: '#27272a' } 
                        }
                    }
                }
            });
        }, 100);
    }

    function generateShareImage() {
        const canvas = document.getElementById('shareCanvas');
        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, '#1e3a8a');
        gradient.addColorStop(1, '#1e40af');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        const username = 'Utilisateur';
        const prideQuotes = [
            'Voyez comment ' + username + ' a progressé !',
            'Regardez les accomplissements de ' + username + ' !',
            username + ' construit de meilleures habitudes !',
            'Admirez la progression de ' + username + ' !'
        ];
        const quote = prideQuotes[Math.floor(Math.random() * prideQuotes.length)];
        
        ctx.fillStyle = '#ffffff';
        ctx.font = '36px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(quote, canvas.width / 2, 220);
        
        ctx.font = 'bold 80px sans-serif';
        ctx.fillText('Ma Progression Routines', canvas.width / 2, 320);
        
        ctx.font = 'bold 120px sans-serif';
        ctx.fillStyle = '#fbbf24';
        ctx.fillText('⭐ Niveau ' + state.level, canvas.width / 2, 480);
        
        const xpInLevel = state.xp % constants.XP_PER_LEVEL;
        ctx.font = '50px sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.fillText(xpInLevel + ' / ' + constants.XP_PER_LEVEL + ' XP', canvas.width / 2, 560);
        
        const barWidth = 800, barHeight = 60, barX = (canvas.width - barWidth) / 2, barY = 610;
        ctx.fillStyle = '#18181b';
        ctx.fillRect(barX, barY, barWidth, barHeight);
        
        const fillWidth = (xpInLevel / constants.XP_PER_LEVEL) * barWidth;
        const fillGradient = ctx.createLinearGradient(barX, 0, barX + barWidth, 0);
        fillGradient.addColorStop(0, '#3B82F6');
        fillGradient.addColorStop(1, '#60A5FA');
        ctx.fillStyle = fillGradient;
        ctx.fillRect(barX, barY, fillWidth, barHeight);
        
        const statsY = 760;
        ctx.font = 'bold 60px sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.fillText('🔥 ' + state.streak + ' jours', canvas.width / 2, statsY);
        
        ctx.font = '50px sans-serif';
        ctx.fillText(state.totalTasksCompleted + ' tâches complétées', canvas.width / 2, statsY + 80);
        ctx.fillText('✨ ' + state.perfectDaysCount + ' journées parfaites', canvas.width / 2, statsY + 160);
        
        ctx.font = 'bold 50px sans-serif';
        ctx.fillText('Mes meilleurs badges', canvas.width / 2, statsY + 280);
        
        const topBadges = state.unlockedBadges.slice(0, 3);
        let badgeY = statsY + 360;
        topBadges.forEach((badgeId, index) => {
            const badge = constants.BADGES.find(b => b.id === badgeId);
            if (badge) {
                ctx.font = '80px sans-serif';
                ctx.fillText(badge.icon, canvas.width / 2 - 300 + (index * 300), badgeY);
                ctx.font = '40px sans-serif';
                ctx.fillText(badge.name, canvas.width / 2 - 300 + (index * 300), badgeY + 80);
            }
        });
        
        ctx.font = '40px sans-serif';
        ctx.fillStyle = 'rgba(255,255,255,0.7)';
        ctx.fillText('SanteMentale.org', canvas.width / 2, canvas.height - 100);
        
        canvas.toBlob(async blob => {
            const formData = new FormData();
            formData.append('image', blob, 'share.png');
            formData.append('level', state.level);
            formData.append('xp', state.xp);
            formData.append('streak', state.streak);
            formData.append('tasks', state.totalTasksCompleted);
            try {
                const response = await fetch('/v1/share/upload.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    const shareUrl = 'https://app.santementale.org/share/' + result.uid;
                    if (navigator.share) {
                        navigator.share({ title: 'Ma progression Routines', url: shareUrl });
                    } else {
                        prompt('Lien de partage:', shareUrl);
                    }
                } else {
                    alert('Erreur lors de la création du partage');
                }
            } catch (e) {
                alert('Erreur: ' + e.message);
            }
        });
    }

    function renderSummary() {
        calcStreak();
        
        let html = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
        html += '<h3 class="font-bold mb-3"><span class="material-symbols-outlined mr-2" style="vertical-align:middle;">trending_up</span>Résumé</h3>';
        html += '<div class="grid grid-cols-2 gap-3">';
        
        html += '<div class="text-center p-3 bg-zinc-950 rounded-lg">';
        html += '<div class="text-4xl font-bold">' + state.history.length + '</div>';
        html += '<div class="text-xs text-zinc-500">Jours enregistrés</div></div>';
        
        html += '<div class="text-center p-3 bg-blue-950 rounded-lg border border-blue-900">';
        html += '<div class="text-4xl font-bold text-blue-400">' + state.streak + '</div>';
        html += '<div class="text-xs text-zinc-500">Série actuelle</div></div>';
        
        html += '<div class="text-center p-3 bg-green-950 rounded-lg border border-green-900">';
        html += '<div class="text-4xl font-bold text-green-400">' + state.totalTasksCompleted + '</div>';
        html += '<div class="text-xs text-zinc-500">Tâches complétées</div></div>';
        
        html += '<div class="text-center p-3 bg-yellow-950 rounded-lg border border-yellow-900">';
        html += '<div class="text-4xl font-bold text-yellow-400">' + state.perfectDaysCount + '</div>';
        html += '<div class="text-xs text-zinc-500">Journées parfaites</div></div>';
        
        html += '</div></div>';
        
        document.getElementById('summaryCards').innerHTML = html;
    }

    function renderTotalTime() {
        let totalMinutes = 0;
        state.history.forEach(day => {
            state.routines.forEach(r => {
                r.tasks.forEach(t => {
                    if (day.routines[r.id] && day.routines[r.id][t.id]) {
                        totalMinutes += t.duration;
                    }
                });
            });
        });
        
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        
        let html = '<div class="bg-gradient-to-br from-purple-950 to-purple-900 border border-purple-800 rounded-lg p-4 text-center">';
        html += '<span class="material-symbols-outlined text-purple-300" style="font-size:48px;">timer</span>';
        html += '<div class="text-3xl font-bold text-purple-200 mt-2">' + hours + 'h ' + minutes + 'min</div>';
        html += '<div class="text-sm text-purple-300 mt-1">Temps total consacré à vos routines</div>';
        html += '</div>';
        
        document.getElementById('totalTimeCard').innerHTML = html;
    }

    // Export du module
    return {
        init: function() {
            renderChart();
            renderSummary();
            renderTotalTime();
            
            const shareBtn = document.getElementById('shareBtn');
            if (shareBtn) {
                shareBtn.addEventListener('click', generateShareImage);
            }
        },
        cleanup: function() {
            if (statsChart) {
                statsChart.destroy();
                statsChart = null;
            }
        }
    };

})();
