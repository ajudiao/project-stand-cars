# AutoAI Motors — Visão Geral

- Versão html, css, js puro

Site estático que mostra uma vitrine de veículos, recomendações simples por questionário e uma secção de notícias.

- Frontend: HTML, CSS e JavaScript (sem build).
- Dados: `js/data.js` (veículos e notícias).

Como rodar localmente:

```bash
cd /home/andredev/Transferências/code(3)
python3 -m http.server 8000
# Abrir http://localhost:8000/
```
Páginas principais:
- `veiculos.html` — lista e filtros
- `veiculo-detalhes.html?id=...` — detalhe do veículo
- `recomendacao-ia.html` — questionário de recomendações
- `noticias.html` — artigos

Notas rápidas:
- Use `js/data.js` como fonte única; não redeclare `vehicles`/`newsArticles`.
- `js/components.js` tem `getImageSrc()` para resolver caminhos de imagens.