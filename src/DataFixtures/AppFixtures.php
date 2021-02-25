<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Product;
use Liior\Faker\Prices;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
	protected $slugger;

	public function __construct(SluggerInterface $slugger)
	{
		$this->slugger = $slugger;
	}
	
    public function load(ObjectManager $manager)
    {
		$faker = Factory::create('fr-FR');
		$faker->addProvider(new \Liior\Faker\Prices($faker));
		$faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

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
					->setCategory($category);
				
				$manager->persist($product);
			}
		}

        $manager->flush();
    }
}
