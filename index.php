<?php
session_start();
include("config/db.php");

// Fetch cars for car list section
$cars = mysqli_query($conn, "SELECT * FROM cars ORDER BY id DESC");

// Fetch total bookings for dynamic happy customers
$bookings = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bookings"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Rental System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <style>
    body { scroll-behavior: smooth; overflow-x: hidden; }
    section { padding: 60px 0; }
    .navbar-brand { font-weight: bold; }
    .car-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 5px; }
    .fade-in { opacity: 0; animation: fadeInAnimation 1s forwards; }
    @keyframes fadeInAnimation { to { opacity: 1; } }
    h1, h2 { line-height: 1.2; }
    @media (max-width: 768px) { h1 { font-size: 2rem; } h2 { font-size: 1.5rem; } }
    /* Modern Full-Screen Loader */
#loader {
  position: fixed;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #1a5f7a, #3fa2c2);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  flex-direction: column;
  color: white;
  font-family: 'Arial', sans-serif;
}

.loader-dots {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}

.loader-dots div {
  width: 12px;
  height: 12px;
  background-color: white;
  border-radius: 50%;
  animation: bounce 1s infinite;
}

.loader-dots div:nth-child(2) { animation-delay: 0.2s; }
.loader-dots div:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}

.hero-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;      /* makes video cover full area */
  z-index: 0;
}

.video-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.45); /* dark overlay for better text contrast */
  z-index: 1;
}

#home .container {
  z-index: 2;
}

