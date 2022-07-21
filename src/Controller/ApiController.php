<?php

namespace App\Controller;

use App\ApiCaller\ApiCaller;
use App\Entity\Article;
use App\Entity\Badgeage;
use App\Entity\Ilot;
use App\Entity\LigneOF;
use App\Entity\OrdreFab;
use App\Repository\ArticleRepository;
use App\Repository\BadgeageRepository;
use App\Repository\IlotRepository;
use App\Repository\LigneOFRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiController extends AbstractController
{
    private const URL_BASE = "http://srvphp:3000/badg3";
    private const URL_GET_ONE_ARTICLE = self::URL_BASE . "/get/art/"; // + artId


    /**
     * Récupère un nouveau recid pour la table SLF_BADGEAGE.
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getNewRecidBadgeageAX(): array
    {
        return ApiCaller::getData(self::URL_BASE . "/recid/new");
    }


    /**
     * Récupère un badgeage selon son numéro d'OF et son ilot AX.
     *
     * @param Ilot $ilot
     * @param OrdreFab $ordreFab
     * @param Badgeage|null $badgeage
     * @return Badgeage
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getOneBadgeage(Ilot $ilot, OrdreFab $ordreFab, Badgeage $badgeage = null): Badgeage
    {
        // ex. http://srvphp:3000/api/badg3/get/40/C125333-1
        $data = ApiCaller::getData(self::URL_BASE . "/get/" . $ilot->getNomAX() . "/" . $ordreFab->getNumero());

        $badgeage->setOrdreFab($ordreFab);
        $badgeage->setIlot($ilot);
        $badgeage->setDateBadgeage($data['dateDebut']);


        return $badgeage;
    }


    /**
     * Récupère un badgeage selon son ilot AX.
     *
     * @param EntityManagerInterface $emi
     * @param BadgeageRepository $repo
     * @param IlotRepository $repoIlot
     * @param int $ilotAX
     * @return ArrayCollection
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBadgeageByIlot(EntityManagerInterface $emi, BadgeageRepository $repo, IlotRepository $repoIlot, int $ilotAX): ArrayCollection
    {
        // ex. http://srvphp:3000/api/badg3/get/ilot/40
        // ex. http://srvphp:3000/api/badg3/of/get/of/C125333-1
        $listeObj = new ArrayCollection();
        $data = ApiCaller::getData(self::URL_BASE . "/get/ilot/" . $ilotAX);

        foreach ($data as $d) {
            // OrdreFab + LignesOF + Client + Article
            $ordreFab = $repo->findOneBy(["numero" => $data["codebarre"]]);

            // todo: si null, le construire de A-Z, ainsi que le client, etc.
            if (null === $ordreFab) {
                //todo: getOdreFabDepuisAX($codebarre)
                $dataOF = ApiCaller::getData(self::URL_BASE . "/of/get/of/" . $data["codebarre"]);
//                $ordreFab = $this->OrdreFabBuilder($data);
            }

            // Badgeage
            $badgeage = new Badgeage();
            $ilot = $repoIlot->findOneBy(["nomAX" => $ilotAX]);

            // si ilot == null, alors erreur... ya pas de table ilot dans ax
            if (null == $ilot) {
                throw new \Exception('Ilot ' . $ilotAX . ' inconnu.');     // todo: faire des exceptions personnalisées
            }

            $badgeage->setIlot($ilot);
            $badgeage->setOrdreFab($ordreFab);
            $badgeage->setDateBadgeage($data['dateDebut']);
            $badgeage->setRecid($data['recid']);

            $listeObj->add($badgeage);
        }

        return $listeObj;
    }

//    /**
//     * Récupère un badgeage selon son ilot AX.
//     *
//     * @param EntityManagerInterface $emi
//     * @param BadgeageRepository     $repo
//     * @param Ilot                   $ilot
//     * @param OrdreFab|null          $ordreFab
//     * @param Badgeage|null          $badgeage
//     * @return ArrayCollection
//     * @throws ClientExceptionInterface
//     * @throws DecodingExceptionInterface
//     * @throws RedirectionExceptionInterface
//     * @throws ServerExceptionInterface
//     * @throws TransportExceptionInterface
//     */
//    public function getBadgeageByIlot(EntityManagerInterface $emi, BadgeageRepository $repo, Ilot $ilot, OrdreFab $ordreFab = null, Badgeage $badgeage = null): ArrayCollection {
//        // ex. http://srvphp:3000/api/badg3/get/ilot/40
//        $listeObj = new ArrayCollection();
//        $data     = ApiCaller::getData(self::URL_BASE . "/get/ilot/" . $ilot->getNomAX());
//
//        foreach ($data as $d) {
//            // OrdreFab + LignesOF + Client + Article
//            if (null === $ordreFab) {
//                $ordreFab = $repo->findOneBy(["numero" => $data["codebarre"]]);
//
//                // todo: si tjs null, le construire de A-Z, ainsi que le client, etc.
//            }
//
//            // Badgeage
//            $badgeage->setIlot($ilot);
//            $badgeage->setOrdreFab($ordreFab);
//            $badgeage->setDateBadgeage($data['dateDebut']);
//            $badgeage->setRecid($data['recid']);
//
//            $listeObj->add($badgeage);
//        }
//
//        return $listeObj;
//    }

    private static function OrdreFabBuilder(LigneOFRepository $repoLigne, ArticleRepository $repoArticle, $dataFromApi)
    {
        $ordre = new OrdreFab();
        $lignesOF = new ArrayCollection();

        foreach ($dataFromApi as $data) {
            $ligne = new LigneOF();
            $article = new Article();

            // get article from MYSQL
            $article = $repoArticle->findOneBy(["code" => $data["art_id"]]);

            // if article == null
            $article = ApiCaller::getData(self::URL_BASE . "/get/art/" . $data['art_id']);


//            $ligne->setRecid($data["recid"]);
//            $ligne->setNumLigne($data["num_ligne"]);
//            $ligne->setArticle($article);
        }
    }

}
