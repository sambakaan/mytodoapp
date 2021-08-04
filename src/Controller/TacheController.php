<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TacheController extends AbstractController
{
    /**
     * @Route("/taches", name="app_taches")
     */
    public function index(TacheRepository $tacheRepository): Response
    {
        $liste_des_taches = $tacheRepository->findAll();
        return $this->render('tache/index.html.twig', [
            'liste_des_taches' => $liste_des_taches,
        ]);
    }

    /**
     * @Route("/taches/create", name="app_taches_create")
     */
    public function create(Request $request) : Response {

        $tache = new Tache();
        //creation du formulaire 
        $formulaire = $this->createForm(TacheType::class,$tache);


        //ecouter la requete de l'utilisateur 
        $formulaire->handleRequest($request);

        //tester si le formualaire est envoyé et validé 
        if($formulaire->isSubmitted() && $formulaire->isValid()){

            //on va utiliser doctrine pour enregistrer notre tache 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tache);
            $entityManager->flush();

            //renvoi l'utilisateur vers la page index 
            return $this->redirectToRoute("app_taches");

        }
        

        return $this->render("tache/create.html.twig",[
            'formulaire' => $formulaire->createView()
        ]);

    }
}
