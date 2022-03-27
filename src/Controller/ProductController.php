<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Classe\search;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(entityManagerInterface $entityManager){
        $this->entityManager=$entityManager;
    }
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request) 
    {
        $search = new search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $products=$this->entityManager->getRepository(Product::class)->findWithSearch($search);
           
        } else {
            $products=$this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products'=>$products,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */

    public function show($slug) 
    {   
        

        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug'=>$slug]);

        if(!$product){
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product'=>$product
        ]);
    }
}
