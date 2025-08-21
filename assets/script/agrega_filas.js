//FUNCION PARA AGREGAR Y ELIMINAR CAMPOS DINAMICAMENTE
var cont=1;
    
//FUNCION AGREGA CAPACIDAD
function Add()  //Esta la funcion que agrega las filas segunda parte :
{
    cont++;
    //autocompletar//
    var indiceFila=1;
    myNewRow = document.getElementById('tabla').insertRow(-1);
    myNewRow.id=indiceFila;
    myNewCell=myNewRow.insertCell(-1);
    myNewCell.innerHTML='<div class="form-group has-feedback"><div class="fileinput fileinput-new" data-provides="fileinput"><div class="form-group has-feedback"><label class="control-label">Foto de Terreno: </label><div class="input-group"><div class="form-control" data-trigger="fileinput"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg><span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-success btn-file"><span class="fileinput-new"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg> Selecciona Imagen</span><span class="fileinput-exists"><i data-feather="image"></i> Cambiar</span><input type="file" class="btn btn-default" data-original-title="Subir Imagen" data-rel="tooltip" placeholder="Suba su Imagen" name="file[]" id="file" autocomplete="off" title="Buscar Archivo"></span><a href="#" class="input-group-addon btn btn-dark fileinput-exists" data-dismiss="fileinput"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Quitar</a></div><small><p>Para Subir la Foto debe tener en cuenta:<br> * La Imagen debe ser extension.jpeg,jpg</p></small></div></div></div>';
    indiceFila++;
}

//FUNCION BORRAR CAPACIDAD
function Delete() {
    var table = document.getElementById('tabla');
    if(table.rows.length > 1)
    {
       table.deleteRow(table.rows.length -1);
       cont--;
    }
}

////////////FUNCION ASIGNA VALOR DE CONT PARA EL FOR DE MOSTRAR DATOS MP-MOD-TT////////
function asigna() {
   valor=document.form.var_cont.value=cont;
}