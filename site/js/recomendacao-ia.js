// Lógica de Recomendação IA
const questions = [
  {
    id: 1,
    question: "Qual é o seu orçamento?",
    type: "range",
    options: [
      { value: "0-20000", label: "Até 20.000€", score: { budget: 20000 } },
      { value: "20000-40000", label: "20.000€ - 40.000€", score: { budget: 40000 } },
      { value: "40000-60000", label: "40.000€ - 60.000€", score: { budget: 60000 } },
      { value: "60000+", label: "Mais de 60.000€", score: { budget: 100000 } },
    ],
  },
  {
    id: 2,
    question: "Como vai usar o veículo principalmente?",
    type: "single",
    options: [
      { value: "city", label: "Cidade", icon: "🏙️", score: { usage: "city" } },
      { value: "highway", label: "Estrada", icon: "🛣️", score: { usage: "highway" } },
      { value: "family", label: "Família", icon: "👨‍👩‍👧‍👦", score: { usage: "family" } },
      { value: "sport", label: "Desportivo", icon: "🏎️", score: { usage: "sport" } },
    ],
  },
  {
    id: 3,
    question: "Quantos passageiros normalmente?",
    type: "single",
    options: [
      { value: "1-2", label: "1-2 pessoas", score: { passengers: 2 } },
      { value: "3-4", label: "3-4 pessoas", score: { passengers: 4 } },
      { value: "5+", label: "5 ou mais", score: { passengers: 7 } },
    ],
  },
  {
    id: 4,
    question: "Preferência de combustível?",
    type: "single",
    options: [
      { value: "Gasolina", label: "Gasolina", icon: "⛽", score: { fuel: "Gasolina" } },
      { value: "Diesel", label: "Diesel", icon: "🚗", score: { fuel: "Diesel" } },
      { value: "Elétrico", label: "Elétrico", icon: "🔋", score: { fuel: "Elétrico" } },
      { value: "Híbrido", label: "Híbrido", icon: "🔌", score: { fuel: "Híbrido" } },
    ],
  },
  {
    id: 5,
    question: "Características mais importantes?",
    type: "multiple",
    options: [
      { value: "tech", label: "Tecnologia", icon: "📱", score: { features: ["tech"] } },
      { value: "safety", label: "Segurança", icon: "🛡️", score: { features: ["safety"] } },
      { value: "comfort", label: "Conforto", icon: "💺", score: { features: ["comfort"] } },
      { value: "performance", label: "Performance", icon: "⚡", score: { features: ["performance"] } },
      { value: "economy", label: "Economia", icon: "💰", score: { features: ["economy"] } },
    ],
  },
]

let currentQuestion = 0
let answers = {}
// Resolver veículos a partir da fonte de dados global sem sombrear a ligação global.
// Preferir o `vehicles` global (declarado em js/data.js). Se não estiver disponível, usar window.vehicles ou array vazio.
const VEHICLES = (typeof vehicles !== 'undefined' && Array.isArray(vehicles)) ? vehicles : (typeof window !== 'undefined' && Array.isArray(window.vehicles) ? window.vehicles : [])

function formatPrice(price) {
  return new Intl.NumberFormat("pt-PT", {
    style: "currency",
    currency: "EUR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(price)
}

function renderQuestion() {
  const container = document.getElementById("questions-container")
  const question = questions[currentQuestion]

  let optionsHTML = ""

  if (question.type === "range" || question.type === "single") {
    optionsHTML = question.options
      .map(
        (option) => `
      <button 
        class="option-btn flex items-center justify-between rounded-lg border-2 border-border bg-background p-4 text-left transition-all hover:border-primary hover:bg-primary/5 ${answers[question.id] === option.value ? "border-primary bg-primary/5" : ""}"
        data-value="${option.value}"
      >
        <span class="flex items-center gap-3">
          ${option.icon ? `<span class="text-2xl">${option.icon}</span>` : ""}
          <span class="font-medium">${option.label}</span>
        </span>
        <svg class="h-5 w-5 text-primary ${answers[question.id] === option.value ? "" : "opacity-0"}" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
      </button>
    `,
      )
      .join("")
  } else if (question.type === "multiple") {
    const selectedValues = answers[question.id] || []
    optionsHTML = question.options
      .map(
        (option) => `
      <button 
        class="option-btn-multiple flex items-center justify-between rounded-lg border-2 border-border bg-background p-4 text-left transition-all hover:border-primary hover:bg-primary/5 ${selectedValues.includes(option.value) ? "border-primary bg-primary/5" : ""}"
        data-value="${option.value}"
      >
        <span class="flex items-center gap-3">
          ${option.icon ? `<span class="text-2xl">${option.icon}</span>` : ""}
          <span class="font-medium">${option.label}</span>
        </span>
        <svg class="h-5 w-5 text-primary ${selectedValues.includes(option.value) ? "" : "opacity-0"}" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
      </button>
    `,
      )
      .join("")
  }

  container.innerHTML = `
    <div class="fade-in">
      <h2 class="mb-6 text-2xl font-bold">${question.question}</h2>
      ${question.type === "multiple" ? '<p class="mb-4 text-sm text-muted-foreground">Selecione uma ou mais opções</p>' : ""}
      <div class="space-y-3">
        ${optionsHTML}
      </div>
    </div>
  `

  // Add event listeners
  if (question.type === "multiple") {
    document.querySelectorAll(".option-btn-multiple").forEach((btn) => {
      btn.addEventListener("click", () => {
        const value = btn.dataset.value
        const currentAnswers = answers[question.id] || []

        if (currentAnswers.includes(value)) {
          answers[question.id] = currentAnswers.filter((v) => v !== value)
        } else {
          answers[question.id] = [...currentAnswers, value]
        }

        renderQuestion()
        updateNextButton()
      })
    })
  } else {
    document.querySelectorAll(".option-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        answers[question.id] = btn.dataset.value
        renderQuestion()
        updateNextButton()
      })
    })
  }

  updateProgress()
  updateNextButton()
}

