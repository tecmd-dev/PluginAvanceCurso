<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


//Definimos los servicios
$services = array(

    'external_api_rest' => array(
        'functions' => array('local_external_api_get_course_progress'),
        'restrictedusers' => 0,
        'enabled' => 1,
        'timecreated' => time(),
        'shortname' => 'ext_progress',
    ),

);
//Definimos las funciones correspondientes al servicio
$functions = array(
    'local_external_api_get_course_progress' => array(
        'classname' => 'local_uniminuto_external_api',
         'methodname' => 'uniminuto_get_course_percentage',
         'classpath' => 'local/rest_api_unimin/externallib.php',
         'description' => 'Obtener el progreso del curso para los estudiantes.',
         'type' => 'read',
         'services' => array('ext_progress'),
    ),
);
