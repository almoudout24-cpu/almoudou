<?php require_once 'fonctions.php'; ?>

<?php
// ==================== TRAITEMENT DE LA RECHERCHE (100% PHP) ====================
// Récupération du mot-clé depuis $_GET (comme demandé dans la consigne)
$mot_cle = cleanInput($_GET['q'] ?? '');

// Récupération de tous les projets
$tous_les_projets = getProjects();

// Filtrage des projets par mot-clé (titre, description, technologies)
$projets_filtrer = [];

if ($mot_cle !== '') {
    foreach ($tous_les_projets as $projet) {
        // Mise en minuscules pour une recherche insensible à la casse
        $titre = strtolower($projet['title']);
        $description = strtolower($projet['description']);
        $tech = strtolower(implode(' ', $projet['tech']));
        
        // Vérification si le mot-clé est présent
        if (strpos($titre, $mot_cle) !== false || 
            strpos($description, $mot_cle) !== false ||
            strpos($tech, $mot_cle) !== false) {
            $projets_filtrer[] = $projet;
        }
    }
} else {
    // Pas de recherche : afficher tous les projets
    $projets_filtrer = $tous_les_projets;
}

// Variables pour l'affichage
$nombre_resultats = count($projets_filtrer);
$recherche_effectuee = ($mot_cle !== '');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almoudou Traoré | Projets</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles pour les images de projet */
        .project-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        
        .project-icon {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            padding: 2rem;
            text-align: center;
            font-size: 3rem;
            color: white;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .project-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent);
        }
        
        .project-info {
            padding: 1.5rem;
        }
        
        .project-info h3 {
            margin-bottom: 0.5rem;
        }
        
        .project-info p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
        
        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .project-tech span {
            background: rgba(183, 110, 255, 0.12);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            color: var(--accent);
        }

        /* Barre de recherche */
        .search-section {
            margin: 2rem 0 3rem 0;
        }
        
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            width: 100%;
            max-width: 500px;
            transition: var(--transition);
        }
        
        .search-box:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(183, 110, 255, 0.2);
        }
        
        .search-box i {
            color: var(--text-secondary);
            margin-right: 0.8rem;
        }
        
        .search-box input {
            flex: 1;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1rem;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        
        .search-box input::placeholder {
            color: var(--text-secondary);
        }
        
        .search-box button {
            background: var(--accent);
            border: none;
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .search-box button:hover {
            background: var(--accent-hover);
        }
        
        /* Résultats */
        .results-info {
            text-align: center;
            margin-bottom: 2rem;
            padding: 0.8rem;
            border-radius: 12px;
            background: var(--bg-card);
            border: 1px solid var(--border);
        }
        
        .results-info .badge {
            display: inline-block;
            background: var(--accent);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            background: var(--bg-card);
            border-radius: 20px;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }
        
        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--accent);
        }
        
        .clear-search {
            display: inline-block;
            margin-top: 1rem;
            color: var(--accent);
            text-decoration: none;
        }
        
        .clear-search:hover {
            text-decoration: underline;
        }
        
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 1rem;
        }
        
        /* Hero span sur une ligne */
        .hero h1 span {
            display: inline;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .projects-grid {
                grid-template-columns: 1fr;
            }
            .hero h1 {
                font-size: 2.5rem;
            }
            .search-box {
                flex-direction: column;
                gap: 0.8rem;
                border-radius: 20px;
                background: transparent;
            }
            .search-box input {
                width: 100%;
                background: var(--bg-card);
                padding: 0.8rem;
                border-radius: 50px;
                border: 1px solid var(--border);
            }
            .search-box button {
                width: 100%;
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <main>
        <section class="hero fade-in">
            <div class="hero-badge">
                <i class="fas fa-folder-open"></i> Mes réalisations
            </div>
            <h1><span>Mes projets</span></h1>
            <p>Découvrez les projets que j'ai réalisés durant mon parcours</p>
        </section>

        <!-- ==================== FORMULAIRE DE RECHERCHE ==================== -->
        <!-- Utilisation de $_GET comme demandé dans la consigne (section 5.3) -->
        <div class="search-section fade-in">
            <div class="search-container">
                <form method="GET" action="" style="width: 100%; display: flex; justify-content: center;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" 
                               name="q" 
                               value="<?php echo htmlspecialchars($mot_cle); ?>" 
                               placeholder="Rechercher un projet... (titre, description, technologie)">
                        <button type="submit">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Affichage des résultats de recherche (PHP) -->
            <div class="results-info">
                <?php if ($recherche_effectuee): ?>
                    <i class="fas fa-search"></i> 
                    <?php if ($nombre_resultats > 0): ?>
                        <?php echo $nombre_resultats; ?> projet<?php echo $nombre_resultats > 1 ? 's' : ''; ?> trouvé<?php echo $nombre_resultats > 1 ? 's' : ''; ?> 
                        pour "<strong><?php echo htmlspecialchars($mot_cle); ?></strong>"
                        <a href="projets.php" class="badge">
                            <i class="fas fa-times"></i> Effacer
                        </a>
                    <?php else: ?>
                        Aucun résultat pour "<strong><?php echo htmlspecialchars($mot_cle); ?></strong>"
                        <a href="projets.php" class="badge">
                            <i class="fas fa-undo"></i> Voir tous les projets
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <i class="fas fa-info-circle"></i> 
                    <?php echo $nombre_resultats; ?> projet<?php echo $nombre_resultats > 1 ? 's' : ''; ?> au total
                    <span class="badge">Recherche par mot-clé</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- ==================== AFFICHAGE DYNAMIQUE DES PROJETS ==================== -->
        <!-- Utilisation d'une boucle foreach comme demandé dans la consigne -->
        <?php if (empty($projets_filtrer)): ?>
            <div class="no-results fade-in">
                <i class="fas fa-search"></i>
                <h3>Aucun projet trouvé</h3>
                <p>Essayez d'autres mots-clés ou <a href="projets.php" class="clear-search">affichez tous les projets</a></p>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($projets_filtrer as $projet): ?>
                    <div class="project-card fade-in">
                        <div class="project-icon">
                            <?php if ($projet['hasImage'] && file_exists($projet['image'])): ?>
                                <img src="<?php echo htmlspecialchars($projet['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($projet['title']); ?>" 
                                     class="project-img">
                            <?php else: ?>
                                <i class="fas fa-code"></i>
                            <?php endif; ?>
                        </div>
                        <div class="project-info">
                            <h3><?php echo htmlspecialchars($projet['title']); ?></h3>
                            <p><?php echo htmlspecialchars($projet['description']); ?></p>
                            <div class="project-tech">
                                <?php foreach ($projet['tech'] as $tech): ?>
                                    <span><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
     
