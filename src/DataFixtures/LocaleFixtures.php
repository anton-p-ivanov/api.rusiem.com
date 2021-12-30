<?php

namespace App\DataFixtures;

use App\Entity\Locale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class LocaleFixtures
 *
 * @package App\DataFixtures
 */
class LocaleFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $languages = json_decode(file_get_contents(__DIR__.'/Data/Locale.json'));

        foreach ($languages as $language) {
            $model = new Locale();

            foreach ($language as $name => $value) {
                $setterName = 'set' . ucfirst($name);
                if (method_exists($model, $setterName)) {
                    $model->$setterName($value);
                }
            }

            $manager->persist($model);
        }

        $manager->flush();
    }
}