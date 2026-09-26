# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/)
y este proyecto se adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [Unreleased]

## [0.0.0] - 2026-09-26

### Añadido
- **Puesta en marcha del ecosistema de desarrollo**:
  - Orquestación contenerizada con Docker para backend, frontend y servidor web con recarga rápida y soporte de depuración.
  - Servidor web configurado para centralizar todo el tráfico entrante en un único punto de entrada para el backend.
- **Cimientos del framework backend propio**:
  - Sistema propio de carga dinámica de clases y módulos sin dependencias de gestores externos.
  - Motor de enrutamiento capaz de resolver verbos HTTP, rutas agrupadas por versión, rutas de recursos RESTful y parámetros dinámicos en URL.
  - Captura y procesamiento estructurado de solicitudes HTTP (parámetros de consulta, cuerpo, encabezados y método).
  - Respuestas API estandarizadas en formato JSON con códigos de estado HTTP y manejo centralizado de excepciones.
- **Estructura base del frontend propio**:
  - Configuración inicial de la aplicación cliente en TypeScript puro, priorizando el control absoluto del DOM y la tipificación estricta sin frameworks externos.
  - Maquetación y sistema de estilos inicial con diseño responsivo y estética moderna tipo Glassmorphism.
- **Preparación del motor de base de datos propio**:
  - Estructuración del directorio de datos para dar inicio a la implementación del motor de almacenamiento y consultas basado en archivos JSON.
- **Documentación del proyecto**:
  - Manifiesto y guía inicial del proyecto explicando la filosofía de desarrollo desde cero y su propósito como portafolio recopilatorio.
