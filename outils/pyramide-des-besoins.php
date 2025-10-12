<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Pyramide des besoins</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <!-- iOS Safari -->
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            padding: 5%; /* Padding relatif en pourcentage */
            width: 90%; /* Largeur relative */
            margin: 0 auto; /* Centrage horizontal */
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
        /* Pyramide des besoins */
        .pyramid {
            width: 100%; /* Pleine largeur du conteneur */
            display: flex;
            flex-direction: column;
            margin-bottom: 30px;
        }
        .level {
            padding: 10px;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; /* Pour positionner l'icône */
        }
        .level.unanswered {
            background-color: #1a1a1a; /* Noir pour non répondu */
            border: 1px solid #ccc; /* Liseré gris */
            border-left: 4px solid #0d47a1; /* Bordure bleue */
        }
        .level.dark {
            background-color: #f1c40f; /* Jaune pour échoué */
            color: #000; /* Texte noir pour lisibilité */
            border-left: 4px solid #c0392b; /* Bordure rouge */
        }
        .level.light {
            background-color: #0d47a1; /* Bleu pour comblé */
            border-left: 4px solid #2ecc71; /* Bordure verte */
        }
        .level-1 { width: 100%; }
        .level-2 { width: 80%; align-self: center; }
        .level-3 { width: 60%; align-self: center; }
        .level-4 { width: 40%; align-self: center; }
        .level-5 {
            width: 25%; /* Légèrement élargi */
            align-self: center;
            font-size: clamp(12px, 3.5vw, 14px); /* Police réduite */
            white-space: normal; /* Retour à la ligne */
        }
        .level-text {
            flex: 1; /* Occupe l'espace disponible */
            text-align: center; /* Centre le texte */
        }
        .level-icon {
            font-size: 16px; /* Icône agrandie */
            position: absolute;
            right: 10px; /* Collée à la bordure intérieure droite */
        }
        .unanswered .level-icon {
            color: #fff; /* Blanc pour point d'interrogation */
        }
        .dark .level-icon {
            color: #000; /* Noir pour icône d'avertissement */
        }
        .light .level-icon {
            color: #fff; /* Blanc pour coche */
        }
        /* Questions et conseils */
        .questions, .advice {
            width: 100%; /* Pleine largeur du conteneur */
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            border-left: 4px solid #0d47a1;
        }
        .questions p {
            font-size: 16px;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .questions label {
            display: inline-block;
            margin-right: 10px;
            font-size: 16px;
        }
        .questions input[type="radio"] {
            margin-right: 5px;
        }
        .submit-btn, .back-btn {
            padding: 10px 20px;
            background-color: #0d47a1;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* Supprimer soulignement */
        }
        .reset-btn {
            padding: 10px 20px;
            background-color: #c0392b; /* Rouge foncé */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .advice h3 {
            font-size: 18px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .advice p {
            color: #ccc;
            margin-bottom: 15px;
            line-height: 1.5;
            text-align: justify; /* Texte justifié */
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
        /* Animation pour les boutons */
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
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
                padding: 3%; /* Réduction du padding en mode paysage */
            }
        }
        /* Ajustement pour petits écrans */
        @media (max-width: 480px) {
            .container {
                padding: 10px; /* Padding réduit pour petits écrans */
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
    <!-- Contenu principal -->
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <p>Évaluez vos besoins quotidiens avec la pyramide de Maslow. Cliquez sur un niveau pour répondre aux questions.</p>
        <!-- Pyramide -->
        <div class="pyramid">
            <div class="level level-5 unanswered" data-level="5"><span class="level-text">Épanouis-<br>sement</span> <i class="fas fa-question-circle level-icon"></i></div>
            <div class="level level-4 unanswered" data-level="4"><span class="level-text">Estime</span> <i class="fas fa-question-circle level-icon"></i></div>
            <div class="level level-3 unanswered" data-level="3"><span class="level-text">Sociaux</span> <i class="fas fa-question-circle level-icon"></i></div>
            <div class="level level-2 unanswered" data-level="2"><span class="level-text">Sécurité</span> <i class="fas fa-question-circle level-icon"></i></div>
            <div class="level level-1 unanswered" data-level="1"><span class="level-text">Physiologiques</span> <i class="fas fa-question-circle level-icon"></i></div>
        </div>
        <!-- Questions pour chaque niveau -->
        <div id="questions-container" class="questions">
            <h3 id="level-title"></h3>
            <form id="level-form">
                <!-- Questions dynamiques ici -->
            </form>
            <button class="submit-btn" onclick="submitAnswers()">Soumettre</button>
        </div>
        <!-- Conseils -->
        <div id="advice-container" class="advice" style="display: none;">
            <div id="advice-text"></div>
        </div>
        <!-- Boutons Retour et Réinitialiser -->
        <div class="button-container">
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Retour</a>
            <button class="reset-btn" onclick="resetPyramid()">Réinitialiser</button>
        </div>
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
            // Mise à jour du message de bienvenue
            const welcomeMessage = document.getElementById('welcomeMessage');
            welcomeMessage.innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${username},</span>`;

            // Chargement des réponses sauvegardées
            loadSavedResponses();
            updateAdvice(); // Afficher les conseils initiaux

            // Événements pour les niveaux de la pyramide
            document.querySelectorAll('.level').forEach(level => {
                level.addEventListener('click', () => showQuestions(level.dataset.level));
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

        // Données des niveaux : questions et conseils
        const levelsData = {
            1: {
                title: 'Physiologiques',
                adviceTitle: 'Besoin physiologique',
                questions: [
                    'Avez-vous mangé suffisamment aujourd\'hui ?',
                    'Avez-vous bu assez d\'eau ?',
                    'Avez-vous bien dormi la nuit dernière ?'
                ],
                advice: 'Assurez-vous de maintenir une alimentation équilibrée en incluant des repas variés riches en nutriments essentiels, comme des légumes, des fruits et des protéines. Hydratez-vous régulièrement tout au long de la journée, en visant environ 1,5 à 2 litres d’eau. Privilégiez un sommeil de qualité d’au moins 7 à 8 heures par nuit en établissant une routine régulière et en évitant les écrans avant de dormir.'
            },
            2: {
                title: 'Sécurité',
                adviceTitle: 'Besoin de sécurité',
                questions: [
                    'Vous sentez-vous en sécurité dans votre environnement ?',
                    'Votre situation financière est-elle stable ?',
                    'Votre santé est-elle prise en charge ?'
                ],
                advice: 'Vérifiez la sécurité de votre environnement en vous assurant que votre lieu de vie est sécurisé et confortable. Pour stabiliser votre situation financière, établissez un budget clair et envisagez des économies à long terme. Prenez soin de votre santé en consultant régulièrement un médecin pour des bilans, en suivant les traitements nécessaires et en adoptant des habitudes saines comme l’exercice modéré.'
            },
            3: {
                title: 'Sociaux',
                adviceTitle: 'Besoin social',
                questions: [
                    'Avez-vous interagi avec des amis ou de la famille aujourd\'hui ?',
                    'Vous sentez-vous aimé et soutenu ?',
                    'Appartenez-vous à une communauté ou un groupe ?'
                ],
                advice: 'Renforcez vos liens sociaux en contactant un proche pour une conversation ou une rencontre. Participez à des activités de groupe, comme des clubs ou des événements locaux, pour élargir votre réseau. Rejoignez une communauté qui partage vos intérêts, que ce soit en ligne ou en personne, pour cultiver un sentiment d’appartenance et de soutien mutuel.'
            },
            4: {
                title: 'Estime',
                adviceTitle: 'Besoin d’estime',
                questions: [
                    'Avez-vous accompli quelque chose dont vous êtes fier aujourd\'hui ?',
                    'Vous respectez-vous vous-même ?',
                    'Sentez-vous que les autres vous respectent ?'
                ],
                advice: 'Fixez-vous de petits objectifs quotidiens pour renforcer votre confiance en vous, comme terminer une tâche ou apprendre une nouvelle compétence. Célébrez vos réussites, même modestes, pour cultiver l’estime de soi. Entourez-vous de personnes positives qui valorisent vos efforts et évitez les environnements toxiques qui pourraient nuire à votre perception de vous-même.'
            },
            5: {
                title: 'Épanouissement',
                displayTitle: 'Épanouis-<br>sement',
                adviceTitle: 'Besoin d’accomplissement',
                questions: [
                    'Poursuivez-vous vos passions ou centres d\'intérêt ?',
                    'Apprenez-vous quelque chose de nouveau ?',
                    'Vous sentez-vous en chemin vers votre épanouissement ?'
                ],
                advice: 'Consacrez du temps régulier à vos hobbies ou passions pour nourrir votre créativité et votre bien-être. Explorez une nouvelle compétence ou un sujet qui vous intéresse, comme un cours en ligne ou un livre. Réfléchissez à vos objectifs de vie à long terme, établissez un plan pour les atteindre et cherchez des opportunités d’épanouissement personnel, comme le bénévolat ou des projets significatifs.'
            }
        };

        // Afficher les questions pour un niveau
        function showQuestions(level) {
            const questionsContainer = document.getElementById('questions-container');
            const levelTitle = document.getElementById('level-title');
            const form = document.getElementById('level-form');
            const adviceContainer = document.getElementById('advice-container');

            levelTitle.textContent = levelsData[level].title;
            form.innerHTML = '';
            adviceContainer.style.display = 'none';

            levelsData[level].questions.forEach((q, index) => {
                form.innerHTML += `
                    <div>
                        <p>${q}</p>
                        <label><input type="radio" name="q${index}" value="yes"> Oui</label>
                        <label><input type="radio" name="q${index}" value="no"> Non</label>
                    </div>
                `;
            });

            // Restaurer les réponses précédentes si elles existent
            const savedAnswers = localStorage.getItem(`pyramide_answers_${level}`);
            if (savedAnswers) {
                const answers = JSON.parse(savedAnswers);
                answers.forEach((answer, index) => {
                    if (answer) {
                        form.querySelector(`input[name="q${index}"][value="${answer}"]`).checked = true;
                    }
                });
            }

            questionsContainer.style.display = 'block';
            form.dataset.level = level;
        }

        // Mettre à jour les conseils pour tous les besoins échoués
        function updateAdvice() {
            const adviceContainer = document.getElementById('advice-container');
            const adviceText = document.getElementById('advice-text');
            const today = new Date().toISOString().split('T')[0];
            let adviceHTML = '';

            for (let level = 1; level <= 5; level++) {
                const saved = localStorage.getItem(`pyramide_${level}_${today}`);
                if (saved) {
                    const { fulfilled } = JSON.parse(saved);
                    if (!fulfilled) {
                        adviceHTML += `
                            <h3>${levelsData[level].adviceTitle}</h3>
                            <p>${levelsData[level].advice}</p>
                        `;
                    }
                }
            }

            if (adviceHTML) {
                adviceText.innerHTML = adviceHTML;
                adviceContainer.style.display = 'block';
            } else {
                adviceContainer.style.display = 'none';
            }
        }

        // Soumettre les réponses
        function submitAnswers() {
            const form = document.getElementById('level-form');
            const level = form.dataset.level;
            const questionsCount = levelsData[level].questions.length;
            let yesCount = 0;
            let allAnswered = true;
            const answers = [];

            // Vérifier si toutes les questions ont une réponse
            for (let i = 0; i < questionsCount; i++) {
                const answer = form.querySelector(`input[name="q${i}"]:checked`);
                if (!answer) {
                    allAnswered = false;
                    break;
                }
                answers.push(answer.value);
                if (answer.value === 'yes') {
                    yesCount++;
                }
            }

            if (!allAnswered) {
                alert('Veuillez répondre à toutes les questions.');
                return;
            }

            // Sauvegarder les réponses pour ce niveau
            localStorage.setItem(`pyramide_answers_${level}`, JSON.stringify(answers));

            const isFulfilled = yesCount >= Math.ceil(questionsCount / 2); // Majorité de "Oui"

            // Mettre à jour la couleur du niveau et l'icône
            const levelElement = document.querySelector(`.level[data-level="${level}"]`);
            levelElement.classList.remove('unanswered', 'dark', 'light');
            levelElement.classList.add(isFulfilled ? 'light' : 'dark');
            levelElement.innerHTML = isFulfilled
                ? `<span class="level-text">${levelsData[level].displayTitle || levelsData[level].title}</span> <i class="fas fa-check-circle level-icon"></i>`
                : `<span class="level-text">${levelsData[level].displayTitle || levelsData[level].title}</span> <i class="fas fa-triangle-exclamation level-icon"></i>`;

            // Sauvegarder l'état du niveau avec la date du jour
            const today = new Date().toISOString().split('T')[0];
            localStorage.setItem(`pyramide_${level}_${today}`, JSON.stringify({ fulfilled: isFulfilled }));

            // Mettre à jour les conseils
            updateAdvice();

            // Masquer les questions
            document.getElementById('questions-container').style.display = 'none';
        }

        // Charger les réponses sauvegardées pour aujourd'hui
        function loadSavedResponses() {
            const today = new Date().toISOString().split('T')[0];
            for (let level = 1; level <= 5; level++) {
                const saved = localStorage.getItem(`pyramide_${level}_${today}`);
                if (saved) {
                    const { fulfilled } = JSON.parse(saved);
                    const levelElement = document.querySelector(`.level[data-level="${level}"]`);
                    levelElement.classList.remove('unanswered', 'dark', 'light');
                    levelElement.classList.add(fulfilled ? 'light' : 'dark');
                    levelElement.innerHTML = fulfilled
                        ? `<span class="level-text">${levelsData[level].displayTitle || levelsData[level].title}</span> <i class="fas fa-check-circle level-icon"></i>`
                        : `<span class="level-text">${levelsData[level].displayTitle || levelsData[level].title}</span> <i class="fas fa-triangle-exclamation level-icon"></i>`;
                }
            }
        }

        // Réinitialiser la pyramide
        function resetPyramid() {
            const today = new Date().toISOString().split('T')[0];
            for (let level = 1; level <= 5; level++) {
                const levelElement = document.querySelector(`.level[data-level="${level}"]`);
                levelElement.classList.remove('unanswered', 'dark', 'light');
                levelElement.classList.add('unanswered');
                levelElement.innerHTML = `<span class="level-text">${levelsData[level].displayTitle || levelsData[level].title}</span> <i class="fas fa-question-circle level-icon"></i>`;
                localStorage.removeItem(`pyramide_${level}_${today}`);
                localStorage.removeItem(`pyramide_answers_${level}`);
            }
            document.getElementById('questions-container').style.display = 'none';
            updateAdvice(); // Mettre à jour les conseils (vides après réinitialisation)
        }
    </script>
</body>
</html>
