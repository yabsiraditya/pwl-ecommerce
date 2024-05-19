function showPassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

// Slide Show 
let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  slides[slideIndex-1].style.display = "block";
  setTimeout(showSlides, 5000);
}

// Dropdown


window.addEventListener('load', function() {
  // wait until the page loads before working with HTML elements
  document.addEventListener('click', function(event) {
    //click listener on the document
    document.querySelectorAll('.dropdown-content').forEach(function(el) {
      if (el !== event.target) el.classList.remove('show')
      // close any showing dropdown that isn't the one just clicked
    });
    if (event.target.matches('.dropbtn')) {
      event.target.closest('.dropdown').querySelector('.dropdown-content').classList.toggle('show')
    }
    // if this is a dropdown button being clicked, toggle the show class
  })
})
