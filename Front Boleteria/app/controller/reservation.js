function loadReservation(id){
    $.ajax( {
        url: 'http://127.0.0.1:8000/api/events/'+id,
        dataType: 'json',
        success: function(data) {
            if (data.code == 201) {
                var event = data.data;
                if(event != null){
                    var avalible = event.capacity - event.used;
                    while (avalible > 0) {
                        $('#quantity').prepend("<option value='"+avalible+"' >"+avalible+"</option>");
                        if (avalible == 1) {
                            $("#quantity").val('1')
                        }
                        avalible--;
                    }
                    $('#eventId').val(event.event_id);
                    $('#exampleModalLabel').text(event.name);
                }
            }else{
                alert(data.message);
            }
        }
     });
}

function storeReservation(){

    if($('#clientId').val() == ''){
        $.ajax( {
            method: "POST",
            url: 'http://127.0.0.1:8000/api/clients',
            dataType: 'json',
            data: {
                "firstname" : $('#firtsname').val(),
                "lastname" : $('#lastname').val(),
                "type_document" : $('#type_document').val(),
                "document" :$('#document').val(),
                "celphone" : $('#celphone').val()
            },
            success: function(data) {
                if (data.code == 201) {
                    client = data.data;
                    $('#clientId').val(client.client_id);
                    saveReservation();
                }else{
                    alert(data.message);
                }
            }
        });
    }else{
        saveReservation();
    }
}

function saveReservation(){
    $.ajax( {
        method: "POST",
        url: 'http://127.0.0.1:8000/api/reservations',
        dataType: 'json',
        data: {
            "client_id" : $('#clientId').val(),
            "event_id" : $('#eventId').val(),
            "quantity" : $('#quantity').val()
        },
        success: function(data) {
            if (data.code == 201) {
                clearForm();
                $('#exampleModal').modal('toggle');
                alert(data.message);
            }else{
                alert(data.message);
            }
        }
    });
}
