<?php

namespace MicroCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Saisir au moins 2 caractères',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ.'
                    ])], 'label' => 'Nom'))
            ->add('password', RepeatedType::class, array(
                'constraints' => [
                    new Length([
                        'min' => 4,

                        'minMessage' => 'Saisir au moins 4 caractères',

                    ]),
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ.'
                    ])],
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit correspondre',
                'options' => array('required' => true),
                'first_options' => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Répéter le mot de passe'),
            ))
            ->add('role', ChoiceType::class, array(
                'choices' => array('Admin' => 'ROLE_ADMIN')
            ));
    }

    public function getName()
    {
        return 'user';
    }
}
