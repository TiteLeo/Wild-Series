<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/category",name="category_")
 */
class CategoryController extends AbstractController

{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/",name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }
    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */

    public function new(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }

        return $this->renderForm('category/new.html.twig', [
            "form" => $form,
        ]);

    }

    /**
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(Category $category): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($category);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category : ' . $category . ' found in category\'s table.'
            );
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category'=>$category],
                ['id'=> 'DESC'],
                3
            );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }

}
