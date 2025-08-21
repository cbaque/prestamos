$(function() {
  $(".calendario").flatpickr({
    allowInput: true,
    dateFormat: "d-m-Y"});
});

$(function() {
  $(".fnacimiento").flatpickr({
    maxDate: "today",
    allowInput: true,
    dateFormat: "d-m-Y",
    static : true
  });
});

$(function() {
  $("#fechapagocuota").flatpickr({
    allowInput: true,
    dateFormat: "d-m-Y"
  });
});

$(function() {
  $(".fechapagocuota").flatpickr({
    allowInput: true,
    dateFormat: "d-m-Y",
    static : true
  });
});

$(function() {
  $("#fechalimite").flatpickr({
    allowInput: true,
    dateFormat: "d-m-Y",
    minDate: "today"
  });
});

$(function() {
  $(".fechalimite").flatpickr({
    allowInput: true,
    dateFormat: "d-m-Y",
    minDate: "today",
    static : true
  });
});

$(function() {
  $("#desde").flatpickr({
    dateFormat: "d-m-Y"});
});

$(function() {
  $("#hasta").flatpickr({
    dateFormat: "d-m-Y"});
});

$(function() {
  $("#fecha").flatpickr({ 
    allowInput: true,
    dateFormat: "d-m-Y",
    static : true });
});


$('body').on('focus',".tiempo_hora", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});


$('body').on('focus',"#fecha_hora", function(){
    $(this).flatpickr({
       enableTime: true,
       allowInput: true,
       dateFormat: "d-m-Y H:i"
    });
});

$(function() {
  $(".hora_modal").flatpickr({ 
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    defaultDate: "",
    static : true 
  });
});

/*$("#dpFecha").flatpickr({locale: "es"})
$("#dpFecha2").flatpickr({
    locale: "es",
    dateFormat: "d/m/Y",
    maxDate: (new Date()).setFullYear((new Date()).getFullYear() - 18)
})
$("#dpFecha3").flatpickr({
    locale: "es",
    dateFormat: "d/m/Y",
    maxDate: "today"
})
$("#dpFecha4")
    .flatpickr({
        locale: "es",
        dateFormat: "d/m/Y",
        minDate: "today"
    })
    .setDate("today")
$("#dpFecha5").flatpickr({
    locale: "es",
    dateFormat: "d/m/Y",
    disable: [function (date) {
        var dia = date.getDate()
 
        return ([15, 30].indexOf(dia) == -1)
    }]
})
$("#dpFecha6").flatpickr({
    locale: "es",
    dateFormat: "d/m/Y",
    disable: [function (date) {
        var diaSemana = date.getDay()
 
        return ([6, 0].indexOf(diaSemana) != -1)
    }]
})*/


/*$('body').on('focus',"#inicio_anestesia", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});

$('body').on('focus',"#final_anestesia", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});

$('body').on('focus',"#inicio_cirugia", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});

$('body').on('focus',"#final_cirugia", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});

$('body').on('focus',"#llegada_paciente", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});

$('body').on('focus',"#salida_paciente", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});

$('body').on('focus',"#final_cirugia", function(){
    $(this).flatpickr({
       enableTime: true,
       noCalendar: true,
       dateFormat: "H:i",
       defaultDate: ""
    });
});*/