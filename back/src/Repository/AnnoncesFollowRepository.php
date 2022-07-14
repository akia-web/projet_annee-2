<?php

namespace App\Repository;

use App\Entity\AnnoncesFollow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnoncesFollow|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnoncesFollow|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnoncesFollow[]    findAll()
 * @method AnnoncesFollow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesFollowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnoncesFollow::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AnnoncesFollow $entity, bool $flush = true): void
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
    public function remove(AnnoncesFollow $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AnnoncesFollow[] Returns an array of AnnoncesFollow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    // public function apiFindAll($value){
    //     $qb = $this->createQueryBuilder('a')
    //     ->select('a.id', 'a.user.id')
    //     ->andWhere('a.user.id = :val')
    //         ->setParameter('val', $value);
    //     $query = $qb->getQuery();
    //     return $query->execute();
    // }

    /*
    public function findOneBySomeField($value): ?AnnoncesFollow
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
