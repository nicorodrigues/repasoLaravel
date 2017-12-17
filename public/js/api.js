window.addEventListener('load', function() {

    var boton = document.querySelector('button');

    boton.addEventListener('click', imprimir);



    function imprimir() {
        var select = document.querySelector('select');
        var id = select.value;

        var csrf = document.getElementById("csrf-token").content;
        var ajax = axios.post('/api/show', {
            id: 1,
            'XSRF-TOKEN': csrf
        })
        .then(function(resp) {
            console.log(resp.data);
        })
    }
});
