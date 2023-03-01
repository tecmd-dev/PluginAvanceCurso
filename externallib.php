<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 require_once '../../config.php';
 require_once($CFG->dirroot. '/lib/externallib.php');

use core_completion\progress;

class local_uniminuto_external_api extends external_api
{

    public static function uniminuto_get_course_percentage_parameters()
    {
        return new external_function_parameters(
            array(
                'course_id' => new external_value(PARAM_RAW, 'course_id'),
                'user_id' => new external_value(PARAM_CLEAN, 'user_id')
            )
        );
    }

    public function uniminuto_get_course_percentage($course_id,$user_id)
    {

        global $DB;
        $mycourses = enrol_get_users_courses($user_id, true);
        $check_course = false;
        $percentageFinish = "No data";
        foreach ($mycourses as $mycourse) {
            if( $course_id == $mycourse->id ){ $check_course = true; break; }
        }

        if( $check_course )
        {
            $dataCourse = $DB->get_record('course', array('id' => $course_id));
            $modinfo = get_fast_modinfo($course_id, $user_id)->get_cms();//Obtener info del todo el curso
            $completioninfo = new \completion_info($dataCourse);
            $activities_complete = $activities_completion = $activity_completed = 0;

            foreach ($modinfo as $cm) {
                $infosection = $cm->get_section_info(); //Informacion de la seccion
                $hiddensection = ($infosection->available && ($infosection->uservisible || $infosection->visible)) ? false : true ;
                if ( $hiddensection == false ){
                    //Si la actividad se encuentra visible
                    if ($cm->modname == 'label') { continue; }
                    if ( $cm->deletioninprogress == 0 ){
                        if ( $cm->visible || ( $cm->uservisible && $cm->available && $cm->visibleoncoursepage && !$cm->is_stealth()) ) {
                            $activity_completed = $completioninfo->get_data($cm, true, $user_id)->completionstate;
                            $activities_complete += $activity_completed;
                            if ( $cm->completion > 0 ) { $activities_completion++; }
                        }
                    }
                }
            }

            if ($activities_completion != 0)
            {
                $percent_complete = $activities_complete * 100 / $activities_completion;
                $percentageFinish = intval($percent_complete);
            }
        }


        $resp = new stdClass();
        $resp->course_id = $course_id;
        $resp->user_id = $user_id;
        $resp->percentage = $percentageFinish;
        return $resp;
                   
    }

    /* 
     * Notificar al nodo de una actualización
     * Método para estructurar la respuesta
     */
    public static function uniminuto_get_course_percentage_returns()
    {
        return new external_single_structure(
            array(
                'course_id'  => new external_value(PARAM_RAW, ''),
                'user_id'  => new external_value(PARAM_RAW, ''),
                'percentage'  => new external_value(PARAM_RAW, '')
            )
        );
    }

}
