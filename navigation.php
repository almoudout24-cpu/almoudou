<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav>
    <div class="nav-container">
        <a href="index.php" class="logo">AT</a>
        <ul class="nav-links">
            <li><a href="index.php" <?php echo $current_page == 'index.php' ? 'class="active"' : ''; ?>>Accueil</a></li>
            <li><a href="a-propos.php" <?php echo $current_page == 'a-propos.php' ? 'class="active"' : ''; ?>>À propos</a></li>
            <li><a href="projets.php" <?php echo $current_page == 'projets.php' ? 'class="active"' : ''; ?>>Projets</a></li>
            <li><a href="contact.php" <?php echo $current_page == 'contact.php' ? 'class="active"' : ''; ?>>Contact</a></li>
        </ul>
        <button class="theme-toggle" id="themeToggle">
            <i class="fas <?php echo getTheme() == 'light' ? 'fa-moon' : 'fa-sun'; ?>"></i>
        </button>
    </div>
</nav>