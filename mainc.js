$(document).ready(function(){
    tablaCards = $("#tablaCards").DataTable({
        "columnDefs":[{
         "targets": -1,
         "data":null,
         "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-danger btnBorrarCard'>Borrar</button></div></div>"  
        }],
         
         //Para cambiar el lenguaje a español
     "language": {
             "lengthMenu": "Mostrar _MENU_ registros",
             "zeroRecords": "No se encontraron resultados",
             "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
             "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
             "infoFiltered": "(filtrado de un total de _MAX_ registros)",
             "sSearch": "Buscar:",
             "oPaginate": {
                 "sFirst": "Primero",
                 "sLast":"Último",
                 "sNext":"Siguiente",
                 "sPrevious": "Anterior"
            },
              "sProcessing":"Procesando...",
        }
    });

    var fila; //capturar la fila para editar o borrar el registro 
//botón BORRAR
$(document).on("click", ".btnBorrarCard", function(){    
    fila = $(this);
    idcard = parseInt($(this).closest("tr").find('td:eq(0)').text());
    opcion = 1 //borrar
    var respuesta = confirm("¿Está seguro de eliminar el registro: "+idcard+"?");
    if(respuesta){
        $.ajax({
            url: "bd/crudc.php",
            type: "POST",
            dataType: "json",
            data: {opcion:opcion, idcard:idcard},
            success: function(){
                tablaCards.row(fila.parents('tr')).remove().draw();
            }
        });
    }   
});

});