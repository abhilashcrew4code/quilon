<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product View with Hover Zoom</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: #f9f9f9;
    }
    .product-card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      max-width: 800px;
      display: flex;
      gap: 20px;
    }
    .image-section {
      display: flex;
      flex-direction: column;
      gap: 10px;
      position: relative;
    }
    .main-image-container {
      position: relative;
      width: 350px;
      height: 300px;
      border: 1px solid #ddd;
      overflow: hidden;
      border-radius: 10px;
    }
    .main-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      cursor: crosshair;
    }
    .zoom-view {
      display: none;
      position: absolute;
      left: 370px;
      top: 0;
      width: 400px;
      height: 300px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background-repeat: no-repeat;
      background-size: 200% 200%; /* Zoom scale */
    }
    .thumbnail-container {
      display: flex;
      gap: 8px;
    }
    .thumbnail-container img {
      width: 60px;
      height: 60px;
      border-radius: 6px;
      cursor: pointer;
      border: 2px solid transparent;
      transition: 0.3s;
    }
    .thumbnail-container img.active,
    .thumbnail-container img:hover {
      border: 2px solid #25D366;
    }
    .details {
      flex: 1;
    }
    .details h2 {
      margin-top: 0;
    }
    .buy-btn {
      display: inline-block;
      margin-top: 15px;
      padding: 12px 20px;
      background: #25D366;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: 0.3s;
    }
    .buy-btn:hover {
      background: #1DA851;
    }
  </style>
</head>
<body>

  <div class="product-card">
    <!-- Image Section -->
    <div class="image-section">
      <div class="main-image-container">
        <img id="mainImage" class="main-image" src="{{ asset('assets/img/demo.jpg') }}" alt="Product">
        <div id="zoomView" class="zoom-view"></div>
      </div>
      <div class="thumbnail-container">
        <img src="{{ asset('assets/img/demo.jpg') }}" class="active">
        <img src="{{ asset('assets/img/demo1.jpg') }}">
        <img src="{{ asset('assets/img/demo2.jpg') }}">
      </div>
    </div>

    <!-- Product Info -->
    <div class="details">
      <h2 id="productName">Sample Product</h2>
      <p id="productCode">Code: P12345</p>
      <p id="productPrice">Price: ₹499</p>
      <a id="buyNowBtn" class="buy-btn" target="_blank">Buy Now</a>
    </div>
  </div>

  <script>
    const thumbnails = document.querySelectorAll(".thumbnail-container img");
    const mainImage = document.getElementById("mainImage");
    const zoomView = document.getElementById("zoomView");

    // Thumbnail click -> change image
    thumbnails.forEach(thumb => {
      thumb.addEventListener("click", () => {
        mainImage.src = thumb.src;
        zoomView.style.backgroundImage = `url(${thumb.src})`;

        thumbnails.forEach(t => t.classList.remove("active"));
        thumb.classList.add("active");
      });
    });

    // Hover zoom effect
    mainImage.addEventListener("mouseenter", () => {
      zoomView.style.display = "block";
      zoomView.style.backgroundImage = `url(${mainImage.src})`;
    });

    mainImage.addEventListener("mousemove", (e) => {
      const rect = mainImage.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;

      zoomView.style.backgroundPosition = `${x}% ${y}%`;
    });

    mainImage.addEventListener("mouseleave", () => {
      zoomView.style.display = "none";
    });

    // WhatsApp Buy Now
    const buyBtn = document.getElementById("buyNowBtn");
    const phoneNumber = "918891155404"; // Change to your number
    const productName = document.getElementById("productName").innerText;
    const productCode = document.getElementById("productCode").innerText;

    buyBtn.addEventListener("click", function() {
      const message = `Hello, I want to buy:\n\n${productName}\n${productCode}`;
      const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
      buyBtn.href = url;
    });
  </script>

</body>
</html>

