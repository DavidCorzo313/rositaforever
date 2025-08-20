document.addEventListener('DOMContentLoaded', function () {
    var myCarousel = document.querySelector('#carouselExample');
  
    if (myCarousel) {
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 3000, // Cambia cada 3 segundos
            pause: false,   // No pausarlo por defecto
            wrap: true
        });
  
        // Seleccionar todas las imágenes dentro del carrusel
        var carouselImages = myCarousel.querySelectorAll('.carousel-item img');
  
        carouselImages.forEach(img => {
            img.addEventListener('mouseenter', function () {
                console.log("Mouse sobre la imagen: Pausando carrusel");
                carousel.pause(); // Pausar carrusel
            });
  
            img.addEventListener('mouseleave', function () {
                console.log("Mouse fuera de la imagen: Reanudando carrusel");
                carousel.cycle(); // Reanudar carrusel
            });
        });
    } else {
        console.error("No se encontró el carrusel.");
    }
  });


  