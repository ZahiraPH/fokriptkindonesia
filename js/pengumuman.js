// Pengumuman.js
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector("#searchInput");
  const filterForm = document.querySelector(".filter-form");
  const filterSelect = document.querySelector(".filter-select");

  // Setup suggestion box
  let suggestionBox;
  if (searchInput) {
    suggestionBox = document.createElement("div");
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
  }

  // Timing & debounce
  let timeout;
  let submitTimeout;

  function submitCari() {
    const query = searchInput.value.trim();
    const status = filterSelect ? filterSelect.value : "";

    if (query === "" && status === "") {
      const cleanURL = window.location.origin + window.location.pathname;
      window.location.href = cleanURL;
    } else {
      if (filterForm) filterForm.submit();
    }
  }

  function fetchPengumuman() {
    const query = searchInput.value.trim();
    const status = filterSelect ? filterSelect.value : "";

    // Fetch live data
    fetch(`../php/get_pengumuman_live.php?search=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}`)
      .then(res => res.json())
      .then(data => renderCards(data));

    // Fetch suggestion
    if (query.length > 0) {
      fetch(`../php/get_pengumuman_suggestion.php?search=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => renderSuggestion(data));
    } else {
      suggestionBox.innerHTML = "";
      suggestionBox.style.display = "none";
    }
  }

  function renderSuggestion(items) {
    suggestionBox.innerHTML = "";
    if (items.length === 0) {
      suggestionBox.style.display = "none";
      return;
    }

    items.forEach(item => {
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
  }

  function renderCards(data) {
    const container = document.querySelector(".pengumuman-grid");
    container.innerHTML = "";

    if (!data.length) {
      container.innerHTML = `<div class="no-results"><h3>Tidak ada pengumuman ditemukan</h3></div>`;
      return;
    }

    data.forEach(item => {
      const card = document.createElement("div");
      card.className = "pengumuman-card";
      card.innerHTML = `
        <div class="pengumuman-status ${item.status}">
          ${item.status === 'active' ? 'Aktif' : 'Tidak Aktif'}
        </div>
        <div class="pengumuman-content">
          <h3>${item.judul}</h3>
          <p class="pengumuman-excerpt">${item.deskripsi.substring(0, 150)}...</p>
          <div class="pengumuman-meta">
            <span class="pengumuman-date"><i class="fas fa-calendar"></i> ${new Date(item.created_at).toLocaleDateString('id-ID')}</span>
            <span class="pengumuman-time"><i class="fas fa-clock"></i> ${new Date(item.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</span>
          </div>
          <button class="read-more-btn" onclick='window.location.href="detail_pengumuman.php?id=${item.id}"'>
            <i class="fas fa-eye"></i> Baca Selengkapnya
          </button>
        </div>`;
      container.appendChild(card);
    });
  }

  // Event listener input
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      clearTimeout(timeout);
      clearTimeout(submitTimeout);

      const query = searchInput.value.trim();

      if (query.length === 0) {
        suggestionBox.innerHTML = "";
        suggestionBox.style.display = "none";

        submitTimeout = setTimeout(() => {
          submitCari();
        }, 500);

        return;
      }

      timeout = setTimeout(() => {
        fetchPengumuman();
      }, 300);

      submitTimeout = setTimeout(() => {
        submitCari();
      }, 1000);
    });

    searchInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        submitCari();
      }
    });
  }

  if (filterSelect) {
    filterSelect.addEventListener("change", () => {
      if (filterForm) filterForm.submit();
    });
  }

  // Tutup suggestion saat klik di luar
  document.addEventListener("click", (e) => {
    if (suggestionBox && !suggestionBox.contains(e.target) && e.target !== searchInput) {
      suggestionBox.style.display = "none";
    }
  });

  // Load awal
  // fetchPengumuman();
});
