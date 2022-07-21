<?php

namespace App\Repository;

use App\Entity\Badgeage;
use App\Entity\Ilot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Badgeage>
 *
 * @method Badgeage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badgeage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badgeage[]    findAll()
 * @method Badgeage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badgeage::class);
    }

    public function add(Badgeage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Badgeage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return float|int|mixed|string
     *
     * Récupère les badgeages par îlot et par date pour les 7 derniers jours
     */
    public function findBadgeage(Ilot $ilot)
    {
        $date = date("Y/m/d h:i:s", strtotime("-8 days"));
        $queryBuilder = $this->createQueryBuilder('b');
        $queryBuilder
            ->andWhere('b.ilot = :ilot')
            ->andWhere('b.dateBadgeage >= :date')
            ->setParameter('date', $date)
            ->setParameter('ilot', $ilot)
            ->orderBy('b.dateBadgeage', 'DESC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Badgeage[] Returns an array of Badgeage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Badgeage
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
