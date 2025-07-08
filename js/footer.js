document.addEventListener("DOMContentLoaded", () => {
  // Animate footer elements on scroll
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

  // Observe footer sections
  const footerSections = document.querySelectorAll(".footer-section")
  footerSections.forEach((section, index) => {
    section.style.opacity = "0"
    section.style.transform = "translateY(20px)"
    section.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`
    observer.observe(section)
  })

  // Add current year to copyright
  const currentYear = new Date().getFullYear()
  const copyrightText = document.querySelector(".footer-bottom p")
  if (copyrightText) {
    copyrightText.textContent = copyrightText.textContent.replace("2025", currentYear)
  }

  // Smooth scroll to top functionality
  const backToTopBtn = document.createElement("button")
  backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>'
  backToTopBtn.className = "back-to-top"
  backToTopBtn.setAttribute("aria-label", "Back to top")
  document.body.appendChild(backToTopBtn)

  // Show/hide back to top button
  window.addEventListener("scroll", () => {
    if (window.pageYOffset > 300) {
      backToTopBtn.classList.add("show")
    } else {
      backToTopBtn.classList.remove("show")
    }
  })

  // Back to top functionality
  backToTopBtn.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    })
  })

  // Add hover effects to social media links
  const socialLinks = document.querySelectorAll('.footer-section a[href*="#"]')
  socialLinks.forEach((link) => {
    link.addEventListener("mouseenter", function () {
      const icon = this.querySelector("i")
      if (icon) {
        icon.style.transform = "scale(1.2)"
      }
    })

    link.addEventListener("mouseleave", function () {
      const icon = this.querySelector("i")
      if (icon) {
        icon.style.transform = "scale(1)"
      }
    })
  })
})

// Add CSS for back to top button
const style = document.createElement("style")
style.textContent = `
    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    
    .back-to-top.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .back-to-top:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }
    
    .footer-section i {
        transition: transform 0.3s ease;
    }
`
document.head.appendChild(style)
