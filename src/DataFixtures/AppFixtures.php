<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBooks($manager);
    }

	private function loadBooks(ObjectManager $manager): void
	{
		for ($i = 0; $i < 2000; $i++) {
			$book = new Book();
			$book->setTitle("Foo bar {$i}");
			$book->setPrice(mt_rand(10, 100));
			$manager->persist($book);
		}

		$manager->flush();
	}

	private function loadUsers(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setName('nadir');
        $userAdmin->setSurname('nadir');
        $userAdmin->setUsername('nadir');
        $userAdmin->setEmail('admin@symfony.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $encodedPassword = $this->passwordEncoder->encodePassword($userAdmin, 'nadir');
        $userAdmin->setPassword($encodedPassword);
        $manager->persist($userAdmin);

        $manager->flush();
    }
}

//curl -H 'content-type: application/json' -v -X  POST http://127.0.0.1:8000/api/token -H 'Authorization:Basic tony_admin:admin'