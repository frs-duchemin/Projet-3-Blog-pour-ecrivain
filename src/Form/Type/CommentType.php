<?php
namespace MicroCMS\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Saisir au moins 2 caractères',
                        'maxMessage' => 'Saisir moins de 30 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ.'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 300,
                        'minMessage' => 'Saisir au moins 2 caractères',
                        'maxMessage' => 'Saisir moins de 300 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ.'
                    ])
                ]
            ]);
    }
    public function getName()
    {
        return 'comment';
    }
}