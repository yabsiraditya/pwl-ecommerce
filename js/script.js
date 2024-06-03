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

const field1 = document.getElementById("total");
const button1 = document.getElementById("checkout");

field1.addEventListener("input", function() {
  button1.disabled = field.value <= 1;
})

function success() {
  if(document.getElementById("total").value <= 0) { 
           document.getElementById('checkout').disabled = true; 
       } else { 
           document.getElementById('checkout').disabled = false;
       }
   }


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
