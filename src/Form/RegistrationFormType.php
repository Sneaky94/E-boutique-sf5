<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex as UnidataRegex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les C.G.V.',
                    ]),
                ],
                'label' => 'Accepter les C.G.V.'
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide !',
                    ]),
                    // new Length([
                    //     'min' => 4,
                    //     'minMessage' => 'Le mot de passe doit comporter {{ limit }} caractère minimum',
                    //     // max length allowed by Symfony for security reasons
                    //     'max' => 32,
                    //     'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractère',

                    // ]),
                    new Regex([
                        "pattern" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{6,32})$/",
                        "message" => "Le mot de passe ne correspond pas au modèle"
                    ])
                ],
                'help' => "Modèle : au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère parmi -+!*$@%_"
            ])
            ->add("civilite", ChoiceType::class , [
                "label" => "Civilité",
                "choices" =>[
                    "Mme" => "f",
                    "M." => "h",
                    "NSP" => "a"
                ],
                "expanded" => true
            ])
            ->add("prenom", null , [
                "label" => "Prénom"
            ])
            ->add("nom")
            ->add("adresse", TextareaType::class)
            ->add('telephone')
            ->add("code_postal", TextType::class, [
                "label" => "Code postal",
                "constraints" => [
                    new Regex([
                        "pattern" => "/^((0[1-9])|([1-8][0-9])|(9[0-57-8]))[0-9]{3}$/",
                        "message" => "Ce n'est pas un code postal français valide"
                    ])
                    ],
                    "required" => false
            ])
            ->add("ville")
            ->add("pays")
            ->add("email", EmailType::class, [
                "required" => false
            ])
            ->add("enregistrer", SubmitType::class, [
                "attr" => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
