// Function to preview document in a new tab
function previewDocument(file) {
  window.open(`../uploads/dokumen/${file}`, "_blank");
}

// Function to download document
function downloadDocument(file) {
  const a = document.createElement("a");
  a.href = `../uploads/dokumen/${file}`;
  a.download = file;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}

// Function to clear search
function clearSearch() {
  const url = new URL(window.location);
  url.searchParams.delete("search");
  url.searchParams.delete("page");
  window.location.href = url.toString();
}

// DOM Ready
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector("#searchInput");
  const searchForm = document.querySelector(".search-form");
  const searchBtn = document.querySelector(".search-btn");

  const suggestionBox = document.createElement("div");
  suggestionBox.id = "suggestion-box";
  suggestionBox.style.position = "absolute";
  suggestionBox.style.top = "100%";
  suggestionBox.style.left = "0";
  suggestionBox.style.right = "0";
  suggestionBox.style.background = "white";
  suggestionBox.style.border = "1px solid #ccc";
  suggestionBox.style.zIndex = "100";
  suggestionBox.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.1)";
  suggestionBox.style.borderRadius = "0 0 1rem 1rem";
  suggestionBox.style.maxHeight = "200px";
  suggestionBox.style.overflowY = "auto";
  suggestionBox.style.display = "none";

  const inputGroup = searchInput.parentElement;
  inputGroup.style.position = "relative";
  inputGroup.appendChild(suggestionBox);

  let timeout, submitTimeout;

  function submitCari() {
    const query = searchInput.value.trim();
    if (query === "") {
      const cleanURL = window.location.origin + window.location.pathname;
      window.location.href = cleanURL;
    } else {
      searchForm.submit();
    }
  }

  // Live suggestion
  searchInput.addEventListener("input", function () {
    const query = this.value.trim();
    clearTimeout(timeout);
    clearTimeout(submitTimeout);

    if (query.length === 0) {
      suggestionBox.innerHTML = "";
      suggestionBox.style.display = "none";

      // Auto clear search
      submitTimeout = setTimeout(() => {
        submitCari();
      }, 500);

      return;
    }

    // Fetch suggestion
    timeout = setTimeout(() => {
      fetch(`get_dokumen_suggestion.php?keyword=${encodeURIComponent(query)}`)
        .then((res) => res.json())
        .then((data) => {
          suggestionBox.innerHTML = "";

          if (data.length > 0) {
            data.forEach((item) => {
              const div = document.createElement("div");
              div.textContent = item.judul;
              div.style.padding = "0.75rem 1rem";
              div.style.cursor = "pointer";
              div.style.borderBottom = "1px solid #f1f5f9";

              div.addEventListener("click", () => {
                searchInput.value = item.judul;
                suggestionBox.innerHTML = "";
                suggestionBox.style.display = "none";
                submitCari();
              });

              suggestionBox.appendChild(div);
            });
            suggestionBox.style.display = "block";
          } else {
            suggestionBox.style.display = "none";
          }
        });
    }, 300);

    // Auto submit after 1 second
    submitTimeout = setTimeout(() => {
      submitCari();
    }, 1000);
  });

  // Press Enter
  searchInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      submitCari();
    }
  });

  // Click outside → hide suggestions
  document.addEventListener("click", function (e) {
    if (!inputGroup.contains(e.target)) {
      suggestionBox.style.display = "none";
    }
  });

  // Loading state on submit
  if (searchBtn && searchForm) {
    searchForm.addEventListener("submit", () => {
      searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
      searchBtn.disabled = true;
    });
  }

  // Animate cards on scroll
  const cards = document.querySelectorAll(".dokumen-card");
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";
    card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
    observer.observe(card);
  });

  // Hover effect
  cards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      const icon = this.querySelector(".dokumen-icon i");
      if (icon) icon.style.transform = "scale(1.1)";
    });
    card.addEventListener("mouseleave", function () {
      const icon = this.querySelector(".dokumen-icon i");
      if (icon) icon.style.transform = "scale(1)";
    });
  });

  // Ripple effect on buttons
  const buttons = document.querySelectorAll(".btn-download, .btn-preview");
  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const ripple = document.createElement("span");
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;

      ripple.style.width = ripple.style.height = size + "px";
      ripple.style.left = x + "px";
      ripple.style.top = y + "px";
      ripple.classList.add("ripple");

      this.appendChild(ripple);
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });

  // Style
  const style = document.createElement("style");
  style.textContent = `
    .btn-download, .btn-preview {
      position: relative;
      overflow: hidden;
    }

    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: scale(0);
      animation: ripple-animation 0.6s linear;
      pointer-events: none;
    }

    @keyframes ripple-animation {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    .modal-content {
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
  `;
  document.head.appendChild(style);
});
