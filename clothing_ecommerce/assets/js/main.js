// assets/js/main.js

// Example of a simple DOMContentLoaded event to ensure the page is loaded before running JS
document.addEventListener("DOMContentLoaded", function () {
  // Add any JavaScript interactions or event listeners here

  // Example: Smooth scrolling for internal links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });

  // Example: Toggle mobile menu
  const menuToggle = document.querySelector(".menu-toggle");
  const nav = document.querySelector("nav ul");
  if (menuToggle && nav) {
    menuToggle.addEventListener("click", () => {
      nav.classList.toggle("active");
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const increaseBtn = document.getElementById("increase");
  const decreaseBtn = document.getElementById("decrease");
  const quantityInput = document.getElementById("quantity");

  increaseBtn.addEventListener("click", () => {
    let currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
  });

  decreaseBtn.addEventListener("click", () => {
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
      quantityInput.value = currentValue - 1;
    }
  });

  const thumbnails = document.querySelectorAll(".thumbnail");
  const mainImage = document.querySelector(".main-image");

  thumbnails.forEach((thumbnail) => {
    thumbnail.addEventListener("click", () => {
      mainImage.src = thumbnail.src;
    });
  });
});
