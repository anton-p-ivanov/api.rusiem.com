<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SiteFixtures
 *
 * @package App\DataFixtures
 */
class SiteFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = json_decode(file_get_contents(__DIR__ . '/Data/Site.json'));

        foreach ($data as $datum) {
            $site = new Site();

            foreach ($datum as $name => $value) {
                $setterName = 'set' . ucfirst($name);
                if (method_exists($site, $setterName)) {
                    $site->$setterName($value);
                }
            }

            $manager->persist($site);
        }

        $manager->flush();
    }
}