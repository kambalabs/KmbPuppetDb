<?php
/**
 * PuppetDb Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
return array(
    'puppetdb' => array(
        'base_uri' => 'https://localhost:8080/',
        'http_options' => array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
        ),
    ),
);
