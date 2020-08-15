<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Form\Type;

use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ApiKeyFormType
 * @package EzPlatform\HubSpot\HubSpot\Repository\Form\Type
 */
class ApiKeyFormType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'apiKey',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'API Key *',
                    'label_attr' => [
                            'class' => 'mb-3',
                        ],
                ]
            )->add(
                'id',
                HiddenType::class
            )->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSubmitData']
            );
    }

    /**
     * @todo when no changes in the input field, apiKey is not sent to controller / Request. dump $form->getData() in controller.
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function onPreSubmitData(FormEvent $event)
    {
        $data = $event->getData();
        $apiKey = $event->getForm()->getData();
        $apiKey->setApiKey($data['apiKey']);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ApiKey::class,
        ));
    }
}
