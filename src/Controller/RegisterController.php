<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(request $request, UserPasswordHasherInterface $passwordHasher  )
    {
        $user= new User();
        $form=$this->createForm(RegisterType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $password = $passwordHasher->hashPassword($user, $user->getPassword());
          
            $user->setPassword($password);

          
           $this->entityManager->persist($user);
           $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
