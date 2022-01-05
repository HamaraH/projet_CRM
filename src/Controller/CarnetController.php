<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarnetController extends AbstractController
{
    /**
     * @Route("/carnet/ajout", name="carnet_ajout")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {

        $client = new Client();

        $form = $this->createForm(ClientFormType::class, $client);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $client->setCorrespondingUser($this->getUser());

            $manager->persist($client);
            $manager->flush();


            return $this->render('home/home.twig');
        }


        return $this->render('carnet_adresse/ajout.twig', [
            "clientForm" => $form->createView()
        ]);
    }

    // route permettant de visualiser une fiche client
    /**
     * @Route("/carnet/client/{id}", name="carnet_view")
     */
    public function voir_client(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        // récupération de l'objet repository permettant d'effectuer les requêtes
        $repository = $manager->getRepository(Client::class);

        // recherche par id (paramètre de la route)
        $client = $repository->findBy(array('id' => $id))[0];

        return $this->render('carnet_adresse/client.twig', [
            'client' => $client
        ]);
    }


    // route permettant de visualiser une fiche client
    /**
     * @Route("/carnet/modifier/{id}", name="carnet_view")
     */
    public function modifier_client(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        // récupération de l'objet repository permettant d'effectuer les requêtes
        $repository = $manager->getRepository(Client::class);

        // recherche par id (paramètre de la route)
        $client = $repository->findBy(array('id' => $id))[0];

        return $this->render('carnet_adresse/client.twig', [
            'client' => $client
        ]);
    }

}
