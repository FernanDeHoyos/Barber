<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Bienvenido a BarberSaaS" />

    <div class="welcome-container">
        <main class="welcome-card">
            <h1 class="title">BarberSaaS</h1>
            <p class="subtitle">La plataforma premium para la gestión de turnos de tu barbería.</p>
            <div class="status-badge">
                <span class="status-dot"></span>
                Inertia.js + Vue 3 configurado correctamente
            </div>
        </main>
    </div>
</template>

<style scoped>
.welcome-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-color, #0f0f11);
    font-family: var(--font-primary, sans-serif);
    padding: 20px;
}

.welcome-card {
    background-color: var(--accent-color, #1a1a1f);
    border: 1px solid rgba(212, 175, 55, 0.15); /* Subtle gold border */
    border-radius: 16px;
    padding: 40px;
    max-width: 500px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.welcome-card:hover {
    transform: translateY(-5px);
    border-color: var(--secondary-color, #d4af37);
}

.title {
    font-family: var(--font-heading, serif);
    color: var(--secondary-color, #d4af37);
    font-size: 2.5rem;
    margin-bottom: 12px;
    font-weight: 700;
}

.subtitle {
    color: var(--text-light, #f3f4f6);
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 24px;
    opacity: 0.9;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: rgba(59, 130, 246, 0.1);
    color: #60a5fa;
    padding: 8px 16px;
    border-radius: 9999px;
    font-size: 0.9rem;
    font-weight: 500;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-dot {
    width: 8px;
    height: 8px;
    background-color: #3b82f6;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    }
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
    }
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}
</style>
