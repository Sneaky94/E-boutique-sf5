<?php

namespace App\Repository;

use App\Entity\AdresseLivraison;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdresseLivraison>
 *
 * @method AdresseLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdresseLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdresseLivraison[]    findAll()
 * @method AdresseLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdresseLivraison::class);
    }

    public function add(AdresseLivraison $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AdresseLivraison $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function recherche(Client $value): array
    {
        return $this->createQueryBuilder('al')
            ->where('al.client=:val')
            ->setParameter('val', $value->getId())
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return AdresseLivraison[] Returns an array of AdresseLivraison objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdresseLivraison
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
