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
/**
 * @package    external_api
 * @copyright  2015 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//Instanciamos la variable global de la base de datos
global $DB;
//Establecemos un token personalizado para nuestro servicio
$tok = '2023.External_api_query.rest';
//Consultamos en la tabla de tokens externos nuestro token 
$tb_tok = $DB->get_records_sql("SELECT id FROM {external_tokens} WHERE token = :token ", array('token' => sha1($tok)));
//Consultamos en la tabla de servicios externos nuestro nombre de servicio
$ext_ser1 = $DB->get_record_sql("SELECT id FROM {external_services} WHERE name = :name LIMIT 1", array('name' => "external_api_rest"));
//Si no se encuentra un token, procederemos a registrarlos en la tabla external_tokens
if (empty($tb_tok)) {
    $registro_token = new stdClass();
    $registro_token->token = sha1($tok);
    $registro_token->tokentype = 0;
    $registro_token->userid = 2;
    $registro_token->externalserviceid = $ext_ser1->id;
    $registro_token->id = null;
    $registro_token->contextid = 1;
    $registro_token->creatorid = 2;
    $registro_token->iprestriction = null;
    $registro_token->validuntil = 0;
    $registro_token->timecreated = time();
    $registro_token->lastaccess = null;
    $DB->insert_record('external_tokens', $registro_token);
}
