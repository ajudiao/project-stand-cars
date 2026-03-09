// Lógica de Pesquisa e Filtros de Veículos
// NOTA: os dados reais dos veículos são fornecidos por `js/data.js` (carregado antes deste ficheiro).
// NÃO redeclare `vehicles` ou `brands` aqui ou teremos um erro de declaração duplicada.

// Prefer the global `vehicles` from js/data.js but avoid redeclaring the identifier
const VEHICLES = (typeof vehicles !== 'undefined' && Array.isArray(vehicles)) ? vehicles : (typeof window !== 'undefined' && Array.isArray(window.vehicles) ? window.vehicles : [])
let filteredVehicles = [...VEHICLES]

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

function renderVehicles(vehiclesToRender) {
  const grid = document.getElementById("vehicles-grid")
  const noResults = document.getElementById("no-results")
  const resultsCount = document.getElementById("results-count")

  if (vehiclesToRender.length === 0) {
    grid.innerHTML = ""
    noResults.classList.remove("hidden")
    resultsCount.textContent = "Nenhum veículo encontrado"
    return
  }

  noResults.classList.add("hidden")
  resultsCount.textContent = `${vehiclesToRender.length} veículo${vehiclesToRender.length !== 1 ? "s" : ""} encontrado${vehiclesToRender.length !== 1 ? "s" : ""}`

  grid.innerHTML = vehiclesToRender
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

function applyFilters() {
  const searchTerm = document.getElementById("search-input").value.toLowerCase()
  const selectedBrand = document.getElementById("brand-filter").value
  const maxPrice = Number.parseInt(document.getElementById("price-range").value)
  const selectedFuel = document.getElementById("fuel-filter").value
  const selectedTransmission = document.getElementById("transmission-filter").value
  const selectedCondition = document.getElementById("condition-filter").value

  filteredVehicles = VEHICLES.filter((vehicle) => {
    const matchesSearch =
      !searchTerm ||
      vehicle.brand.toLowerCase().includes(searchTerm) ||
      vehicle.model.toLowerCase().includes(searchTerm)
    const matchesBrand = !selectedBrand || vehicle.brand === selectedBrand
    const matchesPrice = vehicle.price <= maxPrice
    const matchesFuel = !selectedFuel || vehicle.fuelType === selectedFuel
    const matchesTransmission = !selectedTransmission || vehicle.transmission === selectedTransmission
    const matchesCondition = !selectedCondition || vehicle.condition === selectedCondition

    return matchesSearch && matchesBrand && matchesPrice && matchesFuel && matchesTransmission && matchesCondition
  })

  applySorting()
}

function applySorting() {
  const sortValue = document.getElementById("sort-select").value

  switch (sortValue) {
    case "price-asc":
      filteredVehicles.sort((a, b) => a.price - b.price)
      break
    case "price-desc":
      filteredVehicles.sort((a, b) => b.price - a.price)
      break
    case "mileage-asc":
      filteredVehicles.sort((a, b) => a.mileage - b.mileage)
      break
    case "year-desc":
      filteredVehicles.sort((a, b) => b.year - a.year)
      break
    case "newest":
    default:
      filteredVehicles.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
  }

  renderVehicles(filteredVehicles)
}

function populateBrandFilter() {
  const brandFilter = document.getElementById("brand-filter")
  // `brands` is populated in `js/data.js` from the vehicles array.
  // Fallback: if it's not available, derive from vehicles.
  const brandList = typeof brands !== 'undefined' ? brands : [...new Set((VEHICLES || []).map((v) => v.brand))].sort()

  brandList.forEach((brand) => {
    const option = document.createElement("option")
    option.value = brand
    option.textContent = brand
    brandFilter.appendChild(option)
  })
}

function initializeFilters() {
  populateBrandFilter()

  // Search input
  document.getElementById("search-input").addEventListener("input", applyFilters)

  // Brand filter
  document.getElementById("brand-filter").addEventListener("change", applyFilters)

  // Price range
  const priceRange = document.getElementById("price-range")
  const priceValue = document.getElementById("price-value")
  // Initialize displayed price value
  priceValue.textContent = formatPrice(Number.parseInt(priceRange.value))

  priceRange.addEventListener("input", (e) => {
    priceValue.textContent = formatPrice(Number.parseInt(e.target.value))
    applyFilters()
  })

  // Other filters
  document.getElementById("fuel-filter").addEventListener("change", applyFilters)
  document.getElementById("transmission-filter").addEventListener("change", applyFilters)
  document.getElementById("condition-filter").addEventListener("change", applyFilters)

  // Sort
  document.getElementById("sort-select").addEventListener("change", applySorting)

  // Clear filters
  document.getElementById("clear-filters").addEventListener("click", () => {
    document.getElementById("search-input").value = ""
    document.getElementById("brand-filter").value = ""
    document.getElementById("price-range").value = "100000"
    document.getElementById("price-value").textContent = formatPrice(100000)
    document.getElementById("fuel-filter").value = ""
    document.getElementById("transmission-filter").value = ""
    document.getElementById("condition-filter").value = ""
    document.getElementById("sort-select").value = "newest"
    applyFilters()
  })

  // Initial render
  applyFilters()
}

document.addEventListener("DOMContentLoaded", initializeFilters)
