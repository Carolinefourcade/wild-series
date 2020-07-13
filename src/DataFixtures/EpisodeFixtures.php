<?php


namespace App\DataFixtures;


use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        $episodeReference = 1;
    for ($i = 0; $i < 12; $i++) {
        for ($h = 1; $h < rand(10, 20); $h++) {
            $episode = new Episode();
            $episode->setSeason($this->getReference('season_' . $i));
            $episode->setTitle($faker->words(rand(1, 3), true));
            $episode->setNumber($h);
            $episode->setSynopsis($faker->text(200));
            $manager->persist($episode);
            $this->addReference('episode_' . $episodeReference, $episode);
            $episodeReference++;
        }
    }
        $manager->flush();
}
}
