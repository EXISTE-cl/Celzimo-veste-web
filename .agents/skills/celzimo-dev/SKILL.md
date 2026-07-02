---
name: celzimo-dev
description: Inicia el servidor de desarrollo local para el proyecto Celzimo Veste.
---
# Celzimo Dev

**Propósito:** Proveer una forma estándar, rápida y de bajo consumo de levantar el entorno local del proyecto Celzimo Veste.
**Cuándo usarla:** Siempre que el usuario solicite "probar el proyecto", "levantar localhost" o "ver los cambios locales".
**Entradas esperadas:** Ninguna o comandos de puerto si se necesita especificar.
**Salida esperada:** URL del servidor de desarrollo (ej. `http://localhost:3000`).
**Restricciones:** 
- Solo para uso en desarrollo local.
- No despliega a producción.
**Reglas de Créditos:**
- Ejecutar `npm run dev` (que utiliza `serve`).
- No realizar instalaciones de npm (`npm install`) repetitivamente a menos que el `package.json` haya cambiado drásticamente.
- No hacer un análisis de dependencias complejo, ya que es un sitio estático.

## Instrucciones:
1. Navega al directorio del proyecto `C:\Users\Cristobal\.gemini\antigravity\scratch\celzimo-veste`.
2. Verifica si la carpeta `node_modules` existe; de no ser así, ejecuta `npm install` una única vez.
3. Ejecuta `npm run dev`.
4. Informa al usuario la dirección local.
