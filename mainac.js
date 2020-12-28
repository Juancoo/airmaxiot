$(document).ready(function(){
    tablaAssignc = $("#tablaAssignc").DataTable({
       "columnDefs":[{
        "targets": -1,
        "data":null,
        "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-danger btnBorrar'>Borrar</button></div></div>"  
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
    $("#btnNuevoAc").click(function(){
        $("#formAssignc").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Nuevo Acceso");            
        $("#modalCRUD").modal("show"); 

        idassign=null;
        trabajador=null;
        alias=null;  
        opcion = 1; //agregar
    });
    var fila; //capturar la fila para editar o borrar el registro
        //botón BORRAR
$(document).on("click", ".btnBorrar", function(){    
    fila = $(this);
    idassign = $(this).closest("tr").find('td:eq(0)').text();
    opcion = 2 //borrar
    var respuesta = confirm("¿Está seguro de eliminar el acceso: "+idassign+"?");
    if(respuesta){
        $.ajax({
            url: "bd/crudac.php",
            type: "POST",
            dataType: "json",
            data: {opcion:opcion, idassign:idassign},
            success: function(){
                tablaAssignc.row(fila.parents('tr')).remove().draw();
            }
        });
    }   
});

    $("#formAssignc").submit(function(e){
        e.preventDefault();    
        cedula = $("#id_to_workers").val();
        iddevice = $("#id_to_device").val();
        $.ajax({
            url: "bd/crudac.php",
            type: "POST",
            dataType: "json",
            data: {cedula:cedula, iddevice:iddevice, idassign:idassign,trabajador:trabajador,alias:alias,opcion:opcion},
            success: function(data){  
                console.log(data);
                idassign = data[0].idassign;            
                cedula = data[0].cedula;
                trabajador = data[0].trabajador;
                iddevice = data[0].iddevice;
                alias = data[0].alias;
                if(opcion == 1){tablaAssignc.row.add([idassign,cedula,trabajador,alias]).draw();}
                       
            }        
        });
        $("#modalCRUD").modal("hide");    
        
    });   
}); 