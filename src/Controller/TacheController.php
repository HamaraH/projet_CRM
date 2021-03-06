<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Tache;
use App\Form\TacheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TacheController extends AbstractController
{
    /**
     * @Route("/tache/ajout/{id}", name="ajout_tache")
     */
    public function index(int $id, EntityManagerInterface $manager, Request $request): Response
    {
        // création de la nouvelle tache
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // si l'utilisateur souhaite ajouter une tâche
            if($form->get('valider')->isClicked()){

                //récupération du client correspondant
                $client = $manager->getRepository(Client::class)->findBy(array('id' => $id))[0];

                $tache->setPublicationDate(new \DateTime('now'));
                $tache->setCorrespondingClient($client);
                $tache->setEtat('A faire');

                // sauvegarde de l'entité Note dans la bdd
                $manager->persist($tache);

                $manager->flush();

                return $this->redirectToRoute('carnet_view', ['id'=>$id]);
            }
            // s'il souhaite l'annuler
            else{
                return $this->redirectToRoute('carnet_view', ['id'=>$id]);
            }
        }

        return $this->render('tache/ajout_tache.twig', [
            'tacheForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/tache/supprimer/{id_tache}", name="suppression_tache")
     */
    public function supprimer_tache(int $id_tache, EntityManagerInterface $manager, Request $request): Response
    {

        $tache = $manager->getRepository(Tache::class)->findBy(array('id' => $id_tache))[0];
        $id = $tache->getCorrespondingClient()->getId();

        // suppression de la tache
        $manager->remove($tache);
        $manager->flush();

        return $this->redirectToRoute('carnet_view', array('id' => $id));
    }

    /**
     * @Route("/tache/valider/{id_tache}", name="valider_tache")
     */
    public function valider_tache(int $id_tache, EntityManagerInterface $manager, Request $request): Response
    {

        // on récupère la tache en question
        $tache = $manager->getRepository(Tache::class)->findBy(array('id' => $id_tache))[0];
        dump($tache);
        $id = $tache->getCorrespondingClient()->getId();

        //mise à jour de l'état
        $tache->setEtat('Fait');

        // sauvegarde en bdd
        $manager->persist($tache);
        $manager->flush();

        return $this->redirectToRoute('carnet_view', array('id' => $id));
    }

}
