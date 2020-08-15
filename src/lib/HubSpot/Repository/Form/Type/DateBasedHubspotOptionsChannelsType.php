<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Form\Type;

use EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateBasedHubspotOptionsChannelsType
 * @package EzPlatform\HubSpot\HubSpot\Repository\Form\Type
 */
class DateBasedHubspotOptionsChannelsType extends AbstractType
{
    /** @var \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver */
    private $hubspotChannelsAndConfigResolver;

    /**
     * DateBasedHubspotOptionsChannelsType constructor.
     * @param \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver
     */
    public function __construct(
        HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver
    ) {
        $this->hubspotChannelsAndConfigResolver = $hubspotChannelsAndConfigResolver;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['data']['apiChannelsData']) {
            $types = [];
            $params = [];
            foreach ($options['data']['apiChannelsData'] as $channel) {
                $types[$channel['channelId']] = $channel['channelId'];
                $params[$channel['channelId']] = $channel;
            }

            $builder->add('values', ChoiceType::class, [
                'choices'   => $types,
                'expanded'  => true,
                'multiple'  => true,
                'required'  => false,
                'choice_attr' => function ($val, $key, $index) {
                    return ['class' => 'channel_type_'.$val];
                },
                'attr' => [
                    'class' => 'options_hubspot__channels_input',
                    'params'    => $params
                ]
            ]);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'hubspot',
        ]);
    }
}
