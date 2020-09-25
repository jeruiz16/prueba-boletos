function loadClient(){
    $('#clientId').val('');
    var documentId = $('#document').val();
    $.ajax( {
        url: 'http://127.0.0.1:8000/api/clients/'+documentId,
        dataType: 'json',
        success: function(data) {
            if (data.code == 201) {
                var client = data.data;
                console.log(client);
                if(client != null){
                    $('#firtsname').val(client.firstname);
                    $('#lastname').val(client.lastname);
                    $('#type_document').val(client.type_document);
                    $('#document').val(client.document);
                    $('#celphone').val(client.celphone);
                    $('#clientId').val(client.client_id);
                }
                $('#firtsname').parent().parent().toggleClass('d-none');
                $('#lastname').parent().parent().toggleClass('d-none');
                $('#celphone').parent().parent().toggleClass('d-none');
                $('#quantity').parent().parent().toggleClass('d-none');
                $('#consultar').toggleClass('d-none');
                $('#reservar').toggleClass('d-none');
            }else{
                $('#firtsname').parent().parent().toggleClass('d-none');
                $('#lastname').parent().parent().toggleClass('d-none');
                $('#celphone').parent().parent().toggleClass('d-none');
                $('#quantity').parent().parent().toggleClass('d-none');
                $('#consultar').toggleClass('d-none');
                $('#reservar').toggleClass('d-none');
                alert(data.message);
            }
        }
    });
}

function clearForm(){
    $('#firtsname').parent().parent().toggleClass('d-none');
    $('#lastname').parent().parent().toggleClass('d-none');
    $('#celphone').parent().parent().toggleClass('d-none');
    $('#quantity').parent().parent().toggleClass('d-none');
    $('#consultar').toggleClass('d-none');
    $('#reservar').toggleClass('d-none');
    $('#firtsname').val('');
    $('#lastname').val('');
    $('#type_document').val('CC');
    $('#document').val('');
    $('#celphone').val('');
    $('#clientId').val('');
    $('#eventId').val('');
    $('#exampleModalLabel').text('');
    $('#quantity option').each(function() {
        $(this).remove();
    });
}


function listReservations() {

    var document = $('#searchDocument').val();
    if(document == ''){
        document = '0';
    }
    $('#reservationsTable').DataTable({
        "ajax": {
            url: 'http://127.0.0.1:8000/api/reservations/ByClient/'+document,
            dataType: 'json'
        },
        "columns": [
            { "data": "event_id" },
            { "data": "name" },
            { "data": "date" },
            { "data": "ticket_id" }
        ],
        destroy: true,
    });

    $('#searchReservation').removeClass('d-none');

}

function listClientsTable() {
    $('#clientsTable').DataTable({
        "ajax": {
            url: 'http://127.0.0.1:8000/api/clients/',
            dataType: 'json'
        },
        "columns": [
            { "data": "client_id" },
            { "data": "firstname" },
            { "data": "lastname" },
            { "data": "type_document" },
            { "data": "document" },
            { "data": "celphone" }
        ],
        destroy: true,
    });
}