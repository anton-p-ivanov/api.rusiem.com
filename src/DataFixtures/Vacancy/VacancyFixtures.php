<?php

namespace App\DataFixtures\Vacancy;

use App\Entity\Locale;
use App\Entity\Site;
use App\Entity\Vacancy\Group;
use App\Entity\Vacancy\Vacancy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class VacancyFixtures
 *
 * @package App\DataFixtures\Vacancy
 */
class VacancyFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugger = new AsciiSlugger();

        $groups = $manager->getRepository(Group::class)->findAll();
        $site = $manager->getRepository(Site::class)->findOneBy(['isDefault' => true]);
        $locale = $manager->getRepository(Locale::class)->find('ru');

        for ($counter = 0; $counter < 50; $counter++) {
            $title = $faker->text;
            $vacancy = (new Vacancy())
                ->setTitle($title)
                ->setSlug($slugger->slug($title))
                ->setDescription($faker->realText)
                ->setContent($faker->realText(1000))
                ->setPublishedAt($faker->dateTimeThisYear)
                ->setGroup($this->getRandomGroup($groups))
                ->setSites([$site])
                ->setLocale($locale);

            $manager->persist($vacancy);
        }

        $manager->flush();
    }

    /**
     * @param array $groups
     *
     * @return Group
     */
    private function getRandomGroup(array $groups): Group
    {
        return $groups[array_rand($groups)];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            GroupFixtures::class
        ];
    }
}