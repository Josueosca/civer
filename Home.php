<?php
// home.php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio | TecnoRed Ciber</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>


  <link rel="stylesheet" href="Estilos/footer.css">
  <link rel="stylesheet" href="Estilos/modal.css">
  <link rel="stylesheet" href="Estilos/navbar.css">
  <link rel="stylesheet" href="Estilos/header.css">
  <link rel="stylesheet" href="Estilos/modules.css"> <!-- Opcional, para estilos de módulos -->
  <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="Imagenes/logo.png" alt="Logo TecnoRed">
      <span class="ms-2 brand-text">TecnoRed</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="nosotros.php">
            <i class="bi bi-people-fill"></i> Sobre Nosotros
          </a>
        </li>
     
        <li class="nav-item">
          <a class="nav-link" href="#inventario">
            <i class="bi bi-box-seam"></i> Inventario / Catálogo
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#asesorias">
            <i class="bi bi-headset"></i> Asesorías
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


  <header class="bg-primary text-center py-5">
    <div class="container">
      <h1>Bienvenido a TecnoRed</h1>
      <p class="lead">Tu centro de conexión, tecnología y soluciones digitales</p>
    </div>
  </header>

  <main>
    
  <section class="sobre-nosotros-preview">
  <div class="contenido">
    <h2>¿Quiénes somos?</h2>
    <p>
            Descubre cómo nuestro sistema optimiza el uso de estaciones y controla el tiempo de uso de manera <span class="resaltado">inteligente</span> y <span class="resaltado">transparente</span>. 
            Visualiza el rendimiento en cada estación con un <span class="resaltado">diseño intuitivo</span> que facilita la administración y mejora la productividad. 
            <span class="resaltado">Gestiona tu tiempo.</span> <span class="resaltado">Maximiza tu potencial.</span>
        </p>

    <div class="slider-con-flecha">
  <div class="slider">
    <div class="slide-track">
      <div class="slide"><img src="Imagenes/eco1.jpg" alt="Innovación verde"></div>
      <div class="slide"><img src="Imagenes/tech2.jpg" alt="Tecnología limpia"></div>
      <div class="slide"><img src="Imagenes/equipo3.jpg" alt="Trabajo en equipo"></div>
      <div class="slide"><img src="Imagenes/solar4.jpg" alt="Energía solar"></div>
      <div class="slide"><img src="Imagenes/filtracion5.jpg" alt="Filtración eficiente"></div>
    </div>
  </div>

  <div class="indicador-seccion">
    <a href="#computadoras" aria-label="Ir a Computadoras" class="flecha-container">
      <div class="flecha-circular">
        <i class="fas fa-arrow-down"></i>
      </div>
    </a>
  </div>
</div>


    <a href="nosotros.php" class="btn-ver-mas">Conócenos a fondo</a>
  </div>
</section>




  <script>
const track = document.getElementById('slide-track');
const slides = Array.from(track.children);
const totalSlides = slides.length;

// Clonar las imágenes para que se duplique la fila (para efecto loop)
slides.forEach(slide => {
  const clone = slide.cloneNode(true);
  track.appendChild(clone);
});

// Ajustamos el ancho total del track para que funcione la animación
const slideWidth = slides[0].offsetWidth;
const gap = 10; // gap en px entre imágenes, igual que CSS
track.style.width = `${(slideWidth + gap) * totalSlides * 2}px`;

</script>


