<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\CategoryType;
use App\Form\Type\ProductType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {

    #[Route('/category/list', name: 'category_list')]
    public function list(ManagerRegistry $doctrine): Response {
        $repository = $doctrine->getRepository(Category::class);
        $categories = $repository->findAllCategories();
        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/category', name: 'category_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if($user->getUserIdentifier() != 'dredd123') {
            return new Response('Unknown user.');
        }

        $entityManager = $doctrine->getManager();
        $category = new Category();
        $category->setName('PUBG');

        $form = $this->createForm(CategoryType::class, $category);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $category = $form->getData();

            $entityManager->persist($category);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('category_create');
        }
        // tell Doctrine you want to (eventually) save the Product (no queries yet)


        return $this->renderForm('category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/category/{id}/delete', name: 'category_delete')]
    public function deleteCategory(int $id, ManagerRegistry $doctrine) {
        $category = $doctrine->getRepository(Category::class)->findById($id);
        if($category){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($category);
            $products = $category->getProducts(); // TODO realize clear category?
            $entityManager->flush();
            return $this->redirectToRoute('category_list');
        }
        else
            return new Response('No found category with id: ' . $id);
    }

    #[Route('/category/{id}/edit', name: 'category_edit')]
    public function editCategory(int $id, ManagerRegistry $doctrine, Request $request) {
        $category = $doctrine->getRepository(Category::class)->findById($id);
        if($category){
            $entityManager = $doctrine->getManager();


            $form = $this->createForm(CategoryType::class, $category);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($category);
                $entityManager->flush();
            }

            return $this->renderForm('category/edit.html.twig', [
                'form' => $form
            ]);
        }
        else
            return new Response('No found category with id: ' . $id);
    }

    #[Route('/category/{id}/view', name: 'category_view')]
    public function viewCategory(int $id, ManagerRegistry $doctrine) {
        $category = $doctrine->getRepository(Category::class)->findById($id);
        if($category){
            $products = $category->getProducts();

            if($products->isEmpty())
                return $this->render('error.html.twig', [
                        'error' => 'Not found products.',
                    ]);

            return $this->render('category/view.html.twig', [
                'category' => $category,
                'products'  => $products
            ]);
        }
        else
            return $this->render('error.html.twig', [
                'error' => 'Not found category.',
            ]);
    }
}
