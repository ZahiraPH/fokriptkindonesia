document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm")
  const loginBtn = document.querySelector(".login-btn")
  const usernameInput = document.getElementById("username")
  const passwordInput = document.getElementById("password")

  // Form validation
  loginForm.addEventListener("submit", (e) => {
    const username = usernameInput.value.trim()
    const password = passwordInput.value.trim()

    if (!username || !password) {
      e.preventDefault()
      showAlert("Username dan password harus diisi", "error")
      return
    }

    // Add loading state
    loginBtn.classList.add("loading")
    loginBtn.innerHTML = '<i class="fas fa-spinner"></i> Memproses...'
  })

  // Input focus effects
  const inputs = document.querySelectorAll(".input-group input")
  inputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.style.transform = "scale(1.02)"
    })

    input.addEventListener("blur", function () {
      this.parentElement.style.transform = "scale(1)"
    })
  })

  // Auto-focus username field
  usernameInput.focus()

  // Enter key navigation
  usernameInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      passwordInput.focus()
    }
  })

  passwordInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      loginForm.submit()
    }
  })

  // Add ripple effect to login button
  loginBtn.addEventListener("click", function (e) {
    const ripple = document.createElement("span")
    const rect = this.getBoundingClientRect()
    const size = Math.max(rect.width, rect.height)
    const x = e.clientX - rect.left - size / 2
    const y = e.clientY - rect.top - size / 2

    ripple.style.width = ripple.style.height = size + "px"
    ripple.style.left = x + "px"
    ripple.style.top = y + "px"
    ripple.classList.add("ripple")

    this.appendChild(ripple)

    setTimeout(() => {
      ripple.remove()
    }, 600)
  })

  // Animate alerts
  const alerts = document.querySelectorAll(".alert")
  alerts.forEach((alert) => {
    alert.style.opacity = "0"
    alert.style.transform = "translateY(-10px)"

    setTimeout(() => {
      alert.style.transition = "opacity 0.3s ease, transform 0.3s ease"
      alert.style.opacity = "1"
      alert.style.transform = "translateY(0)"
    }, 100)
  })
})

// Toggle password visibility
function togglePassword() {
  const passwordInput = document.getElementById("password")
  const passwordIcon = document.getElementById("passwordIcon")

  if (passwordInput.type === "password") {
    passwordInput.type = "text"
    passwordIcon.classList.remove("fa-eye")
    passwordIcon.classList.add("fa-eye-slash")
  } else {
    passwordInput.type = "password"
    passwordIcon.classList.remove("fa-eye-slash")
    passwordIcon.classList.add("fa-eye")
  }
}

// Show alert function
function showAlert(message, type) {
  const existingAlert = document.querySelector(".alert")
  if (existingAlert) {
    existingAlert.remove()
  }

  const alert = document.createElement("div")
  alert.className = `alert alert-${type}`
  alert.innerHTML = `
    <i class="fas fa-${type === "error" ? "exclamation-circle" : "check-circle"}"></i>
    ${message}
  `

  const form = document.querySelector(".login-form")
  form.parentNode.insertBefore(alert, form)

  // Animate alert
  alert.style.opacity = "0"
  alert.style.transform = "translateY(-10px)"

  setTimeout(() => {
    alert.style.transition = "opacity 0.3s ease, transform 0.3s ease"
    alert.style.opacity = "1"
    alert.style.transform = "translateY(0)"
  }, 100)

  // Auto-remove alert after 5 seconds
  setTimeout(() => {
    if (alert.parentNode) {
      alert.style.opacity = "0"
      alert.style.transform = "translateY(-10px)"
      setTimeout(() => {
        alert.remove()
      }, 300)
    }
  }, 5000)
}

// Add CSS for ripple effect
const style = document.createElement("style")
style.textContent = `
  .login-btn {
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
  
  .input-group {
    transition: transform 0.2s ease;
  }
`
document.head.appendChild(style)
