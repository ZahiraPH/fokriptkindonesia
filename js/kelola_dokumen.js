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

// Edit dokumen function
function editDokumen(id) {
  fetch(`get_dokumen.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const dokumen = data.dokumen
        document.getElementById("edit_id").value = dokumen.id
        document.getElementById("edit_judul").value = dokumen.judul
        document.getElementById("edit_deskripsi").value = dokumen.deskripsi
        document.getElementById("edit_uploaded_by").value = dokumen.uploaded_by
        document.getElementById("edit_existing_file").value = dokumen.file || ""

        // Show current file if exists
        const currentFileDiv = document.getElementById("current_file")
        if (dokumen.file) {
          currentFileDiv.innerHTML = `
            <small style="color: #6b7280;">File saat ini:</small><br>
            <a href="../uploads/dokumen/${dokumen.file}" target="_blank" style="color: #f59e0b; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
              <i class="fas fa-file-alt"></i>
              ${dokumen.file}
            </a>
          `
        } else {
          currentFileDiv.innerHTML = '<small style="color: #6b7280;">Tidak ada file</small>'
        }

        openModal("editModal")
      } else {
        alert("Gagal memuat data dokumen")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Terjadi kesalahan saat memuat data")
    })
}

// View dokumen function
function viewDokumen(id) {
  fetch(`get_dokumen.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const dokumen = data.dokumen;
        const file = dokumen.file;
        const ext = file.split('.').pop().toLowerCase();
        const fileUrl = `../uploads/dokumen/${file}`;
        const absoluteUrl = location.origin + '/' + fileUrl.replace('../', '');

        let viewer = '';

        if (ext === 'pdf') {
          viewer = `<iframe src="${fileUrl}" width="100%" height="600px" style="border: none;"></iframe>`;
        } else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext)) {
          viewer = `
            <iframe 
              src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(absoluteUrl)}" 
              width="100%" height="600px" style="border: none;">
            </iframe>`;
        } else {
          viewer = `<p>Preview tidak tersedia. Silakan <a href="${fileUrl}" download>download file</a>.</p>`;
        }

        const content = `
          <div class="detail-content">
            <h3 style="color: #1e293b; margin-bottom: 1rem;">${dokumen.judul}</h3>
            <div style="margin-bottom: 1.5rem;">
              <div style="margin-bottom: 1rem;">
                <strong style="color: #374151;">Diupload oleh:</strong><br>
                <span style="color: #6b7280;">${dokumen.uploaded_by}</span>
              </div>
              <div style="margin-bottom: 1rem;">
                <strong style="color: #374151;">Tanggal Upload:</strong><br>
                <span style="color: #6b7280;">${new Date(dokumen.created_at).toLocaleDateString("id-ID")}</span>
              </div>
              <div style="margin-bottom: 1rem;">
                <strong style="color: #374151;">Deskripsi:</strong><br>
                <div style="margin-top: 0.5rem; line-height: 1.6; color: #374151;">
                  ${dokumen.deskripsi.replace(/\n/g, "<br>")}
                </div>
              </div>
            </div>
            ${viewer}
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
              <a href="${fileUrl}" download class="btn btn-primary">
                <i class="fas fa-download"></i> Download File
              </a>
            </div>
          </div>
        `;

        document.getElementById("viewContent").innerHTML = content;
        openModal("viewModal");
      } else {
        alert("Gagal memuat detail dokumen");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Terjadi kesalahan saat memuat data");
    });
}


// Delete dokumen function
function deleteDokumen(id) {
  if (confirm("Apakah Anda yakin ingin menghapus dokumen ini?")) {
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

// File validation
document.addEventListener("DOMContentLoaded", () => {
  const fileInputs = document.querySelectorAll('input[type="file"]')

  fileInputs.forEach((fileInput) => {
    fileInput.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        const allowedTypes = [
          "application/pdf",
          "application/msword",
          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
          "application/vnd.ms-excel",
          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          "application/vnd.ms-powerpoint",
          "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        ]

        if (!allowedTypes.includes(file.type)) {
          alert("Format file tidak didukung. Gunakan format PDF, DOC, DOCX, XLS, XLSX, PPT, atau PPTX")
          this.value = ""
          return
        }

        // Check file size (max 10MB)
        const maxSize = 10 * 1024 * 1024 // 10MB in bytes
        if (file.size > maxSize) {
          alert("Ukuran file terlalu besar. Maksimal 10MB")
          this.value = ""
          return
        }
      }
    })
  })

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
