<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreFilm')
            ->add('annee')
            ->add('classification')
            ->add('sortie')
            ->add('duree')
            ->add('genre')
            ->add('directeur')
            ->add('scenariste')
            ->add('acteurs')
            ->add('synopsis')
            ->add('langue')
            ->add('nationalite')
            ->add('recompense')
            ->add('illustration')
            ->add('notations')
            ->add('metascore')
            ->add('imdbNotation')
            ->add('imdbVotes')
            ->add('imdbId')
            ->add('type')
            ->add('dvd')
            ->add('boxoffice')
            ->add('production')
            ->add('website')
            ->add('reponse')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Films'
        ));
    }
}
