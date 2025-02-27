document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".carousel-container");
    const prevBtn = document.querySelector(".carousel-prev");
    const nextBtn = document.querySelector(".carousel-next");
    const cardWidth = 300 + 20; // Card width + gap
    const visibleCards = 3; // Number of visible cards
    const scrollAmount = cardWidth * visibleCards; // Move by 3 cards
  
    prevBtn.addEventListener("click", () => {
      container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });
  
    nextBtn.addEventListener("click", () => {
      container.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });
  });
  