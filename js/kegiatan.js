document.addEventListener("DOMContentLoaded", () => {
  // Animasi saat scroll
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

  const cards = document.querySelectorAll(".kegiatan-card");
  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";
    card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
    observer.observe(card);
  });

  // Hover efek pada status
  cards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      const status = this.querySelector(".kegiatan-status");
      if (status) status.style.transform = "scale(1.05)";
    });
    card.addEventListener("mouseleave", function () {
      const status = this.querySelector(".kegiatan-status");
      if (status) status.style.transform = "scale(1)";
    });
  });

  // Smooth scroll saat pagination
  const paginationLinks = document.querySelectorAll(".pagination a");
  paginationLinks.forEach((link) => {
    link.addEventListener("click", () => {
      setTimeout(() => {
        window.scrollTo({ top: 0, behavior: "smooth" });
      }, 100);
    });
  });

  // Tutup modal dengan klik luar
  const kegiatanModal = document.getElementById("kegiatanModal");
  function closeKegiatanModal() {
    if (kegiatanModal) {
      const modalContent = kegiatanModal.querySelector(".modal-content");
      if (modalContent) {
        modalContent.style.opacity = "0";
        modalContent.style.transform = "scale(0.9) translateY(20px)";
        setTimeout(() => {
          kegiatanModal.style.display = "none";
          document.body.style.overflow = "auto";
        }, 300);
      }
    }
  }

  if (kegiatanModal) {
    kegiatanModal.addEventListener("click", (e) => {
      if (e.target === kegiatanModal) closeKegiatanModal();
    });
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeKegiatanModal();
  });

  // Arahkan ke detail kegiatan
  window.openKegiatanModal = function (kegiatan) {
    window.location.href = `detail_kegiatan.php?id=${kegiatan.id}`;
  };

  // SEARCH DAN FILTER
  const searchInput = document.querySelector("#searchInput");
  const filterForm = document.querySelector(".filter-form");
  const filterSelect = document.querySelector(".filter-select");
  const searchBtn = document.querySelector(".search-btn");

  if (filterSelect) {
    filterSelect.addEventListener("change", () => {
      if (filterForm) filterForm.submit();
    });
  }

  if (filterForm) {
    filterForm.addEventListener("submit", (e) => {
      const searchValue = searchInput ? searchInput.value.trim() : "";
      const statusValue = filterSelect ? filterSelect.value : "";

      if (!searchValue && !statusValue) {
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.get("search") && !urlParams.get("status")) {
          e.preventDefault();
          if (searchInput) searchInput.focus();
        }
      }

      if (searchBtn) {
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        searchBtn.disabled = true;
      }
    });
  }

  // LIVE SUGGESTION DENGAN AUTO SUBMIT
  if (searchInput) {
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

    let timeout;
    let submitTimeout;

    function submitCari() {
      const form = document.querySelector(".filter-form");
      const query = searchInput.value.trim();

      if (query === "") {
        const cleanURL = window.location.origin + window.location.pathname;
        window.location.href = cleanURL;
      } else {
        form.submit();
      }
    }

    searchInput.addEventListener("input", function () {
      const query = this.value.trim();
      clearTimeout(timeout);
      clearTimeout(submitTimeout);

      if (query.length === 0) {
        suggestionBox.innerHTML = "";
        suggestionBox.style.display = "none";

        submitTimeout = setTimeout(() => {
          submitCari();
        }, 500);

        return;
      }

      timeout = setTimeout(() => {
        fetch(`get_kegiatan_suggestion.php?keyword=${encodeURIComponent(query)}`)
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

      submitTimeout = setTimeout(() => {
        submitCari();
      }, 1000);
    });

    searchInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        submitCari();
      }
    });

    document.addEventListener("click", function (e) {
      if (!inputGroup.contains(e.target)) {
        suggestionBox.style.display = "none";
      }
    });
  }

  // Badge transition
  const statusBadges = document.querySelectorAll(".kegiatan-status");
  statusBadges.forEach((badge) => {
    badge.style.transition = "transform 0.3s ease";
  });
});
