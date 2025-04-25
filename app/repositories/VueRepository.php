<?php

namespace App_citations\Repositories;

use App_citations\Entities\Vue;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Citation;
use Doctrine\ORM\EntityRepository;

class VueRepository extends EntityRepository
{
    public function countVuesByCitation(int $citationId): int
    {
        return (int) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.citation = :citationId')
            ->setParameter('citationId', $citationId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasUserViewed(int $userId, int $citationId): bool
    {
        $vue = $this->createQueryBuilder('v')
            ->where('v.utilisateur = :userId')
            ->andWhere('v.citation = :citationId')
            ->setParameter('userId', $userId)
            ->setParameter('citationId', $citationId)
            ->getQuery()
            ->getOneOrNullResult();

        return $vue !== null;
    }
}
