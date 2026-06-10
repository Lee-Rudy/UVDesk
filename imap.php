<?php

//test imap pour tester la connexion imap , GMail de google account de l'user
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();

if (file_exists(__DIR__ . '/.env')) {
    $dotenv->load(__DIR__ . '/.env');
}

if (file_exists(__DIR__ . '/.env.local')) {
    $dotenv->load(__DIR__ . '/.env.local');
}

$host = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'ewenbruce82@gmail.com';
$password = $_ENV['GMAIL_PASSWORD'] ?? $_SERVER['GMAIL_PASSWORD'] ?? null;

if (!$password) {
    die("GMAIL_PASSWORD introuvable\n");
}

$connection = imap_open($host, $username, $password);

if ($connection) {
    echo "Connexion IMAP réussie !\n";
    imap_close($connection);
} else {
    echo "Échec\n";
    echo imap_last_error() . "\n";
}