<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {


    #[Route('/product', name: 'product_create')]
    public function createProduct(Request $request, ManagerRegistry $doctrine): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if($user->getUserIdentifier() != 'dredd123') {
            return new Response('Unknown user.');
        }
        $entityManager = $doctrine->getManager();
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $product = $form->getData();

            $entityManager->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('product_create');
        }
        // tell Doctrine you want to (eventually) save the Product (no queries yet)


        return $this->renderForm('product/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/product/list', name: 'product_list')]
    public function listProducts(ManagerRegistry $doctrine) {
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findAllProducts();

        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/product/{id}/delete', name: 'product_delete')]
    public function deleteProduct(int $id, ManagerRegistry $doctrine) {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->findById($id);;
        if($product){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_list');
        }
        else
            return new Response('No found product with id: ' . $id);
    }

    #[Route('/product/{id}/view', name: 'product_view')]
    public function viewProduct(int $id, ManagerRegistry $doctrine) {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->findById($id);

        return $this->render('product/view.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/{id}/edit', name: 'product_edit')]
    public function editCategory(int $id, ManagerRegistry $doctrine, Request $request) {
        $product = $doctrine->getRepository(Product::class)->findById($id);
        if($product){
            $entityManager = $doctrine->getManager();


            $form = $this->createForm(ProductType::class, $product);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($product);
                $entityManager->flush();
            }

            return $this->renderForm('product/edit.html.twig', [
                'form' => $form
            ]);
        }
        else
            return new Response('No found product with id: ' . $id);
    }
}
