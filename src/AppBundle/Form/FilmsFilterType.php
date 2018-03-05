<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class FilmsFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreFilm', Filters\TextFilterType::class)
            ->add('annee', Filters\TextFilterType::class)
            ->add('classification', Filters\TextFilterType::class)
            ->add('sortie', Filters\TextFilterType::class)
            ->add('duree', Filters\TextFilterType::class)
            ->add('genre', Filters\TextFilterType::class)
            ->add('directeur', Filters\TextFilterType::class)
            ->add('scenariste', Filters\TextFilterType::class)
            ->add('acteurs', Filters\TextFilterType::class)
            ->add('synopsis', Filters\TextFilterType::class)
            ->add('langue', Filters\TextFilterType::class)
            ->add('nationalite', Filters\TextFilterType::class)
            ->add('recompense', Filters\TextFilterType::class)
            ->add('illustration', Filters\TextFilterType::class)
            ->add('notations', Filters\TextFilterType::class)
            ->add('metascore', Filters\TextFilterType::class)
            ->add('imdbNotation', Filters\TextFilterType::class)
            ->add('imdbVotes', Filters\TextFilterType::class)
            ->add('imdbId', Filters\NumberFilterType::class)
            ->add('type', Filters\TextFilterType::class)
            ->add('dvd', Filters\TextFilterType::class)
            ->add('boxoffice', Filters\TextFilterType::class)
            ->add('production', Filters\TextFilterType::class)
            ->add('website', Filters\TextFilterType::class)
            ->add('reponse', Filters\TextFilterType::class)
            ->add('id', Filters\NumberFilterType::class)
        
        ;
        $builder->setMethod("GET");


    }

    public function getBlockPrefix()
    {
        return null;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
