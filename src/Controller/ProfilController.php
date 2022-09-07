<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="app_profil")
     */
    public function index(): Response
    {
        /**
            Pour récupérer les informtions de l'utilisateur connecté dans le contrôleur :
                $client = $this->getUser();
            Mais on peut récupérer l'utilisateur connecté directement dans un fichier Twig avec :
                app.user
        */
        return $this->render('profil/index.html.twig');
    }

    /**
     * EXO : ajouter une route dans ce contrôleur pour afficher la liste des produits
     *       dans une liste UL (pour chaque produit afficher le titre et le prix)
     */

    /**
     * @Route("/liste-produits", name="app_profil_liste")
     */
    public function liste(ProduitRepository $produitRepository): Response
    {
        return $this->render("profil/liste_produits.html.twig", [ 
            "produits" => $produitRepository->findAll() 
        ]);
    }

    /**
     * @route("/detail-commande-{id}", name="app_profil_commande", requirements={"id"="\d+"})
    */
    public function detailCommande(Commande $commande): Response{
        if( $commande->getClient() == $this->getUser()){
                   return $this->render("profil/detail_commande.html.twig", [
            "commande" => $commande
        ]); 
        }
        throw $this->createAccessDeniedException("Vous n'avez pas accès à cet URL");
        // $this->addFlash("danger", "ERREUR 403 : Vous n'avez pas acces a cet URL");
        // return $this->redirectToRoute("app_home");
    }

}
