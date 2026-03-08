// Lógica de Artigos de Notícias
let currentCategory = "Todos"

// Usar newsArticles de js/data.js (fonte única). Criar alias local para evitar redeclaração.
const NEWS_ARTICLES = (typeof newsArticles !== 'undefined' && Array.isArray(newsArticles)) ? newsArticles : (typeof window !== 'undefined' && Array.isArray(window.newsArticles) ? window.newsArticles : [])

function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("pt-PT", { year: "numeric", month: "long", day: "numeric" })
}

function renderFeaturedArticle() {
  const featured = NEWS_ARTICLES[0]
  const container = document.getElementById("featured-article")

  container.innerHTML = `
    <div class="overflow-hidden rounded-2xl border border-border bg-card transition-shadow hover:shadow-lg">
      <div class="grid gap-8 lg:grid-cols-2">
        <div class="aspect-[16/10] overflow-hidden lg:aspect-auto">
          <img src="${typeof getImageSrc === 'function' ? getImageSrc(featured.image) : featured.image}" alt="${featured.title}" class="h-full w-full object-cover">
        </div>
        <div class="flex flex-col justify-center p-8">
          <span class="mb-3 inline-block w-fit rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
            ${featured.category}
          </span>
          <h2 class="mb-4 text-3xl font-bold lg:text-4xl">${featured.title}</h2>
          <p class="mb-4 text-lg leading-relaxed text-muted-foreground">${featured.excerpt}</p>
          <div class="mb-6 flex items-center gap-4 text-sm text-muted-foreground">
            <span>${featured.author}</span>
            <span>•</span>
            <span>${formatDate(featured.date)}</span>
            <span>•</span>
            <span>${featured.readTime} de leitura</span>
          </div>
          <a href="noticia-detalhes.html?id=${featured.id}" class="inline-flex w-fit items-center justify-center rounded-lg bg-primary px-6 py-3 text-base font-medium text-primary-foreground transition-colors hover:bg-primary/90">
            Ler Artigo Completo
            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  `
}

function renderArticles() {
  const filtered = currentCategory === "Todos" ? NEWS_ARTICLES.slice(1) : NEWS_ARTICLES.filter((article) => article.category === currentCategory)

  const container = document.getElementById("articles-grid")

  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="col-span-full rounded-lg border border-border bg-card p-12 text-center">
        <p class="text-muted-foreground">Nenhum artigo encontrado nesta categoria.</p>
      </div>
    `
    return
  }

  container.innerHTML = filtered
    .map(
      (article) => `
    <article class="group overflow-hidden rounded-lg border border-border bg-card transition-shadow hover:shadow-lg fade-in">
      <div class="aspect-[16/10] overflow-hidden">
        <img src="${typeof getImageSrc === 'function' ? getImageSrc(article.image) : article.image}" alt="${article.title}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
      </div>
      <div class="p-6">
        <span class="mb-3 inline-block rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
          ${article.category}
        </span>
        <h3 class="mb-2 text-xl font-bold">${article.title}</h3>
        <p class="mb-4 leading-relaxed text-muted-foreground">${article.excerpt}</p>
        <div class="mb-4 flex items-center gap-3 text-sm text-muted-foreground">
          <span>${article.author}</span>
          <span>•</span>
          <span>${article.readTime}</span>
        </div>
        <a href="noticia-detalhes.html?id=${article.id}" class="inline-flex items-center text-sm font-medium text-primary transition-colors hover:underline">
          Ler mais
          <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </article>
  `,
    )
    .join("")
}

function initializeCategories() {
  const categoryButtons = document.querySelectorAll(".category-btn")

  categoryButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      // Update active state
      categoryButtons.forEach((b) => {
        b.classList.remove("active", "bg-primary", "text-primary-foreground")
        b.classList.add("bg-background")
      })
      btn.classList.add("active", "bg-primary", "text-primary-foreground")
      btn.classList.remove("bg-background")

      // Update current category
      currentCategory = btn.dataset.category

      // Re-render articles
      renderArticles()
    })
  })
}

document.addEventListener("DOMContentLoaded", () => {
  renderFeaturedArticle()
  renderArticles()
  initializeCategories()
})
