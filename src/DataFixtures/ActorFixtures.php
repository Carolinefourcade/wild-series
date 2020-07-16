<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
const ACTORS = [
    'Andrew Lincoln' => ['program_0', 'program_5'],
    'Norman Reedus' => ['program_0'],
    'Lauren Cohan' => ['program_0'],
    'Danai Gurira' => ['program_0'],
];
public function getDependencies()
{
    return [ProgramFixtures::class];
}

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
{
    $i = 0;
    foreach (self::ACTORS as $name => $programs) {
        $actor = new Actor();
        $actor->setName($name);
        foreach ($programs as $program) {
            $actor->addProgram($this->getReference($program));
        }
        $slugify = new Slugify();
        $slug = $slugify->generate($actor->getName());
        $actor->setSlug($slug);
        $manager->persist($actor);
        $this->addReference('actor_' . $i, $actor);
        $i++;
    }

    $faker = Faker\Factory::create('en_US');
    for ($h = 5; $h < 56; $h++) {
        $actor = new Actor();
        $actor->setName($faker->name);
        $actor->addProgram($this->getReference('program_' . rand(0,5)));
        $slugify = new Slugify();
        $slug = $slugify->generate($actor->getName());
        $actor->setSlug($slug);
        $manager->persist($actor);
        $this->addReference('actor_' . $h, $actor);
    }
    $manager->flush();
    // TODO: Implement load() method.
}
}
