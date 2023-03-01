<?php
	require_once('../../config.php');


/*-- ----------------------------
-- Show
-- ----------------------------*/
	if ($_GET['id'] == 'url') 
	{
		// Obtener la URL del sitio
		$site_url = $CFG->wwwroot;

		echo $site_url;
	}

/*-- ----------------------------
-- Show
-- ----------------------------*/

	if ($_GET['id'] == 'session') 
	{
		$session_data = array(
		  'userid' => $USER->id,
		  'courseid' => $COURSE->id
		);


		echo json_encode($session_data);
	}

/*-- ----------------------------
-- Show
-- ----------------------------*/

	if ($_GET['id'] == 'pagina') 
	{

		$direccion = $_GET['direccion'];

		$courseid = get_course_from_page_url($direccion);
		echo $courseid;

	}

/*-- ----------------------------
-- Show
-- ----------------------------*/
	function get_course_from_page_url($url) {
	    global $DB;

	    // extraer el id de la actividad de la página de la URL
	    $params = array();
	    parse_str(parse_url($url, PHP_URL_QUERY), $params);
	    $cmid = $params['id'];

	    // consulta SQL para obtener el curso al que pertenece la actividad de la página
	    $sql = "SELECT c.id 
	            FROM {course} c 
	            JOIN {course_modules} cm ON c.id = cm.course 
	            WHERE cm.id = :cmid";
	    $courseid = $DB->get_field_sql($sql, array('cmid' => $cmid));

	    return $courseid;
	}





?>