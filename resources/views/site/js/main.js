// Funções Utilitárias
function formatPrice(price) {
  return new Intl.NumberFormat("pt-PT", {
    style: "currency",
    currency: "EUR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(price)
}

function formatNumber(number) {
  return new Intl.NumberFormat("pt-PT").format(number)
}

// Os dados de veículos vêm de `js/data.js`. Use o array global `vehicles` fornecido lá.
// Preferir o `vehicles` global de data.js, fallback para window.vehicles, caso contrário array vazio.
// Usar um nome local diferente para evitar redeclarar o `vehicles` global.
const VEHICLES = (typeof vehicles !== 'undefined' && Array.isArray(vehicles)) ? vehicles : (typeof window !== 'undefined' && Array.isArray(window.vehicles) ? window.vehicles : [])

// Load Featured Vehicles on Home Page
function loadFeaturedVehicles() {
  const container = document.getElementById("featured-vehicles")
  if (!container) return

  const featured = (Array.isArray(VEHICLES) ? VEHICLES : []).slice(0, 3)

  container.innerHTML = featured
    .map(
      (vehicle) => `
    <div class="group overflow-hidden rounded-lg border border-border bg-card transition-shadow hover:shadow-lg fade-in">
      <div class="relative aspect-[4/3] overflow-hidden">
  <img src="${typeof getImageSrc === 'function' ? getImageSrc(vehicle.images[0]) : vehicle.images[0]}" alt="${vehicle.brand} ${vehicle.model}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
        <span class="absolute right-4 top-4 rounded-full bg-primary px-3 py-1 text-xs font-medium text-primary-foreground">
          ${vehicle.condition}
        </span>
      </div>
      <div class="p-6">
        <div class="mb-2 flex items-start justify-between">
          <div>
            <h3 class="text-xl font-bold">${vehicle.brand} ${vehicle.model}</h3>
            <p class="text-sm text-muted-foreground">${vehicle.year}</p>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold text-primary">${formatPrice(vehicle.price)}</div>
          </div>
        </div>
        <div class="mb-4 flex flex-wrap gap-2">
          <span class="rounded-full bg-secondary/10 px-3 py-1 text-xs font-medium text-secondary-foreground">${vehicle.transmission}</span>
          <span class="rounded-full bg-secondary/10 px-3 py-1 text-xs font-medium text-secondary-foreground">${vehicle.fuelType}</span>
          <span class="rounded-full bg-secondary/10 px-3 py-1 text-xs font-medium text-secondary-foreground">${formatNumber(vehicle.mileage)} km</span>
        </div>
        <a href="veiculo-detalhes.html?id=${vehicle.id}" class="inline-flex w-full items-center justify-center rounded-lg border border-border bg-transparent px-4 py-2 text-sm font-medium transition-colors hover:bg-muted">
          Ver Detalhes
          <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
  `,
    )
    .join("")
}

// Initialize home page
if (window.location.pathname.endsWith("index.html") || window.location.pathname === "/") {
  document.addEventListener("DOMContentLoaded", loadFeaturedVehicles)
}

// Smooth scroll for anchor links
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "start" })
      }
    })
  })
})
