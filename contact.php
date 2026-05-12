<?php 
require_once 'fonctions.php';

// ==================== 5.1 FORMULAIRE DE CONTACT ====================
$erreurs_contact = [];
$succes_contact = false;
$nom_contact = '';
$email_contact = '';
$message_contact = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    $nom_contact = cleanInput($_POST['nom'] ?? '');
    $email_contact = cleanInput($_POST['email'] ?? '');
    $message_contact = cleanInput($_POST['message'] ?? '');
    
    if (empty($nom_contact)) $erreurs_contact[] = 'Le nom est obligatoire.';
    if (!filter_var($email_contact, FILTER_VALIDATE_EMAIL)) $erreurs_contact[] = 'L\'adresse e-mail est invalide.';
    if (empty($message_contact)) $erreurs_contact[] = 'Le message ne peut pas être vide.';
    
    if (empty($erreurs_contact)) {
        $succes_contact = true;
        sendContactEmail($nom_contact, $email_contact, 'Message depuis formulaire contact', $message_contact);
    }
}

// ==================== 5.2 FORMULAIRE DE DEMANDE DE PROJET ====================
$erreurs_demande = [];
$succes_demande = false;
$demande = [
    'nom' => '',
    'email' => '',
    'societe' => '',
    'type_projet' => '',
    'description' => '',
    'budget' => '',
    'delai' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'demande') {
    $demande['nom'] = cleanInput($_POST['nom'] ?? '');
    $demande['email'] = cleanInput($_POST['email'] ?? '');
    $demande['societe'] = cleanInput($_POST['societe'] ?? '');
    $demande['type_projet'] = cleanInput($_POST['type_projet'] ?? '');
    $demande['description'] = cleanInput($_POST['description'] ?? '');
    $demande['budget'] = cleanInput($_POST['budget'] ?? '');
    $demande['delai'] = cleanInput($_POST['delai'] ?? '');
    
    if (empty($demande['nom'])) $erreurs_demande['nom'] = 'Le nom est obligatoire.';
    if (!filter_var($demande['email'], FILTER_VALIDATE_EMAIL)) $erreurs_demande['email'] = 'L\'adresse e-mail est invalide.';
    if (empty($demande['type_projet'])) $erreurs_demande['type_projet'] = 'Le type de projet est obligatoire.';
    if (empty($demande['description'])) $erreurs_demande['description'] = 'La description du projet est obligatoire.';
    if (empty($demande['budget'])) $erreurs_demande['budget'] = 'Le budget est obligatoire.';
    if (empty($demande['delai'])) $erreurs_demande['delai'] = 'Le délai est obligatoire.';
    
    if (empty($erreurs_demande)) {
        $succes_demande = true;
    }
}

// ==================== 5.3 FORMULAIRE DE RECHERCHE DE PROJETS ====================
$mot_cle = cleanInput($_GET['q'] ?? '');
$projets_filtrer = getProjects();
$resultats_recherche = [];

