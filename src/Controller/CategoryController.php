<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="wild_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/category/add", name="add")
     * @param Request $request
     * @return Response
     */

public function add(Request $request, EntityManagerInterface $entityManager)
{
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager->persist($category);
        $entityManager->flush();
        $this->addFlash('success', 'Your category is created.');

        return $this->redirectToRoute('wild_add');
    }
    return $this->render('wild/add.html.twig', [
        'form' => $form->createView(),
    ]);
}


}
