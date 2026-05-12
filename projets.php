<?php require_once 'fonctions.php'; ?>
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
            pointer-events: none;
            cursor: default;
        }
        
        .project-icon {
            background: none;
            padding: 0;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .project-icon i {
            font-size: 4rem;
            color: white;
            display: block;
        }
        
        .icon-wrapper {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .project-card {
            cursor: default;
            transition: all 0.3s ease;
        }

        /* Pour que "Mes Projets" reste sur une seule ligne */
        .hero h1 span {
            display: inline;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.2;
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
        
        /* Filtres mots-clés */
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.8rem;
            margin-bottom: 2rem;
        }
        
        .filter-btn {
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Inter', sans-serif;
        }
        
        .filter-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
        }
        
        .filter-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }
        
        /* Résultats */
        .results-count {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
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
        
        /* Animation des cartes */
        .project-card {
            opacity: 1;
            transform: scale(1);
            transition: all 0.3s ease;
        }
        
        .project-card.hidden {
            display: none;
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
            <h1> <span>Mes projets</span></h1>
            <p>Découvrez les projets que j'ai réalisés durant mon parcours</p>
        </section>

        <!-- Barre de recherche et filtres -->
        <div class="search-section fade-in">
            <div class="search-container">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Rechercher un projet... (titre, description, technologie)">
                </div>
            </div>
            
            <div class="filters-container" id="filtersContainer">
                <button class="filter-btn active" data-filter="all">Tous</button>
                <button class="filter-btn" data-filter="web">🌐 Web</button>
                <button class="filter-btn" data-filter="iot">📡 IoT</button>
                <button class="filter-btn" data-filter="c">⚙️ C</button>
                <button class="filter-btn" data-filter="reseau">🔄 Réseau</button>
                <button class="filter-btn" data-filter="php">🐘 PHP</button>
                <button class="filter-btn" data-filter="python">🐍 Python</button>
                <button class="filter-btn" data-filter="javascript">📜 JavaScript</button>
            </div>
            
            <div class="results-count" id="resultsCount"></div>
        </div>

        <div class="projects-grid" id="projectsGrid">
            <?php 
            $projects = getProjects();
            foreach ($projects as $index => $project): 
                // Déterminer la catégorie du projet pour le filtrage
                $categories = [];
                $titleLower = strtolower($project['title']);
                $descLower = strtolower($project['description']);
                $techLower = array_map('strtolower', $project['tech']);
                
                if (strpos($titleLower, 'agro') !== false || strpos($descLower, 'agriculteur') !== false) $categories[] = 'web';
                if (strpos($titleLower, 'iot') !== false || in_array('esp32', $techLower)) $categories[] = 'iot';
                if (strpos($titleLower, 'c') !== false || in_array('langage c', $techLower)) $categories[] = 'c';
                if (strpos($titleLower, 'reverse') !== false || strpos($titleLower, 'proxy') !== false || in_array('nginx', $techLower)) $categories[] = 'reseau';
                if (in_array('php', $techLower)) $categories[] = 'php';
                if (in_array('python', $techLower)) $categories[] = 'python';
                if (in_array('javascript', $techLower)) $categories[] = 'javascript';
                
                $categories = array_unique($categories);
                $dataCategories = implode(' ', $categories);
            ?>
            <div class="project-card" 
                 data-title="<?php echo strtolower($project['title']); ?>" 
                 data-description="<?php echo strtolower($project['description']); ?>"
                 data-tech="<?php echo strtolower(implode(' ', $project['tech'])); ?>"
                 data-categories="<?php echo $dataCategories; ?>">
                <div class="project-icon">
                    <?php if ($project['hasImage']): ?>
                        <img src="<?php echo $project['image']; ?>" alt="<?php echo $project['title']; ?>" class="project-img" onerror="this.src='https://placehold.co/600x400/2a2a2a/B76EFF?text=<?php echo urlencode($project['title']); ?>'">
                    <?php else: ?>
                        <div class="icon-wrapper">
                            <i class="fas <?php echo $project['icon']; ?>"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="project-info">
                    <h3><?php echo $project['title']; ?></h3>
                    <p><?php echo $project['description']; ?></p>
                    <div class="project-tech">
                        <?php foreach ($project['tech'] as $tech): ?>
                        <span><?php echo $tech; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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

        // ========== FONCTIONNALITÉS DE RECHERCHE ET FILTRAGE ==========
        
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');
        const resultsCount = document.getElementById('resultsCount');
        
        let currentFilter = 'all';
        let currentSearch = '';
        
        // Fonction pour mettre à jour l'affichage des projets
        function filterProjects() {
            let visibleCount = 0;
            
            projectCards.forEach(card => {
                const title = card.dataset.title;
                const description = card.dataset.description;
                const tech = card.dataset.tech;
                const categories = card.dataset.categories;
                
                // Vérifier le filtre
                let matchesFilter = (currentFilter === 'all') || categories.includes(currentFilter);
                
                // Vérifier la recherche
                let matchesSearch = true;
                if (currentSearch !== '') {
                    matchesSearch = title.includes(currentSearch) || 
                                   description.includes(currentSearch) || 
                                   tech.includes(currentSearch);
                }
                
                if (matchesFilter && matchesSearch) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Mettre à jour le compteur
            const total = projectCards.length;
            if (visibleCount === total) {
                resultsCount.textContent = `${total} projet${total > 1 ? 's' : ''} affiché${total > 1 ? 's' : ''}`;
            } else {
                resultsCount.textContent = `${visibleCount} projet${visibleCount > 1 ? 's' : ''} sur ${total}`;
            }
            
            if (visibleCount === 0) {
                if (!document.querySelector('.no-results-message')) {
                    const grid = document.getElementById('projectsGrid');
                    const noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-results no-results-message';
                    noResultsMsg.innerHTML = `
                        <i class="fas fa-search"></i>
                        <h3>Aucun projet trouvé</h3>
                        <p>Essayez d'autres mots-clés ou filtres</p>
                    `;
                    noResultsMsg.style.gridColumn = '1 / -1';
                    grid.appendChild(noResultsMsg);
                }
            } else {
                const existingMsg = document.querySelector('.no-results-message');
                if (existingMsg) existingMsg.remove();
            }
        }
        
        // Écouteur pour la barre de recherche
        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value.toLowerCase().trim();
            filterProjects();
        });
        
        // Écouteurs pour les boutons de filtre
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Mettre à jour l'état actif des boutons
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Mettre à jour le filtre courant
                currentFilter = btn.dataset.filter;
                filterProjects();
            });
        });
        
        // Initialisation
        filterProjects();

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