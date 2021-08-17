<?php

namespace texnixe\Related;

return [
    'page.*:after' => function($event, $page) {
        Related::flush();
    },
    'file.*:after' => function() {
        Related::flush();
    },

];
