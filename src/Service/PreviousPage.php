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

            // Impression
            $impression = HOME . "impression/" . $nomURL;

            // Options
            $optionsMenu = HOME . "options/" . $nomURL . "/menu";
            $optionsHistoriqueIlot = HOME . "options/" . $nomURL . "/HistoriqueIlot";
            $optionsHistoriqueCommande = HOME . "options/" . $nomURL . "/HistoriqueCommande";

            // Peinture
            $peintureView = HOME . "badgeage/Peinture/view";
            $laqSstView = HOME . "badgeage/LaqSst/view";
            $laqAccView = HOME . "badgeage/LaqAcc/view";
            $laqPanView = HOME . "badgeage/LaqPan/view";
            $laqSupView = HOME . "badgeage/LaqSup/view";
            $laqEtiqHome = HOME . "badgeage/Laquage/LaqEtiqHome";
            $laqEtiqOF = HOME . "badgeage/LaqEtiqOF/view";
            $laqEtiqRAL = HOME . "badgeage/LaqEtiqRAL/view";

            if ($url == $badgeageView) {
                $uri = HOME;
            } else if (
                $url == $impression || $url == $optionsMenu) {
                $uri = $badgeageView;
            } else if (str_contains($url, $badgeageDelete)) {
                $uri = $badgeageDetail;
            } else if (
                $url == $optionsHistoriqueIlot ||
                $url == $optionsHistoriqueCommande ||
                $url == $badgeageDetail ||
                str_contains($url, $optionsHistoriqueCommande)) {
                $uri = $optionsMenu;
            } else if (
                $url == $laqSstView ||
                $url == $laqAccView ||
                $url == $laqPanView ||
                $url == $laqSupView ||
                $url == $laqEtiqHome) {
                $uri = $peintureView;
            } else if ($url == $laqEtiqOF || $url == $laqEtiqRAL) {
                $uri = $laqEtiqHome;
            }
        }
        return $uri;
    }
}