<?php require_once 'fonctions.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almoudou Traoré | À propos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles supplémentaires pour la photo de profil */
        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
            box-shadow: 0 10px 30px rgba(183, 110, 255, 0.3);
            transition: var(--transition);
        }
        
        .profile-image:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 40px rgba(183, 110, 255, 0.4);
        }
        
        .about-header {
            display: flex;
            align-items: center;
            gap: 3rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        
        .about-title h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .about-title .tagline {
            color: var(--accent);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        .about-title .location {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Styles pour la section parcours */
        .timeline-item {
            margin-bottom: 1.8rem;
            border-left: 2px solid var(--accent);
            padding-left: 1.5rem;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--accent);
            border: 2px solid var(--bg-primary);
        }

        .timeline-date {
            font-size: 0.85rem;
            color: var(--accent);
            font-weight: 500;
            margin-bottom: 0.3rem;
            display: inline-block;
        }

        .timeline-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--text-primary);
        }

        .timeline-subtitle {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .timeline-grade {
            font-size: 0.9rem;
            color: var(--accent);
            font-weight: 500;
            margin-top: 0.4rem;
        }

        @media (max-width: 768px) {
            .about-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
            .profile-image {
                width: 150px;
                height: 150px;
            }
            .timeline-item {
                padding-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <main>
        <section class="hero fade-in">
            <div class="hero-badge">
                <i class="fas fa-user"></i> Qui suis-je ?
            </div>
            <h1> <span>À propos de moi</span></h1>
        </section>

        <!-- Section avec photo de profil -->
        <div class="about-header fade-in">
            <div class="profile-wrapper">
                <img src="tete2.jpeg" 
                     alt="Almoudou Traoré" 
                     class="profile-image">
            </div>
            <div class="about-title">
                <h2>Almoudou Traoré</h2>
                <div class="tagline">
                    <i class="fas fa-code"></i> Étudiant en Licence 2 – Génie Logiciel & Réseaux
                </div>
                <div class="location">
                    <i class="fas fa-map-marker-alt"></i> Malien au Sénégal
                </div>
            </div>
        </div>

        <!-- SECTION 1 : QUI SUIS-JE ? -->
        <div class="about-content fade-in">
            <div class="about-text">
                <h2>Qui suis-je ?</h2>
                <p>Passionné par les technologies émergentes, je me spécialise dans le développement logiciel, les systèmes embarqués et l'administration réseau.</p>
                <div class="about-highlight">
                    <i class="fas fa-quote-left" style="color: var(--accent); margin-right: 10px;"></i>
                    Je transforme ma curiosité technique en compétences solides au service de l'Afrique.
                </div>
                <p>Originaire du Mali, j'ai choisi de poursuivre ma formation au Sénégal pour élargir mes horizons et contribuer au développement technologique du continent africain.</p>
            </div>

            <!-- SECTION 2 : PARCOURS ACADÉMIQUE (déplacé ici) -->
            <div class="about-text">
                <h2><i class="fas fa-graduation-cap"></i> Parcours académique</h2>
                
                <div class="timeline-item">
                    <span class="timeline-date">2024 – aujourd'hui</span>
                    <h3 class="timeline-title">Licence 2 – Génie Logiciel & Réseaux</h3>
                    <div class="timeline-subtitle">ESTM, Dakar – Sénégal</div>
                </div>

                <div class="timeline-item">
                    <span class="timeline-date">2023 – 2024</span>
                    <h3 class="timeline-title">Licence 1 – Génie Logiciel & Admin Réseaux</h3>
                    <div class="timeline-subtitle">ESTM, Dakar – Sénégal</div>
                </div>

                <div class="timeline-item">
                    <span class="timeline-date">2022 – 2023</span>
                    <h3 class="timeline-title">Baccalauréat – Série Sciences Exactes</h3>
                    <div class="timeline-subtitle">Lycée Moderne Cheick Modibo Diarra, Bamako – Mali</div>
                </div>

                <div class="timeline-item">
                    <span class="timeline-date">2019-2020</span>
                    <h3 class="timeline-title">Diplôme d'Études Fondamentales (DEF)</h3>
                    <div class="timeline-subtitle">École Prosper Kamara – Mali</div>
                    <div class="timeline-grade">🏅 Mention : Très bien</div>
                </div>
            </div>
        </div>

        <!-- SECTION 3 : DOMAINES D'EXPERTISE & OBJECTIFS (seule, en dessous) -->
        <div class="about-content fade-in" style="margin-top: 0;">
            <div class="about-text">
                <h2>Domaines d'expertise</h2>
                <div class="interests">
                    <span class="interest-tag"><i class="fas fa-microchip"></i> Systèmes embarqués</span>
                    <span class="interest-tag"><i class="fas fa-shield-alt"></i> Cybersécurité</span>
                    <span class="interest-tag"><i class="fas fa-brain"></i> Intelligence Artificielle</span>
                    <span class="interest-tag"><i class="fas fa-globe"></i> Développement Web</span>
                    <span class="interest-tag"><i class="fas fa-network-wired"></i> Administration Réseau</span>
                    <span class="interest-tag"><i class="fas fa-mobile-alt"></i> IoT</span>
                </div>
            </div>
            <div class="about-text">
                <h2>Objectifs</h2>
                <p>Mon objectif est de devenir un expert capable de créer des solutions innovantes adaptées aux défis africains, en combinant logiciel, matériel et connectivité.</p>
            </div>
        </div>
    </main>

    <?php include 'pied-de-page.php'; ?>

    <script>
        // Gestion du thème - UNIFORME
        const themeToggle = document.getElementById('themeToggle');
        let savedTheme = localStorage.getItem('theme');
        
        if (!savedTheme) {
            savedTheme = '<?php echo getTheme(); ?>';
        }
        
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (themeToggle) {
                themeToggle.innerHTML = savedTheme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
            }
        }
        
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                themeToggle.innerHTML = newTheme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
                
                fetch('save-theme.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'theme=' + newTheme
                }).catch(err => console.log('Erreur:', err));
            });
        }

        // Animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    </script>
</body>
</html>