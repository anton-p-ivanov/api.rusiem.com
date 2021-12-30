<?php

namespace App\DataFixtures\Mail;

use App\DataFixtures\LocaleFixtures;
use App\Entity\Locale;
use App\Entity\Mail\Template;
use App\Entity\Mail\TemplateLang;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class TemplateFixtures
 *
 * @package App\DataFixtures\Vacancy
 */
class TemplateFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $locales = $manager->getRepository(Locale::class)->findAll();

        for ($counter = 0; $counter < 50; $counter++) {
            $translations = [];
            $template = (new Template())
                ->setCode($faker->slug)
                ->setRecipient($faker->email)
                ->setSender($faker->email);

            foreach ($locales as $locale) {
                $translations[] = (new TemplateLang())
                    ->setLocale($locale)
                    ->setSubject($faker->text)
                    ->setText($faker->realText)
                    ->setHtml($faker->randomHtml)
                    ->setTemplate($template);
            }

            $template->setTranslations($translations);

            $manager->persist($template);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            LocaleFixtures::class
        ];
    }
}