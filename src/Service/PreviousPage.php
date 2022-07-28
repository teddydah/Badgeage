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
            'Peinture',
            'LaqSst',
            'LaqEtiqHome',
            'LaqEtiqOF',
            'LaqEtiqRAL',
            'LaqAcc',
            'LaqPan',
            'LaqSup',
            'Permanente',
            'PrepaPerm',
            'Serrurerie',
            'Temporaire'
        ]);


        // URL page courante
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $uri = $url;

        foreach (NOM_URL as $nomURL) {
            // Badgeage
            $badgeageView = HOME . "badgeage/" . $nomURL . "/view";
            $badgeageDetail = HOME . "badgeage/" . $nomURL . "/detail";
            $badgeageDelete = HOME . "badgeage/" . $nomURL . "/delete";
            $badgeageLaquage = HOME . "badgeage/Laquage" . $nomURL;

            // Impression
            $impression = HOME . $nomURL . "/impression";

            // Options
            $optionsMenu = HOME . "options/" . $nomURL . "/menu";
            $optionsHistoriqueIlot = HOME . "options/" . $nomURL . "/HistoriqueIlot";
            $optionsHistoriqueCommande = HOME . "options/" . $nomURL . "/HistoriqueCommande";

            if ($url == $badgeageView) {
                $uri = HOME;
            } else if ($url == $impression || $url == $optionsMenu) {
                $uri = $badgeageView;
            } else if (
                $url == $optionsHistoriqueIlot ||
                $url == $optionsHistoriqueCommande ||
                $url == $optionsHistoriqueCommande . substr($url, -10) ||
                $url == $badgeageDetail) {
                $uri = $optionsMenu;
            } else if(str_contains($url, $badgeageDelete)) {
                $uri = $badgeageDetail;
            }
        }
        return $uri;
    }
}