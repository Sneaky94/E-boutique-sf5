<?php

namespace App\Controller\Admin;

use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    # [Route('/stat', name: 'app_stat')]
    
    /**
    * @Route("/admin", name="app_admin_statistique_show")
    */

    public function show(ProduitRepository $pr, CommandeRepository $cr, ClientRepository $clientRepository): Response
    {
        $produits = $pr->findAll();
        $compteur = 0;
        $produitFavori = null;
        foreach ($produits as $prod) {
            if( $prod->getDetails()->count() > $compteur ){
                $produitFavori = $prod;
                $compteur = $prod->getDetails()->count();
            }
        }

        $produitFavori = $pr->produitBestSeller()[0]["produit"];
      
        return $this->render('admin/stat/stats.html.twig', [
            "clients" => $clientRepository->findAll(),
            "commandes" => $cr->findAll(),
            "produits" => $produits,
            "produitFavori" => $produitFavori
        ]);
    }

}
 