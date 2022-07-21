<?php

namespace App\ApiCaller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiCaller
{
    /**
     * @param string $url Adresse de l'API (ex. http://srvphp:3000/badg3/recid/new)
     * @return array                 Tableau de resultat en JSON
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public static function getData(string $url): array {
        // Création du client capable de faire des requêtes HTTP
        $client = HttpClient::create();

        // Envoi de la requête à l'API
        $response = $client->request("GET", $url);

        // Retourne le résultat sous forme de tableau JSON
        return $response->toArray();
    }

}