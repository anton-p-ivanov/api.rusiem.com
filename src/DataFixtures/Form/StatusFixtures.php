<?php

namespace App\DataFixtures\Form;

use App\Entity\Form\Form;
use App\Entity\Form\Status;
use App\Entity\Form\StatusLang;
use App\Entity\Locale;
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
class StatusFixtures extends Fixture implements DependentFixtureInterface
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
        $forms = $manager->getRepository(Form::class)->findAll();
        $locales = $manager->getRepository(Locale::class)->findAll();

        foreach ($forms as $form) {
            foreach (Status::$types as $type) {
                $title = $faker->text;
                $translations = array_map(function (Locale $locale) use ($title) {
                    return (new StatusLang())
                        ->setLocale($locale)
                        ->setTitle($title);
                }, $locales);

                $status = (new Status())
                    ->setForm($form)
                    ->setType($type)
                    ->setName($slugger->slug($title))
                    ->setIsDefault($type === Status::TYPE_DEFAULT)
                    ->setTranslations($translations);

                $manager->persist($status);
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            FormFixtures::class
        ];
    }
}