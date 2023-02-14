<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config.php';
require_once($CFG->dirroot . '/lib/externallib.php');

use core_completion\progress;

class local_uniminuto_external_api extends external_api
{

    /**
     * Declara y retorna los parámetros a ser usados en la función del web services.
     *
     * @return array Devuelve un array con todos los parámetros que harán parte
     * de nuestro web services.
     */

    public static function uniminuto_get_course_percentage_parameters()
    {
        return new external_function_parameters(
            array(
                'course_id' => new external_value(PARAM_RAW, 'course_id'),
                'user_id' => new external_value(PARAM_CLEAN, 'user_id')
            )
        );
    }

    /**
     * Función que retorna el porcentaje de avance en un curso con un usuario y un curso especifico 
     * 
     * @param int $course_id id del curso a consultar
     * @param int $user_id id del usuario a consultar
     * 
     * @return object objeto que contiene el id del curso, el id del usuario y el porcentaje de
     * avance del curso.
     */

    public static function uniminuto_get_course_percentage($course_id, $user_id)
    {
        global $DB;

        $dataCourse = $DB->get_record('course', array('id' => $course_id));

        if ($dataCourse) {

            $percentage = progress::get_course_progress_percentage($dataCourse, $user_id);
            $percentageFinish = ($percentage === null) ? "No data" : $percentage;
            $resp = new stdClass();
            $resp->course_id = $course_id;
            $resp->user_id = $user_id;
            $resp->percentage = $percentageFinish;
            return $resp;

        } else {

            $resp = new stdClass();
            $resp->course_id = "No data";
            $resp->user_id = "No data";
            $resp->percentage = "No data";
            return $resp;
        }
    }


    /**
     * Función que retorna la respuesta del web services 
     * 
     * @return array retorna la respuesta como un array con cada uno de los valores
     * especificados en la función anterior uniminuto_get_course_percentage
     */

    public static function uniminuto_get_course_percentage_returns()
    {
        return new external_single_structure(
            array(
                'course_id'  => new external_value(PARAM_RAW, ''),
                'user_id'  => new external_value(PARAM_RAW, ''),
                'percentage'  => new external_value(PARAM_RAW, ''),
            )
        );
    }
}
