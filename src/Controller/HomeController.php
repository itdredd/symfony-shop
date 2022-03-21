<?php
namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    #[Route('/', name: 'home_page')]
    public function home(ManagerRegistry $doctrine): Response {
        $repository = $doctrine->getRepository(Category::class);
        $categories = $repository->findAllCategories();
        return $this->render('index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/faq', name: 'faq')]
    public function faq() {
        return $this->render('faq.html.twig', [
        ]);
    }


}
