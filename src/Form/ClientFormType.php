<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // modification d'un client -> le client est passé en paramètre dans options[data]
       if(isset($options['data'])){

           $builder
               ->setMethod('POST')
               ->add('societe', TextType::class, [
                   'required' => true,
                   'label' => 'Societe'
               ])
               ->add('lastName', TextType::class, [
                   'required' => true,
                   'label' => "Nom"
               ])
               ->add('firstName', TextType::class, [
                   'required' => true,
                   'label' => 'Prénom'
               ])
               ->add('email', EmailType::class, [
                   'required' => true,
                   'label' => "E-mail"
               ])
               ->add('telephone', TextType::class, [
                   'required' => true,
                   'label' => 'Numéro de téléphone'
               ])
               ->add('Submit', SubmitType::class, [
                   'label' => "Valider la modification"
               ]);

       }
       // creation d'un client
       else{

           $builder
               ->setMethod('POST')
               ->add('societe', TextType::class, [
                   'required' => true,
                   'label' => 'Societe'
               ])
               ->add('lastName', TextType::class, [
                   'required' => true,
                   'label' => "Nom"
               ])
               ->add('firstName', TextType::class, [
                   'required' => true,
                   'label' => 'Prénom'
               ])
               ->add('email', EmailType::class, [
                   'required' => true,
                   'label' => "E-mail"
               ])
               ->add('telephone', TextType::class, [
                   'required' => true,
                   'label' => 'Numéro de téléphone'
               ])
               ->add('Submit', SubmitType::class, [
                   'label' => "Ajouter au carnet d'adresses"
               ]);
       }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
