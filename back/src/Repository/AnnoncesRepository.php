<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Annonces $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Annonces $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // public function apiFindAll(){
    //     $qb = $this->createQueryBuilder('a')
    //     ->select('a.id', 'a.name', 'a.description',  'a.images', 'a.adresse', 'a.codepostal', 'a.ville', 'a.date');
    //     $query = $qb->getQuery();
    //     return $query->execute();
    // }

    //  /**
    //   * @return Annonces[] Returns an array of Annonces objects
    //   */
    
    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('a.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?Annonces
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Annonces[] Returns an array of Annonces objects
    */
    
    public function findByDateAfterNow($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.date > :val')
            ->setParameter('val', $value)
            ->orderBy('a.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDateBeforeNow($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.date < :val')
            ->setParameter('val', $value)
            ->orderBy('a.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDateAfterNowAndCategorie($date, $categorie)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.date > :date')
            ->andWhere('a.categorie = :valCategorie')
            ->setParameter('date', $date)
            ->setParameter(':valCategorie', $categorie)
            // ->orderBy('a.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
