# Barber SaaS - Product Requirements

## Descripción General

Sistema SaaS multi-tenant para barberías.

Cada tenant representa una barbería independiente con:

* Clientes
* Barberos
* Servicios
* Agenda
* Reportes

Stack:

* Laravel 12
* Filament 4
* MariaDB
* Multi-tenancy por subdominios

---

# Roles

## Super Admin

### SA-01

Como super admin, quiero crear y eliminar tenants para gestionar quién usa la plataforma.

### SA-02

Como super admin, quiero activar o desactivar tenants para suspender el acceso sin eliminarlos.

### SA-03

Como super admin, quiero ver métricas globales del negocio.

### SA-04

Como super admin, quiero gestionar planes de suscripción.

### SA-05

Como super admin, quiero consultar historial de facturación.

### SA-06

Como super admin, quiero acceder a tenants en modo lectura para soporte.

### SA-07

Como super admin, quiero recibir alertas de límites de plan.

---

## Admin de Barbería

### A-01

Configurar perfil de barbería.

### A-02

Gestionar barberos.

### A-03

Gestionar servicios.

### A-04

Asignar servicios a barberos.

### A-05

Configurar disponibilidad.

### A-06

Ver calendario.

### A-07

Gestionar citas.

### A-08

Consultar historial de clientes.

### A-09

Recibir notificaciones.

### A-10

Ver reportes.

### A-11

Bloquear horarios y fechas.

---

## Cliente

### C-01

Consultar barbería.

### C-02

Reservar cita.

### C-03

Reservar sin registro.

### C-04

Recibir confirmación.

### C-05

Cancelar cita.

### C-06

Recibir recordatorios.

### C-07

Ver disponibilidad en tiempo real.

---

# Consideraciones Técnicas

## Multi-tenancy

### MT-01

Sistema debe separar datos por tenant.

### MT-02

No permitir acceso entre tenants.

### MT-03

Subdominios para cada tenant.

### MT-04

Configurar .env para multi-tenancy.

---

## Calendario

### CAL-01

Bloquear fechas y horarios.

### CAL-02

Mostrar disponibilidad en tiempo real.

### CAL-03

Integrar con citas.

### CAL-04

Notificaciones de bloqueos.

---

## Citas

### APP-01

Reserva en tiempo real.

### APP-02

Confirmación automática.

### APP-03

Cancelación con políticas.

### APP-04

Recordatorios.

### APP-05

No permitir sobrecupos.

### APP-06

Validar servicios asignados a barberos.

### APP-07

Bloquear horarios ocupados.

---

## Pagos

### PAY-01

Manejar pagos de suscripción.

### PAY-02

Integrar con Stripe.

### PAY-03

Facturación automática.

---

## Notificaciones

### NOT-01

Confirmación de citas.

### NOT-02

Recordatorios.

### NOT-03

Cancelaciones.

### NOT-04

Alertas de nuevos clientes.

---

## Escalabilidad

### SCA-01

Arquitectura preparada para crecimiento.

### SCA-02

Optimización de consultas.

---

## Seguridad

### SEC-01

Aislamiento por tenant.

### SEC-02

Roles y permisos.

### SEC-03

Auditoría de acciones.

---

## Filament 4

### FIL-01

Crear Resources para cada módulo.

### FIL-02

Usar Actions para operaciones complejas.

### FIL-03

Widgets para dashboards.

### FIL-04

Validaciones en Form Requests.
