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

// Clear filters function
function clearFilters() {
  const url = new URL(window.location.href);
  url.searchParams.delete('filter_kategori');
  url.searchParams.delete('filter_bidang');
  url.searchParams.delete('search');
  window.location.href = url.toString();
}

// View pengurus function
function viewPengurus(id) {
  fetch(`get_pengurus.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const pengurus = data.pengurus
        const content = `
                    <div class="detail-content">
                        <h3 style="color: #1e293b; margin-bottom: 1.5rem;">${pengurus.nama}</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <strong style="color: #374151;">Username:</strong><br>
                                <span style="color: #6b7280;">${pengurus.username}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Kategori:</strong><br>
                                <span class="kategori-badge kategori-${pengurus.kategori.toLowerCase().replace(" ", "-")}">${pengurus.kategori}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Bidang:</strong><br>
                                <span style="color: #6b7280;">${pengurus.bidang}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Sub Bidang:</strong><br>
                                <span style="color: #6b7280;">${pengurus.sub_bidang || "-"}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Jabatan:</strong><br>
                                <span style="color: #6b7280;">${pengurus.jabatan}</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Jenis Kelamin:</strong><br>
                                <span style="color: #6b7280;">${pengurus.jenis_kelamin}</span>
                            </div>
                        </div>
                        <div>
                            <strong style="color: #374151;">Asal PTK:</strong><br>
                            <span style="color: #6b7280;">${pengurus.asal_ptk}</span>
                        </div>
                    </div>
                `
        document.getElementById("viewContent").innerHTML = content
        openModal("viewModal")
      } else {
        alert("Gagal memuat detail pengurus: " + (data.message || "Unknown error"))
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// Edit pengurus function
function editPengurus(id) {
  fetch(`get_pengurus.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const pengurus = data.pengurus
        // Populate edit form fields
        document.getElementById("edit_id").value = pengurus.id
        document.getElementById("edit_id_display").value = `#${pengurus.id}` // Tampilkan ID dengan format
        document.getElementById("edit_nama").value = pengurus.nama
        document.getElementById("edit_username").value = pengurus.username
        document.getElementById("edit_kategori").value = pengurus.kategori
        document.getElementById("edit_bidang").value = pengurus.bidang
        document.getElementById("edit_sub_bidang").value = pengurus.sub_bidang || ""
        document.getElementById("edit_jabatan").value = pengurus.jabatan
        document.getElementById("edit_asal_ptk").value = pengurus.asal_ptk
        document.getElementById("edit_jenis_kelamin").value = pengurus.jenis_kelamin

        openModal("editModal")
      } else {
        alert("Gagal memuat data pengurus: " + (data.message || "Unknown error"))
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// Delete pengurus function
function deletePengurus(id) {
  if (confirm("Apakah Anda yakin ingin menghapus pengurus ini? Tindakan ini tidak dapat dibatalkan.")) {
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
  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => {
        alert.style.display = 'none';
      }, 300);
    }, 5000);
  });

  const forms = document.querySelectorAll("form")
  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      const requiredFields = form.querySelectorAll("[required]")
      let isValid = true
      let errorMessage = ""

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false
          field.style.borderColor = "#ef4444"
          field.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.1)"
        } else {
          field.style.borderColor = "#d1d5db"
          field.style.boxShadow = "none"
        }
      })

      // Password validation for add form
      const password = form.querySelector("#password")
      if (password && password.value && password.value.length < 6) {
        isValid = false
        password.style.borderColor = "#ef4444"
        password.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.1)"
        errorMessage = "Password minimal 6 karakter"
      }

      // Username validation
      const username = form.querySelector('input[name="username"]')
      if (username && username.value) {
        if (username.value.length < 3) {
          isValid = false
          username.style.borderColor = "#ef4444"
          username.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.1)"
          errorMessage = "Username minimal 3 karakter"
        }
      }

      if (!isValid) {
        e.preventDefault()
        if (errorMessage) {
          alert(errorMessage)
        } else {
          alert("Mohon lengkapi semua field yang wajib diisi")
        }
      }
    })
  })

  // Username validation - remove spaces and special characters
  const usernameFields = document.querySelectorAll('input[name="username"]')
  usernameFields.forEach(field => {
    field.addEventListener("input", function () {
      // Remove spaces and special characters, keep only alphanumeric and underscore
      this.value = this.value.replace(/[^a-zA-Z0-9_]/g, "")
    })
  })

  // Real-time validation feedback
  const inputs = document.querySelectorAll('input[required], select[required]')
  inputs.forEach(input => {
    input.addEventListener('blur', function() {
      if (!this.value.trim()) {
        this.style.borderColor = "#ef4444"
        this.style.boxShadow = "0 0 0 3px rgba(239, 68, 68, 0.1)"
      } else {
        this.style.borderColor = "#10b981"
        this.style.boxShadow = "0 0 0 3px rgba(16, 185, 129, 0.1)"
        
        // Reset to normal after 2 seconds
        setTimeout(() => {
          this.style.borderColor = "#d1d5db"
          this.style.boxShadow = "none"
        }, 2000)
      }
    })
  })
})