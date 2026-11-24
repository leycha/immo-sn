<?php

require BASE_PATH . '/app/Views/components/icon.php';

/*
|--------------------------------------------------------------------------
| DONNÉES DE LA PAGE D'ACCUEIL
|--------------------------------------------------------------------------
*/

$biensAccueil = is_array($biens ?? null) ? $biens : [];
$agencesAccueil = is_array($agences ?? null) ? $agences : [];
$resumeAccueil = is_array($resume ?? null) ? $resume : [];
$categoriesAccueil = is_array($categories ?? null) ? $categories : [];

$imageHero = 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1800&q=85';
$imageFallback = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80';

/*
|--------------------------------------------------------------------------
| STATISTIQUES RÉELLES
|--------------------------------------------------------------------------
*/

$totalBiens = (int) ($resumeAccueil['biens'] ?? 0);
$totalQuartiers = (int) ($resumeAccueil['quartiers'] ?? 0);
$totalAgences = (int) ($resumeAccueil['agences'] ?? 0);

/*
|--------------------------------------------------------------------------
| CONFIGURATION DES CATÉGORIES
|--------------------------------------------------------------------------
*/

$categoriesDesign = [
    'maison' => ['icon' => 'house', 'description' => 'Maisons familiales et résidentielles'],
    'maisons' => ['icon' => 'house', 'description' => 'Maisons familiales et résidentielles'],
    'appartement' => ['icon' => 'building-2', 'description' => 'Appartements modernes et confortables'],
    'appartements' => ['icon' => 'building-2', 'description' => 'Appartements modernes et confortables'],
    'immeuble' => ['icon' => 'building', 'description' => 'Immeubles et opportunités professionnelles'],
    'immeubles' => ['icon' => 'building', 'description' => 'Immeubles et opportunités professionnelles'],
    'bureau' => ['icon' => 'briefcase-business', 'description' => 'Bureaux et espaces professionnels'],
    'bureaux' => ['icon' => 'briefcase-business', 'description' => 'Bureaux et espaces professionnels'],
    'local-commercial' => ['icon' => 'store', 'description' => 'Locaux adaptés aux activités commerciales'],
    'local_commercial' => ['icon' => 'store', 'description' => 'Locaux adaptés aux activités commerciales'],
    'local commercial' => ['icon' => 'store', 'description' => 'Locaux adaptés aux activités commerciales'],
    'locaux-commerciaux' => ['icon' => 'store', 'description' => 'Locaux adaptés aux activités commerciales'],
    'studio' => ['icon' => 'door-open', 'description' => 'Studios modernes et fonctionnels'],
    'studios' => ['icon' => 'door-open', 'description' => 'Studios modernes et fonctionnels'],
    'terrain' => ['icon' => 'map', 'description' => 'Terrains pour vos futurs projets'],
    'terrains' => ['icon' => 'map', 'description' => 'Terrains pour vos futurs projets'],
    'villa' => ['icon' => 'house', 'description' => 'Villas et résidences haut de gamme'],
    'villas' => ['icon' => 'house', 'description' => 'Villas et résidences haut de gamme'],
];

$normaliserSlugCategorie = static function ($slug) {
    $slug = trim((string) $slug);
    if (function_exists('mb_strtolower')) {
        $slug = mb_strtolower($slug, 'UTF-8');
    } else {
        $slug = strtolower($slug);
    }
    $slug = strtr($slug, [
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'à' => 'a', 'â' => 'a', 'ä' => 'a',
        'î' => 'i', 'ï' => 'i',
        'ô' => 'o', 'ö' => 'o',
        'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c',
    ]);
    $slug = preg_replace('/[\s_]+/', '-', $slug);
    return trim($slug, '-');
};

