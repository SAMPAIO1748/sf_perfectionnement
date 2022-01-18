<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function searchByTerm($term)
    {
        // QueryBuilder permet de créer des requêtes SQL en PHP
        $queryBuilder = $this->createQueryBuilder('article');

        $query = $queryBuilder
            ->select('article') // select sur la table article
            ->leftJoin('article.category', 'category') // leftjoin sur la table category
            ->leftJoin('article.writer', 'writer') // leftjoin sur la table writer
            ->where('article.title LIKE :term') // WHERE de SQL
            ->orWhere('article.content LIKE :term') // OR WHERE de SQL
            ->orWhere('category.name LIKE :term')
            ->orWhere('category.description LIKE :term')
            ->orWhere('writer.name LIKE :term')
            ->orWhere('writer.firstname LIKE :term')
            ->setParameter('term', '%' . $term . '%') // On attribue le term renté et on le sécurise
            ->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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

    /*
    public function findOneBySomeField($value): ?Article
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
