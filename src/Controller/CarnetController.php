<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Note;
use App\Form\ClientFormType;
use App\Form\NoteType;
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
            $client->setNotes(array());
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
     * @Route("/carnet/modifier/{id}", name="carnet_modifier")
     */
    public function modifier_client(int $id, Request $request, EntityManagerInterface $manager): Response
    {

        // récupération de l'objet repository permettant d'effectuer les requêtes
        $repository = $manager->getRepository(Client::class);

        // recherche par id (paramètre de la route)
        $client = $repository->findBy(array('id' => $id))[0];

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
            'client' => $client
        ]);
    }


    // route permettant de gérer l'ajout d'une note à un client
    /**
     * @Route("/carnet/ajout_note/{id}", name="carnet_ajout_note")
     */
    public function ajout_note(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        // création de l'objet note
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);

        // quand on clique sur un des deux boutons
        if($form->isSubmitted() && $form->isValid()){

            // si l'utilisateur souhaite enregistrer la note
            if($form->get('valider')->isClicked()){

                $note->setPublicationDate(new \DateTime('now'));
                // sauvegarde de l'entité Note dans la bdd
                $manager->persist($note);
                $manager->flush();

                // récupération du client correspondant
                $repository = $manager->getRepository(Client::class);
                $client = $repository->findBy(array('id'=> $id))[0];

                // ajout de la note à l'array des notes du client
                $notes = $client->getNotes();
                array_push($notes, $note->getId());
                $client->setNotes($notes);

                $manager->persist($client);
                $manager->flush();

                return $this->redirectToRoute('carnet_view', ['id'=>$id]);
            }
            // s'il souhaite l'annuler
            else{
                return $this->redirectToRoute('carnet_view', ['id'=>$id]);
            }
        }
        return $this->render('carnet_adresse/ajout_note.twig', [
            'noteForm' => $form->createView()
        ]);
    }
}