?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    :root {
        --navy: #0A192F;
        --navy-light: #112240;
        --gold: #F5C542;
        --gold-dark: #D4A017;
        --gold-light: #FFE082;
        --white: #FFFFFF;
        --gray-50: #F8FAFC;
        --gray-100: #F1F5F9;
        --gray-200: #E2E8F0;
        --gray-300: #CBD5E1;
        --gray-500: #64748B;
        --gray-700: #334155;
        --shadow-soft: 0 4px 24px rgba(10, 25, 47, 0.06);
        --shadow-strong: 0 20px 60px rgba(10, 25, 47, 0.12);
        --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    * { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(245, 197, 66, 0.3); }
        50% { box-shadow: 0 0 40px rgba(245, 197, 66, 0.6); }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-fadeInUp-delay-1 { animation: fadeInUp 0.8s 0.2s ease-out both; }
    .animate-fadeInUp-delay-2 { animation: fadeInUp 0.8s 0.4s ease-out both; }
    .animate-fadeInUp-delay-3 { animation: fadeInUp 0.8s 0.6s ease-out both; }

    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background: var(--navy);
    }
    .hero-bg { position: absolute; inset: 0; z-index: 0; }
    .hero-bg img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
    .hero-overlay {
        position: absolute; inset: 0; z-index: 1;
        background: linear-gradient(135deg, rgba(10,25,47,0.96) 0%, rgba(10,25,47,0.85) 50%, rgba(10,25,47,0.5) 100%);
    }
    .hero-overlay-bottom {
        position: absolute; bottom: 0; left: 0; right: 0; height: 200px;
        background: linear-gradient(to top, var(--navy), transparent); z-index: 2;
    }
    .hero-content { position: relative; z-index: 3; width: 100%; padding: 120px 0 80px; }
    .hero-title {
        font-size: clamp(2.5rem, 6vw, 5rem);
        font-weight: 900; line-height: 1.05; letter-spacing: -0.03em;
        color: var(--white); margin: 0;
    }
    .hero-title .highlight {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text; animation: shimmer 3s linear infinite;
    }
    .search-premium {
        background: rgba(255,255,255,0.98); border-radius: 16px; padding: 6px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3); display: flex; gap: 6px;
        transition: var(--transition); border: 1px solid rgba(255,255,255,0.3);
    }
    .search-premium:focus-within {
        box-shadow: 0 20px 80px rgba(245,197,66,0.25); transform: translateY(-2px);
    }
    .search-premium input {
        flex: 1; border: none; background: var(--gray-50); border-radius: 12px;
        padding: 18px 24px; font-size: 15px; font-weight: 500; color: var(--navy);
        outline: none; min-width: 0;
    }
    .search-premium input::placeholder { color: var(--gray-500); }
    .search-premium button {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy); border: none; border-radius: 12px;
        padding: 18px 32px; font-weight: 800; font-size: 14px;
        cursor: pointer; transition: var(--transition); white-space: nowrap;
        display: flex; align-items: center; gap: 8px;
    }
    .search-premium button:hover {
        transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,197,66,0.5);
    }
    .btn-primary {
        display: inline-flex; align-items: center; gap: 10px;
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy); padding: 16px 32px; border-radius: 14px;
        font-weight: 800; font-size: 14px; text-decoration: none;
        transition: var(--transition); border: none; cursor: pointer;
        box-shadow: 0 4px 15px rgba(245,197,66,0.3);
    }
    .btn-primary:hover {
        transform: translateY(-3px); box-shadow: 0 12px 35px rgba(245,197,66,0.5);
    }
    .btn-outline {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.08); color: var(--white);
        padding: 16px 32px; border-radius: 14px; font-weight: 700; font-size: 14px;
        text-decoration: none; transition: var(--transition);
        border: 1.5px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px);
    }
    .btn-outline:hover {
        background: rgba(255,255,255,0.15); border-color: var(--gold); transform: translateY(-3px);
    }
    .stats-bar {
        position: relative; z-index: 3; background: rgba(10,25,47,0.95);
        border-top: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(20px);
    }
    .stats-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        max-width: 1200px; margin: 0 auto; padding: 0 24px;
    }
    .stat-item {
        padding: 32px 24px; text-align: center;
        border-right: 1px solid rgba(255,255,255,0.08); transition: var(--transition);
    }
    .stat-item:last-child { border-right: none; }
    .stat-item:hover { background: rgba(245,197,66,0.05); }
    .stat-number {
        font-size: 2.5rem; font-weight: 900; color: var(--gold);
        letter-spacing: -0.02em; line-height: 1;
    }
    .stat-label {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.15em; color: var(--gray-300); margin-top: 8px;
    }
    .section-tag { display: inline-flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .section-tag-line {
        width: 40px; height: 3px;
        background: linear-gradient(90deg, var(--gold), var(--gold-dark)); border-radius: 2px;
    }
    .section-tag-text {
        font-size: 11px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.25em; color: var(--gold-dark);
    }
    .section-title {
        font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900;
        color: var(--navy); letter-spacing: -0.03em; line-height: 1.15; margin: 0;
    }
    .cat-card {
        position: relative; background: var(--white); border-radius: 20px;
        padding: 32px; border: 1px solid var(--gray-200);
        transition: var(--transition); overflow: hidden;
        display: flex; flex-direction: column; min-height: 240px; text-decoration: none;
    }
    .cat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
        transform: scaleX(0); transform-origin: left; transition: transform 0.4s ease;
    }
    .cat-card:hover::before { transform: scaleX(1); }
    .cat-card:hover {
        transform: translateY(-8px); border-color: var(--gold); box-shadow: var(--shadow-strong);
    }
    .cat-icon {
        width: 60px; height: 60px; border-radius: 16px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        color: var(--gold); display: flex; align-items: center; justify-content: center;
        transition: var(--transition); margin-bottom: 24px;
    }
    .cat-card:hover .cat-icon {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy); transform: rotate(-5deg) scale(1.05);
    }
    .property-card {
        background: var(--white); border-radius: 24px; overflow: hidden;
        border: 1px solid var(--gray-200); transition: var(--transition);
        text-decoration: none; display: flex; flex-direction: column;
    }
    .property-card:hover {
        transform: translateY(-10px); box-shadow: var(--shadow-strong); border-color: var(--gold);
    }
    .property-image {
        position: relative; height: 240px; overflow: hidden; background: var(--gray-100);
    }
    .property-image img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .property-card:hover .property-image img { transform: scale(1.08); }
    .property-badge {
        position: absolute; top: 16px; left: 16px; padding: 6px 14px; border-radius: 8px;
        font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
        color: var(--white); backdrop-filter: blur(10px);
    }
    .property-badge.sale { background: linear-gradient(135deg, var(--gold), var(--gold-dark)); color: var(--navy); }
    .property-badge.rent { background: rgba(10,25,47,0.9); }
    .property-price {
        position: absolute; bottom: 16px; right: 16px;
        background: rgba(10,25,47,0.95); color: var(--white);
        padding: 8px 16px; border-radius: 10px; font-weight: 800; font-size: 15px;
        backdrop-filter: blur(10px);
    }
    .property-price span { color: var(--gold); font-size: 11px; }
    .agency-card {
        background: var(--white); border-radius: 20px; padding: 28px;
        border: 1px solid var(--gray-200); transition: var(--transition);
        display: flex; flex-direction: column; text-decoration: none;
    }
    .agency-card:hover {
        transform: translateY(-6px); border-color: var(--gold); box-shadow: var(--shadow-strong);
    }
    .agency-avatar {
        width: 64px; height: 64px; border-radius: 16px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        color: var(--gold); display: flex; align-items: center; justify-content: center;
        font-size: 22px; font-weight: 900; overflow: hidden;
        border: 2px solid rgba(245,197,66,0.3); transition: var(--transition);
    }
    .agency-card:hover .agency-avatar {
        border-color: var(--gold); box-shadow: 0 0 20px rgba(245,197,66,0.3);
    }
    .agency-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .cta-final {
        position: relative;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        padding: 100px 0; overflow: hidden;
    }
    .cta-final::before {
        content: ''; position: absolute; top: -50%; right: -20%;
        width: 600px; height: 600px; border-radius: 50%;
        background: radial-gradient(circle, rgba(245,197,66,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .cta-final::after {
        content: ''; position: absolute; bottom: -30%; left: -10%;
        width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(245,197,66,0.05) 0%, transparent 70%);
        pointer-events: none;
    }
    .scroll-indicator {
        position: absolute; bottom: 100px; left: 50%; transform: translateX(-50%);
        z-index: 5; animation: float 2s ease-in-out infinite;
        color: rgba(255,255,255,0.5); font-size: 12px;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .benefit-card {
        background: var(--white); border-radius: 20px; padding: 36px;
        border: 1px solid var(--gray-200); transition: var(--transition);
        display: flex; flex-direction: column;
    }
    .benefit-card:hover {
        transform: translateY(-8px); border-color: var(--gold); box-shadow: var(--shadow-strong);
    }
    .benefit-icon {
        width: 64px; height: 64px; border-radius: 18px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        color: var(--gold); display: flex; align-items: center; justify-content: center;
        margin-bottom: 24px; transition: var(--transition);
    }
    .benefit-card:hover .benefit-icon {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy); transform: scale(1.05) rotate(-3deg);
    }
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-item:nth-child(2) { border-right: none; }
    }
    @media (max-width: 768px) {
        .hero-content { padding: 100px 0 60px; }
        .search-premium { flex-direction: column; }
        .search-premium button { width: 100%; justify-content: center; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-item { padding: 24px 16px; }
        .stat-number { font-size: 1.8rem; }
    }
    @media (max-width: 640px) {
        .hero-title { font-size: 2.2rem; }
    }
</style>

<!-- =========================================================
     1. HERO
========================================================= -->

<section class="hero-section">
    <div class="hero-bg">
        <img src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>" alt="Immobilier de prestige">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-overlay-bottom"></div>

    <div class="hero-content">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px; width: 100%;">
            <div style="max-width: 780px;">
                <div class="animate-fadeInUp" style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 28px;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--gold); animation: pulseGlow 2s ease-in-out infinite;"></div>
                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.25em; color: var(--gold);">
                        Plateforme immobilière de référence
                    </span>
                </div>

                <h1 class="hero-title animate-fadeInUp-delay-1">
                    Trouvez le bien<br>
                    qui vous <span class="highlight">correspond.</span>
                </h1>

                <p class="animate-fadeInUp-delay-2" style="margin-top: 24px; font-size: 16px; line-height: 1.8; color: rgba(255,255,255,0.75); max-width: 560px;">
                    Découvrez une sélection exceptionnelle de maisons, appartements, villas, terrains et espaces professionnels partout au Sénégal. Votre prochain projet immobilier commence ici.
                </p>

                <div class="animate-fadeInUp-delay-3 search-premium" style="margin-top: 36px; max-width: 680px;">
                    <form action="/biens" method="GET" style="display: flex; gap: 6px; width: 100%; flex: 1; flex-wrap: wrap;">
                        <div style="flex: 1; display: flex; align-items: center; gap: 12px; padding-left: 8px; min-width: 200px;">
                            <span style="color: var(--gold-dark); flex-shrink: 0;">
                                <?= icon('search', 'h-5 w-5') ?>
                            </span>
                            <input type="text" name="q" placeholder="Ville, quartier, type de bien...">
                        </div>
                        <button type="submit">
                            Rechercher
                            <?= icon('arrow-right', 'h-4 w-4') ?>
                        </button>
                    </form>
                </div>

                <div class="animate-fadeInUp-delay-3" style="margin-top: 32px; display: flex; flex-wrap: wrap; gap: 12px;">
                    <a href="/biens" class="btn-primary">
                        Explorer les biens
                        <?= icon('arrow-right', 'h-4 w-4') ?>
                    </a>
                    <a href="/biens/carte" class="btn-outline">
                        <?= icon('map-pin', 'h-4 w-4') ?>
                        Voir la carte
                    </a>
                    <a href="/scanner" class="btn-outline">
                        <?= icon('qr-code', 'h-4 w-4') ?>
                        Scanner un QR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-indicator">
        <span>Découvrir</span>
        <?= icon('chevron-down', 'h-5 w-5') ?>
    </div>
</section>

<section class="stats-bar">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number"><?= number_format($totalBiens, 0, ',', ' ') ?></div>
            <div class="stat-label">Biens référencés</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= number_format($totalQuartiers, 0, ',', ' ') ?></div>
            <div class="stat-label">Quartiers couverts</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= number_format($totalAgences, 0, ',', ' ') ?></div>
            <div class="stat-label">Agences partenaires</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Accès plateforme</div>
        </div>
    </div>
</section>

<!-- =========================================================
     2. CATÉGORIES
========================================================= -->

<section style="background: var(--gray-50); padding: 96px 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; border-radius: 50%; border: 60px solid rgba(10,25,47,0.03); pointer-events: none;"></div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-bottom: 48px;">
            <div>
                <div class="section-tag">
                    <span class="section-tag-line"></span>
                    <span class="section-tag-text">Nos catégories</span>
                </div>
                <h2 class="section-title">Quel bien recherchez-vous ?</h2>
                <p style="margin-top: 12px; color: var(--gray-500); font-size: 15px; line-height: 1.7; max-width: 480px;">
                    Identifiez rapidement le type de bien correspondant à votre projet immobilier.
                </p>
            </div>
            <a href="/biens" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 800; color: var(--navy); text-decoration: none;">
                Voir toutes les annonces
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>
        </div>

        <?php if (empty($categoriesAccueil)): ?>
            <div style="text-align: center; padding: 60px 24px; background: var(--white); border-radius: 20px; border: 1px solid var(--gray-200);">
                <p style="color: var(--gray-500); font-weight: 600;">Aucune catégorie disponible pour le moment.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                <?php foreach ($categoriesAccueil as $categorie): ?>
                    <?php
                    $nomCategorie = $categorie['libelle'] ?? 'Immobilier';
                    $slugBrut = $categorie['slug'] ?? $nomCategorie;
                    $slugCategorie = $normaliserSlugCategorie($slugBrut);
                    $totalCategorie = (int) ($categorie['total'] ?? 0);
                    $designCategorie = $categoriesDesign[$slugCategorie] ?? ['icon' => 'house', 'description' => 'Découvrez nos biens immobiliers'];
                    ?>
                    <a href="/biens?category_id=<?= urlencode((string) ($categorie['id'] ?? '')) ?>" class="cat-card">
                        <div class="cat-icon">
                            <?= icon($designCategorie['icon'], 'h-7 w-7') ?>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: var(--navy); margin: 0 0 8px 0;">
                            <?= htmlspecialchars($nomCategorie, ENT_QUOTES, 'UTF-8') ?>
                        </h3>
                        <p style="font-size: 13px; line-height: 1.6; color: var(--gray-500); margin: 0; flex: 1;">
                            <?= htmlspecialchars($designCategorie['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 12px; font-weight: 700; color: var(--navy);">
                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: var(--gold); margin-right: 8px;"></span>
                                <?= number_format($totalCategorie, 0, ',', ' ') ?> bien<?= $totalCategorie > 1 ? 's' : '' ?>
                            </span>
                            <span style="color: var(--gray-300);">
                                <?= icon('arrow-up-right', 'h-5 w-5') ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- =========================================================
     3. BIENS À DÉCOUVRIR
========================================================= -->

<section style="background: var(--white); padding: 96px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-bottom: 48px;">
            <div>
                <div class="section-tag">
                    <span class="section-tag-line"></span>
                    <span class="section-tag-text">Sélection immobilière</span>
                </div>
                <h2 class="section-title">Les biens à découvrir</h2>
                <p style="margin-top: 12px; color: var(--gray-500); font-size: 15px;">Retrouvez les opportunités immobilières disponibles sur IMMO-SN.</p>
            </div>
            <a href="/biens" class="btn-primary" style="padding: 14px 24px; font-size: 13px;">
                Toutes les annonces
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>
        </div>

        <?php if (empty($biensAccueil)): ?>
            <div style="text-align: center; padding: 80px 24px; background: var(--gray-50); border-radius: 24px; border: 1px solid var(--gray-200);">
                <h3 style="font-size: 20px; font-weight: 800; color: var(--navy); margin: 0;">Aucun bien disponible</h3>
                <p style="margin-top: 8px; color: var(--gray-500); font-size: 14px;">Les nouvelles annonces apparaîtront ici prochainement.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px;">
                <?php foreach (array_slice($biensAccueil, 0, 6) as $bien): ?>
                    <?php
                    $id = $bien['id'] ?? '';
                    $photo = $bien['photo'] ?? null;
                    $photoUrl = $photo
                        ? (str_starts_with($photo, 'http') ? $photo : '/' . ltrim($photo, '/'))
                        : $imageFallback;
                    $titre = $bien['titre'] ?? 'Bien immobilier';
                    $ville = $bien['ville_nom'] ?? 'Sénégal';
                    $quartier = $bien['quartier_nom'] ?? '';
                    $prix = (float) ($bien['prix'] ?? 0);
                    $categorie = $bien['categorie_nom'] ?? 'Immobilier';
                    $typeOffre = strtolower($bien['type_offre'] ?? '');
                    $isLocation = $typeOffre === 'location';
                    ?>
                    <a href="/biens/<?= rawurlencode((string) $id) ?>" class="property-card">
                        <div class="property-image">
                            <img
                                src="<?= htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>"
                                onerror="this.src='<?= htmlspecialchars($imageFallback, ENT_QUOTES, 'UTF-8') ?>'"
                            >
                            <span class="property-badge <?= $isLocation ? 'rent' : 'sale' ?>">
                                <?= $isLocation ? 'Location' : 'À vendre' ?>
                            </span>
                            <div class="property-price">
                                <?= number_format($prix, 0, ',', ' ') ?> <span>FCFA<?= $isLocation ? '/mois' : '' ?></span>
                            </div>
                        </div>
                        <div style="padding: 24px;">
                            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gold-dark); margin-bottom: 8px;">
                                <?= htmlspecialchars($categorie, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <h3 style="font-size: 17px; font-weight: 800; color: var(--navy); margin: 0 0 12px 0; line-height: 1.4;">
                                <?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>
                            </h3>
                            <p style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--gray-500); margin: 0 0 16px 0;">
                                <?= icon('map-pin', 'h-4 w-4') ?>
                                <?= htmlspecialchars($ville, ENT_QUOTES, 'UTF-8') ?>
                                <?php if ($quartier): ?> · <?= htmlspecialchars($quartier, ENT_QUOTES, 'UTF-8') ?><?php endif; ?>
                            </p>
                            <div style="display: flex; align-items: center; gap: 20px; padding-top: 16px; border-top: 1px solid var(--gray-100); font-size: 12px; color: var(--gray-700); font-weight: 600;">
                                <?php if (!empty($bien['chambres'])): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                                        <?= icon('bed-double', 'h-4 w-4') ?>
                                        <?= (int) $bien['chambres'] ?> ch.
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($bien['surface'])): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                                        <?= icon('maximize', 'h-4 w-4') ?>
                                        <?= (int) $bien['surface'] ?> m²
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div style="margin-top: 48px; text-align: center;">
                <a href="/biens" class="btn-primary">
                    Voir toutes les annonces
                    <?= icon('arrow-right', 'h-4 w-4') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- =========================================================
     4. EXPLORER LA CARTE
========================================================= -->

<section style="background: var(--gray-50); padding: 96px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
        <div style="position: relative; overflow: hidden; background: var(--navy); border-radius: 28px; box-shadow: var(--shadow-strong);">
            <div style="display: grid; grid-template-columns: 1fr; gap: 0;" class="carte-grid">
                <div style="position: relative; min-height: 300px;">
                    <img src="https://images.unsplash.com/photo-1524666041070-9d87656c25f5?auto=format&fit=crop&w=1200&q=85"
                         alt="Carte des biens immobiliers"
                         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to right, transparent, var(--navy));"></div>
                </div>
                <div style="padding: 60px 40px; display: flex; flex-direction: column; justify-content: center;">
                    <div class="section-tag">
                        <span class="section-tag-line"></span>
                        <span class="section-tag-text" style="color: var(--gold);">Localisation</span>
                    </div>
                    <h2 style="font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 900; color: var(--white); letter-spacing: -0.02em; line-height: 1.2; margin: 0 0 16px 0;">
                        Explorez les biens<br>
                        <span style="color: var(--gold);">sur la carte interactive.</span>
                    </h2>
                    <p style="font-size: 15px; line-height: 1.8; color: rgba(255,255,255,0.7); margin: 0 0 28px 0; max-width: 480px;">
                        Visualisez les biens disponibles par quartier, repérez leur emplacement et identifiez plus facilement les opportunités correspondant à votre recherche.
                    </p>
                    <div>
                        <a href="/biens/carte" class="btn-primary">
                            <?= icon('map', 'h-4 w-4') ?>
                            Accéder à la carte
                            <?= icon('arrow-right', 'h-4 w-4') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media (min-width: 1024px) {
        .carte-grid { grid-template-columns: 5fr 7fr !important; }
    }
