<?php
/**
 *  Registro da estrutura de pastas e namespaces
 *
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs([ $config->application->controllersDir ])->register();
$loader->registerNamespaces(array( 'Library' => APP_PATH . '/library' ));
$loader->register();