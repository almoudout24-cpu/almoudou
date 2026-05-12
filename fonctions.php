<?php
// ============================================
// FICHIER DE FONCTIONS - PORTFOLIO
// ============================================
// Ce fichier centralise toutes les fonctions réutilisables
// dans le portfolio (thème, emails, compétences, projets...)
// Il est inclus dans chaque page avec require_once
// ============================================

// ------------------------------------------------------------
// 1. FONCTIONS DE GESTION DU THÈME (clair/sombre)
// ------------------------------------------------------------

/**
 * Fonction getTheme()
 * Récupère le thème stocké dans le cookie du visiteur
 * 
 * @return string Le thème actif ('dark' par défaut, ou 'light')
 * 
 * Explication :
 * - isset($_COOKIE['theme']) : vérifie si un cookie nommé 'theme' existe
 * - Si oui, on retourne sa valeur
 * - Sinon, on retourne 'dark' (mode sombre par défaut)
 * - Les cookies permettent de mémoriser le choix de l'utilisateur
 */
function getTheme() {
    if (isset($_COOKIE['theme'])) {
        return $_COOKIE['theme'];
    }
    return 'dark';
}

/**
 * Fonction setTheme($theme)
 * Crée un cookie pour stocker le thème choisi
 * 
 * @param string $theme Le thème à sauvegarder ('dark' ou 'light')
 * 
 * Explication :
 * - setcookie() : crée un cookie côté navigateur
 * - Paramètres : (nom, valeur, expiration, chemin)
 * - time() + 365*24*3600 = le cookie expire dans 1 an
 * - '/' = le cookie est valable sur tout le site
 */
function setTheme($theme) {
    setcookie('theme', $theme, time() + 365 * 24 * 3600, '/');
}

// ------------------------------------------------------------
// 2. FONCTIONS DE SÉCURITÉ ET VALIDATION
// ------------------------------------------------------------

/**
 * Fonction validateEmail($email)
 * Vérifie si un email est valide (format correct)
 * 
 * @param string $email L'adresse email à vérifier
 * @return mixed L'email si valide, false sinon
 * 
 * Explication :
 * - filter_var() : fonction PHP native pour filtrer les variables
 * - FILTER_VALIDATE_EMAIL : constante qui vérifie le format email
 * - Exemple : "jean@example.com" ✅  /  "jean@" ❌
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Fonction cleanInput($data)
 * Nettoie les données saisies par l'utilisateur (sécurité)
 * 
 * @param string $data La donnée brute à nettoyer
 * @return string La donnée nettoyée
 * 
 * Explication (3 étapes de nettoyage) :
 * 1. trim() : supprime les espaces au début et à la fin
 * 2. stripslashes() : supprime les anti-slashs (\) inutiles
 * 3. htmlspecialchars() : convertit les caractères HTML en entités
 *    Exemple : "<script>" devient "&lt;script&gt;"
 *    (empêche les attaques XSS - Cross Site Scripting)
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ------------------------------------------------------------
// 3. FONCTION D'ENVOI D'EMAIL (SIMULATION)
// ------------------------------------------------------------

/**
 * Fonction sendContactEmail($name, $email, $subject, $message)
 * Simule l'envoi d'un email depuis le formulaire de contact
 * 
 * @param string $name Nom de l'expéditeur
 * @param string $email Email de l'expéditeur
 * @param string $subject Sujet du message
 * @param string $message Corps du message
 * @return bool Toujours true (simulation pour le TP)
 * 
 * Détails importants :
 * - $to : Email du destinataire (ton email pro à remplacer)
 * - $headers : En-têtes de l'email (From, Reply-To, Content-Type)
 * - En production : il faudrait utiliser une vraie fonction mail() 
 *   ou un service comme SMTP, PHPMailer, etc.
 * - Ici on simule l'envoi avec 'return true' pour les besoins du TP
 */
function sendContactEmail($name, $email, $subject, $message) {
    // ⚠️ À MODIFIER : Mets ton vrai email ici !
    $to = "almoudou.traore@example.com";
    
    // Construction des en-têtes
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Construction du message complet
    $fullMessage = "Nom: " . $name . "\n";
    $fullMessage .= "Email: " . $email . "\n";
    $fullMessage .= "Message:\n" . $message;
    
    // Sujet par défaut si vide
    $mailSubject = $subject ?: "Nouveau message du portfolio";
    
    // Simulation d'envoi (à remplacer par mail($to, $mailSubject, $fullMessage, $headers))
    return true;
}

// ------------------------------------------------------------
// 4. FONCTION RETOURNANT LES COMPÉTENCES
// ------------------------------------------------------------

/**
 * Fonction getSkills()
 * Retourne la liste des compétences techniques
 * 
 * @return array Tableau multidimensionnel des compétences
 * 
 * Structure d'une compétence :
 * - 'name'    : Nom affiché
 * - 'percent' : Niveau de maîtrise (0 à 100)
 * - 'icon'    : Classe FontAwesome pour l'icône
 * 
 * Utilisation dans la page : foreach(getSkills() as $skill)
 */