.book-btn {
  transition: all 0.4s ease;
  background: linear-gradient(135deg, #1a5f7a, #3fa2c2);
  color: white;
  border: none;
}

.book-btn:hover {
  transform: scale(1.08);
  background: linear-gradient(135deg, #3fa2c2, #1a5f7a);
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}


  </style>
</head>
<body>
  <!-- Modern Loader -->
<div id="loader">
  <h2>Loading Car Rental System...</h2>
  <div class="loader-dots">
    <div></div>
    <div></div>
    <div></div>
  </div>
</div>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top" data-aos="fade-down">
  <div class="container">
    <a class="navbar-brand" href="#">🚗 Car Rental</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#cars">Cars</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="#stats">Stats</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="user/register.php">Signup</a></li>
        <li class="nav-item"><a class="nav-link" href="user/login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>


<!-- Hero Section with Background Video -->
<section id="home" class="position-relative text-center text-white d-flex align-items-center justify-content-center" style="height: 100vh; overflow: hidden;">
  <!-- Background Video -->
  <video autoplay muted loop playsinline class="hero-video">
    <source src="videos/car-road.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <!-- Dark Overlay -->
  <div class="video-overlay"></div>

  <!-- Content -->
  <div class="container position-relative z-1">
    <h1 class="display-4 fw-bold mb-3" data-aos="fade-down">Drive Your Dream Car Today</h1>
    <p class="lead mb-4" data-aos="fade-up">Fast • Reliable • Affordable Car Rentals</p>
    <a href="user/login.php" class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-lg book-btn" data-aos="zoom-in">
      🚗 Book Now
    </a>
  </div>
</section>


<!-- Features Section -->
<section id="features" class="py-5 bg-white">
  <div class="container">
    <h2 class="text-center mb-5" data-aos="fade-up">✨ Our Features</h2>
    <div class="row text-center">
      <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h5 class="card-title">Wide Range of Cars</h5>
            <p class="card-text">Choose from a variety of brands and models to suit your needs.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h5 class="card-title">Affordable Prices</h5>
            <p class="card-text">Competitive daily rates to make your trip budget-friendly.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h5 class="card-title">24/7 Customer Support</h5>
            <p class="card-text">We are always ready to assist you for a smooth rental experience.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Cars Section -->
<section id="cars" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5" data-aos="fade-up">🚗 Available Cars</h2>
    <div id="carsCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $cars_per_slide = 3;
        $i = 0;
        $first = true;
        while($row = mysqli_fetch_assoc($cars)):
          if($i % $cars_per_slide == 0):
            if(!$first) echo '</div></div>'; 
            echo '<div class="carousel-item '.($first ? 'active' : '').'"><div class="row justify-content-center">';
            $first = false;
          endif;
        ?>
          <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card car-card shadow-sm h-100">
              <img src="images/<?= $row['image'] ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($row['car_name']) ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($row['car_name']) ?></h5>
                <p class="card-text">
                  Brand: <?= htmlspecialchars($row['brand']) ?><br>
                  Model: <?= htmlspecialchars($row['model']) ?><br>
                  Price: ₹<?= $row['price_per_day'] ?>/day<br>
                  Fuel: <?= htmlspecialchars($row['fuel_type']) ?><br>
                  Seats: <?= $row['seats'] ?>
                </p>
                <a href="user/login.php" class="btn btn-primary w-100">Book Now</a>
              </div>
            </div>
          </div>
        <?php
          $i++;
        endwhile;
        if($i > 0) echo '</div></div>'; 
        ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carsCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carsCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-5 bg-white">
  <div class="container">
    <h2 class="text-center mb-5" data-aos="fade-up">💬 Testimonials</h2>
    <div class="row justify-content-center">
      <div class="col-12 col-md-8" data-aos="fade-up" data-aos-delay="100">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <p class="card-text">"Amazing service! Renting a car was so easy and the cars were in excellent condition."</p>
            <h6 class="card-subtitle mt-2 text-muted">- Shubhransh Vishesh Srivastava</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats / Counters Section -->
<section id="stats" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5" data-aos="fade-up">📊 Our Achievements</h2>
    <div class="row text-center">
      <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h3 class="counter" data-target="<?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM cars")) ?>">0</h3>
            <p>Cars Available</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h3 class="counter" data-target="<?= $bookings ?>">0</h3>
            <p>Happy Customers</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h3 class="counter" data-target="5">0</h3>
            <p>Years of Experience</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5">
  <div class="container" data-aos="fade-up">
    <h2 class="text-center mb-5">📨 Contact Us</h2>
    <div class="row justify-content-center">
      <div class="col-12 col-lg-5 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body">
            <h5 class="card-title mb-3">Get in Touch</h5>
            <p class="card-text"><strong>Address:</strong> Tehipulia, Lucknow</p>
            <p class="card-text"><strong>Email:</strong> infocar@gmail.com</p>
            <p class="card-text"><strong>Phone:</strong> +91 9876XXXXXXX</p>
            <p class="card-text">We are here to assist you 24/7. Send us a message using the form!</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title mb-4">Send Us a Message</h5>
            <form action="contact_submit.php" method="POST">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100">Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-primary text-white py-3 text-center" data-aos="fade-up">
  <div class="container">
    <p class="mb-0">© <?php echo date("Y"); ?> Car Rental System | All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>

// Hide loader after page fully loads
window.addEventListener('load', () => {
  const loader = document.getElementById('loader');

  // Start fade-out after page loads
  loader.style.opacity = '0';
  loader.style.transition = 'opacity 2s ease'; // fade out takes 2 seconds

  // Hide loader completely after fade-out completes
  setTimeout(() => loader.style.display = 'none', 5000); // 5 seconds total
});

AOS.init({ duration: 1200, once: true });

// Counter Animation
const counters = document.querySelectorAll('.counter');
const speed = 200;

counters.forEach(counter => {
  const animate = () => {
    const target = +counter.getAttribute('data-target');
    const count = +counter.innerText;
    const increment = Math.ceil(target / speed);

    if(count < target){
      counter.innerText = count + increment;
      setTimeout(animate, 20);
    } else { counter.innerText = target; }
  }

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        animate();
        observer.unobserve(counter);
      }
    });
  }, { threshold: 0.5 });

  observer.observe(counter);
});
</script>
</body>
</html>
