<?php

 
namespace App\Controller;
 
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AdresseLivraisonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class StripeController extends AbstractController
{
    /**
     * @Route("/stripe", name="app_stripe")
     */
    public function index(Session $session, AdresseLivraisonRepository $adresseLivraisonRepository): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'panier' => $session->get("panier",[]),
            'adresses' => $adresseLivraisonRepository->recherche($this->getUser()) ?: null
        ]);
    }
 
 
    /**
     * @Route("/stripe/create-charge", name="app_stripe_charge", methods={"POST"})
     */
    public function createCharge(Request $request, Session $session)
    {
        $total = 0;
        foreach($session->get("panier", []) as $panier){
            $total = $total + $panier["quantite"] * $panier["produit"]->getPrix();
        }

        
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Charge::create ([
                "amount" => $total * 100 ,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "description" => "Message de teste"
        ]);
        $this->addFlash(
            'success',
            'Paiement effectué avec succès !'
        );
        return $this->redirectToRoute('app_panier_valider', [], Response::HTTP_SEE_OTHER);
    }




    
}


