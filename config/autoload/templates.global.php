<?php

use Framework\View\Twig\Extension\RouteExtension;
use Framework\View\Twig\TwigRender;
use Interop\Container\ContainerInterface;
use Twig\Environment;

return [
    'dependencies' => [
        'factories' => [
            TwigRender::class => function (ContainerInterface $container) {
                return new TwigRender(
                    $container->get(Environment::class),
                    $container->get('config')['templates']['extension'],
                );
            },
            Environment::class => function (ContainerInterface $container) {
                $debug = $container->get('config')['debug'];
                $config = $container->get('config')['twig'];

                $loader = new Twig\Loader\FilesystemLoader();
                $loader->addPath($config['template_dir']);

                $environment = new Twig\Environment($loader, [
                    'cache' => $debug ? false : $config['cache_dir'],
                    'debug' => $debug,
                    'strict_variables' => $debug,
                    'auto_reload' => $debug,
                ]);

                if ($debug) {
                    $environment->addExtension(new Twig\Extension\DebugExtension());
                }

                $environment->addExtension($container->get(RouteExtension::class));

                return $environment;
            },
        ],
    ],

    'templates' => [
        'extension' => '.html.twig',
    ],

    'twig' => [
        'template_dir' => 'views',
        'cache_dir' => 'var/cache/twig',
        'extensions' => [],
    ],
];