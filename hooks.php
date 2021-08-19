<?php

namespace texnixe\Related;

return [
    'page.*:after' => function() {
        Related::flush();
    },
    'file.*:after' => function() {
        Related::flush();
    },

];
