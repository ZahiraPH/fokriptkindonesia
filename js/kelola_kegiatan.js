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

// Edit kegiatan function
function editKegiatan(id) {
  fetch(`get_kegiatan.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const kegiatan = data.kegiatan
        document.getElementById("edit_id").value = kegiatan.id
        document.getElementById("edit_judul").value = kegiatan.judul
        document.getElementById("edit_deskripsi").value = kegiatan.deskripsi
        document.getElementById("edit_tanggal_mulai").value = kegiatan.tanggal_mulai
        document.getElementById("edit_tanggal_selesai").value = kegiatan.tanggal_selesai
        document.getElementById("edit_lokasi").value = kegiatan.lokasi
        document.getElementById("edit_penanggung_jawab").value = kegiatan.penanggung_jawab
        document.getElementById("edit_bidang").value = kegiatan.bidang
        document.getElementById("edit_status").value = kegiatan.status

        openModal("editModal")
      } else {
        alert("Gagal memuat data kegiatan")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// View kegiatan function
function viewKegiatan(id) {
  fetch(`get_kegiatan.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const kegiatan = data.kegiatan
        const content = `
                    <div class="detail-content">
                        <h3 style="color: #1e293b; margin-bottom: 1rem;">${kegiatan.judul}</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <strong style="color: #374151;">Tanggal Mulai:</strong><br>
                                <span style="color: #6b7280;">${new Date(kegiatan.tanggal_mulai).toLocaleDateString("id-ID")}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Tanggal Selesai:</strong><br>
                                <span style="color: #6b7280;">${new Date(kegiatan.tanggal_selesai).toLocaleDateString("id-ID")}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Lokasi:</strong><br>
                                <span style="color: #6b7280;">${kegiatan.lokasi}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Status:</strong><br>
                                <span class="status-badge status-${kegiatan.status}">${kegiatan.status.charAt(0).toUpperCase() + kegiatan.status.slice(1)}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Penanggung Jawab:</strong><br>
                                <span style="color: #6b7280;">${kegiatan.penanggung_jawab}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Bidang:</strong><br>
                                <span style="color: #6b7280;">${kegiatan.bidang}</span>
                            </div>
                        </div>
                        <div>
                            <strong style="color: #374151;">Deskripsi:</strong>
                            <div style="margin-top: 0.5rem; line-height: 1.6; color: #374151;">
                                ${kegiatan.deskripsi.replace(/\n/g, "<br>")}
                            </div>
                        </div>
                    </div>
                `
        document.getElementById("viewContent").innerHTML = content
        openModal("viewModal")
      } else {
        alert("Gagal memuat detail kegiatan")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// Delete kegiatan function
function deleteKegiatan(id) {
  if (confirm("Apakah Anda yakin ingin menghapus kegiatan ini?")) {
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

// Date validation
document.addEventListener("DOMContentLoaded", () => {
  const tanggalMulai = document.getElementById("tanggal_mulai")
  const tanggalSelesai = document.getElementById("tanggal_selesai")
  const editTanggalMulai = document.getElementById("edit_tanggal_mulai")
  const editTanggalSelesai = document.getElementById("edit_tanggal_selesai")

  function setupDateValidation(startField, endField) {
    if (startField && endField) {
      startField.addEventListener("change", function () {
        endField.min = this.value
        if (endField.value && endField.value < this.value) {
          endField.value = this.value
        }
      })

      endField.addEventListener("change", function () {
        if (this.value < startField.value) {
          alert("Tanggal selesai tidak boleh lebih awal dari tanggal mulai")
          this.value = startField.value
        }
      })
    }
  }

  setupDateValidation(tanggalMulai, tanggalSelesai)
  setupDateValidation(editTanggalMulai, editTanggalSelesai)

  // Form validation
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
