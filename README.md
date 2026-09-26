# 🍃 Folium

> **Un portafolio interactivo y recopilatorio de proyectos construido desde la raíz.**  
> *Sin frameworks de terceros. Sin atajos. Ingeniería de software en su estado más puro.*

---

## 💡 Filosofía del Proyecto

**Folium** nace no solo como una vitrina para exhibir proyectos personales, sino como un **desafío técnico integral**: demostrar y consolidar el dominio del desarrollo web construyendo cada pieza del rompecabezas desde sus cimientos (*first principles*).

En la industria actual es común apoyarse en frameworks consolidados (Laravel, Symfony, React, Vue, etc.) y bases de datos relacionales estándar. El verdadero diferencial y propósito de este repositorio es **crear nuestras propias herramientas**:

1. ⚙️ **Framework Backend Propio (PHP 8.5)**:
   - Arquitectura construida desde cero sin librerías externas.
   - Sistema de autocarga de clases compatible con estándares modernos.
   - Enrutador dinámico con soporte de verbos HTTP, rutas agrupadas, recursos RESTful y captura de parámetros.
   - Abstracción completa del ciclo de vida de las solicitudes HTTP (`Request`) y generador de respuestas API JSON estandarizadas.

2. 🎨 **Framework / Arquitectura Frontend Propia (TypeScript Vanilla)**:
   - Control total sobre el DOM, el estado y el ciclo de renderizado.
   - Uso de TypeScript estricto para máxima robustez de tipos sin atarse a frameworks pesados de frontend.
   - Interfaz moderna, responsiva y con diseño Glassmorphism implementado con CSS limpio.

3. 🗄️ **Motor de Base de Datos Propio (JSON Storage Engine)**:
   - Motor personalizado de persistencia, almacenamiento e indexación basado en archivos estructurados JSON (`server/data/`).
   - Gestión de lectura, escritura y consultas implementada directamente desde el núcleo del backend.

---

## 🛠️ Stack Tecnológico e Infraestructura

- **Backend**: PHP 8.5 FPM orquestado sobre un servidor Nginx con redirección centralizada (`try_files`).
- **Frontend**: TypeScript Vanilla empaquetado con Vite para desarrollo ágil y compilación optimizada.
- **Base de Datos**: Motor de persistencia propio sobre JSON en disco.
- **Contenedores**: Docker & Docker Compose para un entorno de ejecución aislado, homogéneo y reproducible.
- **Herramientas de Desarrollo**: Integración con Xdebug para depuración avanzada paso a paso.

---

## 🚀 Inicio Rápido

### Requisitos Previos
- [Docker](https://www.docker.com/) y [Docker Compose](https://docs.docker.com/compose/).
- [Node.js](https://nodejs.org/) *(opcional, solo si deseas compilar el frontend fuera de Docker)*.

### 1. Levantar el Entorno de Desarrollo
Ejecuta en la raíz del proyecto:

```bash
docker-compose up -d --build
```

### 2. Puntos de Acceso
- 🌐 **Frontend (Vite + TS)**: [http://localhost:5173](http://localhost:5173)
- 🔌 **Backend API (PHP 8.5 + Nginx)**: [http://localhost:8080](http://localhost:8080)

---

## 📂 Estructura del Repositorio

```text
Folium/
├── client/                 # Frontend en TypeScript Vanilla (Vite)
│   ├── src/                # Código fuente, componentes y lógica del cliente
│   ├── Dockerfile.dev      # Contenedor de desarrollo para el cliente
│   └── package.json
├── server/                 # Backend propio en PHP 8.5
│   ├── core/               # Núcleo del framework (Router, Request, API, Autoloader)
│   ├── src/Controllers/    # Controladores de la aplicación
│   ├── data/               # Almacenamiento del motor de base de datos JSON
│   └── public/             # Punto de entrada único (index.php) expuesto al servidor
├── docker/                 # Configuraciones de infraestructura (Nginx, PHP)
├── docker-compose.yml      # Orquestación de servicios
├── CHANGELOG.md            # Registro histórico de evolución del proyecto
└── README.md               # Manifiesto y documentación general
```

---

## 📌 Historial de Versiones

Consulta el archivo [CHANGELOG.md](file:///home/abdiel/projects/personal/Folium/CHANGELOG.md) para conocer las capacidades introducidas en cada versión del ecosistema.
