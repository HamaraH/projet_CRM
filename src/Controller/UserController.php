<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    // route qui mène à la page de création de compte
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager): Response
    {
        // création de la nouvelle entité qui correspond a l'utilisateur
        $user = new User();

        //création du formulaire d'inscription lié à l'entité
        $form = $this->createForm(RegisterType::class, $user);

        //gestion de la requête renvoyée par la page
        $form->handleRequest($request);

        // quand le form est renvoyé
        if($form->isSubmitted() && $form->isValid()){

            dump($request->request->all());

            // creation et sélection de la fonction de hashage
            $factory = new PasswordHasherFactory([
                'common' => ['algorithm' => 'bcrypt'],
                'memory-hard' => ['algorithm' => 'sodium'],
            ]);

            //hashage du mot de passe
            $passwordHasher = $factory->getPasswordHasher('common');
            $hash = $passwordHasher->hash($user->getPassword());
            $user->setPassword($hash);

            // enregistrement de l'utilisateur dans la BDD
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');
        }


        return $this->render('user/register.twig', [
           'registerForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function index(): Response
    {
        return $this->render('user/login.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


}
