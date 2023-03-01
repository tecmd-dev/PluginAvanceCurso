// Función para obtener la ruta canonica del plugin
// Sólo aplica para landing del curso y el recurso de página
function address() 
{
   let url = window.location.href;
   url = url.replace('/mod/page', '/course');


    var pos = url.indexOf('/course');
    if (pos >= 0) {
      url = url.substring(0, pos);
    }
    return url;
}


let servidor = address();


// Llamada a la ruta con fetch
fetch(servidor + '/local/rest_api_unimin/dominio.php?id=url')
   .then(response => response.text())
   .then(data => {
      // Almacenar la respuesta en una variable
      const dominio = data.trim();
      let course_id = 0;

      //Función para obtener el Id del usuario
      async function obtenerUsuario() {
         try {
            const response = await fetch(servidor + '/local/rest_api_unimin/dominio.php?id=session');
            const data = await response.json();
            const usuario = data['userid'];
            console.log("El ID del usuario es: " + usuario);


            //Validar si el usuario esta en un curso o página (actividad)
            var resultado = buscarPalabraEnUrl('course');

            if (resultado == true) 
            {
                // Obtener el ID del curso
                var parametroEjemplo = obtenerParametro("id");

                if (parametroEjemplo != null) {
                    course_id = parametroEjemplo;
                } else {
                   console.log("El ID del curso no está presente en la URL");
                }

                console.log("El ID del curso es: " + course_id);
            }

            if (resultado == false) 
            {
                let direccion = window.location.href;       

                fetch(servidor + '/local/rest_api_unimin/dominio.php?id=pagina&direccion='+direccion)
                            .then(response => response.text())
                            .then(data => {          
                              course_id =  data.trim();
                              console.log("El curso de la pagina es: " + course_id);
                            })
                            .catch(error => console.error(error));
               }
            

            //-----------------------------------------------------------------------------------------------
            //Inicio Consumo del Ws

            setTimeout(function () {

               let ws = '/webservice/rest/server.php?wstoken=e84595d5d11e21fa2c236c98c75d81da4cb79a24&wsfunction=local_external_api_get_course_progress&course_id=' + course_id + '&user_id=' + usuario + '&moodlewsrestformat=json';

               fetch(servidor + ws)
                  .then(response => response.json())
                  .then(data => {
                     console.log(data);
                     document.getElementById("progress_2023").innerHTML = data.percentage;
                  })
                  .catch(error => console.error(error));
            }, 1000);
            //Fin Consumo del Ws
            //------------------------------------------------------------------------------------------------
            
         } catch (error) {
            console.error(error);
         }
      }

      var usuario = setTimeout(() => obtenerUsuario(), 1000);

      //------------------------------------------------------------------------------------------------------
      // Función para obtener un id del curso por URL
      function obtenerParametro(nombreParametro) {
         // Obtener la URL actual
         var url = window.location.href;

         // Obtener el valor del id usando una expresión regular
         var regex = new RegExp('[?&]' + nombreParametro + '(=([^&#]*)|&|#|$)');
         var results = regex.exec(url);

         // Si no se encontró el id, devolver null
         if (!results || !results[2]) {
            return null;
         }

         // Decodificar el valor y devolverlo
         return decodeURIComponent(results[2].replace(/\+/g, ' '));
      }
      // Fin función obtener ID


      //------------------------------------------------------------------------------------------------------

      function buscarPalabraEnUrl(palabra) {
        // Obtener la URL actual
        var url = window.location.href;

        // Buscar la palabra en la URL
        if (url.includes(palabra)) {
          return true;
        } else {
          return false;
        }
      }



   })
   .catch(error => console.error(error));