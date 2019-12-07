<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param string|null $query
     * @return Comment[]
     */
    public function findAllWithSearch(?string $query): array
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.article', 'a')
            ->addSelect('a');

        if ($query) {
            $qb->andWhere('c.content LIKE :query OR c.authorName LIKE :query OR a.title LIKE :query')
                ->setParameter('query', "%$query%");
        }

        return $qb
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
