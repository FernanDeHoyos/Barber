# Barber SaaS

## Tecnologías
- Laravel 12
- Filament 4
- MariaDB
- TailwindCSS

## Arquitectura
- Multi-tenant por subdominios
- Un tenant = una barbería
- Middleware TenantMiddleware

## Módulos
- Clientes
- Barberos
- Servicios
- Citas
- Pagos
- Dashboard

## Convenciones
- Usar Actions de Filament cuando sea posible
- No usar lógica de negocio en Controllers
- Servicios en App/Services
- Validaciones mediante Form Requests

## Objetivo
Convertirse en un SaaS para barberías de Latinoamérica.