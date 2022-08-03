<?php

namespace App\Service;

use App\Repository\IlotRepository;

class PreviousPage
{
    public function pagePrecedente(IlotRepository $ilotRepository): string
    {
        // URL de la page d'accueil
        define("HOME", "http://" . $_SERVER['HTTP_HOST'] . "/badgeage/public/");

        // URL de la page d'accueil admin
        define("ADMIN", "http://localhost/badgeage/public/admin/");

        // URL page courante
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $uri = $url;

        $ilots = $ilotRepository->findByNomURL();

        foreach ($ilots as $nomURL) {
            // Badgeage
            $badgeageView = HOME . "badgeage/" . current($nomURL) . "/view";
            $badgeageDetail = HOME . "badgeage/" . current($nomURL) . "/detail";
            $badgeageDelete = HOME . "badgeage/" . current($nomURL) . "/delete";

            // Peinture
            $peintureView = HOME . "badgeage/Peinture/view";
            $laqSstView = HOME . "badgeage/LaqSst/view";
            $laqAccView = HOME . "badgeage/LaqAcc/view";
            $laqPanView = HOME . "badgeage/LaqPan/view";
            $laqSupView = HOME . "badgeage/LaqSup/view";
            $laqEtiqHome = HOME . "badgeage/Laquage/LaqEtiqHome";

            $laqEtiqOF = HOME . "impression/LaqEtiqOF";
            $laqEtiqRAL = HOME . "impression/LaqEtiqRAL";

            // Impression
            $impression = HOME . "impression/" . current($nomURL);
            $impressionMiseEnFab = HOME . "impression/MiseEnFab";

            // Photo
            $photo = HOME . "photo/" . current($nomURL);
            $photoSelect = HOME . "photo/" . current($nomURL);

            // Options
            $optionsMenu = HOME . "options/" . current($nomURL) . "/menu";
            $optionsHistoriqueIlot = HOME . "options/" . current($nomURL) . "/HistoriqueIlot";
            $optionsHistoriqueCommande = HOME . "options/" . current($nomURL) . "/HistoriqueCommande";

            // Admin
            $adminSettings = ADMIN . "settings";

            // Admin - ilots
            $adminIlots = ADMIN . "ilots/index";
            $ilotRead = ADMIN . "ilot/" . current($nomURL);
            $ilotUpdate = ADMIN . "ilot/" . current($nomURL) . "/edit";
            $ilotsAdd = ADMIN . "ilots/add";

            // Admin - printers
            $adminPrinters = ADMIN . "printers/index";
            $printersAdd = ADMIN . "printers/add";
            $adminPrinter = ADMIN . "printer/";

            if ($url == $badgeageView || $url == $impressionMiseEnFab) {
                $uri = HOME;
            } else if (
                $url == $laqSstView ||
                $url == $laqAccView ||
                $url == $laqPanView ||
                $url == $laqSupView ||
                $url == $laqEtiqHome) {
                $uri = $peintureView;
            } else if ($url == $laqEtiqOF || $url == $laqEtiqRAL) {
                $uri = $laqEtiqHome;
            } else if (
                $url == $impression ||
                $url == $photo ||
                $url == $optionsMenu) {
                $uri = $badgeageView;
                // Si l'URL de la page courante (exemple : "http://localhost/badgeage/public/badgeage/Adhesif/delete/id")
                // contient "badgeage/$nomURL"/delete (astuce par rapport à l'id)
                // => c'est qu'il s'agit de la page de suppression d'un badgeage
                // => donc "Précédent" pointe vers la page de détail d'un badgeage
            } else if (str_contains($url, $badgeageDelete)) {
                $uri = $badgeageDetail;
            } else if (
                $url == $optionsHistoriqueIlot ||
                $url == $optionsHistoriqueCommande ||
                $url == $badgeageDetail ||
                str_contains($url, $optionsHistoriqueCommande)) {
                $uri = $optionsMenu;
            } else if (
                $url == $adminSettings ||
                $url == $adminIlots ||
                $url == $adminPrinters) {
                $uri = ADMIN;
            } else if (
                $url == $ilotRead ||
                $url == $ilotUpdate ||
                $url == $ilotsAdd) {
                $uri = $adminIlots;
            } else if (
                str_contains($url, $adminPrinter) ||
                $url == $printersAdd) {
                $uri = $adminPrinters;
            } else if (
                str_contains($url, $photoSelect) &&
                str_contains($url, 'select')) {
                $uri = $photo;
            }
        }
        return $uri;
    }
}