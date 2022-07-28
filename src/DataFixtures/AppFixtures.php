<?php

namespace App\DataFixtures;

use App\DataFixtures\Data\AdresseData;
use App\DataFixtures\Data\ClientData;
use App\DataFixtures\Data\IlotData;
use App\DataFixtures\Data\OrdreFabData;
use App\DataFixtures\Data\PrinterData;
use App\DataFixtures\Data\UserData;
use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\Ilot;
use App\Entity\OrdreFab;
use App\Entity\Printer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * Réinitialisation des index lors du vidage des tables
     */
    private function truncate(): void
    {
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        $this->connection->executeQuery('TRUNCATE TABLE adresse');
        $this->connection->executeQuery('TRUNCATE TABLE article');
        $this->connection->executeQuery('TRUNCATE TABLE badgeage');
        $this->connection->executeQuery('TRUNCATE TABLE client');
        $this->connection->executeQuery('TRUNCATE TABLE ilot');
        $this->connection->executeQuery('TRUNCATE TABLE ligne_of');
        $this->connection->executeQuery('TRUNCATE TABLE ordonnancement');
        $this->connection->executeQuery('TRUNCATE TABLE ordre_fab');
        $this->connection->executeQuery('TRUNCATE TABLE printer');
        $this->connection->executeQuery('TRUNCATE TABLE status');
        $this->connection->executeQuery('TRUNCATE TABLE user');
    }

    public function load(ObjectManager $manager): void
    {
        date_default_timezone_set('Europe/Paris');

        $this->truncate();

        $printerList = [];

        foreach (PrinterData::$printerData as $data) {
            $printer = new Printer();

            $printer->setNom($data['nom']);
            $printer->setIp($data['ip']);
            $printer->setPort(9100);

            $printerList[$data['nom']] = $printer;

            $manager->persist($printer);
        };

        foreach (IlotData::$ilotData as $data) {
            $ilot = new Ilot();

            $ilot->setNomAX($data['nom_ax']);
            $ilot->setNomIRL($data['nom_irl']);
            $ilot->setNomURL($data['nom_url']);
            $ilot->setInitiales($data['initiales']);
            $ilot->setPrinter($printerList[$data['printer']]);

            $manager->persist($ilot);
        };

        $clientList = [];

        foreach (ClientData::$clientData as $data) {
            $client = new Client();

            $client->setNom($data['nom']);
            $client->setNumero($data['numero']);
            $client->setRecid($data['recid']);

            $clientList[$data['nom']] = $client;

            $manager->persist($client);
        };

        $adresseList = [];

        foreach (AdresseData::$adresseData as $data) {
            $adresse = new Adresse();

            $adresse->setRecid($data['recid']);
            $adresse->setCodePostal($data['code_postal']);
            $adresse->setVille($data['ville']);
            $adresse->setFullAddress($data['full_address']);
            $adresse->setCodePays($data['code_pays']);

            $adresseList[$data['recid']] = $adresse;

            $manager->persist($adresse);
        };

        foreach (OrdreFabData::$ordreFabData as $data) {
            $ordreFab = new OrdreFab();

            $ordreFab->setNumero($data['numero']);
            $ordreFab->setDateEcheance(new \DateTime());
            $ordreFab->setClient($clientList[$data['client']]);
            $ordreFab->setAdresseLivraison($adresseList[$data['adresse']]);

            $manager->persist($ordreFab);
        };

        foreach (UserData::$userData as $data) {
            $user = new User();

            $user->setEmail($data['email']);
            $user->setRoles([$data['roles']]);
            $user->setPassword($data['password']);

            $manager->persist($user);
        };

        $manager->flush();
    }
}
