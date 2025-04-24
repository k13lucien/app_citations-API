<?php

namespace App_citations\Repositories;

use App_citations\Entities\Like;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Citation;
use Doctrine\ORM\EntityRepository;

class LikeRepository extends EntityRepository
{
    public function hasUserLikedCitation(Utilisateur $user, Citation $citation): bool
    {
        $like = $this->findOneBy([
            'utilisateur' => $user,
            'citation' => $citation
        ]);

        return $like !== null;
    }
}
