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

<!-- =========================================================
     NOUVEAU DESIGN — STYLE ULTRA MODERNE
========================================================= -->

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

    /* Animations d'apparition */
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

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
    .animate-fadeInUp-delay-1 { animation: fadeInUp 0.8s 0.2s ease-out both; }
    .animate-fadeInUp-delay-2 { animation: fadeInUp 0.8s 0.4s ease-out both; }
    .animate-fadeInUp-delay-3 { animation: fadeInUp 0.8s 0.6s ease-out both; }
    .animate-scaleIn { animation: scaleIn 0.6s ease-out forwards; }

    /* Hero */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background: var(--navy);
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .hero-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(10, 25, 47, 0.96) 0%,
            rgba(10, 25, 47, 0.85) 50%,
            rgba(10, 25, 47, 0.5) 100%
        );
        z-index: 1;
    }

    .hero-overlay-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(to top, var(--navy), transparent);
        z-index: 2;
    }

    .hero-content {
        position: relative;
        z-index: 3;
        width: 100%;
        padding: 120px 0 80px;
    }

    .hero-title {
        font-size: clamp(2.5rem, 6vw, 5rem);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -0.03em;
        color: var(--white);
        margin: 0;
    }

    .hero-title .highlight {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
    }

    /* Search bar premium */
    .search-premium {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 16px;
        padding: 6px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        display: flex;
        gap: 6px;
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .search-premium:focus-within {
        box-shadow: 0 20px 80px rgba(245, 197, 66, 0.25);
        transform: translateY(-2px);
    }

    .search-premium input {
        flex: 1;
        border: none;
        background: var(--gray-50);
        border-radius: 12px;
        padding: 18px 24px;
        font-size: 15px;
        font-weight: 500;
        color: var(--navy);
        outline: none;
        min-width: 0;
    }

    .search-premium input::placeholder { color: var(--gray-500); }

    .search-premium button {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy);
        border: none;
        border-radius: 12px;
        padding: 18px 32px;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-premium button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 197, 66, 0.5);
    }

    /* CTA Buttons */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy);
        padding: 16px 32px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 14px;
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(245, 197, 66, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(245, 197, 66, 0.5);
    }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.08);
        color: var(--white);
        padding: 16px 32px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: var(--transition);
        border: 1.5px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .btn-outline:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--gold);
        transform: translateY(-3px);
    }

    /* Stats bar */
    .stats-bar {
        position: relative;
        z-index: 3;
        background: rgba(10, 25, 47, 0.95);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .stat-item {
        padding: 32px 24px;
        text-align: center;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        transition: var(--transition);
    }

    .stat-item:last-child { border-right: none; }

    .stat-item:hover { background: rgba(245, 197, 66, 0.05); }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--gold);
        letter-spacing: -0.02em;
        line-height: 1;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--gray-300);
        margin-top: 8px;
    }

    /* Section headers */
    .section-tag {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .section-tag-line {
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, var(--gold), var(--gold-dark));
        border-radius: 2px;
    }

    .section-tag-text {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: var(--gold-dark);
    }

    .section-title {
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 900;
        color: var(--navy);
        letter-spacing: -0.03em;
        line-height: 1.15;
        margin: 0;
    }

    /* Categories cards */
    .cat-card {
        position: relative;
        background: var(--white);
        border-radius: 20px;
        padding: 32px;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-height: 240px;
        text-decoration: none;
    }

    .cat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .cat-card:hover::before { transform: scaleX(1); }

    .cat-card:hover {
        transform: translateY(-8px);
        border-color: var(--gold);
        box-shadow: var(--shadow-strong);
    }

    .cat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        margin-bottom: 24px;
    }

    .cat-card:hover .cat-icon {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--navy);
        transform: rotate(-5deg) scale(1.05);
    }

    /* Property cards */
    .property-card {
        background: var(--white);
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        text-decoration: none;
        display: flex;
        flex-direction: column;
    }

    .property-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-strong);
        border-color: var(--gold);
    }

    .property-image {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: var(--gray-100);
    }

    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .property-card:hover .property-image img { transform: scale(1.08); }

    .property-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--white);
        backdrop-filter: blur(10px);
    }

    .property-badge.sale { background: linear-gradient(135deg, var(--gold), var(--gold-dark)); color: var(--navy); }
    .property-badge.rent { background: rgba(10, 25, 47, 0.9); }

    .property-price {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: rgba(10, 25, 47, 0.95);
        color: var(--white);
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 15px;
        backdrop-filter: blur(10px);
    }

    .property-price span { color: var(--gold); font-size: 11px; }

    /* Agency cards */
    .agency-card {
        background: var(--white);
        border-radius: 20px;
        padding: 28px;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        text-decoration: none;
    }

    .agency-card:hover {
        transform: translateY(-6px);
        border-color: var(--gold);
        box-shadow: var(--shadow-strong);
    }

    .agency-avatar {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 900;
        overflow: hidden;
        border: 2px solid rgba(245, 197, 66, 0.3);
        transition: var(--transition);
    }

    .agency-card:hover .agency-avatar {
        border-color: var(--gold);
        box-shadow: 0 0 20px rgba(245, 197, 66, 0.3);
    }

    .agency-avatar img { width: 100%; height: 100%; object-fit: cover; }

    /* CTA Final */
    .cta-final {
        position: relative;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        padding: 100px 0;
        overflow: hidden;
    }

    .cta-final::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 197, 66, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .cta-final::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 197, 66, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-item:nth-child(2) { border-right: none; }
    }

    @media (max-width: 768px) {
        .hero-content { padding: 100px 0 60px; }
        .search-premium { flex-direction: column; }
        .search-premium input { border-radius: 12px; }
        .search-premium button { width: 100%; justify-content: center; border-radius: 12px; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-item { padding: 24px 16px; }
        .stat-number { font-size: 1.8rem; }
    }

    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .hero-title { font-size: 2.2rem; }
    }

    /* Scroll indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 5;
        animation: float 2s ease-in-out infinite;
        color: rgba(255, 255, 255, 0.5);
        font-size: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
</style>

<!-- =========================================================
     1. HERO — PLEIN ÉCRAN IMMERSIF
========================================================= -->

<section class="hero-section">
    <div class="hero-bg">
        <img src="<?= htmlspecialchars($imageHero, ENT_QUOTES, 'UTF-8') ?>" alt="Immobilier de prestige">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-overlay-bottom"></div>

    <div class="hero-content">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px; width: 100%;">
            <div style="display: grid; grid-template-columns: 1fr; gap: 60px; align-items: center;">

                <div style="max-width: 780px;">
                    <!-- Tag institutionnel -->
                    <div class="animate-fadeInUp" style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 28px;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--gold); animation: pulseGlow 2s ease-in-out infinite;"></div>
                        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.25em; color: var(--gold);">
                            Plateforme immobilière de référence
                        </span>
                    </div>

                    <!-- Titre principal -->
                    <h1 class="hero-title animate-fadeInUp-delay-1">
                        Trouvez le bien<br>
                        qui vous <span class="highlight">correspond.</span>
                    </h1>

                    <!-- Sous-titre -->
                    <p class="animate-fadeInUp-delay-2" style="margin-top: 24px; font-size: 16px; line-height: 1.8; color: rgba(255,255,255,0.75); max-width: 560px; font-weight: 400;">
                        Découvrez une sélection exceptionnelle de maisons, appartements, villas, terrains et espaces professionnels partout au Sénégal. Votre prochain projet immobilier commence ici.
                    </p>

                    <!-- Barre de recherche premium -->
                    <div class="animate-fadeInUp-delay-3 search-premium" style="margin-top: 36px; max-width: 680px;">
                        <form action="/biens" method="GET" style="display: flex; gap: 6px; width: 100%; flex: 1;">
                            <div style="flex: 1; display: flex; align-items: center; gap: 12px; padding-left: 8px;">
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

                    <!-- Actions rapides -->
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
    </div>

    <!-- Indicateur de scroll -->
    <div class="scroll-indicator">
        <span>Découvrir</span>
        <?= icon('chevron-down', 'h-5 w-5') ?>
    </div>
</section>

<!-- Stats intégrées -->
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
     2. CATÉGORIES IMMOBILIÈRES
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
            <a href="/biens" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 800; color: var(--navy); text-decoration: none; transition: var(--transition);">
                Voir toutes les annonces
                <?= icon('arrow-right', 'h-4 w-4') ?>
            </a>
        </div>

        <?php if (empty($categoriesAccueil)): ?>
            <div style="text-align: center; padding: 60px 24px; background: var(--white); border-radius: 20px; border: 1px solid var(--gray-200);">
                <div style="width: 64px; height: 64px; margin: 0 auto; border-radius: 16px; background: var(--gray-100); display: flex; align-items: center; justify-content: center; color: var(--navy);">
                    <?= icon('house', 'h-7 w-7') ?>
                </div>
                <p style="margin-top: 16px; color: var(--gray-500); font-weight: 600;">Aucune catégorie disponible pour le moment.</p>
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
                <div style="width: 72px; height: 72px; margin: 0 auto; border-radius: 20px; background: var(--white); display: flex; align-items: center; justify-content: center; color: var(--gold-dark); box-shadow: var(--shadow-soft);">
                    <?= icon('house', 'h-8 w-8') ?>
                </div>
                <h3 style="