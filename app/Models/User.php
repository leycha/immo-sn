<?php

namespace App\Models;

use Core\Database;
use Core\Validator;
use PDO;
use PDOException;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $sql = '
            SELECT
                id,
                nom,
                prenom,
                email,
                telephone,
                password,
                role,
                status
            FROM users
            WHERE LOWER(email) = LOWER(:email)
            LIMIT 1
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'email' => trim($email),
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function findById(string $id): ?array
    {
        $sql = '
            SELECT
                id,
                nom,
                prenom,
                email,
                telephone,
                ville,
                role,
                status,
                bio,
                is_verified,
                quartier,
                photo_profil,
                created_at,
                updated_at,
                last_login
            FROM users
            WHERE id = :id
            LIMIT 1
        ';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function verifyPassword(
        string $password,
        string $hash
    ): bool {
        return password_verify($password, $hash);
    }

    public static function updatePassword(
        string $id,
        string $newPassword
    ): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        if ($hash === false) {
            return false;
        }

        $sql = '
            UPDATE users
            SET password = :password,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'password' => $hash,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public static function tousPourAdministration(
        string $recherche = '',
        string $role = '',
        string $statut = ''
    ): array {
        $sql = '
            SELECT
                u.id,
                u.nom,
                u.prenom,
                u.email,
                u.telephone,
                u.ville,
                u.role,
                u.status,
                u.is_verified,
                u.created_at,
                u.last_login,
                a.nom_agence
            FROM users u
            LEFT JOIN agences a ON a.user_id = u.id
            WHERE 1 = 1
        ';

        $params = [];

        if ($recherche !== '') {
            $sql .= '
                AND (
                    u.nom ILIKE :recherche_nom
                    OR u.prenom ILIKE :recherche_prenom
                    OR u.email ILIKE :recherche_email
                    OR u.telephone ILIKE :recherche_telephone
                    OR a.nom_agence ILIKE :recherche_agence
                )
            ';

            $terme = '%' . $recherche . '%';

            $params['recherche_nom'] = $terme;
            $params['recherche_prenom'] = $terme;
            $params['recherche_email'] = $terme;
            $params['recherche_telephone'] = $terme;
            $params['recherche_agence'] = $terme;
        }

        $rolesAutorises = [
            'particulier',
            'agence',
            'moderateur',
            'admin',
        ];

        $role = strtolower(trim($role));

        if (in_array($role, $rolesAutorises, true)) {
            $sql .= ' AND LOWER(TRIM(u.role)) = :role';
            $params['role'] = $role;
        }

        $statutsAutorises = [
            'actif',
            'inactif',
            'suspendu',
        ];

        $statut = strtolower(trim($statut));

        if (in_array($statut, $statutsAutorises, true)) {
            $sql .= ' AND LOWER(TRIM(u.status)) = :statut';
            $params['statut'] = $statut;
        }

        $sql .= ' ORDER BY u.created_at DESC';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function modifierRole(
        string $id,
        string $nouveauRole
    ): bool {
        $rolesAutorises = [
            'particulier',
            'agence',
            'moderateur',
            'admin',
        ];

        $nouveauRole = strtolower(trim($nouveauRole));

        if (
            $id === ''
            || !in_array($nouveauRole, $rolesAutorises, true)
        ) {
            return false;
        }

        $sql = '
            UPDATE users
            SET role = :role,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'role' => $nouveauRole,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Modifie les informations personnelles de l'utilisateur connecté.
     * Cette méthode ne modifie ni son rôle ni son statut.
     */
    public static function modifierProfilPersonnel(
        string $id,
        array $donnees
    ): bool {
        if ($id === '') {
            return false;
        }

        $nom = trim((string) ($donnees['nom'] ?? ''));
        $prenom = trim((string) ($donnees['prenom'] ?? ''));
        $email = strtolower(trim((string) ($donnees['email'] ?? '')));
        $telephone = trim((string) ($donnees['telephone'] ?? ''));
        $ville = trim((string) ($donnees['ville'] ?? ''));
        $quartier = trim((string) ($donnees['quartier'] ?? ''));
        $bio = trim((string) ($donnees['bio'] ?? ''));

        if (
            $nom === ''
            || $prenom === ''
            || !Validator::email($email)
        ) {
            throw new \InvalidArgumentException(
                'Veuillez renseigner un nom, un prénom et une adresse e-mail valides.'
            );
        }

        $db = Database::getConnection();

        // Vérifier que l'adresse e-mail n'appartient pas à un autre compte.
        $verification = $db->prepare('
            SELECT COUNT(*)
            FROM users
            WHERE LOWER(email) = LOWER(:email)
              AND id <> :id
        ');

        $verification->execute([
            'email' => $email,
            'id' => $id,
        ]);

        if ((int) $verification->fetchColumn() > 0) {
            throw new \InvalidArgumentException(
                'Cette adresse e-mail est déjà utilisée.'
            );
        }

        $sql = '
            UPDATE users
            SET nom = :nom,
                prenom = :prenom,
                email = :email,
                telephone = :telephone,
                ville = :ville,
                quartier = :quartier,
                bio = :bio,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ';

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone !== '' ? $telephone : null,
            'ville' => $ville !== '' ? $ville : null,
            'quartier' => $quartier !== '' ? $quartier : null,
            'bio' => $bio !== '' ? $bio : null,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Modifie les informations d'un utilisateur depuis l'administration.
     */
    public static function modifierInformations(
        string $id,
        array $donnees
    ): bool {
        if ($id === '') {
            return false;
        }

        $nom = trim((string) ($donnees['nom'] ?? ''));
        $prenom = trim((string) ($donnees['prenom'] ?? ''));
        $email = trim((string) ($donnees['email'] ?? ''));
        $telephone = trim((string) ($donnees['telephone'] ?? ''));
        $ville = trim((string) ($donnees['ville'] ?? ''));
        $role = strtolower(trim((string) ($donnees['role'] ?? '')));
        $status = strtolower(trim((string) ($donnees['status'] ?? '')));

        $rolesAutorises = [
            'particulier',
            'agence',
            'moderateur',
            'admin',
        ];

        $statutsAutorises = [
            'actif',
            'inactif',
            'suspendu',
        ];

        if (
            $nom === ''
            || $prenom === ''
            || $email === ''
            || !Validator::email($email)
            || !in_array($role, $rolesAutorises, true)
            || !in_array($status, $statutsAutorises, true)
        ) {
            return false;
        }

        $db = Database::getConnection();

        // Refuser une adresse e-mail déjà utilisée par un autre compte.
        $verification = $db->prepare('
            SELECT COUNT(*)
            FROM users
            WHERE LOWER(email) = LOWER(:email)
              AND id <> :id
        ');

        $verification->execute([
            'email' => $email,
            'id' => $id,
        ]);

        if ((int) $verification->fetchColumn() > 0) {
            throw new \InvalidArgumentException(
                'Cette adresse e-mail est déjà utilisée.'
            );
        }

        $sql = '
            UPDATE users
            SET nom = :nom,
                prenom = :prenom,
                email = :email,
                telephone = :telephone,
                ville = :ville,
                role = :role,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ';

        $stmt = $db->prepare($sql);

        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone !== '' ? $telephone : null,
            'ville' => $ville !== '' ? $ville : null,
            'role' => $role,
            'status' => $status,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public static function tousPourAdministrationPagines(
        string $recherche = '',
        string $role = '',
        string $statut = '',
        int $limite = 6,
        int $page = 1
    ): array {
        $limite = max(1, min($limite, 100));
        $page = max(1, $page);
        $offset = ($page - 1) * $limite;

        $where = ' WHERE 1 = 1';
        $params = [];

        if ($recherche !== '') {
            $where .= '
                AND (
                    u.nom ILIKE :recherche_nom
                    OR u.prenom ILIKE :recherche_prenom
                    OR u.email ILIKE :recherche_email
                    OR u.telephone ILIKE :recherche_telephone
                    OR a.nom_agence ILIKE :recherche_agence
                )
            ';

            $terme = '%' . $recherche . '%';

            $params['recherche_nom'] = $terme;
            $params['recherche_prenom'] = $terme;
            $params['recherche_email'] = $terme;
            $params['recherche_telephone'] = $terme;
            $params['recherche_agence'] = $terme;
        }

        $rolesAutorises = [
            'particulier',
            'agence',
            'moderateur',
            'admin',
        ];

        $role = strtolower(trim($role));

        if (in_array($role, $rolesAutorises, true)) {
            $where .= ' AND LOWER(TRIM(u.role)) = :role';
            $params['role'] = $role;
        }

        $statutsAutorises = [
            'actif',
            'inactif',
            'suspendu',
        ];

        $statut = strtolower(trim($statut));

        if (in_array($statut, $statutsAutorises, true)) {
            $where .= ' AND LOWER(TRIM(u.status)) = :statut';
            $params['statut'] = $statut;
        }

        $db = Database::getConnection();

        // Compter tous les résultats correspondant aux filtres.
        $sqlTotal = '
            SELECT COUNT(*)
            FROM users u
            LEFT JOIN agences a ON a.user_id = u.id
        ' . $where;

        $stmtTotal = $db->prepare($sqlTotal);
        $stmtTotal->execute($params);
        $total = (int) $stmtTotal->fetchColumn();

        // Récupérer uniquement les utilisateurs de la page demandée.
        $sql = '
            SELECT
                u.id,
                u.nom,
                u.prenom,
                u.email,
                u.telephone,
                u.ville,
                u.role,
                u.status,
                u.is_verified,
                u.created_at,
                u.last_login,
                a.nom_agence
            FROM users u
            LEFT JOIN agences a ON a.user_id = u.id
        ' . $where . '
            ORDER BY u.created_at DESC
            LIMIT :limite OFFSET :offset
        ';

        $stmt = $db->prepare($sql);

        foreach ($params as $nom => $valeur) {
            $stmt->bindValue(':' . $nom, $valeur);
        }

        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return [
            'utilisateurs' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'page' => $page,
            'limite' => $limite,
            'totalPages' => max(1, (int) ceil($total / $limite)),
        ];
    }

    /**
     * Récupère les statistiques globales des utilisateurs.
     *
     * Ces statistiques ne dépendent ni de la recherche,
     * ni des filtres, ni de la pagination.
     */
    public static function statistiquesAdministration(): array
    {
        $sql = '
            SELECT
                COUNT(*) AS total_utilisateurs,

                COUNT(*) FILTER (
                    WHERE LOWER(TRIM(role)) = \'admin\'
                ) AS total_admins,

                COUNT(*) FILTER (
                    WHERE LOWER(TRIM(role)) = \'agence\'
                ) AS total_agences,

                COUNT(*) FILTER (
                    WHERE LOWER(TRIM(role)) = \'particulier\'
                ) AS total_particuliers,

                COUNT(*) FILTER (
                    WHERE LOWER(TRIM(role)) = \'moderateur\'
                ) AS total_moderateurs,

                COUNT(*) FILTER (
                    WHERE LOWER(TRIM(status)) = \'suspendu\'
                ) AS total_suspendus

            FROM users
        ';

        $stmt = Database::getConnection()->query($sql);

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'totalUtilisateurs' => (int) ($resultat['total_utilisateurs'] ?? 0),
            'totalAdmins' => (int) ($resultat['total_admins'] ?? 0),
            'totalAgences' => (int) ($resultat['total_agences'] ?? 0),
            'totalParticuliers' => (int) ($resultat['total_particuliers'] ?? 0),
            'totalModerateurs' => (int) ($resultat['total_moderateurs'] ?? 0),
            'totalSuspendus' => (int) ($resultat['total_suspendus'] ?? 0),
        ];
    }

    /**
     * Crée un nouveau compte particulier.
     */
    public static function creerCompte(array $donnees): string
    {
        $nom = trim((string) ($donnees['nom'] ?? ''));
        $prenom = trim((string) ($donnees['prenom'] ?? ''));
        $email = strtolower(trim((string) ($donnees['email'] ?? '')));
        $telephone = trim((string) ($donnees['telephone'] ?? ''));
        $ville = trim((string) ($donnees['ville'] ?? ''));
        $motDePasse = (string) ($donnees['password'] ?? '');

        $role = (string) ($donnees['role'] ?? 'particulier');

        // Seuls les deux rôles publics sont autorisés.
        if (!in_array($role, ['particulier', 'agence'], true)) {
            throw new \InvalidArgumentException(
                'Le type de compte sélectionné est invalide.'
            );
        }

        if (
            $nom === ''
            || $prenom === ''
            || !Validator::email($email)
            || !Validator::minLength($motDePasse, 8)
        ) {
            throw new \InvalidArgumentException(
                'Les informations fournies sont invalides.'
            );
        }

        if (self::findByEmail($email) !== null) {
            throw new \InvalidArgumentException(
                'Cette adresse e-mail est déjà utilisée.'
            );
        }

        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);

        if ($hash === false) {
            throw new \RuntimeException(
                'Impossible de sécuriser le mot de passe.'
            );
        }

        $sql = '
            INSERT INTO users (
                nom,
                prenom,
                email,
                password,
                telephone,
                ville,
                role
            )
            VALUES (
                :nom,
                :prenom,
                :email,
                :password,
                :telephone,
                :ville,
                :role
            )
            RETURNING id
        ';

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $hash,
            'telephone' => $telephone !== '' ? $telephone : null,
            'ville' => $ville !== '' ? $ville : null,
            'role' => $role,
        ]);

        return (string) $stmt->fetchColumn();
    }
}
