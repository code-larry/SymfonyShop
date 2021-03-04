<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use App\Entity\Purchase;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	protected $slugger;
	protected $encoder;

	public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
	{
		$this->slugger = $slugger;
		$this->encoder = $encoder;
	}
	
    public function load(ObjectManager $manager)
    {
		$faker = Factory::create('fr-FR');
		$faker->addProvider(new \Liior\Faker\Prices($faker));
		$faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
		$faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

		$admin = new User();

		$hash = $this->encoder->encodePassword($admin, "password");

		$admin->setEmail("admin@gmail.com")
			->setFullName("Admin")
			->setPassword($hash)
			->setRoles(['ROLE_ADMIN']);

		$manager->persist($admin);

		$users = [];

		for($u = 0; $u < 5; $u++)
		{
			$user = new User();

			$hash = $this->encoder->encodePassword($user, "password");
			
			$user->setEmail("user$u@gmail.com")
				->setFullName($faker->name())
				->setPassword($hash);
			
			$users[] = $user;
			
			$manager->persist($user);
		}

		// Création de 3 catégories
		for($c = 0; $c < 3; $c++)
		{
			$category = new Category();
			$category->setName($faker->department())
				->setSlug(strtolower($this->slugger->slug($category->getName())));

			$manager->persist($category);

			// Pour chaque catégorie, création de 15 à 20 produits
			for ($p = 0; $p < mt_rand(15,20); $p++)
			{
				$product = new Product();
				$product->setName($faker->productName())
					->setPrice($faker->price(4000, 20000))
					->setSlug(strtolower($this->slugger->slug($product->getName())))
					->setCategory($category)
					->setShortDescription($faker->paragraph())
					->setPicture($faker->imageUrl(400, 400, true));
				
				$manager->persist($product);
			}
		}

		for($p = 0; $p < mt_rand(20, 40); $p++)
		{
			$purchase = new Purchase();

			$purchase->setFullName($faker->name())
				->setAddress($faker->streetAddress())
				->setPostalCode($faker->postcode())
				->setCity($faker->city())
				->setUser($faker->randomElement($users))
				->setTotal(mt_rand(2000, 3000));

			if($faker->boolean(90))
			{
				$purchase->setStatus(Purchase::STATUS_PAID);
			}

			$manager->persist($purchase);
		}

        $manager->flush();
    }
}