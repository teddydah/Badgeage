<?php

namespace App\DataFixtures;

use App\DataFixtures\Data\IlotData;
use App\DataFixtures\Data\PrinterData;
use App\Entity\Ilot;
use App\Entity\Printer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $connection;

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
    }

    public function load(ObjectManager $manager): void
    {
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

        $manager->flush();
    }
}
