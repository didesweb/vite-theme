# Vite Starter Theme (WordPress + Sass)

Este tema usa Vite como bundler y Sass como preprocesador CSS.

## 🚀 Requisitos

- WordPress 6.x
- Node.js 18 o superior (recomendado: Node 20 LTS)
- npm 9 o superior

## 📦 Instalación

1. Copia este tema dentro de `/wp-content/themes/my-vite-theme`
2. Instala dependencias:

   npm install

3. Modo desarrollo (con HMR):

   npm run dev

4. Build para producción:

   npm run build

## 🧱 Estructura

- `src/js/main.js` → Entrada JS
- `src/scss/main.scss` → Entrada Sass
- `dist/` → Archivos generados por Vite

## 📝 Notas

- En modo desarrollo, los assets se cargan desde `http://localhost:5173`
- En producción, se usa `dist/manifest.json`
