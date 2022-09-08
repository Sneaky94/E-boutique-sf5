<?php 

namespace App\Twig;

use App\Entity\Client;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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


    /* Exo : ajouter un filtre pour afficher la civilité correspondant à la lettre enregistrée en bdd */
    public function getFilters()
    {
        return[
            new TwigFilter("autorisations", [$this, "autorisations"])
        ];
    }

}