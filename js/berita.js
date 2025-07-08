document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector("#searchInput");
  const suggestionBox = document.createElement("div");

  // Styling suggestion box
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

  // Fungsi submit pencarian (default vs dengan keyword)
  function submitCari() {
    const form = document.querySelector(".search-form");
    const query = searchInput.value.trim();

    if (query === "") {
      // Kembali ke default tanpa filter
      const cleanURL = window.location.origin + window.location.pathname;
      window.location.href = cleanURL;
    } else {
      form.submit();
    }
  }

  // Saat input berubah
  searchInput.addEventListener("input", function () {
    const query = this.value.trim();
    clearTimeout(timeout);
    clearTimeout(submitTimeout);

    if (query.length === 0) {
      suggestionBox.innerHTML = "";
      suggestionBox.style.display = "none";

      // Kembali ke default otomatis jika dikosongkan
      submitTimeout = setTimeout(() => {
        submitCari();
      }, 500);

      return;
    }

    // Fetch data untuk suggestion
    timeout = setTimeout(() => {
      fetch(`get_berita_suggestion.php?keyword=${encodeURIComponent(query)}`)
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
                submitCari(); // klik → submit
              });

              suggestionBox.appendChild(div);
            });

            suggestionBox.style.display = "block";
          } else {
            suggestionBox.style.display = "none";
          }
        });
    }, 300);

    // Auto-submit setelah berhenti ngetik
    submitTimeout = setTimeout(() => {
      submitCari();
    }, 1000);
  });

  // Enter → submit
  searchInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      submitCari();
    }
  });

  // Klik di luar suggestion box → sembunyikan
  document.addEventListener("click", function (e) {
    if (!inputGroup.contains(e.target)) {
      suggestionBox.style.display = "none";
    }
  });
});