<section id="computadoras" class="computadoras-seccion py-5">
  <div class="container">
    <h2 class="text-center mb-4">Computadoras - Gestión del Tiempo</h2>
    <p>
            Descubre cómo nuestro sistema optimiza el uso de estaciones y controla el tiempo de uso de manera <span class="resaltado">inteligente</span> y <span class="resaltado">transparente</span>. 
            Visualiza el rendimiento en cada estación con un <span class="resaltado">diseño intuitivo</span> que facilita la administración y mejora la productividad. 
            <span class="resaltado">Gestiona tu tiempo.</span> <span class="resaltado">Maximiza tu potencial.</span>
        </p>
    <div class="grid-flecha-wrapper d-flex justify-content-center align-items-center flex-wrap">
      <div class="grid-imagenes-wrapper">
        <!-- Fila superior -->
        <div class="grid-imagenes fila-superior row justify-content-center">
          <div class="grid-item item-1 col-md-4 mb-4" data-bs-toggle="modal" data-bs-target="#modalItem1">
            <div class="image-container">
              <img src="imagenes/computadora1.jpeg" alt="Computadora portátil">
              <div class="image-title">💻 Estación Moderna</div>
            </div>
          </div>

          <div class="grid-item item-2 col-md-4 mb-4" data-bs-toggle="modal" data-bs-target="#modalItem2">
            <div class="image-container">
              <img src="imagenes/computadora2.jpg" alt="Computadora de escritorio">
              <div class="image-title">👥 Usuarios Activos</div>
            </div>
          </div>

          <div class="grid-item item-3 col-md-4 mb-4" data-bs-toggle="modal" data-bs-target="#modalItem3">
            <div class="image-container">
              <img src="imagenes/computadora3.jpeg" alt="Monitoreo en tiempo real">
              <div class="image-title">📊 Monitoreo en Vivo</div>
            </div>
          </div>
        </div>

        <!-- Fila inferior -->
        <div class="grid-imagenes fila-inferior row justify-content-center">
          <div class="grid-item item-4 col-md-5 mb-4" data-bs-toggle="modal" data-bs-target="#modalItem4">
            <div class="image-container">
              <img src="imagenes/computadora4.jpeg" alt="Control remoto digital">
              <div class="image-title">🛰️ Control Remoto</div>
            </div>
          </div>

          <div class="grid-item item-5 col-md-5 mb-4" data-bs-toggle="modal" data-bs-target="#modalItem5">
            <div class="image-container">
              <img src="imagenes/computadora5.jpeg" alt="Alta productividad">
              <div class="image-title">🚀 Alta Productividad</div>
            </div>
          </div>
        </div>
      </div>

      <div class="indicador-seccion ms-4">
        <a href="#inventario" aria-label="Ir a Inventario" class="flecha-container">
          <div class="flecha-circular">
            <i class="fas fa-arrow-down"></i>
          </div>
        </a>
      </div>
    </div>
  </div>

  <!-- Aquí van los modales, igual que antes -->

  <!-- Modales -->
  <!-- Modal 1 -->
  <div class="modal fade" id="modalItem1" tabindex="-1" aria-labelledby="modalItem1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem1Label">💻 Estación Moderna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img src="imagenes/computadora1.jpeg" class="img-fluid mb-3" alt="Computadora portátil">
          <p>Ambientes limpios, organizados y equipados para maximizar el rendimiento. Cada estación está diseñada para brindar confort, accesibilidad y tecnología actualizada.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal 2 -->
  <div class="modal fade" id="modalItem2" tabindex="-1" aria-labelledby="modalItem2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem2Label">👥 Usuarios Activos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img src="imagenes/computadora2.jpg" class="img-fluid mb-3" alt="Usuarios activos">
          <p>Colaboración constante y control efectivo del tiempo de uso. Supervisión grupal que promueve la eficiencia y evita tiempos muertos.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal 3 -->
  <div class="modal fade" id="modalItem3" tabindex="-1" aria-labelledby="modalItem3Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem3Label">📊 Monitoreo en Vivo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img src="imagenes/computadora3.jpeg" class="img-fluid mb-3" alt="Monitoreo en tiempo real">
          <p>Visualización en tiempo real del uso y tiempos de cada estación de trabajo. Ideal para análisis de rendimiento y gestión eficiente.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal 4 -->
  <div class="modal fade" id="modalItem4" tabindex="-1" aria-labelledby="modalItem4Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem4Label">🛰️ Control Remoto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img src="imagenes/computadora4.jpeg" class="img-fluid mb-3" alt="Control remoto">
          <p>Control y supervisión desde cualquier dispositivo. Acceso remoto con autenticación segura para gestionar el sistema en tiempo real.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal 5 -->
  <div class="modal fade" id="modalItem5" tabindex="-1" aria-labelledby="modalItem5Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalItem5Label">🚀 Alta Productividad</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img src="imagenes/computadora5.jpeg" class="img-fluid mb-3" alt="Alta productividad">
          <p>Optimización del tiempo mediante estrategias de uso eficiente. Cada estación está pensada para ofrecer el máximo desempeño por sesión.</p>
        </div>
      </div>
    </div>
  </div>
</section>





