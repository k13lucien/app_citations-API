<?php

namespace App_citations\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use DateTimeImmutable;

class TimestampListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new DateTimeImmutable());
        }

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTimeImmutable());
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTimeImmutable());

            // Nécessaire pour informer Doctrine qu’un champ a été modifié
            $em = $args->getEntityManager();
            $classMeta = $em->getClassMetadata(get_class($entity));
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($classMeta, $entity);
        }
    }
}
