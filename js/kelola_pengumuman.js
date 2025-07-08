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

// Edit pengumuman function
function editPengumuman(id) {
  fetch(`get_pengumuman.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const pengumuman = data.pengumuman
        document.getElementById("edit_id").value = pengumuman.id
        document.getElementById("edit_judul").value = pengumuman.judul
        document.getElementById("edit_deskripsi").value = pengumuman.deskripsi
        document.getElementById("edit_status").value = pengumuman.status
        document.getElementById("edit_created_by").value = pengumuman.created_by

        openModal("editModal")
      } else {
        alert("Gagal memuat data pengumuman")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// View pengumuman function
function viewPengumuman(id) {
  fetch(`get_pengumuman.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const pengumuman = data.pengumuman
        const content = `
                    <div class="detail-content">
                        <h3 style="color: #1e293b; margin-bottom: 1rem;">${pengumuman.judul}</h3>
                        <div style="margin-bottom: 1.5rem;">
                            <div style="margin-bottom: 1rem;">
                                <strong style="color: #374151;">Status:</strong><br>
                                <span class="status-badge status-${pengumuman.status}">${pengumuman.status.charAt(0).toUpperCase() + pengumuman.status.slice(1)}</span>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <strong style="color: #374151;">Tanggal Dibuat:</strong><br>
                                <span style="color: #6b7280;">${new Date(pengumuman.created_at).toLocaleDateString("id-ID")}</span>
                            </div>
                        </div>
                        <div>
                            <strong style="color: #374151;">Deskripsi:</strong>
                            <div style="margin-top: 0.5rem; line-height: 1.6; color: #374151;">
                                ${pengumuman.deskripsi.replace(/\n/g, "<br>")}
                            </div>
                        </div>
                    </div>
                `
        document.getElementById("viewContent").innerHTML = content
        openModal("viewModal")
      } else {
        alert("Gagal memuat detail pengumuman")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// Delete pengumuman function
function deletePengumuman(id) {
  if (confirm("Apakah Anda yakin ingin menghapus pengumuman ini?")) {
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
