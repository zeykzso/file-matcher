<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FileSearchType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', null, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('isRegex', 'checkbox', [
                'required' => false
            ]);
    }
}
