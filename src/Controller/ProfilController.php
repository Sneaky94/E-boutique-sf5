<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

}
