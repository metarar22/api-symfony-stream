<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Film;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Platform;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, 'user');
        $user->setEmail('user@mail.fr');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $admin = new User();
        $passwordAdmin = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setEmail('admin@mail.fr');
        $admin->setPassword($passwordAdmin);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);




        
        
    

        for ($i = 0; $i < 20; ++$i) {
            $Categories = new Category();
            $cat = ['Action', 'Horreur','Humour', 'Anime', 'Documentaire', 'Drame'];
            $Categories->setNom($faker->randomElement($cat));
            
            $platform = new Platform;
            $Plat = ['Prime Video', 'Disney','Netflix', 'HBO', 'AppleTV', 'OCS'];

            $platform->setNom($faker->randomElement($Plat));
            $manager->persist($platform);




            $Films = new Film();
            $Films->setName($faker->word());
            $Films->setReleaseDate($faker->dateTimeThisCentury('-6 years'));
            $Films->setStartLicenceDate($faker->dateTimeThisDecade('+2 years'));
            $Films->setEndLicenceDate($faker->dateTimeThisDecade('+1 years'));
            $Films->setCategory($Categories);
            $Films->setPlatform($platform);


            $start = $Films->getStartLicenceDate();
            $end = $Films->getEndLicenceDate();
            if ($end > $start){
                $Films->setIsAvailable(true);
            } 

            $manager->persist($Categories);
            $manager->persist($Films);








        $manager->flush();
    }
}
}