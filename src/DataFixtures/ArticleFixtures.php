<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];

    protected function loadData(ObjectManager $em): void
    {
        $this->createMany(Article::class, 10, function(Article $article, $index) {
            $article
                ->setTitle($this->faker->text(60))
                ->setContent(implode("\n\n", $this->faker->paragraphs($this->faker->numberBetween(1, 4))));

            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article->setAuthor($this->faker->name)
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFilename($this->faker->randomElement(self::$articleImages));

            /** @var Tag[] $tags */
            $tags = $this->getRandomReferences(Tag::class, $this->faker->numberBetween(0, 5));
            foreach ($tags as $tag) {
                $article->addTag($tag);
            }
        });
    }

    /**
     * @return array
     */
    public function getDependencies(): ?array
    {
        return [
            TagFixture::class
        ];
    }
}
