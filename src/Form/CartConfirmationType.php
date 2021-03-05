<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
				'label' => 'Prénom Nom',
				'attr' => [
					'placeholder' => 'Indiquez les prénom et nom pour la livraison'
				]
			])
			->add('address', TextareaType::class, [
				'label' => 'Adresse de livraison',
				'attr' => [
					'placeholder' => 'Indiquez votre adresse de livraison'
				]
			])
			->add('postalCode', TextType::class, [
				'label' => 'Code postal',
				'attr' => [
					'placeholder' => 'Indiquez le code postal de livraison'
				]
			])
			->add('city', TextType::class, [
				'label' => 'Ville',
				'attr' => [
					'placeholder' => 'Indiquez la ville de livraison'
				]
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class
        ]);
    }
}
