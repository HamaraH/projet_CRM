<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // route qui affiche la page d'arrivée sur le site
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        // variable qui contient les clients associés à l'utilisateur, s'il y en a
        $clients = [];

        // si on est connecté
        if($this->getUser()){

            // récupération de tous les clients de l'utilisateur connecté
            $repository = $manager->getRepository(Client::class);
            $clients =  $repository->findBy(array('corresponding_user' => $this->getUser()->getId()));


        }

        // passage en paramètre des clients au template twig
        return $this->render('home/home.twig', [
           'clients' => $clients,
        ]);
    }
}
