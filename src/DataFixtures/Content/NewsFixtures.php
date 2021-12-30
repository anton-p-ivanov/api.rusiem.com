<?php

namespace App\DataFixtures\Content;

use App\DataFixtures\ContextFixtures;
use App\DataFixtures\LocaleFixtures;
use App\DataFixtures\SiteFixtures;
use App\DataFixtures\TagsFixtures;
use App\Entity\Catalog\Tag;
use App\Entity\Content\News;
use App\Entity\Context;
use App\Entity\Locale;
use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class NewsFixtures
 */
class NewsFixtures extends Fixture implements DependentFixtureInterface
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
        $site = $manager->getRepository(Site::class)->findOneBy(['isDefault' => true]);
        $locale = $manager->getRepository(Locale::class)->find('ru');
        $context = $manager->getRepository(Context::class)->findOneBy(['slug' => 'news']);
        $tags = $manager->getRepository(Tag::class)->findBy(['context' => $context]);

        for ($counter = 0; $counter < 50; $counter++) {
            $title = $faker->text;
            $news = (new News())
                ->setTitle($title)
                ->setDescription($faker->realText)
                ->setContent($faker->realText)
                ->setPublishedAt($faker->dateTimeThisYear)
                ->setIsPublished(true)
                ->setTags($this->getRandomTags($tags))
                ->setSites([$site])
                ->setLocale($locale)
                ->setSlug($slugger->slug($title));

            $manager->persist($news);
        }

        $manager->flush();
    }

    /**
     * @param array $tags
     *
     * @return array
     * @throws \Exception
     */
    private function getRandomTags(array $tags): array
    {
        $collection = [];

        $keys = array_rand($tags, random_int(1, count($tags)));
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            array_push($collection, $tags[$key]);
        }

        return $collection;
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            ContextFixtures::class,
            SiteFixtures::class,
            LocaleFixtures::class,
            TagsFixtures::class,
        ];
    }
}