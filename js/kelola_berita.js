// Modal functions
function openModal(modalId) {
  document.getElementById(modalId).style.display = "block"
  document.body.style.overflow = "hidden"
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none"
  document.body.style.overflow = "auto"
}

// Close modal when clicking outside
window.onclick = (event) => {
  const modals = document.querySelectorAll(".modal")
  modals.forEach((modal) => {
    if (event.target === modal) {
      modal.style.display = "none"
      document.body.style.overflow = "auto"
    }
  })
}

// View berita function
function viewBerita(id) {
  fetch(`get_berita.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const berita = data.berita
        const content = `
                    <div class="detail-content">
                        ${berita.gambar ? `<img src="../uploads/berita/${berita.gambar}" alt="Gambar Berita" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem;">` : ""}
                        <h3 style="color: #1e293b; margin-bottom: 1rem;">${berita.judul}</h3>
                        <div style="margin-bottom: 1rem;">
                            <span style="color: #6b7280; font-size: 0.9rem;">
                                <i class="fas fa-user"></i> ${berita.penulis} | 
                                <i class="fas fa-calendar"></i> ${new Date(berita.created_at).toLocaleDateString("id-ID")}
                            </span>
                        </div>
                        <div style="line-height: 1.6; color: #374151;">
                            ${berita.deskripsi.replace(/\n/g, "<br>")}
                        </div>
                    </div>
                `
        document.getElementById("viewContent").innerHTML = content
        openModal("viewModal")
      } else {
        alert("Gagal memuat detail berita")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// Delete berita function
function deleteBerita(id) {
  if (confirm("Apakah Anda yakin ingin menghapus berita ini?")) {
    const form = document.createElement("form")
    form.method = "POST"
    form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${id}">
        `
    document.body.appendChild(form)
    form.submit()
  }
}

// Form validation
document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("form")
  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      const requiredFields = form.querySelectorAll("[required]")
      let isValid = true

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false
          field.style.borderColor = "#ef4444"
        } else {
          field.style.borderColor = "#d1d5db"
        }
      })

      if (!isValid) {
        e.preventDefault()
        alert("Mohon lengkapi semua field yang wajib diisi")
      }
    })
  })
})

// Edit berita function
function editBerita(id) {
  fetch(`get_berita.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const berita = data.berita
        document.getElementById("edit_id").value = berita.id
        document.getElementById("edit_judul").value = berita.judul
        document.getElementById("edit_deskripsi").value = berita.deskripsi
        document.getElementById("edit_penulis").value = berita.penulis || "";
        document.getElementById("edit_existing_gambar").value = berita.gambar || ""

        // Show current image if exists
        const currentImageDiv = document.getElementById("current_image")
        if (berita.gambar) {
          currentImageDiv.innerHTML = `
            <small style="color: #6b7280;">Gambar saat ini:</small><br>
            <img src="../uploads/berita/${berita.gambar}" alt="Current Image" style="max-width: 200px; max-height: 100px; object-fit: cover; border-radius: 0.25rem; margin-top: 0.25rem;">
          `
        } else {
          currentImageDiv.innerHTML = '<small style="color: #6b7280;">Tidak ada gambar</small>'
        }

        // Declare openModal function or import it
        function openModal(modalId) {
          document.getElementById(modalId).style.display = "block"
        }

        openModal("editModal")
      } else {
        alert("Gagal memuat data berita")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

document.addEventListener("DOMContentLoaded", () => {
  const inputPenulis = document.getElementById("edit_penulis");
  const warningPenulis = document.getElementById("warning_penulis");

  const validPengurusIDs = window.validPengurusIDs || [];

  if (inputPenulis) {
    inputPenulis.addEventListener("input", () => {
      const id = inputPenulis.value.trim();
      if (!validPengurusIDs.includes(id)) {
        warningPenulis.style.display = "block";
      } else {
        warningPenulis.style.display = "none";
      }
    });
  }
});