</style>

<!-- =========================================================
     5. AVANTAGES
========================================================= -->

<section style="background: var(--white); padding: 96px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
        <div style="text-align: center; max-width: 640px; margin: 0 auto 64px auto;">
            <div class="section-tag" style="justify-content: center;">
                <span class="section-tag-line"></span>
                <span class="section-tag-text">Notre engagement</span>
                <span class="section-tag-line"></span>
            </div>
            <h2 class="section-title">Une expérience immobilière<br>plus simple</h2>
            <p style="margin-top: 16px; color: var(--gray-500); font-size: 15px; line-height: 1.7;">
                Des outils pratiques pour faciliter la recherche, la consultation et la découverte des biens immobiliers.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            <div class="benefit-card">
                <div class="benefit-icon"><?= icon('search-check', 'h-7 w-7') ?></div>
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: var(--gold-dark); margin-bottom: 8px;">01 — Recherche</div>
                <h3 style="font-size: 20px; font-weight: 800; color: var(--navy); margin: 0 0 12px 0;">Recherche simplifiée</h3>
                <p style="font-size: 14px; line-height: 1.7; color: var(--gray-500); margin: 0;">Trouvez rapidement un bien grâce aux catégories, aux filtres et à la recherche par localisation.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon"><?= icon('map-pin', 'h-7 w-7') ?></div>
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: var(--gold-dark); margin-bottom: 8px;">02 — Localisation</div>
                <h3 style="font-size: 20px; font-weight: 800; color: var(--navy); margin: 0 0 12px 0;">Localisation précise</h3>
                <p style="font-size: 14px; line-height: 1.7; color: var(--gray-500); margin: 0;">Consultez la position des biens sur une carte interactive pour mieux préparer vos visites.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon"><?= icon('qr-code', 'h-7 w-7') ?></div>
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: var(--gold-dark); margin-bottom: 8px;">03 — Technologie</div>
                <h3 style="font-size: 20px; font-weight: 800; color: var(--navy); margin: 0 0 12px 0;">Technologie QR</h3>
                <p style="font-size: 14px; line-height: 1.7; color: var(--gray-500); margin: 0;">Accédez rapidement aux informations d'un bien grâce au système de QR code intégré.</p>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     6. AGENCES PARTENAIRES
