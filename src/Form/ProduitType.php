<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class, [
                "label"         => "Référence n°",
                "constraints"   => [
                    new Length([
                        "max" => 20,
                        "maxMessage" => "La référence ne doit pas dépaaser 20 caracères",
                        "min"   => 5,
                        "minMessage" => "La référence doit comporter au moins 5 caractères"
                    ])
                ]
            ])
            ->add('categorie', TextType::class, [
                "label" => "Catégorie"
            ])
            ->add('titre')
            ->add('description')
            ->add('photo', FileType::class, [
                /* 
                    L'option 'mapped' avec la valeur 'false' signifie que le champ du formulaire ne doit pas être
                    lié à une propriété de l'objet utilisé pour générer le formulaire. 
                    La valeur de la propriété 'photo' de l'objet ne sera donc pas modifié automatiquement par le formulaire.
                 */
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes"         => [ "image/gif", "image/jpeg", "image/png" ],
                        "mimeTypesMessage"  => "Les formats autorisés sont gif, jpg, png",
                        "maxSize"           => "2048k",
                        "maxSizeMessage"    => "Le fichier ne peut pas peser plus de 2Mo"
                    ])
                ]
            ])
            ->add('prix')
            ->add('stock')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
