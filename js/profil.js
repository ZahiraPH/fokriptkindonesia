// JavaScript untuk tab switching dan interaksi
console.log("Profil.js loaded successfully")

document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM Content Loaded")

  // Tab switching functionality dengan debugging yang lebih detail
  const tabButtons = document.querySelectorAll(".tab-btn")
  const tabContents = document.querySelectorAll(".tab-content")

  console.log("Tab buttons found:", tabButtons.length)
  console.log("Tab contents found:", tabContents.length)

  // Debug: Log semua tab content yang ditemukan
  tabContents.forEach((content, index) => {
    console.log(`Tab content ${index}:`, content.id, content.getAttribute("data-tab-type"))
  })

  // Pastikan ada tab buttons dan contents
  if (tabButtons.length === 0 || tabContents.length === 0) {
    console.error("Tab elements not found!")
    return
  }

  // FORCE tab switching dengan metode yang lebih robust
  function forceTabSwitch(targetTabId) {
    console.log("=== FORCE TAB SWITCH TO:", targetTabId, "===")

    // 1. Hide ALL tab contents dengan multiple methods
    const allTabContents = document.querySelectorAll(".tab-content")
    allTabContents.forEach((content) => {
      content.classList.remove("active")
      content.style.display = "none"
      content.style.visibility = "hidden"
      content.style.opacity = "0"
      console.log("Hidden:", content.id)
    })

    // 2. Remove active from ALL buttons
    const allButtons = document.querySelectorAll(".tab-btn")
    allButtons.forEach((btn) => {
      btn.classList.remove("active")
    })

    // 3. Show target content dengan multiple methods
    const targetContent = document.getElementById(targetTabId)
    const targetButton = document.querySelector(`[data-tab="${targetTabId}"]`)

    if (targetContent && targetButton) {
      // Force show target content
      targetContent.classList.add("active")
      targetContent.style.display = "block"
      targetContent.style.visibility = "visible"
      targetContent.style.opacity = "1"

      // Activate target button
      targetButton.classList.add("active")

      console.log("Showed:", targetTabId)

      // Count members in active tab
      const memberCards = targetContent.querySelectorAll(".member-card")
      console.log(`Members in ${targetTabId}:`, memberCards.length)

      // Log first few member names for verification
      memberCards.forEach((card, idx) => {
        if (idx < 3) {
          const name = card.querySelector("h5")?.textContent
          console.log(`  ${idx + 1}. ${name}`)
        }
      })

      return true
    } else {
      console.error("Target not found:", targetTabId)
      return false
    }
  }

  // Setup tab buttons dengan event listener yang robust
  tabButtons.forEach((button) => {
    const targetTab = button.getAttribute("data-tab")
    console.log("Setting up button for:", targetTab)

    // Remove any existing listeners
    button.removeEventListener("click", handleTabClick)

    // Add new listener
    button.addEventListener("click", handleTabClick)
  })

  function handleTabClick(e) {
    e.preventDefault()
    e.stopPropagation()

    const targetTab = this.getAttribute("data-tab")
    console.log("Button clicked for tab:", targetTab)

    // Force switch
    const success = forceTabSwitch(targetTab)

    if (success) {
      console.log("✅ Tab switch successful!")
    } else {
      console.error("❌ Tab switch failed!")
    }
  }

  // Test functions untuk debugging
  window.testMajelis = () => {
    console.log("=== TESTING MAJELIS ===")
    forceTabSwitch("majelis")
  }

  window.testSenat = () => {
    console.log("=== TESTING SENAT ===")
    forceTabSwitch("senat")
  }

  // Debug function untuk melihat struktur DOM
  window.debugDOM = () => {
    console.log("=== DOM DEBUG ===")

    const majelisTab = document.getElementById("majelis")
    const senatTab = document.getElementById("senat")

    console.log("Majelis tab exists:", !!majelisTab)
    console.log("Senat tab exists:", !!senatTab)

    if (majelisTab) {
      const majelisCards = majelisTab.querySelectorAll(".member-card")
      console.log("Majelis cards:", majelisCards.length)
    }

    if (senatTab) {
      const senatCards = senatTab.querySelectorAll(".member-card")
      console.log("Senat cards:", senatCards.length)

      // Log senat member names
      senatCards.forEach((card, idx) => {
        const name = card.querySelector("h5")?.textContent
        console.log(`Senat ${idx + 1}: ${name}`)
      })
    }
  }

  // Auto-run debug on load
  setTimeout(() => {
    console.log("=== AUTO DEBUG ===")
    window.debugDOM()
  }, 1000)

  // Add hover effects (simplified)
  setTimeout(() => {
    const memberCards = document.querySelectorAll(".member-card")
    memberCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-8px)"
        this.style.boxShadow = "0 15px 35px rgba(0, 0, 0, 0.2)"
      })

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0)"
        this.style.boxShadow = "0 4px 15px rgba(0, 0, 0, 0.1)"
      })
    })
  }, 500)

  console.log("All event listeners attached successfully")
})

// Add CRITICAL CSS to force tab switching
const criticalStyle = document.createElement("style")
criticalStyle.textContent = `
  /* FORCE TAB CONTENT SWITCHING */
  .tab-content {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
  }
  
  .tab-content.active {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
  }
  
  /* Ensure member cards are always visible */
  .member-card {
    opacity: 1 !important;
    transform: translateY(0) !important;
  }
  
  /* Force tab button states */
  .tab-btn {
    background: transparent !important;
    color: #3b82f6 !important;
  }
  
  .tab-btn.active {
    background: #3b82f6 !important;
    color: white !important;
  }
`
document.head.appendChild(criticalStyle)
