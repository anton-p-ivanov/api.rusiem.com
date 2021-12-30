<?php

namespace App\DataFixtures\Vacancy;

use App\Entity\Vacancy\Response;
use App\Entity\Vacancy\Vacancy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ResponseFixtures
 *
 * @package App\DataFixtures\Vacancy
 */
class ResponseFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $vacancies = $manager->getRepository(Vacancy::class)->findBy([], [], 10);

        for ($counter = 0; $counter < 50; $counter++) {
            $object = (new Response())
                ->setEmail($faker->email)
                ->setPhone($faker->phoneNumber)
                ->setFullName($faker->name)
                ->setVacancy($vacancies[array_rand($vacancies)]);

            $manager->persist($object);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            VacancyFixtures::class
        ];
    }
}