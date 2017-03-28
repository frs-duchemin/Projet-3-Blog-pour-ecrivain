<?php

namespace MicroCMS\Form\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class,array(
                'constraints' => array(
                    new NotBlank([
        'message' => 'Monsieur Forteroche, êtes-vous sûr de laisser ce champ vide ??'
    ]),

                ),
                'attr' => array('class'=>'form-control')

            ));
    }

    public function getName()
    {
        return 'article';
    }
}