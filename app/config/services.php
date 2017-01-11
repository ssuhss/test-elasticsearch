<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use \Elasticsearch\ClientBuilder;

/**
 *  Insert das configurações ao DI
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Componente de URL
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 *  Insert View into DI
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 *  Registro do arquivo json
 */
$di->setShared('json', function () {
    $json = file_get_contents(APP_PATH . '/data/vagas.json');
    return $json;
});

/**
 *  Inserindo Configurações do Elasticsearch ao DI
 */
$di->setShared('elasticsearch', function () {
    $config = $this->getConfig();
    $host = [
        'host' => $config->elasticsearch->host,
    ];

    return ClientBuilder::create()->setHosts($host)->build();
});
