// Contact Form Handler
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contact-form")
  const successMessage = document.getElementById("form-success")

  form.addEventListener("submit", (e) => {
    e.preventDefault()

    // Get form data
    const formData = new FormData(form)
    const data = Object.fromEntries(formData)

    console.log("[v0] Form submitted:", data)

    // Simulate API call
    setTimeout(() => {
      // Show success message
      successMessage.classList.remove("hidden")

      // Reset form
      form.reset()

      // Hide success message after 5 seconds
      setTimeout(() => {
        successMessage.classList.add("hidden")
      }, 5000)

      // Scroll to success message
      successMessage.scrollIntoView({ behavior: "smooth", block: "nearest" })
    }, 500)
  })
})
