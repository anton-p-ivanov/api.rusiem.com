<?php

namespace App\DataFixtures\Form;

use App\DataFixtures\ContextFixtures;
use App\DataFixtures\Mail\TemplateFixtures;
use App\DataFixtures\SiteFixtures;
use App\Entity\Context;
use App\Entity\Form\Form;
use App\Entity\Mail\Template;
use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class FormFixtures
 *
 * @package App\DataFixtures
 */
class FormFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugger = new AsciiSlugger();
        $context = $manager->getRepository(Context::class)->find('default');
        $site = $manager->getRepository(Site::class)->findOneBy(['isDefault' => true]);
        $templates = $manager->getRepository(Template::class)->findAll();

        for ($counter = 0; $counter < 50; $counter++) {
            $title = $faker->text;
            $form = (new Form())
                ->setTitle($title)
                ->setSlug($slugger->slug($title))
                ->setIsPublished($faker->boolean)
                ->setPublishedAt($faker->dateTimeThisYear)
                ->setContext($context)
                ->setActiveFrom($faker->dateTimeThisYear)
                ->setTemplate($templates[$counter])
                ->setSites([$site]);

            $manager->persist($form);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            ContextFixtures::class,
            SiteFixtures::class,
            TemplateFixtures::class
        ];
    }
}