function updateProgress() {
  const progress = ((currentQuestion + 1) / questions.length) * 100
  document.getElementById("progress-bar").style.width = `${progress}%`
  document.getElementById("progress-text").textContent = `Pergunta ${currentQuestion + 1} de ${questions.length}`
}

function updateNextButton() {
  const nextBtn = document.getElementById("next-btn")
  const hasAnswer = answers[questions[currentQuestion].id] !== undefined

  if (currentQuestion === questions.length - 1) {
    nextBtn.innerHTML = `
      Ver Recomendações
      <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
      </svg>
    `
  } else {
    nextBtn.innerHTML = `
      Próxima
      <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    `
  }

  nextBtn.disabled = !hasAnswer
}

function calculateRecommendations() {
  // Simple scoring algorithm
  const userPreferences = {
    budget: 100000,
    fuel: null,
    usage: null,
  }

  // Extract preferences from answers
  questions.forEach((q) => {
    const answer = answers[q.id]
    if (!answer) return

    const option = q.options.find((opt) => (q.type === "multiple" ? answer.includes(opt.value) : opt.value === answer))

    if (option && option.score) {
      Object.assign(userPreferences, option.score)
    }
  })

  // Score vehicles
  const scoredVehicles = VEHICLES.map((vehicle) => {
    let score = 0

    // Budget match
    if (vehicle.price <= userPreferences.budget) {
      score += 30
    }

    // Fuel type match
    if (userPreferences.fuel && vehicle.fuelType === userPreferences.fuel) {
      score += 25
    }

    // Condition preference
    if (vehicle.condition === "Novo" || vehicle.condition === "Semi-novo") {
      score += 15
    }

    // Random factor for variety
    score += Math.random() * 30

    return { ...vehicle, score }
  })

  // Sort by score and return top 3
  return scoredVehicles.sort((a, b) => b.score - a.score).slice(0, 3)
}

function showResults() {
  document.querySelector("section:nth-of-type(2)").classList.add("hidden")
  document.getElementById("results-section").classList.remove("hidden")

  const recommendations = calculateRecommendations()
  const container = document.getElementById("recommendations-container")

  container.innerHTML = recommendations
    .map(
      (vehicle, index) => `
    <div class="overflow-hidden rounded-lg border border-border bg-card transition-shadow hover:shadow-lg fade-in" style="animation-delay: ${index * 0.1}s">
      <div class="relative aspect-[4/3] overflow-hidden">
  <img src="${typeof getImageSrc === 'function' ? getImageSrc(vehicle.images[0]) : vehicle.images[0]}" alt="${vehicle.brand} ${vehicle.model}" class="h-full w-full object-cover">
        <span class="absolute left-4 top-4 rounded-full bg-primary px-3 py-1 text-xs font-medium text-primary-foreground">
          #${index + 1} Recomendado
        </span>
      </div>
      <div class="p-6">
        <h3 class="mb-1 text-xl font-bold">${vehicle.brand} ${vehicle.model}</h3>
        <p class="mb-2 text-sm text-muted-foreground">${vehicle.year}</p>
        <div class="mb-4 text-2xl font-bold text-primary">${formatPrice(vehicle.price)}</div>
        <div class="mb-4 space-y-2 text-sm">
          <div class="flex items-center gap-2 text-muted-foreground">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            ${vehicle.transmission} • ${vehicle.fuelType}
          </div>
          <div class="flex items-center gap-2 text-muted-foreground">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            ${vehicle.condition} • ${vehicle.location}
          </div>
        </div>
        <p class="mb-4 text-sm leading-relaxed text-muted-foreground">${vehicle.description.substring(0, 100)}...</p>
        <a href="veiculo-detalhes.html?id=${vehicle.id}" class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
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

  // Scroll to results
  document.getElementById("results-section").scrollIntoView({ behavior: "smooth" })
}

function restart() {
  currentQuestion = 0
  answers = {}
  document.querySelector("section:nth-of-type(2)").classList.remove("hidden")
  document.getElementById("results-section").classList.add("hidden")
  renderQuestion()
  document.getElementById("prev-btn").disabled = true
  window.scrollTo({ top: 0, behavior: "smooth" })
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  renderQuestion()

  document.getElementById("next-btn").addEventListener("click", () => {
    if (currentQuestion < questions.length - 1) {
      currentQuestion++
      renderQuestion()
      document.getElementById("prev-btn").disabled = false
    } else {
      showResults()
    }
  })

  document.getElementById("prev-btn").addEventListener("click", () => {
    if (currentQuestion > 0) {
      currentQuestion--
      renderQuestion()
      if (currentQuestion === 0) {
        document.getElementById("prev-btn").disabled = true
      }
    }
  })

  document.getElementById("restart-btn").addEventListener("click", restart)
})
