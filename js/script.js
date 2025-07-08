// Mobile Menu Toggle
document.addEventListener("DOMContentLoaded", () => {
  const mobileToggle = document.querySelector(".mobile-menu-toggle")
  const mainNav = document.querySelector(".main-nav")

  if (mobileToggle && mainNav) {
    mobileToggle.addEventListener("click", () => {
      mainNav.classList.toggle("active")
      mobileToggle.classList.toggle("active")
    })
  }

  // Smooth scrolling for anchor links
  const anchorLinks = document.querySelectorAll('a[href^="#"]')
  anchorLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })

  // Add scroll effect to header
  window.addEventListener("scroll", () => {
    const header = document.querySelector(".header")
    if (window.scrollY > 100) {
      header.style.background =
        "linear-gradient(135deg, rgba(99, 102, 241, 0.95) 0%, rgba(139, 92, 246, 0.95) 50%, rgba(168, 85, 247, 0.95) 100%)"
    } else {
      header.style.background = "linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%)"
    }
  })

  // Animation on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1"
        entry.target.style.transform = "translateY(0)"
      }
    })
  }, observerOptions)

  // Observe elements for animation
  const animateElements = document.querySelectorAll(".sambutan-item, .about-item")
  animateElements.forEach((el) => {
    el.style.opacity = "0"
    el.style.transform = "translateY(30px)"
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease"
    observer.observe(el)
  })
})

// Dropdown menu functionality for mobile
document.addEventListener("click", (e) => {
  const dropdowns = document.querySelectorAll(".dropdown-menu")
  dropdowns.forEach((dropdown) => {
    if (!dropdown.parentElement.contains(e.target)) {
      dropdown.style.opacity = "0"
      dropdown.style.visibility = "hidden"
    }
  })
})

// Form validation helper (for future use)
function validateForm(formElement) {
  const inputs = formElement.querySelectorAll("input[required], textarea[required]")
  let isValid = true

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      input.classList.add("error")
      isValid = false
    } else {
      input.classList.remove("error")
    }
  })

  return isValid
}

// Loading animation
function showLoading() {
  const loader = document.createElement("div")
  loader.className = "loader"
  loader.innerHTML = '<div class="spinner"></div>'
  document.body.appendChild(loader)
}

function hideLoading() {
  const loader = document.querySelector(".loader")
  if (loader) {
    loader.remove()
  }
}
