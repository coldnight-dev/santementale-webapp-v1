<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Journal de gratitude</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <!-- Manifest PWA (désactivé pour éviter le popup installation) -->
    <link rel="manifest" href="/v1/manifest.json">
    <!-- Couleur de la barre sur mobile -->
    <meta name="theme-color" content="#2c3e50">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- html2pdf.js CDN pour l'export PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        /* Réinitialisation et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
        }
        /* Conteneur principal */
        .container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: 100%;
            text-align: center;
            padding: 5%;
            width: 90%;
            margin: 0 auto;
        }
        /* Logo */
        .logo {
            width: 15vw;
            max-width: 75px;
            height: auto;
            margin-bottom: 20px;
        }
        /* Titres et textes */
        h1 {
            font-size: clamp(18px, 5.5vw, 24px);
            margin-bottom: 15px;
        }
        p {
            font-size: 4vw;
            max-width: 90%;
            line-height: 1.5;
            margin-bottom: 25px;
        }
        /* Zone d'entrée */
        .gratitude-input {
            width: 100%;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0d47a1;
            margin-bottom: 20px;
        }
        .gratitude-input textarea {
            width: 100%;
            min-height: 100px;
            background: transparent;
            border: none;
            color: #ccc;
            font-size: 16px;
            resize: vertical;
        }
        .gratitude-input textarea:focus {
            outline: none;
        }
        .char-count {
            text-align: right;
            color: #888;
            font-size: 14px;
        }
        /* Boutons */
        .submit-btn, .reset-btn, .export-btn {
            padding: 10px 20px;
            background-color: #0d47a1;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* Pas de soulignement */
        }
        .submit-btn {
            display: block; /* Seul sur sa ligne */
            width: fit-content; /* Ajuste la largeur au contenu */
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 10px; /* Espacement avant la ligne suivante */
        }
        .reset-btn {
            background-color: #c0392b;
        }
        .export-btn {
            background-color: #7f8c8d; /* Gris à la place du vert */
        }
        .button-container {
            display: flex;
            flex-direction: column; /* Empile les lignes */
            align-items: center; /* Centre horizontalement */
            gap: 10px; /* Espacement entre les lignes */
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px; /* Espacement entre Vider et Exporter */
        }
        /* Liste des entrées */
        .entries-list {
            width: 100%;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0d47a1;
            margin-bottom: 20px;
        }
        .entry-item {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        .entry-item:last-child {
            border-bottom: none;
        }
        .entry-date {
            font-size: 14px;
            color: #888;
            margin-bottom: 5px;
        }
        .entry-text {
            font-size: 16px;
            color: #ccc;
        }
        /* Notification temporaire */
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #c0392b; /* Rouge */
            color: #fff;
            padding: 15px 25px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        /* Style pour le nom d'utilisateur */
        .username {
            color: #ddd;
            cursor: pointer;
            margin-left: 5px;
        }
        .username:hover {
            text-decoration: underline;
        }
        .pencil-icon {
            width: 14px;
            height: 14px;
            margin-right: 5px;
            vertical-align: middle;
        }
        /* Popup (À propos, Confidentialité, Username) */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .popup-content {
            background: white;
            color: black;
            width: 80%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .popup-content p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .close-btn {
            padding: 10px 20px;
            background: #ccc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        /* Style pour le lien À propos et Confidentialité */
        .about-link {
            color: #0d47a1;
            text-decoration: none;
            cursor: pointer;
        }
        .about-link:hover {
            text-decoration: underline;
        }
        /* Style pour le formulaire dans la popup */
        .username-input {
            padding: 8px;
            width: 80%;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        /* Gestion du mode paysage */
        @media (orientation: landscape) {
            body {
                transform: rotate(90deg);
                transform-origin: left top;
                width: 100vh;
                height: 100vw;
                overflow-x: hidden;
                position: absolute;
                top: 100%;
                left: 0;
            }
            .container {
                padding: 3%;
            }
        }
        /* Ajustement pour petits écrans */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Popup À propos -->
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <p>API : 0.dev</p>
            <p>App : 1.0</p>
            <button class="close-btn" onclick="document.getElementById('aboutPopup').style.display = 'none';">Fermer</button>
        </div>
    </div>
    <!-- Popup Confidentialité -->
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n’est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('privacyPopup').style.display = 'none';">Fermer</button>
        </div>
    </div>
    <!-- Popup pour modifier le nom d'utilisateur -->
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez le nom à afficher sur les rapports PDF que vous générez.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('usernamePopup').style.display = 'none';">Annuler</button>
        </div>
    </div>
    <!-- Notification -->
    <div id="notification" class="notification"></div>
    <!-- Contenu principal -->
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <p>Enregistrez une pensée ou une action de gratitude chaque jour pour cultiver la positivité.</p>
        <!-- Zone d'entrée -->
        <div class="gratitude-input">
            <textarea id="gratitudeEntry" placeholder="Ex. : Je suis reconnaissant(e) pour un appel avec un ami aujourd'hui..." maxlength="200"></textarea>
            <div class="char-count">0/200</div>
        </div>
        <div class="button-container">
            <button class="submit-btn" onclick="saveGratitude()"><i class="fas fa-save"></i> Enregistrer</button>
            <div class="button-group">
                <button class="reset-btn" onclick="clearAll()"><i class="fas fa-trash"></i> Vider</button>
                <button class="export-btn" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Exporter en PDF</button>
            </div>
        </div>
        <!-- Liste des entrées -->
        <div id="entriesList" class="entries-list">
            <!-- Entrées dynamiques ici -->
        </div>
        <!-- Bouton Retour -->
        <a href="index.php" class="submit-btn" style="margin-top: 20px;"><i class="fas fa-arrow-left"></i> Retour</a>
        <p style="margin-top: auto; color: #333;">
            v<?php echo $_COOKIE['client_version']; ?> • Accès anticipé<br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').style.display = 'flex';">À propos</a> &bull; <a class="about-link" onclick="document.getElementById('privacyPopup').style.display = 'flex';">Confidentialité</a><br/>
            <span style="color: #161616;">&copy;2025 SanteMentale.org</span><br>
        </p>
    </div>
    <script>
        // Gestion du nom d'utilisateur
        document.addEventListener('DOMContentLoaded', () => {
            const usernameKey = 'username';
            let username = localStorage.getItem(usernameKey);
            if (!username) {
                localStorage.setItem(usernameKey, 'visiteur');
                username = 'visiteur';
            }
            const welcomeMessage = document.getElementById('welcomeMessage');
            welcomeMessage.innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${username},</span>`;

            // Charger les entrées existantes
            loadEntries();

            // Mettre à jour le compteur de caractères
            const textarea = document.getElementById('gratitudeEntry');
            const charCount = document.querySelector('.char-count');
            textarea.addEventListener('input', () => {
                charCount.textContent = `${textarea.value.length}/200`;
            });
        });

        // Fonction pour enregistrer le nom d'utilisateur
        window.saveUsername = function() {
            const input = document.getElementById('usernameInput');
            const newUsername = input.value.trim() || 'visiteur';
            localStorage.setItem('username', newUsername);
            document.getElementById('welcomeMessage').innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${newUsername},</span>`;
            document.getElementById('usernamePopup').style.display = 'none';
        };

        // Sauvegarder une entrée de gratitude
        function saveGratitude() {
            const textarea = document.getElementById('gratitudeEntry');
            const entry = textarea.value.trim();
            if (entry) {
                const today = new Date().toISOString().split('T')[0];
                let entries = JSON.parse(localStorage.getItem('gratitude_entries') || '[]');
                const existingEntryIndex = entries.findIndex(e => e.date === today);
                if (existingEntryIndex !== -1) {
                    entries[existingEntryIndex].entry = entry;
                } else {
                    entries.push({ date: today, entry });
                }
                localStorage.setItem('gratitude_entries', JSON.stringify(entries));
                textarea.value = '';
                document.querySelector('.char-count').textContent = '0/200';
                loadEntries();
            } else {
                alert('Veuillez entrer une pensée ou action de gratitude.');
            }
        }

        // Charger et afficher les entrées
        function loadEntries() {
            const entriesList = document.getElementById('entriesList');
            const entries = JSON.parse(localStorage.getItem('gratitude_entries') || '[]');
            entriesList.innerHTML = '';
            if (entries.length > 0) {
                entries.forEach(e => {
                    const entryDiv = document.createElement('div');
                    entryDiv.className = 'entry-item';
                    entryDiv.innerHTML = `
                        <div class="entry-date">${new Date(e.date).toLocaleDateString('fr-FR')}</div>
                        <div class="entry-text">${e.entry}</div>
                    `;
                    entriesList.appendChild(entryDiv);
                });
            } else {
                entriesList.innerHTML = '<p>Aucune entrée pour l\'instant.</p>';
            }
        }

        // Effacer toutes les entrées
        function clearAll() {
            if (confirm('Êtes-vous sûr de vouloir effacer toutes les entrées ? Cette action est irréversible.')) {
                localStorage.removeItem('gratitude_entries');
                loadEntries();
            }
        }

        // Exporter en PDF
        function exportToPDF() {
            const username = localStorage.getItem('username') || 'visiteur';
            const date = new Date().toISOString().split('T')[0];
            const fileName = `Journal_de_Gratitude_${username}_${date}.pdf`;
            if (confirm(`Voulez-vous exporter votre journal de gratitude en PDF sous le nom "${fileName}" ?`)) {
                const entries = JSON.parse(localStorage.getItem('gratitude_entries') || '[]');
                const content = `
                    <h1 style="text-align: center; color: #0d47a1;">Journal de Gratitude - ${username}</h1>
                    <h2 style="text-align: center; color: #333;">Rapport généré le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}</h2>
                    ${entries.map(e => `
                        <div style="margin: 20px 0; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            <h3 style="color: #888;">${new Date(e.date).toLocaleDateString('fr-FR')}</h3>
                            <p style="color: #000;">${e.entry}</p>
                        </div>
                    `).join('')}
                `;
                const element = document.createElement('div');
                element.innerHTML = content;
                html2pdf().from(element).save(fileName);

                // Afficher la notification
                const notification = document.getElementById('notification');
                notification.textContent = `PDF "${fileName}" téléchargé ! Vérifiez votre répertoire de téléchargements.`;
                notification.style.display = 'block';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000); // Disparaît après 5 secondes
            }
        }
    </script>
</body>
</html>
