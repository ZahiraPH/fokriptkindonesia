document.addEventListener("DOMContentLoaded", () => {
  // Mobile menu toggle
  const mobileToggle = document.querySelector(".mobile-menu-toggle")
  const mobileNav = document.querySelector(".mobile-nav")

  if (mobileToggle && mobileNav) {
    mobileToggle.addEventListener("click", function () {
      this.classList.toggle("active")
      mobileNav.classList.toggle("active")
      document.body.classList.toggle("mobile-menu-open")
    })
  }

  // Mobile dropdown functionality
  const mobileDropdownToggles = document.querySelectorAll(".mobile-dropdown-toggle")
  mobileDropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault()
      const dropdown = this.nextElementSibling
      const icon = this.querySelector("i")

      dropdown.classList.toggle("active")
      icon.style.transform = dropdown.classList.contains("active") ? "rotate(180deg)" : "rotate(0deg)"
    })
  })

  // Close mobile menu when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".main-header") && !e.target.closest(".mobile-nav")) {
      mobileToggle?.classList.remove("active")
      mobileNav?.classList.remove("active")
      document.body.classList.remove("mobile-menu-open")
    }
  })

  // Header scroll effect
  const header = document.querySelector(".main-header")
  let lastScrollTop = 0

  window.addEventListener("scroll", () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop

    // Add background blur when scrolled
    if (scrollTop > 50) {
      header.style.backdropFilter = "blur(10px)"
      header.style.background = "linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(59, 130, 246, 0.95) 100%)"
    } else {
      header.style.backdropFilter = "none"
      header.style.background = "linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)"
    }

    lastScrollTop = scrollTop
  })

  // Active page highlighting
  const currentPage = window.location.pathname.split("/").pop()
  const navLinks = document.querySelectorAll(".nav-link")

  navLinks.forEach((link) => {
    const href = link.getAttribute("href")
    if (href === currentPage || (currentPage === "" && href === "beranda.php")) {
      link.classList.add("active")
    }
  })

  // Dropdown hover effects
  const dropdowns = document.querySelectorAll(".dropdown")
  dropdowns.forEach((dropdown) => {
    const menu = dropdown.querySelector(".dropdown-menu")

    dropdown.addEventListener("mouseenter", () => {
      menu.style.display = "block"
    })

    dropdown.addEventListener("mouseleave", () => {
      setTimeout(() => {
        if (!dropdown.matches(":hover")) {
          menu.style.display = "none"
        }
      }, 100)
    })
  })
})

// Add CSS for mobile menu body lock
const style = document.createElement("style")
style.textContent = `
    body.mobile-menu-open {
        overflow: hidden;
    }
    
    .mobile-dropdown-toggle i {
        transition: transform 0.3s ease;
    }
`
document.head.appendChild(style)
