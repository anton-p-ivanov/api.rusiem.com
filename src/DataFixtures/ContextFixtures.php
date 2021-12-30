<?php

namespace App\DataFixtures;

use App\Entity\Context;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ContextFixtures
 *
 * @package App\DataFixtures
 */
class ContextFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = json_decode(file_get_contents(__DIR__ . '/Data/Context.json'));

        foreach ($data as $datum) {
            $context = new Context();

            foreach ($datum as $name => $value) {
                $setterName = 'set' . ucfirst($name);
                if (method_exists($context, $setterName)) {
                    $context->$setterName($value);
                }
            }

            $manager->persist($context);
        }

        $manager->flush();
    }
}