<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="app_panier")
     */
    public function index(Session $session): Response
    {
        $panier = $session->get("panier", []); 
        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
        ]);
    }


    /**
     * @Route("/ajouter-produit-{id}", name="app_panier_ajouter", requirements={"id"="\d+"})
     */
    public function ajouter($id, ProduitRepository $pr, Session $session, Request $rq)
    {
        /**
         L'objet de la classe Request contient toutes les valeurs des superglobales de PHP. Pour chaque superglobales, il y a une propriété de $rq qui
         correspond : 
            $rq->query     <=>     $_GET
            $rq->request   <=>     $_POST
            $rq->files     <=>     $_FILES
            ...
            Ces propriétés sont des objets, sur lesquels on peut utiliser les méthodes 
                get('indice'), has(...)
         */
        $quantite = $rq->query->get("qte", 1) ?: 1;
        $produit = $pr->find($id);
        $panier = $session->get("panier", []); // on récupère ce qu'il y a dans le panier en session
        
        $produitDejaDansPanier = false;
        foreach($panier as $indice => $ligne){
            if( $produit->getId() == $ligne["produit"]->getId() ){
                $panier[$indice]["quantite"] += $quantite;
                $produitDejaDansPanier = true;
                break;  // pour sortir de la boucle foreach
            }    
        }
        if( !$produitDejaDansPanier ){
            $panier[] = [ "quantite" => $quantite, "produit" => $produit ];  // on ajoute une ligne au panier => $panier est un array d'array
        }


        $session->set("panier", $panier);  // je remets $panier dans la session, à l'indice 'panier'
        //dd($produit); // dd : Dump and Die
        return $this->redirectToRoute("app_home");
    }
}