function getSkills() {
    return [
        ['name' => 'HTML / CSS', 'percent' => 85, 'icon' => 'fab fa-html5'],
        ['name' => 'C / C++', 'percent' => 75, 'icon' => 'fas fa-code'],
        ['name' => 'JavaScript', 'percent' => 65, 'icon' => 'fab fa-js'],
        ['name' => 'Python / Java', 'percent' => 70, 'icon' => 'fab fa-python'],
        ['name' => 'MySQL / PHP', 'percent' => 60, 'icon' => 'fas fa-database'],
        ['name' => 'ESP32 / Embarqué', 'percent' => 70, 'icon' => 'fas fa-microchip']
    ];
}

// ------------------------------------------------------------
// 5. FONCTION RETOURNANT LES PROJETS
// ------------------------------------------------------------

/**
 * Fonction getProjects()
 * Retourne la liste complète des projets du portfolio
 * 
 * @return array Tableau multidimensionnel des projets
 * 
 * Structure d'un projet :
 * - 'title'       : Titre du projet (avec emoji)
 * - 'description' : Description détaillée
 * - 'tech'        : Tableau des technologies utilisées
 * - 'image'       : Nom du fichier image
 * - 'hasImage'    : Booléen (true = image existe)
 * 
 * Note : Les images doivent être placées dans le dossier approprié
 */
function getProjects() {
    return [
        [
            'title' => '🌾 AgroConnect',
            'description' => 'Solution innovante connectant les agriculteurs aux marchés et aux services agricoles via une plateforme digitale intuitive.',
            'tech' => ['PHP', 'MySQL', 'JavaScript', 'HTML/CSS'],
            'image' => 'a.jpeg',
            'hasImage' => true
        ],
        [
            'title' => '📡 Système IoT Embarqué',
            'description' => "Utilisation d'ESP32 et capteurs pour créer un système de surveillance connecté avec transmission de données en temps réel.",
            'tech' => ['ESP32', 'Arduino', 'C++', 'MQTT'],
            'image' => 'iot.jpeg',
            'hasImage' => true
        ],
        [
            'title' => '⚙️ Application de gestion de contacts en C',
            'description' => 'Application en C permettant d\'ajouter, modifier, supprimer et rechercher des numéros de téléphone dans une base de données MySQL. Interface console intuitive et gestion des erreurs.',
            'tech' => ['Langage C', 'MySQL', 'CRUD'],
            'image' => 'c.PNG',
            'hasImage' => true
        ],
        [
            'title' => '🌍 AfrikDev',
            'description' => 'Plateforme collaborative pour développeurs africains. Conception des pages principales, structure HTML/CSS moderne, accessibilité et responsive design pour une expérience optimale sur tous les appareils.',
            'tech' => ['HTML5', 'CSS3', 'Responsive', 'Collaboratif'],
            'image' => 's.png',
            'hasImage' => true
        ],
        [
            'title' => '🔄 Portfolio Dynamique',
            'description' => 'Site web portfolio personnel avec thème clair/sombre, animations au scroll, et interface moderne entièrement responsive.',
            'tech' => ['PHP', 'HTML5/CSS3', 'JavaScript', 'Fetch API'],
            'image' => 'site.PNG',
            'hasImage' => true
        ],
        [
            'title' => '🔄 Reverse Proxy Nginx',
            'description' => 'Étude et mise en place d\'un reverse-proxy (proxy inverse) avec le serveur web Nginx. Configuration avancée pour la répartition de charge et la sécurité.',
            'tech' => ['Nginx', 'Reverse Proxy', 'Linux', 'Load Balancing', 'SSL/TLS'],
            'image' => 'reverse.png',
            'hasImage' => true
        ],
        [
            'title' => '💾 Serveur Proxy & Cache Squid',
            'description' => 'Mise en place d\'un serveur proxy et proxy-cache avec Squid et SquidGuard : théorie, implémentation pratique et solution de contournement.',
            'tech' => ['Squid', 'SquidGuard', 'Linux', 'Proxy Cache', 'Filtrage'],
            'image' => 'proxy.png',
            'hasImage' => true
        ]
    ];
}

// ------------------------------------------------------------
// 6. FONCTION RETOURNANT LES STATISTIQUES
// ------------------------------------------------------------

/**
 * Fonction getStats()
 * Retourne les statistiques affichées sur le portfolio
 * 
 * @return array Tableau des statistiques
 * 
 * Structure d'une statistique :
 * - 'icon'   : Icône FontAwesome
 * - 'number' : Valeur à afficher (nombre ou texte court)
 * - 'label'  : Libellé descriptif
 * 
 * Utilisation : Affichées en haut de la page d'accueil
 * pour donner un aperçu rapide du profil
 */
function getStats() {
    return [
        ['icon' => 'fa-folder-open', 'number' => '7', 'label' => 'Projets réalisés'],
        ['icon' => 'fa-laptop-code', 'number' => '7', 'label' => 'Langages maîtrisés'],
        ['icon' => 'fa-clock', 'number' => '2+', 'label' => "Années d'apprentissage"]
    ];
}

// Fin du fichier
// ============================================
?>