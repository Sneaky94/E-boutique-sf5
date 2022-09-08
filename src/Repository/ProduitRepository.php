<?php

namespace App\Repository;

use App\Entity\Detail;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Produit[] Returns an array of Produit objects
        SELECT p.*
        FROM produit p
        WHERE p.titre LIKE '%les%'
     */

    public function recherche($value): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.titre LIKE :val')
            ->setParameter('val', "%$value%")
            ->orderBy('p.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
        SELECT d.produit_id, count(*) as nb
        FROM produit p
        JOIN detail d ON p.id = d.produit_id
        GROUP BY d.produit_id
        ORDER BY 2 DESC
        LIMIT 1
     */
    public function produitBestSeller()
    {
        return $this->createQueryBuilder("p")
            ->join(Detail::class, "d", "WITH", "p.id = d.produit")
            ->groupBy("p.id")
            ->select("p as produit", "count(d) as nb")
            ->orderBy("nb", "DESC")
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }


    // SELECT * FROM produit WHERE stock < 10;

    public function produitStock(){
        return $this->createQueryBuilder("p")
        ->where('p.stock < 10')
        ->orderBy('p.titre')
        ->getQuery()
        ->getResult();
    }




    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
