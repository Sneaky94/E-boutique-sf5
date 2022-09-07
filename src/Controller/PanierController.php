<?php

namespace App\Controller;

use DateTime;
use App\Entity\Detail;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

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
        foreach ($panier as $indice => $ligne) {
            if ($produit->getId() == $ligne["produit"]->getId()) {
                $panier[$indice]["quantite"] += $quantite;
                $produitDejaDansPanier = true;
                break;  // pour sortir de la boucle foreach
            }
        }
        if (!$produitDejaDansPanier) {
            $panier[] = ["quantite" => $quantite, "produit" => $produit];  // on ajoute une ligne au panier => $panier est un array d'array
        }


        $session->set("panier", $panier);  // je remets $panier dans la session, à l'indice 'panier'
        //dd($produit); // dd : Dump and Die
        

        //return $this->redirectToRoute("app_home");
        $nb = 0;
        foreach ($panier as $ligne){
            $nb += $ligne["quantite"];
        }


        return $this->json($nb);
    }

    /** 
     * @Route("/vider", name="app_panier_vider")
     */

    public function vider(Session $session)
    {
        $session->remove("panier");
        return $this->redirectToRoute("app_panier");
    }


    /** 
     * @Route("/supprimer-produit-{id}", name="app_panier_supprimer", requirements={"id"="\d+"})
     */

    public function supprimer(Produit $produit, Session $session)
    {
        $panier = $session->get("panier", []);
        foreach ($panier as $indice => $ligne) {
            if ($ligne['produit']->getId() == $produit->getId()) {
                unset($panier[$indice]);
                break;
            }
        }
        $session->set("panier", $panier);
        return $this->redirectToRoute("app_panier");
    }

    /** 
     * @Route("/valider", name="app_panier_valider")
     * @IsGranted("ROLE_CLIENT")
     */

    public function valider(Session $session, ProduitRepository $produitRepository, EntityManagerInterface $em)
    {
        $panier = $session->get("panier", []);
        if ($panier) {
            $cmd = new Commande;
            $cmd->setDateEnregistrement(new DateTime());
            $cmd->setEtat("en Attente");
            $cmd->setClient($this->getUser()); // affecte l'utilisateur connecté a la propriété 'client' de l'objet $cmd
            $montant = 0;
            foreach ($panier as $ligne) {
                /*  On recupere le produit en BDD plutot que d'utiliser l'objet produit enregistré en session, sinon il y a un bug
                    (lié a la serialisation en session) qui ajoute un doublon dans la table produit
                */
                $produit = $produitRepository->find($ligne["produit"]->getId());
                $montant += $produit->getPrix() * $ligne["quantite"];

                $detail = new Detail;
                $detail->setPrix($produit->getPrix());
                $detail->setQuantite($ligne["quantite"]);
                $detail->setProduit($produit);
                $detail->setCommande($cmd);
                $em->persist($detail); // 'persist' est l'equivalant d'une requete préparée INSERT INTO. La requete est mise en attente.

                $produit->setStock($produit->getStock() - $ligne["quantite"]);
            }
            $cmd->setMontant($montant);
            $em->persist($cmd);
            $em->flush(); // Toutes les requetes en attente sont executées
            $session->remove("panier");
            $this->addFlash("success", "Votre commande a été enregistrée");
            return $this->redirectToRoute("app_panier");
        }
        $this->addFlash("danger", "Le panier est vide. Vous ne pouvez pas valider la commande.");
        return $this->redirectToRoute("app_panier");
    }
}
