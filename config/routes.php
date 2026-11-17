<?php

use Core\Router;

function registerRoutes(Router $router): void
{
    
// Public
$router->add('GET', '/', ['HomeController', 'index']);

$router->add('GET', '/biens', ['BienController', 'index']);
$router->add('GET', '/biens/carte', ['BienController', 'carte']);
$router->addDynamic(
    'GET',
    '/biens/{id}/qr',
    ['BienController', 'telechargerQr']
);
$router->addDynamic('GET', '/biens/{id}', ['BienController', 'show']);

// Agences publiques
$router->add('GET', '/agences', ['AgenceController', 'index']);
// Liste privée des biens de l'agence connectée
$router->add(
    'GET',
    '/agence/biens',
    ['AgenceController', 'mesBiens']
);
// Demander le renouvellement d'un bien expiré — Espace agence
$router->addDynamic(
    'POST',
    '/agence/biens/renouveler/{id}',
    ['AgenceController', 'demanderRenouvellement']
);
// Publier un bien — Espace agence
$router->add(
    'GET',
    '/agence/publier',
    ['AgenceController', 'publierBien']
);

$router->add(
    'POST',
    '/agence/publier',
    ['AgenceController', 'publierBien']
);
// =========================================================
// GESTION DES BIENS — ESPACE AGENCE
// =========================================================

// Consulter les détails d'un bien
$router->addDynamic(
    'GET',
    '/agence/biens/{id}',
    ['AgenceController', 'voirBien']
);

// Afficher le formulaire de modification
$router->addDynamic(
    'GET',
    '/agence/biens/{id}/modifier',
    ['AgenceController', 'modifierBien']
);

// Enregistrer les modifications
$router->addDynamic(
    'POST',
    '/agence/biens/{id}/modifier',
    ['AgenceController', 'modifierBien']
);

// Supprimer un bien
$router->addDynamic(
    'POST',
    '/agence/biens/{id}/supprimer',
    ['AgenceController', 'supprimerBien']
);
$router->add(
    'GET',
    '/agence/mon-agence',
    ['AgenceController', 'monAgence']
);
// Profil de l'agence connectée
$router->add(
    'GET',
    '/agence/profil',
    ['AgenceController', 'profil']
);
// Espace QR Codes de l'agence connectée.
$router->add(
    'GET',
    '/agence/qr-codes',
    ['AgenceController', 'mesQrCodes']
);

// Télécharger les QR Codes de l'agence connectée.
$router->add(
    'GET',
    '/agence/qr-codes/zip',
    ['AgenceController', 'telechargerQrZip']
);
// Télécharger le QR code d'un bien appartenant à l'agence.
$router->addDynamic(
    'GET',
    '/agence/qr/{id}',
    ['AgenceController', 'telechargerQr']
);
$router->add(
    'POST',
    '/agence/profil/modifier',
    ['AgenceController', 'modifierProfil']
);
// Enregistrer les modifications de l'agence du responsable connecté
$router->add(
    'POST',
    '/agence/mon-agence/modifier',
    ['AgenceController', 'modifierMonAgence']
);
$router->addDynamic('GET', '/agences/{id}', ['AgenceController', 'show']);
$router->addDynamic(
    'GET',
    '/agences/{id}/qr-zip',
    ['AgenceController', 'telechargerQrZip']
);
// Scanner
$router->add('GET', '/scanner', ['ScannerController', 'index']);
$router->addDynamic('GET', '/scan/{token}', ['ScannerController', 'resolve']);

// =========================================================
// AUTHENTIFICATION
// =========================================================

// Connexion
$router->add('GET', '/login', ['AuthController', 'login']);
$router->add('POST', '/login', ['AuthController', 'authenticate']);
// Mot de passe oublié
$router->add('GET', '/mot-de-passe-oublie', ['AuthController', 'forgotPassword']);
$router->add('POST', '/mot-de-passe-oublie', ['AuthController', 'sendResetLink']);

// Réinitialisation du mot de passe
$router->add('GET', '/reinitialiser-mot-de-passe', ['AuthController', 'resetPassword']);
$router->add('POST', '/reinitialiser-mot-de-passe', ['AuthController', 'updatePassword']);
// Inscription
$router->add('GET', '/inscription', ['AuthController', 'register']);
$router->add('POST', '/inscription', ['AuthController', 'storeRegistration']);

// Déconnexion
$router->add('GET', '/logout', ['AuthController', 'logout']);
// Member
$router->add('GET', '/member/dashboard', ['MemberController', 'dashboard']);
$router->add('GET', '/member/mes-biens', ['MemberController', 'mesBiens']);
// Télécharger le QR code d'un bien appartenant au membre.
$router->addDynamic(
    'GET',
    '/member/qr/{id}',
    ['MemberController', 'telechargerQr']
);
// Télécharger tous les QR codes du membre dans un ZIP.
$router->add(
    'GET',
    '/member/qr-codes/zip',
    ['MemberController', 'telechargerQrZip']
);
// Demander le renouvellement d'une annonce expirée
$router->addDynamic(
    'POST',
    '/member/biens/renouveler/{id}',
    ['MemberController', 'demanderRenouvellement']
);

// =========================================================
// MODIFICATION ET SUPPRESSION DES BIENS
// =========================================================

// Afficher le formulaire de modification d'un bien rejeté
$router->addDynamic(
    'GET',
    '/member/biens/modifier/{id}',
    ['MemberController', 'modifierBien']
);

// Enregistrer les corrections apportées au bien
$router->addDynamic(
    'POST',
    '/member/biens/modifier/{id}',
    ['MemberController', 'modifierBien']
);

// Supprimer un bien appartenant au membre connecté
$router->addDynamic(
    'POST',
    '/member/biens/supprimer/{id}',
    ['MemberController', 'supprimerBien']
);
$router->add('GET', '/member/publier', ['MemberController', 'publier']);
$router->add('POST', '/member/publier', ['MemberController', 'publier']);
$router->add('GET', '/member/messages', ['MemberController', 'messages']);
$router->add('GET', '/member/alertes', ['AlerteController', 'index']);

$router->add('GET', '/member/alertes/creer', ['AlerteController', 'creer']);
$router->add('POST', '/member/alertes/creer', ['AlerteController', 'creer']);

$router->addDynamic('GET', '/member/alertes/modifier/{id}', ['AlerteController', 'modifier']);
$router->addDynamic('POST', '/member/alertes/modifier/{id}', ['AlerteController', 'modifier']);

$router->addDynamic('POST', '/member/alertes/supprimer/{id}', ['AlerteController', 'supprimer']);

$router->addDynamic('POST', '/member/alertes/basculer/{id}', ['AlerteController', 'basculer']);
$router->add('GET', '/member/favoris', ['MemberController', 'favoris']);
// Ajouter un bien aux favoris
$router->add(
    'POST',
    '/member/favoris/ajouter',
    ['MemberController', 'ajouterFavori']
);

// Retirer un bien des favoris
$router->add(
    'POST',
    '/member/favoris/retirer',
    ['MemberController', 'retirerFavori']
);

$router->add('GET', '/member/profil', ['MemberController', 'profil']);
$router->add('POST', '/member/profil', ['MemberController', 'profil']);
$router->add('POST', '/member/mot-de-passe', ['MemberController', 'changerMotDePasse']);

// Messages
$router->add(
    'POST',
    '/messages/envoyer',
    ['MessageController', 'envoyer']
);

// Signalements
$router->add(
    'POST',
    '/signalements',
    ['SignalementController', 'store']
);
    // ==========================

    $router->add(
        'GET',
        '/admin',
        ['AdminController', 'index']
    );

    $router->add(
        'GET',
        '/admin/biens',
        ['AdminController', 'biens']
    );
    // =========================================================
// GESTION DES DEMANDES DE RENOUVELLEMENT — ADMINISTRATION
// =========================================================

// Afficher les demandes de renouvellement en attente
$router->add(
    'GET',
    '/admin/renouvellements',
    ['AdminController', 'renouvellements']
);

// Approuver une demande de renouvellement
$router->addDynamic(
    'POST',
    '/admin/renouvellements/{id}/approuver',
    ['AdminController', 'approuverRenouvellement']
);

// Refuser une demande de renouvellement
$router->addDynamic(
    'POST',
    '/admin/renouvellements/{id}/refuser',
    ['AdminController', 'refuserRenouvellement']
);
// =========================================================
// GESTION DES AGENCES PARTENAIRES — ADMINISTRATION
// =========================================================

// Liste des agences partenaires
$router->add(
    'GET',
    '/admin/agences',
    ['AdminController', 'agences']
);
// Consulter les détails d'une agence partenaire
$router->addDynamic(
    'GET',
    '/admin/agences/{id}/details',
    ['AdminController', 'detailsAgence']
);

// Approuver une agence partenaire
$router->addDynamic(
    'POST',
    '/admin/agences/{id}/approuver',
    ['AdminController', 'approuverAgence']
);

// Rejeter une agence partenaire
$router->addDynamic(
    'POST',
    '/admin/agences/{id}/rejeter',
    ['AdminController', 'rejeterAgence']
);


// Suspendre une agence partenaire
$router->addDynamic(
    'POST',
    '/admin/agences/{id}/suspendre',
    ['AdminController', 'suspendreAgence']
);

// Réactiver une agence suspendue
$router->addDynamic(
    'POST',
    '/admin/agences/{id}/reactiver',
    ['AdminController', 'reactiverAgence']
);
// Modifier le badge de vérification d'une agence
$router->addDynamic(
    'POST',
    '/admin/agences/{id}/badge',
    ['AdminController', 'modifierBadgeAgence']
);

    $router->addDynamic(
    'POST',
    '/admin/signalements/{id}/traiter',
    ['AdminController', 'traiterSignalement']
);

$router->addDynamic(
    'POST',
    '/admin/signalements/{id}/rejeter',
    ['AdminController', 'rejeterSignalement']
);

$router->addDynamic(
    'POST',
    '/admin/signalements/{id}/supprimer',
    ['AdminController', 'supprimerSignalement']
);
// Supprimer une agence refusée (administrateur uniquement)
$router->addDynamic(
    'POST',
    '/admin/agences/{id}/supprimer',
    ['AdminController', 'supprimerAgence']
);
    
    // =========================================================
    // MODÉRATION DES BIENS — ADMINISTRATION
    // =========================================================

    // Valider un bien en attente
    $router->addDynamic(
        'POST',
        '/admin/biens/approuver/{id}',
        ['AdminController', 'approuverBien']
    );

    // Rejeter un bien avec un motif
    $router->addDynamic(
        'POST',
        '/admin/biens/rejeter/{id}',
        ['AdminController', 'rejeterBien']
    );


    $router->add(
        'GET',
        '/admin/cron-monitor',
        ['AdminController', 'cronMonitor']
    );

    $router->add(
        'GET',
        '/admin/mail-logs',
        ['AdminController', 'mailLogs']
    );
    $router->add('GET', '/admin/signalements', ['AdminController', 'signalements']);
    $router->add(
    'GET',
    '/admin/utilisateurs',
    ['AdminController', 'utilisateurs']
);
 
    // Modifier le rôle d'un utilisateur (administrateur uniquement)
    $router->addDynamic(
        'POST',
        '/admin/utilisateurs/{id}/role',
        ['AdminController', 'modifierRoleUtilisateur']
    );
    // Afficher le formulaire de modification d'un utilisateur
$router->addDynamic(
    'GET',
    '/admin/utilisateurs/{id}/modifier',
    ['AdminController', 'modifierUtilisateur']
);

// Enregistrer les modifications d'un utilisateur
$router->addDynamic(
    'POST',
    '/admin/utilisateurs/{id}/modifier',
    ['AdminController', 'modifierUtilisateur']
);

// Supprimer un utilisateur
$router->addDynamic(
    'POST',
    '/admin/utilisateurs/{id}/supprimer',
    ['AdminController', 'supprimerUtilisateur']
);
 

// =========================================================
// ESPACE MODÉRATEUR — IMMO-SN
// =========================================================

$router->add(
    'GET',
    '/moderateur',
    ['ModerateurController', 'index']
);

$router->add(
    'GET',
    '/moderateur/annonces',
    ['ModerateurController', 'annonces']
);
// =========================================================
// GESTION DES DEMANDES DE RENOUVELLEMENT
// =========================================================

// Afficher les demandes de renouvellement en attente
$router->add(
    'GET',
    '/moderateur/renouvellements',
    ['ModerateurController', 'renouvellements']
);

// Approuver une demande de renouvellement
$router->addDynamic(
    'POST',
    '/moderateur/renouvellements/{id}/approuver',
    ['ModerateurController', 'approuverRenouvellement']
);

// Refuser une demande de renouvellement
$router->addDynamic(
    'POST',
    '/moderateur/renouvellements/{id}/refuser',
    ['ModerateurController', 'refuserRenouvellement']
);
// Approuver une annonce depuis l'espace modérateur.
$router->addDynamic(
    'POST',
    '/moderateur/annonces/{id}/approuver',
    ['ModerateurController', 'approuverBien']
);

// Rejeter une annonce depuis l'espace modérateur.
$router->addDynamic(
    'POST',
    '/moderateur/annonces/{id}/rejeter',
    ['ModerateurController', 'rejeterBien']
);
// Historique des décisions de modération
$router->add(
    'GET',
    '/moderateur/historique',
    ['ModerateurController', 'historique']
);
// Liste des signalements
$router->add(
    'GET',
    '/moderateur/signalements',
    ['ModerateurController', 'signalements']
);

// Marquer un signalement comme traité
$router->addDynamic(
    'POST',
    '/moderateur/signalements/{id}/traiter',
    ['ModerateurController', 'traiterSignalement']
);

// Rejeter un signalement
$router->addDynamic(
    'POST',
    '/moderateur/signalements/{id}/rejeter',
    ['ModerateurController', 'rejeterSignalement']
);

// Archiver un signalement traité ou rejeté
$router->addDynamic(
    'POST',
    '/moderateur/signalements/{id}/supprimer',
    ['ModerateurController', 'supprimerSignalement']
);

// Afficher le profil du modérateur
$router->add(
    'GET',
    '/moderateur/profil',
    ['ModerateurController', 'profil']
);

// Modifier le mot de passe du modérateur
$router->add(
    'POST',
    '/moderateur/profil/mot-de-passe',
    ['ModerateurController', 'changerMotDePasse']
);
// Modifier les informations personnelles du modérateur
$router->add(
    'POST',
    '/moderateur/profil/modifier',
    ['ModerateurController', 'modifierProfil']
);
}