<?php

namespace texnixe\Related;

return [
    'page.update:after' => function() {
        Related::flush();
    },
    'page.create:after' => function() {
        Related::flush();
    },
    'file.create:after' => function() {
        Related::flush();
    },
    'page.update:after' => function() {
        Related::flush();
    }
];
