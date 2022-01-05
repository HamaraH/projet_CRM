<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Note;
use App\Entity\Tache;
use App\Form\ClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarnetController extends AbstractController
{
    // route qui permet d'ajouter un client au carnet d'adresses
    /**
     * @Route("/carnet/ajout", name="carnet_ajout")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        // creation de l'objet client correspondant
        $client = new Client();

        $form = $this->createForm(ClientFormType::class, $client);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $client->setCorrespondingUser($this->getUser());

            $manager->persist($client);
            $manager->flush();


            return $this->redirectToRoute('home');
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

        $client = $manager->getRepository(Client::class)->findBy(array('id' => $id))[0];

        // recherche par id (paramètre de la route)
        $notes = $manager->getRepository(Note::class)->findBy(array('corresponding_client' => $id), array('publication_date' => 'DESC'));
        $taches = $manager->getRepository(Tache::class)->findBy(array('corresponding_client' => $id), array('publication_date' => 'ASC'));

        return $this->render('carnet_adresse/client.twig', [
            'taches' => $taches,
            'client' => $client,
            'lastNote' => $notes
        ]);
    }

    // route permettant de visualiser une fiche client
    /**
     * @Route("/carnet/modifier/{id}", name="carnet_modifier")
     */
    public function modifier_client(int $id, Request $request, EntityManagerInterface $manager): Response
    {

        // récupération de l'objet repository permettant d'effectuer les requêtes
        $repository = $manager->getRepository(Client::class);

        // recherche par id (paramètre de la route)
        $client = $repository->findBy(array('id' => $id))[0];
        $taches = $manager->getRepository(Tache::class)->findBy(array('corresponding_client' => $id), array('publication_date' => 'ASC'));

        // utilisation du même formulaire que pour la création d'un client, différenciation grâce à un paramètre
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($client);
            $manager->flush();

            return $this->redirectToRoute('carnet_view', array('id'=> $id));
        }

        return $this->render('carnet_adresse/client.twig', [
            'clientForm' => $form->createView(),
            'client' => $client,
            'taches' => $taches
        ]);
    }

}
