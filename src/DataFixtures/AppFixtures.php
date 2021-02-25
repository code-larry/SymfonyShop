<?php

namespace App\DataFixtures;

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

		for ($i = 0; $i < 101; $i++)
		{
			$product = new Product();
			$product->setName($faker->productName())
				->setPrice($faker->price(4000, 20000))
				->setSlug(strtolower($this->slugger->slug($product->getName())));
			
			$manager->persist($product);
		}

        $manager->flush();
    }
}
