<?php
// nosotros.php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Sobre Nosotros | TecnoRed Ciber</title>

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="Estilos/header.css">
  <link rel="stylesheet" href="Estilos/footer.css">
  <link rel="stylesheet" href="Estilos/nosotros.css">
 

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
   
      <a class="navbar-brand" href="home.php">TecnoRed</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="nosotros.php">Sobre Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="Home.php">Regresar</a></li>
  
        </ul>
      </div>
    </div>
  </nav>

  <header class="bg-primary text-white text-center py-5 mb-5">
    <div class="container">
      <h1>Conoce Más Sobre TecnoRed</h1>
      <p class="lead">Tu centro tecnológico de confianza en la ciudad</p>
    </div>
  </header>

  <main class="container mb-5">

    <!-- Misión, Visión y Valores -->
    <section class="row mb-5">
      <div class="col-md-4">
        <h3>Misión</h3>
        <p>Brindar acceso rápido y confiable a la tecnología, ofreciendo asesorías y servicios digitales que apoyen la educación, el trabajo y el entretenimiento de nuestra comunidad.</p>
      </div>
      <div class="col-md-4">
        <h3>Visión</h3>
        <p>Ser el ciber preferido de la región, reconocido por su innovación, atención personalizada y compromiso social, fomentando el desarrollo tecnológico de todos nuestros clientes.</p>
      </div>
      <div class="col-md-4">
        <h3>Valores</h3>
        <ul>
          <li>Calidad en el servicio</li>
          <li>Responsabilidad y compromiso</li>
          <li>Innovación constante</li>
          <li>Trabajo en equipo</li>
          <li>Atención personalizada</li>
          <li>Respeto y honestidad</li>
        </ul>
      </div>
    </section>

    <!-- Historia -->
    <section class="mb-5">
      <h2>Nuestra Historia</h2>
      <p>
        Fundado en 2015, TecnoRed nació con la idea de ofrecer un espacio accesible para que estudiantes, profesionales y la comunidad en general puedan acceder a internet y herramientas digitales de calidad. A lo largo de los años hemos ampliado nuestros servicios y actualizado nuestra tecnología para estar siempre a la vanguardia.
      </p>
    </section>

    <!-- Equipo -->
    <section class="mb-5">
      <h2>Conoce a Nuestro Equipo</h2>
      <div class="row text-center">
        <div class="col-md-3 team-member mb-4">
          <img src="https://i.pravatar.cc/140?img=1" alt="Juan Pérez" class="rounded-circle" />
          <h5 class="mt-3">Juan Pérez</h5>
          <p>Gerente General</p>
        </div>
        <div class="col-md-3 team-member mb-4">
          <img src="https://i.pravatar.cc/140?img=2" alt="María Gómez" class="rounded-circle" />
          <h5 class="mt-3">María Gómez</h5>
          <p>Soporte Técnico</p>
        </div>
        <div class="col-md-3 team-member mb-4">
          <img src="https://i.pravatar.cc/140?img=3" alt="Carlos Ruiz" class="rounded-circle" />
          <h5 class="mt-3">Carlos Ruiz</h5>
          <p>Asesor Educativo</p>
        </div>
        <div class="col-md-3 team-member mb-4">
          <img src="https://i.pravatar.cc/140?img=4" alt="Ana Morales" class="rounded-circle" />
          <h5 class="mt-3">Ana Morales</h5>
          <p>Administración</p>
        </div>
      </div>
    </section>

    <!-- Servicios -->
    <section class="mb-5">
      <h2>Servicios que Ofrecemos</h2>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Alquiler de computadoras por hora</li>
        <li class="list-group-item">Acceso a internet de alta velocidad</li>
        <li class="list-group-item">Impresiones, escaneo y fotocopias</li>
        <li class="list-group-item">Asesorías en trámites digitales y educativos</li>
        <li class="list-group-item">Venta de accesorios y consumibles</li>
        <li class="list-group-item">Zona de descanso con snacks y bebidas</li>
      </ul>
    </section>

    <!-- Ubicación -->
    <section class="mb-5">
      <h2>Ubicación</h2>
      <p>Visítanos en nuestro local principal para disfrutar de todos nuestros servicios:</p>
      <address>
        Calle Principal #123, Edificio TecnoRed, Local 2<br />
        Ciudad, País<br />
        Teléfono: +58 123 456 7890<br />
        Email: contacto@tecnored.com
      </address>

      <div class="ratio ratio-16x9 mt-3">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.123456789012!2d-74.00594118454451!3d40.712775979331634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a316abf1234%3A0x123456789abcdef0!2sCalle%20Principal%20123!5e0!3m2!1ses-419!2sve!4v1686856000000!5m2!1ses-419!2sve"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Ubicación TecnoRed"
        ></iframe>
      </div>
    </section>

    <!-- Contacto -->
    <section class="mb-5">
      <h2>Contáctanos</h2>
      <p>¿Tienes alguna pregunta o quieres solicitar asesoría? Completa el formulario y te responderemos pronto.</p>
      <form action="procesar_contacto.php" method="post" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre completo</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required />
          <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" required />
          <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
        </div>
        <div class="col-12">
          <label for="mensaje" class="form-label">Mensaje</label>
          <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
          <div class="invalid-feedback">Por favor ingresa un mensaje.</div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </section>

    <section class="mb-5">
  <h2>Galería de Nuestro Espacio</h2>
  <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="imagenes/local1.jpg" class="d-block w-100" alt="Local TecnoRed 1" />
      </div>
      <div class="carousel-item">
        <img src="imagenes/local2.jpg" class="d-block w-100" alt="Local TecnoRed 2" />
      </div>
      <div class="carousel-item">
        <img src="imagenes/evento1.jpg" class="d-block w-100" alt="Evento TecnoRed" />
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>
</section>


    <!-- Redes Sociales -->
    <section class="text-center mb-5">
      <h2>Síguenos en Redes Sociales</h2>
      <div class="social-icons">
        <a href="https://facebook.com/tecnored" target="_blank" aria-label="Facebook" class="me-3">
          <i class="bi bi-facebook"></i> Facebook
        </a>
        <a href="https://twitter.com/tecnored" target="_blank" aria-label="Twitter" class="me-3">
          <i class="bi bi-twitter"></i> Twitter
        </a>
        <a href="https://instagram.com/tecnored" target="_blank" aria-label="Instagram" class="me-3">
          <i class="bi bi-instagram"></i> Instagram
        </a>
        <a href="https://linkedin.com/company/tecnored" target="_blank" aria-label="LinkedIn">
          <i class="bi bi-linkedin"></i> LinkedIn
        </a>
      </div>
    </section>

    <section class="mb-5">
  <h2>Preguntas Frecuentes</h2>
  <div class="accordion" id="faqAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq1">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
          ¿Cómo alquilo una computadora?
        </button>
      </h2>
      <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Puedes alquilar computadoras por hora directamente en nuestro local o reservando vía telefónica.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="faq2">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
          ¿Ofrecen asesorías personalizadas?
        </button>
      </h2>
      <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Sí, contamos con expertos que te guían en trámites digitales y temas tecnológicos.
        </div>
      </div>
    </div>
  </div>