<section id="inventario" class="py-5">
  <div class="container">
  <h2>CATALÓGO</h2>
  <p>
    Productos y servicios disponibles para ti, con <span class="resaltado">ofertas exclusivas</span> y la mejor calidad.
  </p>
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
     <!-- Producto 1 -->
     <div class="swiper-slide text-center">
          <img src="imagenes/mouse.jpeg" alt="Mouse" class="img-fluid" />
          <h3>Mouse Gamer RGB</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Mouse Gamer RGB"
                  data-price="$15"
                  data-description="Precisión y luces LED personalizables.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 2 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/teclado.jpeg" alt="Teclado" class="img-fluid" />
          <h3>Teclado Mecánico</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Teclado Mecánico"
                  data-price="$30"
                  data-description="Ideal para gamers y mecanografía rápida.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 3 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/audifino.jpeg" alt="Audífonos" class="img-fluid" />
          <h3>Audífonos con Micrófono</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Audífonos con Micrófono"
                  data-price="$12"
                  data-description="Comunicación clara para juegos y videollamadas.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 4 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/cables.jpeg" alt="Cables" class="img-fluid" />
          <h3>Cables USB</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Cables USB"
                  data-price="$3"
                  data-description="Carga y transferencia de datos.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 5 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/memoria.jpeg" alt="Memoria USB" class="img-fluid" />
          <h3>Memoria USB 32GB</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Memoria USB 32GB"
                  data-price="$10"
                  data-description="Almacenamiento portátil y seguro.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 6 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/impresiones.jpeg" alt="Impresiones" class="img-fluid" />
          <h3>Impresiones</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Impresiones"
                  data-price="$0.10 c/u"
                  data-description="Impresión de documentos y fotos.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 7 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/internet.jpeg" alt="Internet" class="img-fluid" />
          <h3>Acceso a Internet</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Acceso a Internet"
                  data-price="$0.50 / 30min"
                  data-description="Navega rápido y seguro desde el ciber.">
            Ver detalles
          </button>
        </div>

        <!-- Producto 8 -->
        <div class="swiper-slide text-center">
          <img src="imagenes/recarga.jpeg" alt="Recarga" class="img-fluid" />
          <h3>Recargas Telefónicas</h3>
          <button type="button" class="btn btn-primary" 
                  data-bs-toggle="modal" 
                  data-bs-target="#productoModal"
                  data-title="Recargas Telefónicas"
                  data-price="Desde $1"
                  data-description="Movistar, Digitel y Movilnet.">
            Ver detalles
          </button>
        </div>

      </div>

      <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <!-- slides -->
  </div>
  <!-- Paginación -->
  <div class="swiper-pagination"></div>
</div>

    </div>
  </div>

  <div class="indicador-seccion mt-4 text-center">
    <a href="#asesorias" aria-label="Ir a Asesorías" class="flecha-container">
      <div class="flecha-circular">
        <i class="fas fa-arrow-down"></i>
      </div>
    </a>
  </div>

  <!-- Botón para ver catálogo completo -->
  <div class="text-center mt-4">
    <a href="catalogo.php" class="btn btn-success btn-lg">Ver catálogo completo</a>
  </div>

  <!-- Modal Bootstrap para productos -->
  <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productoModalLabel">Título</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p><strong>Precio: </strong><span id="productoPrecio"></span></p>
          <p id="productoDescripcion"></p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Script para llenar dinámicamente el modal
  var productoModal = document.getElementById('productoModal')
  productoModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget
    var title = button.getAttribute('data-title')
    var price = button.getAttribute('data-price')
    var description = button.getAttribute('data-description')

    var modalTitle = productoModal.querySelector('.modal-title')
    var modalPrice = productoModal.querySelector('#productoPrecio')
    var modalDescription = productoModal.querySelector('#productoDescripcion')

    modalTitle.textContent = title
    modalPrice.textContent = price
    modalDescription.textContent = description
  })