if ($mot_cle !== '') {
    foreach ($projets_filtrer as $projet) {
        $titre = strtolower($projet['title']);
        $description = strtolower($projet['description']);
        $tech = strtolower(implode(' ', $projet['tech']));
        
        if (strpos($titre, $mot_cle) !== false || 
            strpos($description, $mot_cle) !== false ||
            strpos($tech, $mot_cle) !== false) {
            $resultats_recherche[] = $projet;
        }
    }
} else {
    $resultats_recherche = $projets_filtrer;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almoudou Traoré | Contact</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles pour les 3 formulaires */
        .forms-container {
            display: flex;
            flex-direction: column;
            gap: 3rem;
            margin-top: 2rem;
        }
        
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2rem;
            transition: var(--transition);
        }
        
        .form-card h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--accent);
        }
        
        .form-card h2 i {
            margin-right: 0.8rem;
        }
        
        .form-card .subtitle {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group label i {
            margin-right: 0.5rem;
            color: var(--accent);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        
        /* Correction importante pour les selects */
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23B76EFF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            cursor: pointer;
        }
        
        /* Style des options dans le select */
        .form-group select option {
            background: var(--bg-primary);
            color: var(--text-primary);
            padding: 10px;
        }
        
        /* Pour le mode clair */
        [data-theme="light"] .form-group select option {
            background: #FFFFFF;
            color: #111111;
        }
        
        /* Pour le mode sombre */
        [data-theme="dark"] .form-group select option {
            background: #0A0A0A;
            color: #F0F0F0;
        }
        
        .form-group select:focus {
            outline: none;
            border-color: var(--accent);
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
        }
        
        .form-group .error {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 0.3rem;
            display: block;
        }
        
        .success-message {
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid #2ecc71;
            color: #2ecc71;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .error-message {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 0.8rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .btn-submit {
            background: var(--accent);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 2rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }
        
        /* Resume demande */
        .resume-demande {
            background: rgba(183, 110, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        .resume-demande h3 {
            margin-bottom: 1rem;
            color: var(--accent);
        }
        
        .resume-demande p {
            margin: 0.5rem 0;
        }
        
        .resume-demande strong {
            color: var(--accent);
        }
        
        /* Section recherche */
        .search-section {
            margin-top: 2rem;
        }
        
        .search-box {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .search-box input {
            flex: 1;
            padding: 0.8rem 1rem;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-primary);
        }
        
        .search-results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .search-result-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem;
            transition: var(--transition);
        }
        
        .search-result-card:hover {
            transform: translateY(-3px);
            border-color: var(--accent);
        }
        
        .search-result-card h4 {
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        
        .search-result-card p {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        
        .search-result-card .tech-badge {
            display: inline-block;
            background: rgba(183, 110, 255, 0.15);
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-size: 0.7rem;
            margin-right: 0.5rem;
            margin-top: 0.5rem;
            color: var(--accent);
        }
        
        .no-results {
            text-align: center;
            padding: 2rem;
            background: var(--bg-card);
            border-radius: 16px;
            color: var(--text-secondary);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <main>
        <section class="hero fade-in">
            <div class="hero-badge">
                <i class="fas fa-envelope"></i> Entrons en contact
            </div>
            <h1><span> Contactez moi</span></h1>
            <p>N'hésitez pas à me contacter pour toute collaboration ou question</p>
        </section>

        <div class="forms-container">
            <!-- ==================== 5.1 FORMULAIRE DE CONTACT ==================== -->
            <div class="form-card fade-in">
                <h2><i class="fas fa-paper-plane"></i> Formulaire de contact</h2>
                <div class="subtitle">Envoyez-moi un message directement</div>
                
                <?php if ($succes_contact): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> Votre message a été envoyé avec succès ! Je vous répondrai dans les plus brefs délais.
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($erreurs_contact)): ?>
                    <div class="error-message">
                        <?php foreach ($erreurs_contact as $err): ?>
                            <div><i class="fas fa-exclamation-triangle"></i> <?php echo $err; ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="action" value="contact">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nom complet *</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($nom_contact); ?>" placeholder="Votre nom">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email_contact); ?>" placeholder="votre@email.com">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-comment"></i> Message *</label>
                        <textarea name="message" rows="4" placeholder="Votre message..."><?php echo htmlspecialchars($message_contact); ?></textarea>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fas fa-send"></i> Envoyer le message</button>
                </form>
            </div>

            <!-- ==================== 5.2 FORMULAIRE DE DEMANDE DE PROJET ==================== -->
            <div class="form-card fade-in">
                <h2><i class="fas fa-project-diagram"></i> Demande de projet</h2>
                <div class="subtitle">Vous avez un projet ? Décrivez-le moi</div>
                
                <?php if ($succes_demande): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> Votre demande a été enregistrée ! Voici un récapitulatif :
                    </div>
                    <div class="resume-demande">
                        <h3><i class="fas fa-clipboard-list"></i> Récapitulatif de votre demande</h3>
                        <p><strong>👤 Nom :</strong> <?php echo htmlspecialchars($demande['nom']); ?></p>
                        <p><strong>📧 Email :</strong> <?php echo htmlspecialchars($demande['email']); ?></p>
                        <p><strong>🏢 Société :</strong> <?php echo htmlspecialchars($demande['societe'] ?: 'Non spécifiée'); ?></p>
                        <p><strong>📁 Type de projet :</strong> <?php echo htmlspecialchars($demande['type_projet']); ?></p>
                        <p><strong>📝 Description :</strong> <?php echo nl2br(htmlspecialchars($demande['description'])); ?></p>
                        <p><strong>💰 Budget :</strong> <?php echo htmlspecialchars($demande['budget']); ?></p>
                        <p><strong>⏱️ Délai souhaité :</strong> <?php echo htmlspecialchars($demande['delai']); ?></p>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="action" value="demande">
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nom complet *</label>
                            <input type="text" name="nom" value="<?php echo htmlspecialchars($demande['nom']); ?>" placeholder="Votre nom">
                            <?php if (isset($erreurs_demande['nom'])): ?>
                                <span class="error"><?php echo $erreurs_demande['nom']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email *</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($demande['email']); ?>" placeholder="votre@email.com">
                            <?php if (isset($erreurs_demande['email'])): ?>
                                <span class="error"><?php echo $erreurs_demande['email']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Société / Organisation</label>
                        <input type="text" name="societe" value="<?php echo htmlspecialchars($demande['societe']); ?>" placeholder="Nom de votre société (optionnel)">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Type de projet *</label>
                        <select name="type_projet">
                            <option value="">-- Sélectionnez un type de projet --</option>
                            <option value="Site web" <?php echo $demande['type_projet'] == 'Site web' ? 'selected' : ''; ?>>🌐 Site web</option>
                            <option value="Application mobile" <?php echo $demande['type_projet'] == 'Application mobile' ? 'selected' : ''; ?>>📱 Application mobile</option>
                            <option value="Application desktop" <?php echo $demande['type_projet'] == 'Application desktop' ? 'selected' : ''; ?>>💻 Application desktop</option>
                            <option value="Système embarqué / IoT" <?php echo $demande['type_projet'] == 'Système embarqué / IoT' ? 'selected' : ''; ?>>🔌 Système embarqué / IoT</option>
                            <option value="Réseau / Infrastructure" <?php echo $demande['type_projet'] == 'Réseau / Infrastructure' ? 'selected' : ''; ?>>🖧 Réseau / Infrastructure</option>
                            <option value="Cybersécurité" <?php echo $demande['type_projet'] == 'Cybersécurité' ? 'selected' : ''; ?>>🔒 Cybersécurité</option>
                            <option value="Intelligence Artificielle" <?php echo $demande['type_projet'] == 'Intelligence Artificielle' ? 'selected' : ''; ?>>🧠 Intelligence Artificielle</option>
                        </select>
                        <?php if (isset($erreurs_demande['type_projet'])): ?>
                            <span class="error"><?php echo $erreurs_demande['type_projet']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Description du projet *</label>
                        <textarea name="description" rows="4" placeholder="Décrivez votre projet en quelques lignes..."><?php echo htmlspecialchars($demande['description']); ?></textarea>
                        <?php if (isset($erreurs_demande['description'])): ?>
                            <span class="error"><?php echo $erreurs_demande['description']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-euro-sign"></i> Budget estimé *</label>
                            <select name="budget">
                                <option value="">-- Sélectionnez un budget --</option>
                                <option value="Moins de 1000€" <?php echo $demande['budget'] == 'Moins de 1000€' ? 'selected' : ''; ?>>Moins de 1000€</option>
                                <option value="1000€ - 5000€" <?php echo $demande['budget'] == '1000€ - 5000€' ? 'selected' : ''; ?>>1000€ - 5000€</option>
                                <option value="5000€ - 15000€" <?php echo $demande['budget'] == '5000€ - 15000€' ? 'selected' : ''; ?>>5000€ - 15000€</option>
                                <option value="Plus de 15000€" <?php echo $demande['budget'] == 'Plus de 15000€' ? 'selected' : ''; ?>>Plus de 15000€</option>
                            </select>
                            <?php if (isset($erreurs_demande['budget'])): ?>
                                <span class="error"><?php echo $erreurs_demande['budget']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Délai souhaité *</label>
                            <select name="delai">
                                <option value="">-- Sélectionnez un délai --</option>
                                <option value="Moins d'1 mois" <?php echo $demande['delai'] == 'Moins d\'1 mois' ? 'selected' : ''; ?>>Moins d'1 mois</option>
                                <option value="1-3 mois" <?php echo $demande['delai'] == '1-3 mois' ? 'selected' : ''; ?>>1-3 mois</option>
                                <option value="3-6 mois" <?php echo $demande['delai'] == '3-6 mois' ? 'selected' : ''; ?>>3-6 mois</option>
                                <option value="Plus de 6 mois" <?php echo $demande['delai'] == 'Plus de 6 mois' ? 'selected' : ''; ?>>Plus de 6 mois</option>
                            </select>
                            <?php if (isset($erreurs_demande['delai'])): ?>
                                <span class="error"><?php echo $erreurs_demande['delai']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Envoyer la demande</button>
                </form>
            </div>

            <!-- ==================== 5.3 FORMULAIRE DE RECHERCHE DE PROJETS ==================== -->
            <div class="form-card fade-in">
                <h2><i class="fas fa-search"></i> Recherche de projets</h2>
                <div class="subtitle">Parcourez mes réalisations par mot-clé</div>
                
                <div class="search-section">
                    <form method="GET" action="">
                        <div class="search-box">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($mot_cle); ?>" placeholder="Rechercher par titre, description ou technologie...">
                            <button type="submit" class="btn-submit"><i class="fas fa-search"></i> Rechercher</button>
                        </div>
                    </form>
                    
                    <div class="search-results-grid">
                        <?php if (empty($resultats_recherche)): ?>
                            <div class="no-results">
                                <i class="fas fa-search" style="font-size: 2rem;"></i>
                                <p>Aucun projet ne correspond à votre recherche "<?php echo htmlspecialchars($mot_cle); ?>"</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($resultats_recherche as $projet): ?>
                                <div class="search-result-card">
                                    <h4><?php echo $projet['title']; ?></h4>
                                    <p><?php echo substr($projet['description'], 0, 100); ?>...</p>
                                    <div>
                                        <?php foreach ($projet['tech'] as $tech): ?>
                                            <span class="tech-badge"><?php echo $tech; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($mot_cle !== '' && !empty($resultats_recherche)): ?>
                        <div class="error-message" style="margin-top: 1rem; background: rgba(183, 110, 255, 0.1); border-color: var(--accent); color: var(--accent);">
                            <i class="fas fa-info-circle"></i> <?php echo count($resultats_recherche); ?> projet(s) trouvé(s) pour "<?php echo htmlspecialchars($mot_cle); ?>"
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'pied-de-page.php'; ?>

    <script>
        // Gestion du thème
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