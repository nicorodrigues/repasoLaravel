window.addEventListener('load', function() {

  function correrAjax() {
    var name = document.querySelector('#name').value;
    var cost = document.querySelector('#cost').value;
    var profit_margin = document.querySelector('#profit_margin').value;
    var category_id = document.querySelector('select[name="category_id"]').value;
    var csrf = document.querySelector('#csrf-token').content;

    var url = '/api/store';

    axios.post(url, {
      name: name,
      cost: cost,
      profit_margin: profit_margin,
      category_id: category_id,
      'XSRF-TOKEN': csrf
    })
  }





  document.forms[0].addEventListener('submit', function(event) {
    event.preventDefault();
    correrAjax();
  });
});
