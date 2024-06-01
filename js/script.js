function showPassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


// Dropdown
window.addEventListener('load', function() {
  document.addEventListener('click', function(event) {
    document.querySelectorAll('.dropdown-menu').forEach(function(el) {
      if (el !== event.target) el.classList.remove('show')
    });
    if (event.target.matches('.dropbtn')) {
      event.target.closest('.dropdown').querySelector('.dropdown-menu').classList.toggle('show')
    }
  })
})

const field = document.getElementById("password");
const button = document.getElementById("form-submit");

field.addEventListener("input", function() {
  button.disabled = field.value.length < 8;
})

$('#edit').submit(function(event){
  // cancels the form submission
  event.preventDefault();

  //If the form is valid open modal
  if($('#edit')[0].checkValidity() ){
    $('#myModal').modal('toggle');
  }
// do whatever you want here
});

var url = 'dashboard.php#products';
if (url.match('#')) {
  $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
} 

// Sidebar Dashboard
const sidebarToggle = document.querySelector("#sidebar-toggle");
sidebarToggle.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
});
function increment() {
  document.getElementById('quantity').stepUp();
}

function decrement() {
  document.getElementById('quantity').stepDown();
}
