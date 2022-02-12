<?php

namespace Symfony5\Persistence\ORM\Doctrine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class WorkerFixtures extends Fixture
{
    private array $defaultCycleParameters;

    public function __construct(array $defaultCycleParameters)
    {
        $this->defaultCycleParameters = $defaultCycleParameters;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new OrmWorker(
                $faker->uuid(),
                $faker->email(),
                $faker->firstName()
            );

            $user->setPomodoroDuration($this->defaultCycleParameters['pomodoroDuration']);
            $user->setShortBreakDuration($this->defaultCycleParameters['shortBreakDuration']);
            $user->setLongBreakDuration($this->defaultCycleParameters['longBreakDuration']);
            $user->setStartFirstTaskIn($this->defaultCycleParameters['startFirstTaskAfter']);

            $user->setPassword($faker->password());
            $manager->persist($user);
        }

        $manager->flush();
    }
}
