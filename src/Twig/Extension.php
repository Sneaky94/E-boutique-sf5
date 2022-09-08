<?php 

namespace App\Twig;

use Twig\TwigTest;
use Twig\TwigFilter;
use App\Entity\Client;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class Extension extends AbstractExtension{

    public function autorisations(Client $client)
    {
        $autorisations = "";
        foreach ($client->getRoles() as $role) {
            $autorisations .= $autorisations ? ", ": "";
            switch($role){
                case "ROLE_Admin":
                    $autorisations .= "Administrateur";
                    break;

                case "ROLE_MODO":
                    $autorisations .= "Moderateur";
                    break;
                
                case "ROLE_CLIENT":
                    $autorisations .= "Client";
            }
        }
        return $autorisations;
    }


    public function numerique($var){
        return is_numeric($var);
    }

    /* Exo : ajouter un filtre pour afficher la civilité correspondant à la lettre enregistrée en bdd */
    public function getFilters()
    {
        return[
            new TwigFilter("autorisations", [$this, "autorisations"])
        ];
    }

    public function getFunction()
    {
        return[
            new TwigFunction("autorisation", [$this, "autorisation"])
        ];
        
    }

    public function getTests()
    {
        return[
            new TwigTest("numerique", [$this, "numerique"])
        ];
    }
}