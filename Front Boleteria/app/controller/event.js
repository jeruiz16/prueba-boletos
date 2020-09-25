function listEvents(){
    $.ajax( {
        url: 'http://ec2-18-222-175-185.us-east-2.compute.amazonaws.com//api/events',
        dataType: 'json',
        success: function(data) {
            if (data.code == 200) {
                var eventos = data.data;
                if(Array.isArray(eventos) && eventos.length){
                    var view = `<div class="MultiCarousel" data-items="1,3,5,6" data-slide="1" id="MultiCarousel"  data-interval="1000">
                            <div class="MultiCarousel-inner">`;
                    eventos.forEach(evento => {
                        view += `<div class="item">
                                    <div class="pad15">
                                        <p class="lead"> <strong>` + evento.name + `</strong></p>
                                        <p> Fecha del evento: ` + evento.date + `</p>
                                        <p> Capacidad maxima del evento: ` + evento.capacity + `</p>
                                        <a href="#" class="btn btn-primary btn-success" data-toggle="modal" data-target="#exampleModal" onClick="loadReservation(`+evento.event_id+`)">Reservar <span class="glyphicon glyphicon-floppy-save"></span></a>
                                    </div>
                                </div>`;
                    });
                    view += `</div>
                            <button class="btn btn-primary leftLst"><</button>
                            <button class="btn btn-primary rightLst">></button>
                            </div>`;
                    $('#targeCarousel').append(view);
                }
            }else{
                alert(data.message);
            }
        }
    });
}

function listEventsTable() {
    $('#eventsTable').DataTable({
        "ajax": {
            url: 'http://ec2-18-222-175-185.us-east-2.compute.amazonaws.com//api/events/',
            dataType: 'json'
        },
        "columns": [
            { "data": "event_id" },
            { "data": "name" },
            { "data": "date" },
            { "data": "capacity" }
        ],
        destroy: true,
    });
}

function storeEvent() {
    $.ajax( {
        method: "POST",
        url: 'http://ec2-18-222-175-185.us-east-2.compute.amazonaws.com//api/events',
        dataType: 'json',
        data: {
            "name" : $('#name').val(),
            "date" : $('#date').val(),
            "capacity" : $('#capacity').val()
        },
        success: function(data) {
            if (data.code == 201) {
                var event = data.data;
                alert('Se creo el evento y se asigna el codigo ' + event.event_id);
                location.reload();
            }else{
                alert(data.message);
            }
        }
    });
}