<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $em
     * @return void
     */
    protected function loadData(ObjectManager $em): void
    {
        $this->createMany(Comment::class, 20, function(Comment $comment, $index) {
            $comment->setAuthorName($this->faker->name);
            $comment->setContent(
                $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
            );
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 day'));
            $comment->setIsDeleted($this->faker->boolean(20));

            $comment->setArticle($this->getRandomReference(Article::class));
        }, false);
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            ArticleFixtures::class
        ];
    }
}
