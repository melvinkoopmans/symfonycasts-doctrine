<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var array
     */
    private $referencesIndex = [];

    /**
     * @param ObjectManager $em
     * @return void
     */
    abstract protected function loadData(ObjectManager $em): void;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        $this->loadData($manager);
        $manager->flush();
    }

    /**
     * @param string $className
     * @param int $count
     * @param callable $factory
     * @param bool $reference
     */
    protected function createMany(string $className, int $count, callable $factory, $reference = true)
    {
        for ($i = 0; $i < $count; ++$i) {
            $entity = new $className;
            $factory($entity, $i);

            $this->manager->persist($entity);

            // Store for usage later as App\Entity\ClassName_#COUNT#
            if ($reference) {
                $this->addReference($className . '_' . $i, $entity);
            }
        }
    }

    protected function getRandomReference(string $className) {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className.'_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new \Exception(sprintf('Cannot find any references for class "%s"', $className));
        }
        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);
        return $this->getReference($randomReferenceKey);
    }

    protected function getRandomReferences(string $className, int $count)
    {
        $references = [];
        while (count($references) < $count) {
            $references[] = $this->getRandomReference($className);
        }
        return $references;
    }
}
