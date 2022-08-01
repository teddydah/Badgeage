<?php

namespace App\Repository;

use App\Entity\Badgeage;
use App\Entity\Ilot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ilot>
 *
 * @method Ilot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ilot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ilot[]    findAll()
 * @method Ilot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ilot::class);
    }

    public function add(Ilot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ilot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupérer les nomURL des îlots
     *
     * @return float|int|mixed|string
     */
    public function findByNomURL()
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->select('i.nomURL');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    /**
     * Récupérer les îlots pour les affciher sur la page principale, à l'exception des "sous-îlots" de l'îlot Peinture
     *
     * @return float|int|mixed|string
     */
    public function findByIlot()
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder
            ->andWhere('i.nomAX >= 40')
            ->andWhere('i.nomAX <= 120')
            ->orWhere('i.nomAX = 160')
            ->orWhere('i.nomAX = 170')
            ->orWhere('i.nomAX = 230')
            ->orWhere('i.nomAX = 9995')
            ->orderBy('i.nomIRL');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    /**
     * Récupérer les "sous-îlots" de l'îlot principal Peinture
     *
     * @return float|int|mixed|string
     */
    public function findBySousIlotsPeinture()
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder
            ->andWhere('i.nomAX >= 132')
            ->andWhere('i.nomAX <= 140')
            ->orWhere('i.nomAX = 9999')
            ->orderBy('i.nomIRL');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

//    /**
//     * @return Ilot[] Returns an array of Ilot objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ilot
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