</script>


  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
   const swiper = new Swiper(".mySwiper", {
  loop: true, // 🔁 Aquí activas el bucle infinito
  effect: "coverflow",
  grabCursor: true,
  centeredSlides: true,
  slidesPerView: "auto",
  spaceBetween: 30,
  coverflowEffect: {
    rotate: 15,
    stretch: 0,
    depth: 120,
    modifier: 2,
    slideShadows: true,
  },
  pagination: {
    el: ".swiper-pagination",
  },
});

    function openModal(title, price, description) {
      document.getElementById("modalTitle").textContent = title;
      document.getElementById("modalPrice").textContent = price;
      document.getElementById("modalDescription").textContent = description;
      document.getElementById("modal").classList.add("flex");
      document.getElementById("modal").classList.remove("hidden");
    }

    function closeModal() {
      document.getElementById("modal").classList.remove("flex");
      document.getElementById("modal").classList.add("hidden");
    }
  </script>

<section id="asesorias" class="py-5 bg-light">
<div class="container text-center sobre-nosotros-preview">
    <h2>Asesorías</h2>
    <p>Solicita ayuda con trámites o tareas.</p>

    <div class="row justify-content-center g-4">
      <div class="col-12 col-md-4 d-flex">
        <div class="card-flip" tabindex="0" aria-label="Asesoría en trámites legales">
          <div class="card-inner">
            <div class="card-front">
              <img src="Imagenes/legal.jpeg" alt="Documentos y trámites" class="card-img-top" />
              <h3>Trámites legales</h3>
            </div>
            <div class="card-back">
              <p>Asesoría experta para realizar trámites legales con rapidez y seguridad. Simplifica tu proceso y evita errores.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 d-flex">
        <div class="card-flip" tabindex="0" aria-label="Asesoría para tareas académicas">
          <div class="card-inner">
            <div class="card-front">
              <img src="Imagenes/academica.jpeg" alt="Estudiante haciendo tareas" class="card-img-top" />
              <h3>Tareas académicas</h3>
            </div>
            <div class="card-back">
              <p>Apoyo personalizado en redacción, matemáticas y más. Logra tus objetivos académicos con ayuda profesional.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 d-flex">
        <div class="card-flip" tabindex="0" aria-label="Asesoría financiera personal">
          <div class="card-inner">
            <div class="card-front">
              <img src="Imagenes/financiera.jpeg" alt="Finanzas personales" class="card-img-top" />
              <h3>Asesoría financiera</h3>
            </div>
            <div class="card-back">
              <p>Consejos prácticos para organizar y administrar tu presupuesto, mejorando tu economía personal y familiar.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
.container {
  max-width: 1300px; /* un poco más ancho */
  margin: 0 auto;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
}

.card-flip {
  perspective: 1500px;
  cursor: pointer;
  outline: none;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  height: 280px; /* más alto */
  max-width: 320px; /* más ancho */
  margin: 0 auto;
  transition: box-shadow 0.3s ease;
  border-radius: 14px;
}

.card-inner {
  position: relative;
  width: 100%;
  height: 100%;
  transition: transform 0.9s cubic-bezier(0.4, 0, 0.2, 1);
  transform-style: preserve-3d;
  border-radius: 14px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
  background-color: white;
}

.card-flip:hover .card-inner,
.card-flip:focus .card-inner {
  transform: rotateY(180deg);
  box-shadow: 0 20px 40px rgba(0,0,0,0.25);
}

.card-front, .card-back {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius: 14px;
  padding: 2rem 1.5rem;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.card-front {
  color: #2a7f2a;
  background-color: #d4edda;
}

.card-front img.card-img-top {
  max-width: 100%;
  max-height: 180px; /* imagen más grande */
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  flex-shrink: 0;
  box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.card-front h3 {
  margin: 0;
  font-size: 1.8rem; /* texto más grande */
  font-weight: 700;
  color: #2a7f2a;
}

.card-back {
  transform: rotateY(180deg);
  background-color: #2a7f2a;
  color: white;
  font-size: 1.3rem; /* texto más grande */
  line-height: 1.6;
  font-weight: 500;
  padding: 2.5rem 2rem;
}

.col-md-4.d-flex {
  display: flex;
  justify-content: center;
  align-items: stretch;
}

  </style>
</section>









  </main>

  <footer class="custom-footer">
  <div class="footer-content">
    <small>&copy; <?php echo date("Y"); ?> TecnoRed Ciber. Todos los derechos reservados.</small>
    <div class="social-icons">
      <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="facebook">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://twitter.com" target="_blank" aria-label="Twitter" class="twitter">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="instagram">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn" class="linkedin">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div>
    <div class="credits">
      <small>Agradecimientos: Ingeniero Oscar Franco </small>
    </div>
  </div>
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>