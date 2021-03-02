<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
		$category = new Category;
		
		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$category->setSlug(strtolower($slugger->slug($category->getName())));
			$em->persist($category);
			$em->flush();

			return $this->redirectToRoute('homepage');
		}

		$formView = $form->createView();

        return $this->render('category/create.html.twig', [
			'formView' => $formView
		]);
    }

	/**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     */
    public function edit($id, SluggerInterface $slugger, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em): Response
    {
		$category = $categoryRepository->find($id);

		if(!$category)
		{
			throw new NotFoundHttpException("Cette catégorie n'existe pas.");
		}

		// Used with our CategoryVoter
		// $this->denyAccessUnlessGranted('CAN_EDIT', $category, "Vous n'êtes pas le propriétaire ou n'avez pas le rôle ADMIN de cette catégorie.");

		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$category->setSlug(strtolower($slugger->slug($category->getName())));
			$em->flush();

			return $this->redirectToRoute('homepage');
		}

		$formView = $form->createView();

        return $this->render('category/edit.html.twig', [
			'category' => $category,
			'formView' => $formView
		]);
    }
}
