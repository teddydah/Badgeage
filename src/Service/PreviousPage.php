<?php

namespace App\Service;

class PreviousPage
{
    public function pagePrecedente(): string
    {
        // URL de la page d'accueil
        define("HOME", "http://localhost/badgeage/public/");

        // nomURL => îlots
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

        // URL page courante
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $uri = $url;

        foreach (NOM_URL as $nomURL) {
            // Si l'URL correspond à celle de la page "http://localhost/badgeage/public/badgeage/$nomURL/view" :
            // $uri = URL page d'accueil
            if ($url == HOME . "badgeage/" . $nomURL . "/view") {
                $uri = HOME;
                // Sinon si l'URL correspond à celle de la page "$nomURL/impression" :
                // $uri = URL page "http://localhost/badgeage/public/badgeage/$nomURL/view"
            } else if ($url == HOME . $nomURL . "/impression") {
                $uri = HOME . "badgeage/" . $nomURL . "/view";
            }
        }
        return $uri;
    }
}