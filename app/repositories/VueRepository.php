<?php

namespace App_citations\Repositories;

use App_citations\Entities\Vue;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Citation;
use Doctrine\ORM\EntityRepository;

class VueRepository extends EntityRepository
{
    public function getViewsCountForCitation(Citation $citation): int
    {
        return $this->createQueryBuilder('v')
            ->select('count(v.id)')
            ->where('v.citation = :citation')
            ->setParameter('citation', $citation)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasUserViewedCitation(Utilisateur $user, Citation $citation): bool
    {
        $vue = $this->findOneBy([
            'utilisateur' => $user,
            'citation' => $citation
        ]);

        return $vue !== null;
    }
}
