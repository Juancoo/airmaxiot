$(document).ready(function(){
    tablaDevices = $("#tablaDevices").DataTable({
        "columnDefs":[{
         "targets": -1,
         "data":null,
         "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-primary btnEditar'>Editar</button><button class='btn btn-danger btnBorrar'>Borrar</button></div></div>"  
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

    $("#btnNuevo").click(function(){
        $("#formDevices").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Nuevo Dispositivo");            
        $("#modalCRUD").modal("show");    
        $("#serie").prop("disabled", false);
        iduser = null;  
        opcion = 1; //agregar
        
    });  

    
    var fila; //capturar la fila para editar o borrar el registro
    
//botón EDITAR    
$(document).on("click", ".btnEditar", function(){
    fila = $(this).closest("tr");
    iddevice = fila.find('td:eq(0)').text();
    alias = fila.find('td:eq(1)').text();
    iduser = null;
     
    $("#alias").val(alias);
    $("#serie").val(iddevice);
    $("#serie").prop("disabled", true);
    opcion = 2; //editar
    
    $(".modal-header").css("background-color", "#4e73df");
    $(".modal-header").css("color", "white");
    $(".modal-title").text("Editar Dispositivo");            
    $("#modalCRUD").modal("show");  
    
});

//botón BORRAR
$(document).on("click", ".btnBorrar", function(){    
    fila = $(this);
    iddevice = $(this).closest("tr").find('td:eq(0)').text();
    opcion = 3 //borrar
    var respuesta = confirm("¿Está seguro de eliminar el registro: "+iddevice+"?");
    if(respuesta){
        $.ajax({
            url: "bd/crud2.php",
            type: "POST",
            dataType: "json",
            data: {opcion:opcion, iddevice:iddevice},
            success: function(){
                tablaDevices.row(fila.parents('tr')).remove().draw();
            }
        });
    }   
});
    
    $("#formDevices").submit(function(e){
        e.preventDefault();  
        alias = $.trim($("#alias").val());
        iddevice = $.trim($("#serie").val());
            
        $.ajax({
            url: "bd/crud2.php",
            type: "POST",
            dataType: "json",
            data: {iddevice:iddevice, alias:alias, iduser:iduser, opcion:opcion},
            success: function(data){  
                console.log(data);
                iddevice = data[0].iddevice;            
                alias = data[0].alias;
                iduser = data[0].iduser;
                if(opcion == 1){tablaDevices.row.add([iddevice,alias]).draw();}
                else{tablaDevices.row(fila).data([iddevice,alias]).draw();}  
            }        
        });
        $("#modalCRUD").modal("hide");    
        
    }); 
});    
