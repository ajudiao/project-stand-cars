// Navigation Component
function createNavigation() {
  const currentPage = window.location.pathname.split("/").pop() || "index.html"

  const nav = `
    <nav class="sticky top-0 z-50 border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container mx-auto">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center gap-8">
            <a href="index.html" class="flex items-center gap-2">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                <svg class="h-6 w-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <span class="text-xl font-bold">AutoAI Motors</span>
            </a>
            <div class="hidden md:flex md:gap-6">
              <a href="index.html" class="text-sm font-medium transition-colors hover:text-primary ${currentPage === "index.html" || currentPage === "" ? "text-primary" : "text-muted-foreground"}">
                Home
              </a>
              <a href="veiculos.html" class="text-sm font-medium transition-colors hover:text-primary ${currentPage === "veiculos.html" ? "text-primary" : "text-muted-foreground"}">
                Veículos
              </a>
              <a href="recomendacao-ia.html" class="flex items-center gap-1 text-sm font-medium transition-colors hover:text-primary ${currentPage === "recomendacao-ia.html" ? "text-primary" : "text-muted-foreground"}">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Recomendação IA
              </a>
              <a href="noticias.html" class="text-sm font-medium transition-colors hover:text-primary ${currentPage === "noticias.html" ? "text-primary" : "text-muted-foreground"}">
                Notícias
              </a>
              <a href="sobre.html" class="text-sm font-medium transition-colors hover:text-primary ${currentPage === "sobre.html" ? "text-primary" : "text-muted-foreground"}">
                Sobre Nós
              </a>
              <a href="contato.html" class="text-sm font-medium transition-colors hover:text-primary ${currentPage === "contato.html" ? "text-primary" : "text-muted-foreground"}">
                Contato
              </a>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <a href="contato.html" class="hidden sm:inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
              Falar Conosco
            </a>
            <button id="mobile-menu-button" class="md:hidden inline-flex items-center justify-center rounded-lg p-2 text-muted-foreground hover:bg-muted hover:text-foreground">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
      <!-- Mobile Menu -->
      <div id="mobile-menu" class="hidden border-t border-border md:hidden">
        <div class="container mx-auto space-y-1 px-4 py-4">
          <a href="index.html" class="block rounded-lg px-3 py-2 text-base font-medium transition-colors hover:bg-muted ${currentPage === "index.html" || currentPage === "" ? "bg-muted text-primary" : "text-muted-foreground"}">
            Home
          </a>
          <a href="veiculos.html" class="block rounded-lg px-3 py-2 text-base font-medium transition-colors hover:bg-muted ${currentPage === "veiculos.html" ? "bg-muted text-primary" : "text-muted-foreground"}">
            Veículos
          </a>
          <a href="recomendacao-ia.html" class="block rounded-lg px-3 py-2 text-base font-medium transition-colors hover:bg-muted ${currentPage === "recomendacao-ia.html" ? "bg-muted text-primary" : "text-muted-foreground"}">
            Recomendação IA
          </a>
          <a href="noticias.html" class="block rounded-lg px-3 py-2 text-base font-medium transition-colors hover:bg-muted ${currentPage === "noticias.html" ? "bg-muted text-primary" : "text-muted-foreground"}">
            Notícias
          </a>
          <a href="sobre.html" class="block rounded-lg px-3 py-2 text-base font-medium transition-colors hover:bg-muted ${currentPage === "sobre.html" ? "bg-muted text-primary" : "text-muted-foreground"}">
            Sobre Nós
          </a>
          <a href="contato.html" class="block rounded-lg px-3 py-2 text-base font-medium transition-colors hover:bg-muted ${currentPage === "contato.html" ? "bg-muted text-primary" : "text-muted-foreground"}">
            Contato
          </a>
        </div>
      </div>
    </nav>
  `

  document.getElementById("navigation").innerHTML = nav

  // Mobile menu toggle
  const mobileMenuButton = document.getElementById("mobile-menu-button")
  const mobileMenu = document.getElementById("mobile-menu")

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden")
    })
  }
}

// Footer Component
function createFooter() {
  const footer = `
    <footer class="border-t border-border bg-muted/30">
      <div class="container mx-auto px-4 py-12">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <div class="mb-4 flex items-center gap-2">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                <svg class="h-6 w-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <span class="text-xl font-bold">AutoAI Motors</span>
            </div>
            <p class="text-sm text-muted-foreground">
              Encontre o veículo perfeito com a ajuda da inteligência artificial. Tecnologia e experiência ao seu serviço.
            </p>
          </div>
          <div>
            <h3 class="mb-4 text-sm font-semibold">Navegação</h3>
            <ul class="space-y-2 text-sm">
              <li><a href="index.html" class="text-muted-foreground transition-colors hover:text-primary">Home</a></li>
              <li><a href="veiculos.html" class="text-muted-foreground transition-colors hover:text-primary">Veículos</a></li>
              <li><a href="recomendacao-ia.html" class="text-muted-foreground transition-colors hover:text-primary">Recomendação IA</a></li>
              <li><a href="noticias.html" class="text-muted-foreground transition-colors hover:text-primary">Notícias</a></li>
            </ul>
          </div>
          <div>
            <h3 class="mb-4 text-sm font-semibold">Empresa</h3>
            <ul class="space-y-2 text-sm">
              <li><a href="sobre.html" class="text-muted-foreground transition-colors hover:text-primary">Sobre Nós</a></li>
              <li><a href="contato.html" class="text-muted-foreground transition-colors hover:text-primary">Contato</a></li>
              <li><a href="#" class="text-muted-foreground transition-colors hover:text-primary">Política de Privacidade</a></li>
              <li><a href="#" class="text-muted-foreground transition-colors hover:text-primary">Termos de Serviço</a></li>
            </ul>
          </div>
          <div>
            <h3 class="mb-4 text-sm font-semibold">Contato</h3>
            <ul class="space-y-2 text-sm text-muted-foreground">
              <li class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                +244 944 921 970
              </li>
              <li class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                info@autoaimotors.pt
              </li>
              <li class="flex items-start gap-2">
                <svg class="h-4 w-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Av. da Liberdade, 123<br>1250-096 Lisboa
              </li>
            </ul>
          </div>
        </div>
        <div class="mt-8 border-t border-border pt-8 text-center text-sm text-muted-foreground">
          <p>&copy; 2025 AutoAI Motors. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  `

  document.getElementById("footer").innerHTML = footer
}

// Initialize components when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  createNavigation()
  createFooter()
})

// Helper to resolve image paths (prefix with 'public/' when images are stored in the public folder)
function getImageSrc(path) {  
  if (!path) return ""
  // If already absolute or contains a folder, return as-is
  if (path.startsWith("http") || path.startsWith("/") || path.includes("public/")) return path
  return `public/${path}`
}

// Expose globally so other scripts can use it
window.getImageSrc = getImageSrc
