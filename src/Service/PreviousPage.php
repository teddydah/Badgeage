<?php

namespace App\Service;

class PreviousPage
{
    public function pagePrecedente(): string
    {
        // Home
        define("HOME", "http://localhost/badgeage/public/");

        // nomURL
        define('NOM_URL', [
            'Adhesif',
            'Debit',
            'FacesPerm',
            'FacesTempo',
            'FraisagePerm',
            'FraisageTempo',
            'Magasin',
            'MiseEnFab',
            'MobilierUrbain',
            'Peinture', // TODO : sous-îlots
            'Permanente',
            'PrepaPerm',
            'Serrurerie',
            'Temporaire'
        ]);

        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $uri = $url;

        foreach (NOM_URL as $nomURL) {
            if ($url == HOME . "badgeage/" . $nomURL . "/view") {
                $uri = HOME;
            } else if ($url == HOME . $nomURL . "/impression") {
                $uri = HOME . "badgeage/" . $nomURL . "/view";
            }
        }
        return $uri;
    }
}