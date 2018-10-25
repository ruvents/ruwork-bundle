<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\RuworkBundle\Asset\VersionStrategy\FilemtimeStrategy;
use Ruwork\RuworkBundle\Doctrine\NamingStrategy\RuworkNamingStrategy;
use Ruwork\RuworkBundle\EventListener\RedirectAnnotationListener;
use Ruwork\RuworkBundle\ExpressionLanguage\RedirectTargetExpressionLanguage;
use Ruwork\RuworkBundle\Serializer\Encoder\ExcelCsvEncoder;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->defaults()
        ->private();

    // Asset

    $services->set(FilemtimeStrategy::class);

    // Doctrine

    $services->set(RuworkNamingStrategy::class);

    // EventListener

    $services
        ->set(RedirectAnnotationListener::class)
        ->args([
            '$conditionLanguage' => ref('sensio_framework_extra.security.expression_language.default'),
            '$targetLanguage' => ref(RedirectTargetExpressionLanguage::class),
            '$authChecker' => ref('security.authorization_checker'),
            '$tokenStorage' => ref('security.token_storage'),
            '$urlGenerator' => ref('router'),
        ])
        ->tag('kernel.event_subscriber');

    // ExpressionLanguage

    $services->set(RedirectTargetExpressionLanguage::class);

    // Serializer

    $services
        ->set(ExcelCsvEncoder::class)
        ->args([
            '$csvEncoder' => ref('serializer.encoder.csv'),
        ])
        ->tag('serializer.encoder');
};
