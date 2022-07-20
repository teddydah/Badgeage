<?php

namespace App\Repository;

use App\Entity\OrdreFab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrdreFab>
 *
 * @method OrdreFab|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdreFab|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdreFab[]    findAll()
 * @method OrdreFab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdreFabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdreFab::class);
    }

    public function add(OrdreFab $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OrdreFab $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return float|int|mixed|string
     *
     * Récupère les OF par date des 7 derniers jours
     */
    public function findByDate()
    {
        $date = date("Y/m/d h:i:s", strtotime("-8 days"));
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->andWhere('o.dateEcheance >= :date ')
            ->setParameter('date', $date)
            ->orderBy('o.dateEcheance', 'DESC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return OrdreFab[] Returns an array of OrdreFab objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrdreFab
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
