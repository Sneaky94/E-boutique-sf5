<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     * 
     * Dans Symfony, toutes les fonctions liées à une route
     * doivent retourner un objet de la classe Response !!!
     * 
     * Les noms des fichiers twig sont toujours donnés à partir
     * du dossier 𝘵𝘦𝘮𝘱𝘭𝘢𝘵𝘦.
     * Les fichiers auront toujours l'extension .𝘩𝘵𝘮𝘭.𝘵𝘸𝘪𝘨
     * 
     */
    #[Route('/test', name:'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'PoleS',
        ]);
    }

    /**
     * @Route("/test-base", name="app_test_base")
     */
    public function base()
    {
        return $this->render("base.html.twig", [
            "nombre" => 5,
            "nom" => "Cérien"
        ]);
    }

    /**
     * @Route("/test/calcul", name="app_test_calcul")
     */
    public function calcul()
    {
        $a = 13;
        $b = 12;
        return $this->render("test/calcul.html.twig", [
            "nb1" => $a,
            "nb2" => $b
        ]);

        /* EXO : Dans le navigateur, cette route doit afficher
            5 + 12 = ...

            (les valeurs 5 et 12 doivent être affichés
                avec les variables.)
         */
    }

    #[Route('/test/calcul/{a}/{b}', requirements: ["a"=>"\d+[.]?\d+", "b"=>"[0-9]+"], name:'app_test_calcul_dynamique')]
    /**
     * @Route("/test/calcul/{a}/{b?}", requirements={"a"="\d*[.]?\d+", "b"="[0-9]+"}, name="app_test_calcul_dynamique")
     * 
     * 
      REGEX : EXpression REGulière
        \d            : n'importe quel chiffre
        [0-9]         : n'importe quel caractère entre 0 et 9
        [.]           : le caractère .
        .             : n'importe quel caractère

        ?             : le caractère précédent peut être présent 0 ou 1 fois
        +             : le caractère précédent doit être au moins 1 fois
        *             : le caractère précédent peut être 0 ou n fois
     * 
     * La partie du chemin qui se trouve entre {} est dynamique. Elle peut être remplacée
     * par n'importe quelle chaîne de caractères.
     * Pour pouvoir utiliser ces valeurs passées dans l'URL, il faut déclarer des arguments dans
     * la fonction 𝘤𝘢𝘭𝘤𝘶𝘭𝘋𝘺𝘯𝘢𝘮𝘪𝘲𝘶𝘦 qui auront le même nom
     */
    public function calculDynamique($a, $b)
    {
        $b = $b ?? 0;
        return $this->render("test/calcul.html.twig", [
            "nb1" => $a,
            "nb2" => $b
        ]);
    }

}
