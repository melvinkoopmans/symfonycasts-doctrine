<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{
    /**
     * @param ObjectManager $em
     *
     * @return void
     */
    protected function loadData(ObjectManager $em): void
    {
        $this->createMany(Tag::class, 10, function(Tag $tag) {
            $tag->setName($this->faker->realText(20));
        });

        $em->flush();
    }
}
