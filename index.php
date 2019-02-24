<?php
/**
 * Kirby 3 Related Pages Plugin
 *
 * @version   0.9.0
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
        'cache' => true,
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
            return Texnixe\Related\Related::getRelated($this, $options);
        }
    ],
    'fileMethods' => [
        'related' => function (array $options = []) {
            return Texnixe\Related\Related::getRelated($this, $options);
        }
    ],
    'hooks' => [
        'page.update:after' => function() {
            Texnixe\Related\Related::flush();
        },
        'page.create:after' => function() {
            Texnixe\Related\Related::flush();
        },
        'file.create:after' => function() {
            Texnixe\Related\Related::flush();
        },
        'page.update:after' => function() {
            Texnixe\Related\Related::flush();
        }
    ]
]);



