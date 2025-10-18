#!/bin/bash

# Script de modification pour routine-quotidienne v0.11 - Correctifs finaux
# Usage: ./patch_script_v2.sh

SCRIPT_FILE="outils/routine-quotidienne/script.js"
INDEX_FILE="outils/routine-quotidienne/index.php"

if [ ! -f "$SCRIPT_FILE" ]; then
    echo "Erreur: $SCRIPT_FILE n'existe pas"
    exit 1
fi

if [ ! -f "$INDEX_FILE" ]; then
    echo "Erreur: $INDEX_FILE n'existe pas"
    exit 1
fi

# Backup
cp "$SCRIPT_FILE" "${SCRIPT_FILE}.backup"
cp "$INDEX_FILE" "${INDEX_FILE}.backup"

echo "📝 Modification de script.js..."

# 1. Modifier drawShareContent pour utiliser le nom d'utilisateur et corriger le titre
cat > /tmp/new_drawShareContent.js << 'EOFFUNCTION'
function drawShareContent(ctx, canvas) {
    const username = localStorage.getItem('user_display_name') || localStorage.getItem('device_uuid')?.substring(0, 8) || 'Utilisateur';
    const prideQuotes = [
        'Voyez comment ' + username + ' a progressé !',
        'Regardez les accomplissements de ' + username + ' !',
        username + ' construit de meilleures habitudes !',
        'Admirez la progression de ' + username + ' !'
    ];
    const quote = prideQuotes[Math.floor(Math.random() * prideQuotes.length)];

    ctx.fillStyle = '#ffffff'; ctx.font = '36px sans-serif'; ctx.textAlign = 'center';
    ctx.fillText(quote, canvas.width / 2, 220);

    ctx.fillStyle = '#ffffff'; ctx.font = 'bold 80px sans-serif'; ctx.textAlign = 'center';
    ctx.fillText('Routines quotidiennes', canvas.width / 2, 320);

    ctx.font = 'bold 120px sans-serif'; ctx.fillStyle = '#fbbf24';
    ctx.fillText('⭐ Niveau ' + state.level, canvas.width / 2, 480);

    const xpInLevel = state.xp % XP_PER_LEVEL;
    ctx.font = '50px sans-serif'; ctx.fillStyle = '#ffffff';
    ctx.fillText(xpInLevel + ' / ' + XP_PER_LEVEL + ' XP', canvas.width / 2, 560);

    const barWidth = 800, barHeight = 60, barX = (canvas.width - barWidth) / 2, barY = 610;
    ctx.fillStyle = '#18181b'; ctx.fillRect(barX, barY, barWidth, barHeight);
    const fillWidth = (xpInLevel / XP_PER_LEVEL) * barWidth;
    const fillGradient = ctx.createLinearGradient(barX, 0, barX + barWidth, 0);
    fillGradient.addColorStop(0, '#3B82F6'); fillGradient.addColorStop(1, '#60A5FA');
    ctx.fillStyle = fillGradient; ctx.fillRect(barX, barY, fillWidth, barHeight);

    const statsY = 760; ctx.font = 'bold 60px sans-serif'; ctx.fillStyle = '#ffffff';
    ctx.fillText('🔥 ' + state.streak + ' jours', canvas.width / 2, statsY);
    ctx.font = '50px sans-serif'; ctx.fillText(state.totalTasksCompleted + ' tâches complétées', canvas.width / 2, statsY + 80);
    ctx.fillText('✨ ' + state.perfectDaysCount + ' journées parfaites', canvas.width / 2, statsY + 160);

    ctx.font = 'bold 50px sans-serif'; ctx.fillText('Mes meilleurs badges', canvas.width / 2, statsY + 280);
    const topBadges = state.unlockedBadges.slice(0, 3); let badgeY = statsY + 360;
    topBadges.forEach((badgeId, index) => {
        const badge = BADGES.find(b => b.id === badgeId);
        if (badge) {
            ctx.font = '80px sans-serif'; ctx.fillText(badge.icon, canvas.width / 2 - 300 + (index * 300), badgeY);
            ctx.font = '40px sans-serif'; ctx.fillText(badge.name, canvas.width / 2 - 300 + (index * 300), badgeY + 80);
        }
    });

    ctx.font = '40px sans-serif'; ctx.fillStyle = 'rgba(255,255,255,0.7)';
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
                showShareModal(shareUrl);
            } else {
                alert('Erreur lors de la création du partage');
            }
        } catch(e) {
            alert('Erreur: ' + e.message);
        }
    });
}
EOFFUNCTION

# Remplacer drawShareContent
awk '
/^function drawShareContent\(ctx, canvas\) \{$/ {
    while (getline line < "/tmp/new_drawShareContent.js") print line
    in_function = 1
    next
}
in_function && /^}$/ {
    in_function = 0
    next
}
!in_function { print }
' "$SCRIPT_FILE" > "${SCRIPT_FILE}.tmp" && mv "${SCRIPT_FILE}.tmp" "$SCRIPT_FILE"

# 2. Ajouter la fonction showShareModal après closePopup
sed -i '/^function closePopup(id)/a\
\
function showShareModal(url) {\
    const modal = document.getElementById('\''shareModal'\'');\
    document.getElementById('\''shareUrl'\'').value = url;\
    openPopup('\''shareModal'\'');\
}\
\
function copyShareUrl() {\
    const input = document.getElementById('\''shareUrl'\'');\
    input.select();\
    document.execCommand('\''copy'\'');\
    const btn = document.querySelector('\''#shareModal .copy-btn'\'');\
    btn.textContent = '\''✓ Copié !'\'';\
    setTimeout(() => btn.textContent = '\''Copier le lien'\'', 2000);\
}' "$SCRIPT_FILE"

echo "📝 Modification de index.php..."

# 3. Ajouter le popup shareModal après dayDetailPopup
sed -i '/<div id="dayDetailPopup" class="popup">/,/<\/div>$/a\
    \
    <!-- NOUVEAU v0.11 - Popup partage -->\
    <div id="shareModal" class="popup">\
        <div class="popup-content" style="text-align:center;">\
            <h3 style="font-weight:bold;margin-bottom:15px;">🎉 Partage créé !</h3>\
            <p style="margin-bottom:15px;">Partagez votre progression avec ce lien :</p>\
            <input type="text" id="shareUrl" readonly style="width:100%;padding:10px;border:1px solid #ccc;border-radius:5px;margin-bottom:15px;font-family:monospace;font-size:14px;">\
            <button class="close-btn copy-btn" onclick="copyShareUrl()" style="width:100%;background:#10B981;color:white;margin-bottom:10px;">Copier le lien</button>\
            <button class="close-btn" onclick="closePopup('\''shareModal'\'')" style="width:100%;">Fermer</button>\
        </div>\
    </div>' "$INDEX_FILE"

rm -f /tmp/new_drawShareContent.js

echo "✅ Modifications appliquées avec succès!"
echo "✓ Backup créé: ${SCRIPT_FILE}.backup et ${INDEX_FILE}.backup"
echo ""
echo "Changements appliqués:"
echo "  - Fix z-index du pourcentage de niveau (déjà présent)"
echo "  - Logo ajouté à l'image de partage (déjà présent)"
echo "  - Nom d'utilisateur depuis localStorage"
echo "  - Titre changé en 'Routines quotidiennes'"
echo "  - Modal de partage avec bouton copier"
