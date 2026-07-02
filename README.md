# Celzimo Veste

Proyecto estático para el e-commerce de Celzimo Veste.

## Desarrollo Local

Para iniciar un servidor de desarrollo local de manera rápida y sin complicaciones de CORS:

1. Asegúrate de tener [Node.js](https://nodejs.org/) instalado.
2. Abre una terminal en la raíz del proyecto.
3. Ejecuta los siguientes comandos:

```bash
npm install
npm run dev
```

Esto levantará el proyecto típicamente en `http://localhost:3000`.

## Despliegue (Host Final)

El proyecto consta de archivos estáticos (HTML, CSS, JS). No requiere de un servidor de backend (Node.js/PHP) para funcionar.

- **Vercel / Netlify:** Solo conecta el repositorio y el sitio se publicará automáticamente.
- **cPanel / Hosting Tradicional:** Sube todos los archivos directamente a la carpeta `public_html`.
- **GitHub Pages:** Habilita GitHub Pages en las configuraciones del repositorio apuntando a la rama `main`.

## Tecnologías
- HTML5
- CSS3 (Vanilla)
- JavaScript (Vanilla)
- LocalStorage para estado (carrito, simulación de login)
