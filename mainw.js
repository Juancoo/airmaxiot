$(document).ready(function(){
    tablaWorkers = $("#tablaWorkers").DataTable({
       "columnDefs":[{
        "targets": -1,
        "data":null,
        "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-primary btnEditarW'>Editar</button><button class='btn btn-danger btnBorrarW'>Borrar</button></div></div>"  
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

    $("#btnNuevoW").click(function(){
        $("#formWorkers").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Nuevo Trabajador");            
        $("#modalCRUD").modal("show"); 
        $("#cedula").prop("disabled", false);      
        opcion = 1; //agregar
        
    });

    var fila; //capturar la fila para editar o borrar el registro
    
    //botón EDITAR    
    $(document).on("click", ".btnEditarW", function(){
        fila = $(this).closest("tr");
        cedula = fila.find('td:eq(0)').text();
        nombre = fila.find('td:eq(1)').text();
        apellido = fila.find('td:eq(2)').text();
        direccion = fila.find('td:eq(3)').text();
        telefono = fila.find('td:eq(4)').text();
        codcard = fila.find('td:eq(5)').text();
        
        $("#cedula").val(cedula);
        $("#cedula").prop("disabled", true);
        $("#nombre").val(nombre);
        $("#apellido").val(apellido);
        $("#direccion").val(direccion);
        $("#telefono").val(telefono);
        $("#codcard").val(codcard);
        opcion = 2; //editar
        
        $(".modal-header").css("background-color", "#4e73df");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Editar Trabajador");            
        $("#modalCRUD").modal("show");  
        
    });

    //botón BORRAR
$(document).on("click", ".btnBorrarW", function(){    
    fila = $(this);
    cedula = $(this).closest("tr").find('td:eq(0)').text();
    opcion = 3 //borrar
    var respuesta = confirm("¿Está seguro de eliminar al trabajador: "+cedula+"?");
    if(respuesta){
        $.ajax({
            url: "bd/crudw.php",
            type: "POST",
            dataType: "json",
            data: {opcion:opcion, cedula:cedula},
            success: function(){
                tablaWorkers.row(fila.parents('tr')).remove().draw();
            }
        });
    }   
});

function validarCedula(){
    r=false;
    cedula = $.trim($("#cedula").val()); 
    tamcedula = cedula.length;
    total = 0;

    if(tamcedula === 10 && /^[0-9]+$/.test(cedula)){
        for(i = 0; i < tamcedula-1; i++){
            if(i%2 === 0){
                aux = cedula.charAt(i) * 2;
                if(aux > 9){
                    aux = aux-9;
                }
                total += aux;
            }else{
                total += parseInt(cedula.charAt(i));
            }
        }
        total = total % 10 ? 10 - total % 10 : 0;
        if(cedula.charAt(tamcedula-1) == total){
            r=true;  
        }else{
            r=false;
        }
        
    }
    
    return r; 
}

    $("#formWorkers").submit(function(e){
        e.preventDefault();  
        cedula = $.trim($("#cedula").val());  
        nombre = $.trim($("#nombre").val());
        apellido = $.trim($("#apellido").val());
        direccion = $.trim($("#direccion").val());
        telefono = $.trim($("#telefono").val());
        codcard = $.trim($("#codcard").val());

        numerico = /^[0-9]+$/;
        texto = /^[a-zA-Z]+$/;

        //validar
        if(cedula == ''){
            $("#lbcedula").show();
            setTimeout(function(){
                $("#lbcedula").html("<span style='color:red;'>Complete el campo.</span>").fadeOut(5000);
            });
            $("#cedula").focus();
            return false;
        }else if(validarCedula() == false){
            $("#lbcedula").show();
            setTimeout(function(){
                $("#lbcedula").html("<span style='color:red;'>Cedula de identidad incorrecta.</span>").fadeOut(5000);
            });
            $("#cedula").focus();
            return false;
        }
        if(nombre == ''){
            $("#lbnombre").show();
            setTimeout(function(){
                $("#lbnombre").html("<span style='color:red;'>Complete el campo.</span>").fadeOut(5000);
            });
            $("#nombre").focus();
            return false;
        }else if(texto.test(nombre) == false){
            $("#lbnombre").show();
            setTimeout(function(){
                $("#lbnombre").html("<span style='color:red;'>Solo se permite texto.</span>").fadeOut(5000);
            });
            $("#nombre").focus();
            return false;
        }
        if(apellido == ''){
            $("#lbapellido").show();
            setTimeout(function(){
                $("#lbapellido").html("<span style='color:red;'>Complete el campo.</span>").fadeOut(5000);
            });
            $("#apellido").focus();
            return false;
        }else if(texto.test(apellido) == false){
            $("#lbapellido").show();
            setTimeout(function(){
                $("#lbapellido").html("<span style='color:red;'>Solo se permite texto.</span>").fadeOut(5000);
            });
            $("#apellido").focus();
            return false;
        }
        if(direccion == ''){
            $("#lbdireccion").show();
            setTimeout(function(){
                $("#lbdireccion").html("<span style='color:red;'>Complete el campo.</span>").fadeOut(5000);
            });
            $("#direccion").focus();
            return false;
        }
        if(telefono == ''){
            $("#lbtelefono").show();
            setTimeout(function(){
                $("#lbtelefono").html("<span style='color:red;'>Complete el campo.</span>").fadeOut(5000);
            });
            $("#telefono").focus();
            return false;
        }else if(numerico.test(telefono) == false){
            $("#lbtelefono").show();
            setTimeout(function(){
                $("#lbtelefono").html("<span style='color:red;'>Solo se permiten números.</span>").fadeOut(5000);
            });
            $("#telefono").focus();
            return false;
        }
        if(codcard == ''){
            $("#lbcodcard").show();
            setTimeout(function(){
                $("#lbcodcard").html("<span style='color:red;'>Complete el campo.</span>").fadeOut(5000);
            });
            $("#codcard").focus();
            return false;
        }
            
        $.ajax({
            url: "bd/crudw.php",
            type: "POST",
            dataType: "json",
            data: {cedula:cedula, nombre:nombre, apellido:apellido, direccion:direccion,telefono:telefono, codcard:codcard, opcion:opcion},
            success: function(data){  
                console.log(data);
                cedula = data[0].cedula;          
                nombre = data[0].nombre;
                apellido = data[0].apellido;
                direccion = data[0].direccion;
                telefono = data[0].telefono;
                codcard = data[0].codcard;
                if(opcion == 1){tablaWorkers.row.add([cedula,nombre,apellido,direccion,telefono,codcard]).draw();}
                else{tablaWorkers.row(fila).data([cedula,nombre,apellido,direccion,telefono,codcard]).draw();}  
            }        
        });
        $("#modalCRUD").modal("hide");    
        
    });
}); 