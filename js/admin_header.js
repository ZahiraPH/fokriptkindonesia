document.addEventListener("DOMContentLoaded", () => {
  const mobileToggle = document.querySelector(".mobile-toggle")
  const mobileMenu = document.querySelector(".mobile-menu")

  if (mobileToggle && mobileMenu) {
    mobileToggle.addEventListener("click", () => {
      mobileMenu.classList.toggle("active")
    })

    // Close mobile menu when clicking outside
    document.addEventListener("click", (e) => {
      if (!mobileToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.classList.remove("active")
      }
    })

    // Close mobile menu when window is resized to desktop
    window.addEventListener("resize", () => {
      if (window.innerWidth > 768) {
        mobileMenu.classList.remove("active")
      }
    })
  }
})
