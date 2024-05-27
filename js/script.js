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