========================================================= -->

<section style="background: var(--gray-50); padding: 96px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-bottom: 48px;">
            <div>
                <div class="section-tag">
                    <span class="section-tag-line"></span>
                    <span class="section-tag-text">Réseau professionnel</span>
                </div>
                <h2 class="section-title">Nos agences partenaires</h2>
                <p style="margin-top: 12px; color: var(--gray-500); font-size: 15px;">Découvrez les professionnels et agences immobilières présents sur IMMO-SN.</p>
            </div>
            <?php if (!empty($agencesAccueil)): ?>
                <a href="/agences" style="display: inline-flex; align-items: center; gap: 8px; background: var(--navy); color: var(--white); padding: 12px 24px; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; transition: var(--transition);">
                    Toutes les agences
                    <?= icon('arrow-right', 'h-4 w-4') ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($agencesAccueil)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
                <?php foreach (array_slice($agencesAccueil, 0, 3) as $agence): ?>
                    <?php
                    $nomAgence = $agence['nom_agence'] ?? 'Agence immobilière';
                    $initiale = strtoupper(substr(trim($nomAgence), 0, 1));
                    ?>
                    <a href="/agences/<?= rawurlencode((string) ($agence['id'] ?? '')) ?>" class="agency-card">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                            <div class="agency-avatar">
                                <?php if (!empty($agence['logo'])): ?>
                                    <img src="<?= htmlspecialchars($agence['logo'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>">
                                <?php else: ?>
                                    <?= htmlspecialchars($initiale, ENT_QUOTES, 'UTF-8') ?>
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h3 style="font-size: 16px; font-weight: 800; color: var(--navy); margin: 0 0 4px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?= htmlspecialchars($nomAgence, ENT_QUOTES, 'UTF-8') ?>
                                </h3>
                                <p style="font-size: 12px; color: var(--gray-500); margin: 0;">
                                    <?= (int) ($agence['nombre_biens'] ?? 0) ?> biens
                                </p>
                            </div>
                        </div>
                        <p style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--gray-500); margin: 0 0 16px 0;">
                            <?= icon('map-pin', 'h-4 w-4') ?>
                            <?= htmlspecialchars($agence['adresse'] ?? 'Dakar, Sénégal', ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <?php if (!empty($agence['badge_verifie'])): ?>
                            <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: var(--gold-dark); padding: 4px 10px; background: rgba(245,197,66,0.1); border-radius: 8px; margin-bottom: 16px;">
                                <?= icon('shield-check', 'h-3 w-3') ?>
                                Agence vérifiée
                            </div>
                        <?php endif; ?>
                        <div style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--navy);">Consulter l'agence</span>
                            <span style="color: var(--gray-500);">
                                <?= icon('arrow-up-right', 'h-4 w-4') ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 24px; background: var(--white); border-radius: 20px; border: 1px solid var(--gray-200);">
                <p style="color: var(--gray-500); font-weight: 600;">Aucune agence partenaire disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- =========================================================
     7. CTA FINAL
