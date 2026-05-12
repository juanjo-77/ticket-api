
```markdown
# 🎫 Ticket API

API REST para gestión de tickets de soporte técnico, asignación de dispositivos y control de incidentes. Desarrollada con Laravel + SQL Server + Docker.

---

## 🛠️ Tecnologías

- PHP 8.3
- Laravel 11
- SQL Server 2022
- Docker + Docker Compose
- Laravel Sanctum (autenticación)
- Sentry (monitoreo de errores)
- Discord Webhooks (alertas)

---

## ⚙️ Variables de Entorno

Copia `.env.example` a `.env` y configura:

```env
APP_NAME=TicketAPI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlsrv
DB_HOST=sqlserver
DB_PORT=1433
DB_DATABASE=ticket_db
DB_USERNAME=sa
DB_PASSWORD=TicketApi@2024

DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/TU_WEBHOOK
SENTRY_LARAVEL_DSN=https://TU_DSN@sentry.io/...
```

---

## 🐳 Instalación con Docker

```bash
# 1. Clonar el repositorio
git clone https://github.com/TU_USUARIO/ticket-api.git
cd ticket-api

# 2. Copiar variables de entorno
cp .env.example .env

# 3. Configurar .env con tus valores

# 4. Levantar contenedores
docker-compose up -d --build

# 5. Crear la base de datos
docker-compose exec sqlserver /opt/mssql-tools18/bin/sqlcmd \
    -S localhost -U sa -P "TicketApi@2024" -No \
    -Q "CREATE DATABASE ticket_db"

# 6. Dar permisos
docker-compose exec -u root app chown -R www:www /var/www

# 7. Instalar dependencias
docker-compose exec app composer install

# 8. Generar key
docker-compose exec app php artisan key:generate

# 9. Correr migraciones y seeders
docker-compose exec app php artisan migrate --seed
```

---

## 🚀 Endpoints

### Autenticación
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | /api/register | Registrar usuario | No |
| POST | /api/login | Iniciar sesión | No |
| POST | /api/logout | Cerrar sesión | Sí |

### Tickets
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | /api/tickets | Listar tickets | Sí |
| GET | /api/tickets/{id} | Ver ticket | Sí |
| POST | /api/tickets | Crear ticket | Sí |
| PUT | /api/tickets/{id} | Actualizar ticket | Sí |
| DELETE | /api/tickets/{id} | Eliminar ticket | Sí |

### Dispositivos
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | /api/devices | Listar dispositivos | Sí |
| POST | /api/devices/assign | Asignar dispositivo | Sí |

---

## 🔐 Autenticación

La API usa **Laravel Sanctum**. Para endpoints protegidos incluye el token en el header:

```
Authorization: Bearer TU_TOKEN
```

---

## ⚡ Rate Limiting

Todos los endpoints tienen límite de **10 requests por minuto por IP**.

Al exceder el límite:
- Devuelve HTTP `429`
- Envía alerta automática a Discord

---

## 📋 Ejemplos de uso

### Registro
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Juan",
    "email": "juan@test.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "juan@test.com",
    "password": "password123"
  }'
```

### Crear Ticket
```bash
curl -X POST http://localhost:8000/api/tickets \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN" \
  -d '{
    "title": "PC no enciende",
    "description": "La PC del puesto 3 no enciende",
    "type": "incident",
    "priority": "high"
  }'
```

### Asignar Dispositivo
```bash
curl -X POST http://localhost:8000/api/devices/assign \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN" \
  -d '{
    "device_id": 1,
    "user_id": 2
  }'
```

---

## 📊 Monitoreo

### Discord
Alertas automáticas en Discord para:
- ⚠️ Rate limit excedido
- 🚨 Errores 500

### Sentry
Captura y trazabilidad de todas las excepciones en [sentry.io](https://sentry.io)

---

## 🗄️ Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── TicketController.php
│   │   └── DeviceController.php
│   ├── Middleware/
│   │   └── DiscordRateLimitAlert.php
│   └── Requests/
│       ├── RegisterRequest.php
│       ├── LoginRequest.php
│       ├── StoreTicketRequest.php
│       └── AssignDeviceRequest.php
├── Models/
│   ├── User.php
│   ├── Ticket.php
│   ├── Device.php
│   └── ActivityLog.php
└── Services/
    ├── TicketService.php
    ├── DeviceService.php
    └── DiscordService.php
```

---

## 👤 Usuarios de prueba

| Email | Password | Rol |
|-------|----------|-----|
| admin@ticketapi.com | password123 | admin |
| tecnico@ticketapi.com | password123 | technician |


---

## 📸 Evidencias

### Discord — Rate Limit excedido
<img width="879" height="924" alt="imagen" src="https://github.com/user-attachments/assets/ebb8f228-e1dc-40aa-80d0-45776b99cf6c" />


### Terminal — Rate Limit 429
<img width="904" height="595" alt="imagen" src="https://github.com/user-attachments/assets/91c18f8c-52f1-4379-b271-995d1ce44db3" />


### Sentry — Errores capturados
<img width="1456" height="823" alt="imagen" src="https://github.com/user-attachments/assets/f02bf653-f984-481b-ad29-a728ba1ab4e8" />

