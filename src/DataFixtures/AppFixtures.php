<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Player;
use App\Entity\GameMatch;
use App\Entity\MatchResult;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        $players = [];
        for ($i = 1; $i <= 5; $i++) {
            $player = new Player("Player $i");
            $player->setScore(rand(0, 1000));
            $manager->persist($player);
            $players[] = $player;
        }

        for ($i = 1; $i <= 3; $i++) {
            $match = new GameMatch("Mode $i", rand(10, 60));
            $manager->persist($match);

            foreach ($players as $player) {
                $matchResult = new MatchResult(
                    $match,
                    $player,
                    rand(0, 50),
                    rand(0, 50),
                    rand(0, 50),
                    rand(0, 50)
            );
                $manager->persist($matchResult);
            }
        }

        $manager->flush();
    }
}
