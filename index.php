<?php

namespace texnixe\Related;

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Files;
use Kirby\Cms\Pages;

/**
 * Kirby 3 Related Pages Plugin
 *
 * @version   1.0.1
 * @author    Sonja Broda <info@texniq.de>
 * @copyright Sonja Broda <info@texniq.de>
 * @link      https://github.com/texnixe/kirby-related
 * @license   MIT
 */

load([
    'texnixe\\related\\related' => 'src/Related.php'
], __DIR__);

Kirby::plugin('texnixe/related', [
    'options' => [
        'cache' => option('texnixe.related.cache', true),
        'expires' => (60*24*7), // minutes
        'defaults' => [
            'searchField'      => 'tags',
            'matches'          => 1,
            'delimiter'        => ',',
            'languageFilter'   => false,
        ]
    ],
    'pageMethods' => [
        'related' => function (array $options = []) {
            return Related::getRelated($this, new Pages(), $options);
        }
    ],
    'fileMethods' => [
        'related' => function (array $options = []) {
            return Related::getRelated($this, new Files(), $options);
        }
    ],
    'hooks' => require __DIR__ . '/hooks.php'
]);



