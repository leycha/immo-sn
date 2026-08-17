
<?php

$host = "ep-old-rice-ax62ofvw-pooler.c-4.us-east-2.aws.neon.tech";
$dbname = "gestion_immobiliere";
$user = "neondb_owner";
$password = "npg_yjBd6mXb0Mvh";
$port = "5432";

try {

    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require;options='endpoint=ep-old-rice-ax62ofvw'",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie";

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}