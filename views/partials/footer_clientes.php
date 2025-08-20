<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rosita Forever Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- FOOTER -->
<footer class="footer-confiteria text-white">
  <div class="container py-4">
    <div class="row align-items-center text-center text-md-start">
      <div class="col-md-4 mb-3 mb-md-0">
        <h5 class="mb-0 brand-name">Rosita Forever</h5>
        <p class="footer-desc">Tu licorera de confianza</p>
      </div>
      <div class="col-md-4 mb-3 mb-md-0">
        <p class="mb-1">Síguenos:</p>
        <div>
          <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>
      <div class="col-md-4">
        <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i> rositaforever6@gmail.com</p>
        <p class="mb-0"><i class="bi bi-phone-fill me-2"></i> +57 3223017205</p>
      </div>
    </div>
    <hr class="my-3">
    <div class="row">
      <div class="col text-center small">
        © 2025 Rosita Forever, Todos los derechos reservados.
      </div>
    </div>
  </div>
</footer>

<!-- Estilo neón aplicado -->
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700;900&display=swap');

  body {
    font-family: "Poppins", sans-serif;
    background-color: #0a0a0a;
    margin: 0;
    padding: 0;
  }

  .footer-confiteria {
    background: linear-gradient(to right, #0b1d33, #0c2944);
    border-top: 2px solid #00cfff;
    color: #ccfaff;
    font-size: 0.9rem;
    padding: 1.5rem 0;
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.1);
  }

  .footer-confiteria .brand-name {
    color: #00eaff;
    font-weight: bold;
    font-size: 1.3rem;
    text-shadow:
      0 0 6px #00ffff,
      0 0 12px #00ffff,
      0 0 18px #00cfff;
  }

  .footer-confiteria .footer-desc {
    font-size: 0.85rem;
    margin-bottom: 0;
    color: #a6ecff;
  }

  .footer-confiteria .social-icon {
    color: #00cfff;
    font-size: 1.3rem;
    margin: 0 8px;
    transition: color 0.3s ease, transform 0.3s ease;
  }

  .footer-confiteria .social-icon:hover {
    color: #00ffff;
    transform: scale(1.2);
    text-shadow: 0 0 8px #00ffff;
  }

  .footer-confiteria i {
    color: #00bfff;
  }

  .footer-confiteria hr {
    border: none;
    border-top: 1px solid #00cfff;
    margin: 1rem auto;
    width: 80%;
  }

  @media (max-width: 576px) {
    .footer-confiteria .brand-name {
      font-size: 1rem;
    }
    .footer-confiteria .social-icon {
      font-size: 1.1rem;
    }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