========================================================= -->

<section class="cta-final">
    <div style="position: relative; max-width: 900px; margin: 0 auto; padding: 0 24px; text-align: center; z-index: 1;">
        <div style="width: 72px; height: 72px; margin: 0 auto 28px auto; border-radius: 20px; background: rgba(245,197,66,0.15); border: 1.5px solid rgba(245,197,66,0.3); display: flex; align-items: center; justify-content: center; color: var(--gold);">
            <?= icon('house', 'h-8 w-8') ?>
        </div>
        <p style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3em; color: var(--gold); margin: 0 0 16px 0;">
            Votre projet immobilier commence ici
        </p>
        <h2 style="font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 900; color: var(--white); letter-spacing: -0.02em; line-height: 1.15; margin: 0 0 20px 0;">
            Donnez vie à votre prochain<br>
            projet immobilier.
        </h2>
        <p style="font-size: 16px; line-height: 1.8; color: rgba(255,255,255,0.7); margin: 0 auto 40px auto; max-width: 560px;">
            Explorez les annonces, comparez les biens et découvrez les opportunités qui correspondent à vos besoins partout au Sénégal.
        </p>
        <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;">
            <a href="/biens" class="btn-primary">
                <?= icon('search', 'h-4 w-4') ?>
                Commencer ma recherche
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>
            <a href="/biens/carte" class="btn-outline">
                <?= icon('map', 'h-4 w-4') ?>
                Explorer la carte
            </a>
        </div>
        <div style="margin-top: 48px; padding-top: 28px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span style="color: var(--gold);"><?= icon('shield-check', 'h-5 w-5') ?></span>
            <p style="font-size: 12px; color: rgba(255,255,255,0.6); margin: 0;">
                IMMO-SN — Votre espace de découverte immobilière au Sénégal.
            </p>
        </div>
    </div>
</section>