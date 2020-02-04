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
    'file.update:after' => function() {
        Related::flush();
    }
];
