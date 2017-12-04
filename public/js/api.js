window.addEventListener('load', function() {

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



});
