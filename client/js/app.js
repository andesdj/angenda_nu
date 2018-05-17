class EventsManager {
    constructor() {
        this.obtenerDataInicial();
    }


    obtenerDataInicial() {
        $.ajax({
            url: '../server/getEvents.php',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            type: 'GET',
            success: (data) => {
                if (data.msg === "OK") {
                    this.poblarCalendario(data.eventos);
                } else {
                    alert(data.msg);
                    window.location.href = 'index.html';
                }
            },
            error: function (data) {
                alert("error en la comunicación con el servidor");
            }
        });

    }

    poblarCalendario(eventos) {
        $('.calendario').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,basicDay'
            },
            defaultDate: '2017-10-01',
            navLinks: true,
            editable: true,
            eventLimit: true,
            droppable: true,
            dragRevertDuration: 0,
            timeFormat: 'HH:mm',
            events: eventos,
            eventDragStart: (event, jsEvent) => {
                $('.delete-btn').find('img').attr('src', "img/trash-open.png");
                $('.delete-btn').css('background-color', '#a70f19')
            },
            eventDragStop: (event, jsEvent) => {
                var trashEl = $('.delete-btn');
                var ofs = trashEl.offset();
                var x1 = ofs.left;
                var x2 = ofs.left + trashEl.outerWidth(true);
                var y1 = ofs.top;
                var y2 = ofs.top + trashEl.outerHeight(true);
                if (jsEvent.pageX >= x1 && jsEvent.pageX <= x2 &&
                    jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
                    this.eliminarEvento(event, jsEvent);
                }
            },
            eventClick: (event) => {
                if(event !== "") {
                    this.leerEvento(event);
                }
            }
        })
    }

    anadirEvento() {
        var form_data = new FormData();
        form_data.append('titulo', $('#titulo').val());
        form_data.append('start_date', $('#start_date').val());
        if (!$('#allDay').prop('checked')) {
            form_data.append('end_date', $('#end_date').val());
            form_data.append('end_hour', $('#end_hour').val());
            form_data.append('start_hour', $('#start_hour').val());
            form_data.append('allDay', false);
        } else {
            form_data.append('end_date', "");
            form_data.append('end_hour', "");
            form_data.append('start_hour', "");
            form_data.append('allDay', true);
        }

        $.ajax({
            url: '../server/new_event.php',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: (data) =>{
                if (data.msg === "OK") {
                    if ($('#allDay').prop('checked')) {
                        $('.calendario').fullCalendar('renderEvent', {
                            title: $('#titulo').val(),
                            start: $('#start_date').val(),
                            allDay: true
                        });
                    }else {
                        $('.calendario').fullCalendar('renderEvent', {
                            title: $('#titulo').val(),
                            start: $('#start_date').val()+" "+$('#start_hour').val(),
                            end: $('#end_date').val()+" "+$('#end_hour').val(),
                            allDay: false
                        });
                    }
                }else {
                    alert("El mensaje es: " + data)
                }
                this.obtenerDataInicial();
            },
            error: function(data){
                alert("error en la comunicación con el servidor por " + data);
            }
        });
    }

    eliminarEvento(event) {
        var form_data = new FormData();
        form_data.append('id', event.id);
        form_data.append('title', event.title);

        $.ajax({
            url: '../server/delete_event.php',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: (data) => {
                if (data.msg === "OK") {
                    alert('Se ha eliminado el evento exitosamente');
                    window.location.href = 'main.html';
                } else {
                    alert(data.msg);
                }
                this.obtenerDataInicial();
            },
            error: function () {
                alert("error en la comunicación con el servidor al elimina actividad, por: ");
            }
        });
        $('.delete-btn').find('img').attr('src', "img/trash.png");
        $('.delete-btn').css('background-color', '#8B0913')
    }

    leerEvento(evento){
        let id = evento.id,
            start = moment(evento.start).format('YYYY-MM-DD HH:mm:ss');

        var start_date = start.substr(0, 10);

        var form_data = new FormData();
        form_data.append('id', id);
        form_data.append('title', evento.title);
        form_data.append('start_date', start_date);

        $.ajax({
            url: '../server/read_event.php',
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: (response) => {
                $('#id').val(response.id);
                $('#titulo').val(response.title);
                $('#titulo_anterior').val(response.title);
                $('#start_date').val(response.start.substr(0,10));
                $('#start_hour').val(response.start.substr(11,5));
                $('#end_date').val(response.end.substr(0,10));
                $('#end_hour').val(response.end.substr(11,5));
                $('#actualiza').toggleClass('is-hidden', 'is-visible');
                $('#envia').removeClass('is-visible').addClass('is-hidden');
                $('.actualiza').toggleClass('is-hidden' ,'is-visible');
                $('.envia').removeClass('is-visible').addClass('is-hidden');
                $('.delete-btn').addClass('is-hidden');
            },
            error: function () {
                alert('Error de lectura del evento');
            }
        });
    }

    actualizarEvento() {
        var form_data = new FormData();
        form_data.append('id', $('#id').val());
        form_data.append('title', $('#titulo').val())
        form_data.append('title_anterior', $('#titulo_anterior').val())
        form_data.append('start_date', $('#start_date').val());
        form_data.append('end_date', $('#end_date').val());
        form_data.append('start_hour', $('#start_hour').val());
        form_data.append('end_hour', $('#end_hour').val());

        $.ajax({
            url: '../server/update_event.php',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: (response) => {
                if (response.msg === "OK") {
                    alert('Se ha actualizado el evento exitosamente');
                    window.location.href = 'main.html';
                } else {
                    alert(response.msg);
                }
            },
            error: function (response) {
                alert(response.msg);
                alert("error en la comunicación con el servidor al actualizar");
            }
        })
    }

}

function initForm() {
    $('#start_date, #titulo, #end_date').val('');
    $('#start_date, #end_date').datepicker({
        dateFormat: "yy-mm-dd"
    });

    $('#actualiza').addClass('is-hidden');
    $('#envia').addClass('is-visible');

    $('.timepicker').timepicker({
        timeFormat: 'HH:mm',
        interval: 30,
        minTime: '5',
        maxTime: '23:30',
        defaultTime: '7',
        startTime: '5:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
    $('#allDay').on('change', function () {
        if (this.checked) {
            $('.timepicker, #end_date').attr("disabled", "disabled");
        } else {
            $('.timepicker, #end_date').removeAttr("disabled");
        }
    });

}


$(function () {
    initForm();
    var e = new EventsManager();
    $('form').submit(function (event) {
        event.preventDefault();
        e.anadirEvento();
    })
    $('#actualizar').on('click',function (event) {
        event.preventDefault();
        e.actualizarEvento();
    })

});
