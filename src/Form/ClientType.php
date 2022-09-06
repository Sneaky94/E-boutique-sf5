<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $client = $options["data"]; /*  $options["data"] permet de recuperer l'objet utilisé pour generer
                                        le formulaire : dans le controleur
                                            $form = $this->createForm(ClientType::class, $client);
                                    */
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "Client"         => "ROLE_CLIENT",
                    "Modérateur"     => "ROLE_MODO"
                ],
                "multiple"  => true,
                "expanded"  => true,
                "label" => "Accréditation"
            ])
            ->add('password', TextType::class,[
                "mapped" => false,
            //  "required" => $client->getId()  ? false : true
                "required" => !$client->getId()
            ])
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('civilite')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
