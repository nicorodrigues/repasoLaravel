window.addEventListener('load', function() {

<<<<<<< HEAD
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
=======
function listaProductos() {
  var url = '/api/test';

  axios.get(url)
  .then(function(datos) {
    datos = datos.data;
    var divAgarrado = document.querySelector('#productos');
    var ul = document.createElement('ul');

    datos.forEach(function(elemento) {
      var li = document.createElement('li');

      li.innerHTML = elemento.name;

      ul.appendChild(li);
    });

    divAgarrado.appendChild(ul);
  })



}

listaProductos();










function productoEspecifico() {
  var id = document.querySelector('select').value;
  var url = '/api/show/' + id;

  axios.get(url)
  .then(function(datos) {
    console.log(datos.data);
  })
}

var select = document.querySelector('select');

select.addEventListener('change', productoEspecifico);



>>>>>>> b68934af2f16c141f9f9544e5e73f31634d6287c
});
