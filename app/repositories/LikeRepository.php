<?php

namespace App_citations\Repositories;

use App_citations\Entities\Like;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Citation;
use Doctrine\ORM\EntityRepository;

class LikeRepository extends EntityRepository
{
    public function countLikesByCitation(int $citationId): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.citation = :citationId')
            ->setParameter('citationId', $citationId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasUserLiked(int $userId, int $citationId): bool
    {
        $like = $this->createQueryBuilder('l')
            ->where('l.utilisateur = :userId')
            ->andWhere('l.citation = :citationId')
            ->setParameter('userId', $userId)
            ->setParameter('citationId', $citationId)
            ->getQuery()
            ->getOneOrNullResult();

        return $like !== null;
    }

    public function findLike(int $userId, int $citationId): ?Like
    {
        return $this->createQueryBuilder('l')
            ->where('l.utilisateur = :userId')
            ->andWhere('l.citation = :citationId')
            ->setParameter('userId', $userId)
            ->setParameter('citationId', $citationId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
