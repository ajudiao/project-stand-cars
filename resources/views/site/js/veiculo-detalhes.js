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

function getVehicleIdFromURL() {
  const params = new URLSearchParams(window.location.search)
  return params.get("id")
}

function renderVehicleDetails() {
  const vehicleId = getVehicleIdFromURL()
  // Garantir que `vehicles` está disponível (fornecido por js/data.js)
  const vehicleList = (typeof vehicles !== 'undefined' && Array.isArray(vehicles)) ? vehicles : (typeof window !== 'undefined' && Array.isArray(window.vehicles) ? window.vehicles : [])
  const vehicle = vehicleList.find((v) => String(v.id) === String(vehicleId))
  const container = document.getElementById("vehicle-details")

  if (!vehicle) {
    container.innerHTML = `
      <div class="rounded-lg border border-border bg-card p-12 text-center">
        <svg class="mx-auto mb-4 h-16 w-16 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h2 class="mb-2 text-2xl font-bold">Veículo não encontrado</h2>
        <p class="mb-6 text-muted-foreground">O veículo que procura não existe ou foi removido.</p>
        <a href="veiculos.html" class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-base font-medium text-primary-foreground transition-colors hover:bg-primary/90">
          Ver Todos os Veículos
        </a>
      </div>
    `
    return
  }

  // Obter veículos similares
  const similarVehicles = vehicles
    .filter((v) => v.id !== vehicle.id && (v.brand === vehicle.brand || v.fuelType === vehicle.fuelType))
    .slice(0, 3)

  container.innerHTML = `
    <div class="mb-6">
      <a href="veiculos.html" class="inline-flex items-center text-sm text-muted-foreground transition-colors hover:text-primary">
        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Voltar aos veículos
      </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
      <!-- Main Content -->
      <div class="lg:col-span-2">
        <!-- Image Gallery -->
        <div class="mb-8">
            <div class="aspect-[16/10] overflow-hidden rounded-2xl border border-border bg-muted">
            <img src="${typeof getImageSrc === 'function' ? getImageSrc(vehicle.images[0]) : vehicle.images[0]}" alt="${vehicle.brand} ${vehicle.model}" class="h-full w-full object-cover">
          </div>
        </div>

        <!-- Vehicle Info -->
        <div class="mb-8">
          <div class="mb-4 flex items-start justify-between">
            <div>
              <h1 class="mb-2 text-3xl font-bold lg:text-4xl">${vehicle.brand} ${vehicle.model}</h1>
              <p class="text-lg text-muted-foreground">${vehicle.year}</p>
            </div>
            <span class="rounded-full bg-primary px-4 py-2 text-sm font-medium text-primary-foreground">
              ${vehicle.condition}
            </span>
          </div>
          <div class="text-4xl font-bold text-primary">${formatPrice(vehicle.price)}</div>
        </div>

        <!-- Specifications -->
        <div class="mb-8 rounded-lg border border-border bg-card p-6">
          <h2 class="mb-4 text-xl font-bold">Especificações</h2>
          <div class="grid gap-4 sm:grid-cols-2">
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-muted-foreground">Ano</div>
                <div class="font-semibold">${vehicle.year}</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-muted-foreground">Combustível</div>
                <div class="font-semibold">${vehicle.fuelType}</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-muted-foreground">Transmissão</div>
                <div class="font-semibold">${vehicle.transmission}</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-muted-foreground">Quilometragem</div>
                <div class="font-semibold">${formatNumber(vehicle.mileage)} km</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-muted-foreground">Cor</div>
                <div class="font-semibold">${vehicle.color}</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <div class="text-sm text-muted-foreground">Localização</div>
                <div class="font-semibold">${vehicle.location}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-8 rounded-lg border border-border bg-card p-6">
          <h2 class="mb-4 text-xl font-bold">Descrição</h2>
          <p class="leading-relaxed text-muted-foreground">${vehicle.description}</p>
        </div>

        <!-- Features -->
        <div class="mb-8 rounded-lg border border-border bg-card p-6">
          <h2 class="mb-4 text-xl font-bold">Características</h2>
          <div class="grid gap-3 sm:grid-cols-2">
            ${vehicle.features
              .map(
                (feature) => `
              <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>${feature}</span>
              </div>
            `,
              )
              .join("")}
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <div class="sticky top-20 space-y-6">
          <!-- Contact Card -->
          <div class="rounded-lg border border-border bg-card p-6">
            <h3 class="mb-4 text-lg font-bold">Interessado?</h3>
            <div class="space-y-3">
              <a href="contato.html?vehicle=${vehicle.id}" class="flex w-full items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Enviar Mensagem
              </a>
              <a href="tel:+351211234567" class="flex w-full items-center justify-center rounded-lg border border-border bg-background px-4 py-3 text-sm font-medium transition-colors hover:bg-muted">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                +244 944 921 970
              </a>
              <button class="flex w-full items-center justify-center rounded-lg border border-border bg-background px-4 py-3 text-sm font-medium transition-colors hover:bg-muted">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Agendar Test Drive
              </button>
            </div>
          </div>

          <!-- Trust Badges -->
          <div class="rounded-lg border border-border bg-card p-6">
            <h3 class="mb-4 text-lg font-bold">Garantias</h3>
            <div class="space-y-3">
              <div class="flex items-start gap-3">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <div>
                  <div class="font-semibold">Inspeção Completa</div>
                  <div class="text-sm text-muted-foreground">Verificação de 150 pontos</div>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                  <div class="font-semibold">Garantia Incluída</div>
                  <div class="text-sm text-muted-foreground">12 meses de cobertura</div>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div>
                  <div class="font-semibold">Documentação</div>
                  <div class="text-sm text-muted-foreground">Histórico completo</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    ${
      similarVehicles.length > 0
        ? `
      <!-- Similar Vehicles -->
      <div class="mt-16">
        <h2 class="mb-8 text-2xl font-bold">Veículos Similares</h2>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          ${similarVehicles
            .map(
              (v) => `
            <div class="group overflow-hidden rounded-lg border border-border bg-card transition-shadow hover:shadow-lg">
              <div class="relative aspect-[4/3] overflow-hidden">
                <img src="${v.images[0]}" alt="${v.brand} ${v.model}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
              </div>
              <div class="p-6">
                <h3 class="mb-1 text-lg font-bold">${v.brand} ${v.model}</h3>
                <p class="mb-2 text-sm text-muted-foreground">${v.year}</p>
                <div class="mb-4 text-xl font-bold text-primary">${formatPrice(v.price)}</div>
                <a href="veiculo-detalhes.html?id=${v.id}" class="inline-flex w-full items-center justify-center rounded-lg border border-border bg-transparent px-4 py-2 text-sm font-medium transition-colors hover:bg-muted">
                  Ver Detalhes
                </a>
              </div>
            </div>
          `,
            )
            .join("")}
        </div>
      </div>
    `
        : ""
    }
  `
}

document.addEventListener("DOMContentLoaded", renderVehicleDetails)
