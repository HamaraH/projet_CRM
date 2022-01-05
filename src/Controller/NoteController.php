<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Note;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{

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

                $client = $manager->getRepository(Client::class)->findBy(array('id' => $id))[0];

                $note->setPublicationDate(new \DateTime('now'));
                $note->setCorrespondingClient($client);
                // sauvegarde de l'entité Note dans la bdd
                $manager->persist($note);
                $manager->flush();

                // récupération du client correspondant
                $repository = $manager->getRepository(Client::class);
                $client = $repository->findBy(array('id'=> $id))[0];

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

    // route permettant de visualiser les notes plus anciennes
    /**
     * @Route("/carnet/voir_notes/{id}", name="carnet_voir_notes")
     */

    public function voir_notes(int $id, Request $request, EntityManagerInterface $manager): Response
    {

        // récupération des notes du client
        $anciennes_notes = $manager->getRepository(Note::class)->findBy(array('corresponding_client' => $id), array('publication_date' => 'DESC'));

        // on retire la note la plus récente car déjà affichée sur la fiche client
        unset($anciennes_notes[0]);

        return $this->render('carnet_adresse/voir_notes.twig', [
            'notes' => $anciennes_notes
        ]);

    }

}