</section>
<section class="mb-5">
  <h2>Últimos Tweets</h2>
  <a class="twitter-timeline" data-height="400" href="https://twitter.com/tecnored?ref_src=twsrc%5Etfw">Tweets by Tecnored</a>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</section>
<section class="mb-5">
  <h2>Testimonios</h2>
  <div class="row">
    <div class="col-md-4">
      <div class="card p-3">
        <p>"Excelente atención y equipo moderno. Siempre vuelvo por la calidad del servicio."</p>
        <h6>- Laura Méndez</h6>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <p>"Las asesorías me ayudaron mucho con mis trámites digitales, muy recomendados."</p>
        <h6>- José Ramírez</h6>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <p>"Buen ambiente y conexión rápida. Ideal para trabajar y estudiar."</p>
        <h6>- Ana Castillo</h6>
      </div>
    </div>
  </div>
</section>

    <!-- Horarios -->
    <section class="mb-5">
      <h2>Horario de Atención</h2>
      <table class="table table-striped table-bordered w-auto mx-auto">
        <thead>
          <tr>
            <th>Día</th>
            <th>Horario</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Lunes - Viernes</td><td>9:00 AM - 7:00 PM</td></tr>
          <tr><td>Sábado</td><td>10:00 AM - 5:00 PM</td></tr>
          <tr><td>Domingo</td><td>Cerrado</td></tr>
        </tbody>
      </table>
    </section>

  </main>

  <footer class="custom-footer bg-dark text-white py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <small>&copy; <?php echo date("Y"); ?> TecnoRed Ciber. Todos los derechos reservados.</small>
      <div class="social-icons">
        <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="text-white me-3">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://twitter.com" target="_blank" aria-label="Twitter" class="text-white me-3">
          <i class="bi bi-twitter"></i>
        </a>
        <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="text-white me-3">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn" class="text-white">
          <i class="bi bi-linkedin"></i>
        </a>
      </div>
      <div class="credits mt-2 mt-md-0">
        <small>Agradecimientos: Ingeniero Oscar Franco y Jesús Villalba</small>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Bootstrap form validation script
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>

</body>
</html>
