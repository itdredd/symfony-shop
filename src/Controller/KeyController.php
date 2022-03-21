<?php

namespace App\Controller;

use App\Entity\Key;
use App\Entity\Product;
use App\Form\Type\KeyType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KeyController extends AbstractController {


    #[Route('/key', name: 'key_create')]
    public function createKey(Request $request, ManagerRegistry $doctrine): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if($user->getUserIdentifier() != 'dredd123') {
            return new Response('Unknown user.');
        }
        $entityManager = $doctrine->getManager();
        $product = new Key();

        $form = $this->createForm(KeyType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $product = $form->getData();

            $entityManager->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('key_create');
        }
        // tell Doctrine you want to (eventually) save the Product (no queries yet)


        return $this->renderForm('product/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/key/list', name: 'key_list')]
    public function listKeys(ManagerRegistry $doctrine, Request $request) {
        $key = new Key();
        $form = $this->createFormBuilder($key)
            ->add('id', IntegerType::class, [
                'required' => false
            ])
            ->add('key', TextType::class, [
                'required' => false
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'required' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Find key'])
            ->getForm();
        $repository = $doctrine->getRepository(Key::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $key = $form->getData();
            $keys = [];
            $product = $key->getProduct();
            if($key->getProduct()){
                $keys = $repository->findBy(['product' => $product->getId()]);
            }
            if($key->getId()) {
                $keys += $repository->findBy(['id' => $key->getId()]);
            }
            if($key->getKey()) {
                $keys += $repository->findBy(['key' => $key->getKey()]);
            }
            if(empty($key));
                $keys = $repository->findAllKeys();
            // ... perform some action, such as saving the task to the database


        }
        else{
            $keys = $repository->findAllKeys();
        }



        return $this->renderForm('key/list.html.twig', [
            'keys' => $keys,
            'form' => $form
        ]);
    }


}
