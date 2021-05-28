<?php

namespace App\EventListener;

use App\Entity\Biography;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class BiographyEntityListener
{
    public function update(Biography $biography, LifecycleEventArgs $event)
    {
        $biography->setUpdatedAt(new \datetime());
    }
}