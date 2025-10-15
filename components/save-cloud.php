<!-- components/save-cloud.php - NUCLEAR : JS SUIVI SCROLL -->
<span id="saveStatus" class="material-icons" style="position: fixed; top: 20px; right: 20px; z-index: 1001; font-size: 24px; color: #f44336;">cloud_off</span>
<script>
async function saveLocalStorage() {
    const saveStatus = document.getElementById('saveStatus');
    if (!saveStatus) return;
    
    try {
        console.log('Début sauvegarde localStorage...');
        const localStorageData = {};
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            localStorageData[key] = localStorage.getItem(key);
        }
        const formData = new FormData();
        formData.append('device_uuid', localStorage.getItem('device_uuid') || 'unknown');
        formData.append('data', JSON.stringify(localStorageData));
        
        const response = await fetch(window.location.href, { method: 'POST', body: formData });
        const data = await response.json();
        
        if (data.status === 'success') {
            saveStatus.textContent = 'cloud_done';
            saveStatus.style.color = '#81C784';
            console.log('✅ SAUVEGARDE RÉUSSIE - INSTANT !');
        } else {
            saveStatus.style.color = '#f44336';
        }
    } catch (error) {
        console.error('Erreur:', error.message);
        saveStatus.style.color = '#f44336';
    }
}

// ✅ NUCLEAR : SUIVI SCROLL (100% FIABLE)
function followScroll() {
    const saveStatus = document.getElementById('saveStatus');
    if (!saveStatus) return;
    
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    saveStatus.style.top = (20 + scrollTop) + 'px';
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(saveLocalStorage, 100);
    
    // ✅ SUIVI SCROLL ACTIVÉ
    window.addEventListener('scroll', followScroll);
    followScroll(); // Position initiale
});
</script>
