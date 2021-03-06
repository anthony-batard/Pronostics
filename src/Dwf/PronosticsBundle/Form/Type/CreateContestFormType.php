<?php
namespace Dwf\PronosticsBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class CreateContestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('contestName');
        $builder->add('event', 'entity', array(
                'label'         => false,
                'choice_label'  => false,
                'class'         => 'Dwf\PronosticsBundle\Entity\Event',
                'query_builder' => function ($repository) use ($options) {
                    return $repository->createQueryBuilder('e')
                                    ->where('e.active = :active')
                                    ->andWhere('e.finishDate > :date')
                                    ->setParameter('date', date("YmdHis"))
                                    ->setParameter('active', true)
                                    ->orderBy('e.startDate', 'ASC')
                                    ->orderBy('e.name', 'ASC');
                },
                'expanded'      => true,
                'multiple'      => false
        ));
        $builder->add('owner', 'entity_hidden', array('class' => 'Dwf\PronosticsBundle\Entity\User'));
    }

    public function getName()
    {
        return 'dwf_pronosticsbundle_create_contest_form_type';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Dwf\PronosticsBundle\Entity\Contest'
        ));
    }

}