<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('name', TextType::class, [
			'label' => 'Nom du produit',
			'attr' => [
				'placeholder' => 'Tapez le nom du produit'
				]
			])
			->add('shortDescription', TextareaType::class, [
				'label' => 'Descripion courte',
				'attr' => [
					'placeholder' => 'Décrivez votre produit'
					]
			])
			->add('price', MoneyType::class, [
				'label' => 'Prix du produit',
				'currency' => false,
				'attr' => [
					'placeholder' => 'Prix du produit en €'
				]
			])
			->add('picture', UrlType::class, [
				'label' =>'Image du produit',
				'attr' => [
					'placeholder' => 'Tapez une URL d\'image'
				]
			])
			->add('category', EntityType::class, [
				'label' => 'Catégorie',
				'placeholder' => '-- Choisissez une catégorie --',
				'class' => Category::class,
				'choice_label' => function (Category $category) {
					return strtoupper($category->getName());
				}
			]);

		// Transformation du prix en euro avant affichage et en centimes avant enregistrement en DB
		// En utilisant l'option divisor de MoneyType, nous pouvons éviter de créer ce Transformer
		$builder->get('price')->addModelTransformer(new CentimesTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
