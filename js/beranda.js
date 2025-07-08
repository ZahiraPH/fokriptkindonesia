document.addEventListener("DOMContentLoaded", () => {
  // Animate elements on scroll
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

  // Observe all animated elements
  const animatedElements = document.querySelectorAll(
    ".stat-card, .sambutan-card, .about-card, .hero-content, .hero-decoration",
  )

  animatedElements.forEach((element, index) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(30px)"
    element.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`
    observer.observe(element)
  })

  // Counter animation for stats
  const statNumbers = document.querySelectorAll(".stat-content h3")
  const animateCounter = (element, target) => {
    let current = 0
    const increment = target / 100
    const timer = setInterval(() => {
      current += increment
      if (current >= target) {
        current = target
        clearInterval(timer)
      }
      element.textContent = Math.floor(current) + (target >= 1000 ? "+" : "")
    }, 20)
  }

  // Observe stat cards for counter animation
  const statsObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const number = entry.target.querySelector("h3")
          const text = number.textContent
          const value = Number.parseInt(text.replace(/\D/g, ""))
          animateCounter(number, value)
          statsObserver.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.5 },
  )

  document.querySelectorAll(".stat-card").forEach((card) => {
    statsObserver.observe(card)
  })

  // Smooth scrolling for anchor links
  const links = document.querySelectorAll('a[href^="#"]')
  links.forEach((link) => {
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

  // Add ripple effect to buttons
  const buttons = document.querySelectorAll(".btn")
  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
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
  })

  // Parallax effect for hero section
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset
    const heroSection = document.querySelector(".hero-section")
    if (heroSection) {
      heroSection.style.transform = `translateY(${scrolled * 0.5}px)`
    }
  })

  // Auto-play floating animation
  const floatingCards = document.querySelectorAll(".floating-card")
  floatingCards.forEach((card, index) => {
    card.style.animationDelay = `${index * -2}s`
  })
})

// Add CSS for ripple effect and animations
const style = document.createElement("style")
style.textContent = `
    .btn {
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
    
    .hero-section {
        will-change: transform;
    }
    
    .floating-card {
        will-change: transform;
    }
`
document.head.appendChild(style)
