<?php require_once 'fonctions.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almoudou Traoré | Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navigation.php'; ?>

    <main>
        <section class="hero fade-in">
            <div class="hero-badge">
                <i class="fas fa-code"></i> Étudiant en Génie Logiciel & Réseaux
            </div>
            <h1>Almoudou Traoré<br><span>Créateur de solutions innovantes</span></h1>
            <p>Passionné par l'embarqué, la cybersécurité et l'IA — Malien au Sénégal,<br>je transforme ma curiosité technique en compétences solides au service de l'Afrique.</p>
            <div class="btn-group">
                <a href="projets.php" class="btn">→ Voir mes projets</a>
                <a href="contact.php" class="btn btn-outline">☑ Me contacter</a>
            </div>
        </section>

        <section class="welcome-card fade-in">
            <div class="welcome-inner">
                <div class="welcome-avatar"><span>AT</span></div>
                <div class="welcome-text">
                    <h2>Bienvenue sur mon portfolio</h2>
                    <p>Je m'appelle <strong>Almoudou Traoré</strong>, étudiant passionné par le développement logiciel, les systèmes embarqués et l'administration réseau.</p>
                    <p>Originaire du Mali, je poursuis aujourd'hui ma formation au Sénégal.</p>
                </div>
            </div>
        </section>

        <div class="stats-grid">
            <?php foreach (getStats() as $stat): ?>
            <div class="stat-card fade-in">
                <div class="stat-icon"><i class="fas <?php echo $stat['icon']; ?>"></i></div>
                <div class="stat-number"><?php echo $stat['number']; ?></div>
                <div class="stat-label"><?php echo $stat['label']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <section class="skills-section fade-in">
            <h2>Compétences techniques</h2>
            <?php foreach (getSkills() as $skill): ?>
            <div class="skill-bar-item">
                <div class="skill-info">
                    <span class="skill-name"><i class="<?php echo $skill['icon']; ?>"></i> <?php echo $skill['name']; ?></span>
                    <span class="skill-percent"><?php echo $skill['percent']; ?>%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress" data-width="<?php echo $skill['percent']; ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
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
                
                // Sauvegarde PHP via AJAX
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

        const skillsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progress = entry.target.querySelector('.progress');
                    if (progress) {
                        const width = progress.getAttribute('data-width');
                        if (width) progress.style.width = width + '%';
                    }
                    skillsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('.skill-bar-item').forEach(bar => skillsObserver.observe(bar));

        document.querySelectorAll('.progress').forEach(p => {
            const w = p.getAttribute('data-width');
            if (w) {
                p.style.width = '0%';
                p.setAttribute('data-width', w);
            }
        });
    </script>
</body>
</html>