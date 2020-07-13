<?php


namespace App\DataFixtures;


use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 6; $i++) {
            $season = new Season();
            $program = $this->getReference('program_' . $i);
            $season->setProgram($program);
            $season->setNumber(1);
            $season->setYear($program->getYear());
            $season->setDescription($faker->text(150));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }

        $seasonReference = 6;
        for ($h = 0; $h < 6; $h++) {
            $season = new Season();
            $program = $this->getReference('program_' . $h);
            $season->setProgram($program);
            $season->setNumber(2);
            $season->setYear($program->getYear() + 1);
            $season->setDescription($faker->text(150));
            $manager->persist($season);
            $this->addReference('season_' . $seasonReference, $season);
            $seasonReference++;
        }
        $manager->flush();
    }
}
