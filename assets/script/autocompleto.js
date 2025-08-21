// FUNCION AUTOCOMPLETE 
$(function() {
  $("#search_cliente_activo").autocomplete({
    source: "class/busqueda_autocompleto.php?Busqueda_Clientes_Activos=si",
    minLength: 1,
    select: function(event, ui) { 
      $('#codcliente').val(ui.item.codcliente);
      $('#nrodocumento').val(ui.item.cedcliente);
    }  
  });
});


$(function() {
  $("#search_cliente_general").autocomplete({
    source: "class/busqueda_autocompleto.php?Busqueda_Clientes_General=si",
    minLength: 1,
    select: function(event, ui) { 
      $('#codcliente').val(ui.item.codcliente);
      $('#nrodocumento').val(ui.item.cedcliente);
    }  
  });
});


$(function() {
  $("#search_terreno").autocomplete({
    source: "class/busqueda_autocompleto.php?Busqueda_Terrenos=si",
    minLength: 1,
    select: function(event, ui) { 
      $('#codterreno').val(ui.item.codterreno);
    }  
  });
});