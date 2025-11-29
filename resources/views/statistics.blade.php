@extends('layouts.base')

@section('content')
<div class="admin-container">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
    </div>

    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-icon">
                <div class="icon-glow"></div>
                <i class="fas fa-chart-network"></i>
            </div>
            <div class="header-text">
                <h1 class="gradient-text">Аналитика системы</h1>
                <p>Глубокий анализ данных и визуализация метрик</p>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-card">
                <div class="stat-glow"></div>
                <div class="stat-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number" data-count="{{ $documentStats['total'] ?? 0 }}">0</span>
                    <span class="stat-label">Всего документов</span>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>12%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-glow"></div>
                <div class="stat-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number" data-count="{{ $userStats['total'] ?? 0 }}">0</span>
                    <span class="stat-label">Новых пользователей</span>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>8%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-glow"></div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number" data-count="{{ $counterpartyStats['total'] ?? 0 }}">0</span>
                    <span class="stat-label">Контрагентов</span>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>15%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-glow"></div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number" data-count="{{ round($documentStats['average'] ?? 0, 1) }}">0</span>
                    <span class="stat-label">Среднее в день</span>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="period-selector-section">
        <div class="period-card">
            <div class="period-glow"></div>
            <h4><i class="fas fa-filter"></i> Период анализа</h4>
            <div class="period-buttons">
                <a href="{{ route('admin.statistics', ['period' => 'day']) }}" 
                   class="period-btn {{ $period == 'day' ? 'active' : '' }}">
                    <div class="period-btn-glow"></div>
                    <i class="fas fa-sun"></i>
                    <span>День</span>
                    <div class="period-pulse"></div>
                </a>
                <a href="{{ route('admin.statistics', ['period' => 'week']) }}" 
                   class="period-btn {{ $period == 'week' ? 'active' : '' }}">
                    <div class="period-btn-glow"></div>
                    <i class="fas fa-calendar-week"></i>
                    <span>Неделя</span>
                    <div class="period-pulse"></div>
                </a>
                <a href="{{ route('admin.statistics', ['period' => 'month']) }}" 
                   class="period-btn {{ $period == 'month' ? 'active' : '' }}">
                    <div class="period-btn-glow"></div>
                    <i class="fas fa-calendar-alt"></i>
                    <span>Месяц</span>
                    <div class="period-pulse"></div>
                </a>
                <a href="{{ route('admin.statistics', ['period' => 'year']) }}" 
                   class="period-btn {{ $period == 'year' ? 'active' : '' }}">
                    <div class="period-btn-glow"></div>
                    <i class="fas fa-calendar"></i>
                    <span>Год</span>
                    <div class="period-pulse"></div>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <div class="actions-grid">
            <a href="{{ route('admin') }}" class="action-card back-card">
                <div class="card-glow"></div>
                <div class="card-sparkles"></div>
                <div class="action-icon">
                    <div class="icon-orb"></div>
                    <i class="fas fa-arrow-left"></i>
                </div>
                <div class="action-content">
                    <h4>Вернуться в админ-панель</h4>
                    <p>Перейти к управлению пользователями</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
                <div class="card-hover-effect"></div>
            </a>
            
            <a href="{{ route('admin.counterparties.index') }}" class="action-card counterparty-card">
                <div class="card-glow"></div>
                <div class="card-sparkles"></div>
                <div class="action-icon">
                    <div class="icon-orb"></div>
                    <i class="fas fa-building"></i>
                </div>
                <div class="action-content">
                    <h4>Управление контрагентами</h4>
                    <p>Перейти к списку контрагентов</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
                <div class="card-hover-effect"></div>
            </a>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Documents Chart -->
        <div class="chart-card">
            <div class="chart-glow"></div>
            <div class="chart-header">
                <div class="chart-title">
                    <div class="title-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="title-content">
                        <h3>Динамика документов</h3>
                        <p>Количество созданных документов за период</p>
                    </div>
                </div>
                <div class="chart-stats">
                    <div class="chart-stat">
                        <span class="stat-value">{{ $documentStats['total'] ?? 0 }}</span>
                        <span class="stat-label">Всего</span>
                    </div>
                    <div class="chart-stat">
                        <span class="stat-value">{{ round($documentStats['average'] ?? 0, 1) }}</span>
                        <span class="stat-label">В среднем</span>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="documentsChart" height="300"></canvas>
            </div>
            <div class="chart-footer">
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(135deg, #6366f1, #8b5cf6)"></div>
                        <span>Документы</span>
                    </div>
                </div>
                <div class="chart-actions">
                    <button class="chart-btn">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="chart-btn">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Chart -->
        <div class="chart-card">
            <div class="chart-glow"></div>
            <div class="chart-header">
                <div class="chart-title">
                    <div class="title-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="title-content">
                        <h3>Регистрация пользователей</h3>
                        <p>Новые пользователи и активность</p>
                    </div>
                </div>
                <div class="chart-stats">
                    <div class="chart-stat">
                        <span class="stat-value">{{ $userStats['total'] ?? 0 }}</span>
                        <span class="stat-label">Всего</span>
                    </div>
                    <div class="chart-stat">
                        <span class="stat-value">{{ $userStats['active_users'] ?? 0 }}</span>
                        <span class="stat-label">Активных</span>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="usersChart" height="300"></canvas>
            </div>
            <div class="chart-footer">
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(135deg, #10b981, #059669)"></div>
                        <span>Пользователи</span>
                    </div>
                </div>
                <div class="chart-actions">
                    <button class="chart-btn">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="chart-btn">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Counterparties Stats -->
        <div class="stats-grid">
            <div class="stat-card-large">
                <div class="stat-large-glow"></div>
                <div class="stat-large-icon">
                    <div class="icon-orb-large"></div>
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-large-content">
                    <h4>Контрагенты</h4>
                    <div class="stat-large-number" data-count="{{ $counterpartyStats['total'] ?? 0 }}">0</div>
                    <div class="stat-large-label">Всего в системе</div>
                    <div class="stat-large-trend">
                        <div class="trend-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <span>+{{ $counterpartyStats['recent'] ?? 0 }} за месяц</span>
                    </div>
                </div>
                <div class="stat-large-wave"></div>
            </div>

            <div class="stat-card-large">
                <div class="stat-large-glow"></div>
                <div class="stat-large-icon">
                    <div class="icon-orb-large"></div>
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-large-content">
                    <h4>Распределение</h4>
                    <div class="stat-large-number">{{ count($counterpartyStats['by_type'] ?? []) }}</div>
                    <div class="stat-large-label">Категорий</div>
                    <div class="stat-large-types">
                        @foreach(($counterpartyStats['by_type'] ?? []) as $type => $count)
                        <div class="type-item">
                            <div class="type-dot" style="background: linear-gradient(135deg, var(--primary), var(--purple))"></div>
                            <span class="type-name">{{ $type ?: 'Без типа' }}</span>
                            <span class="type-count">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="stat-large-wave"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* Cosmic Variables */
:root {
    --cosmic-primary: #6366f1;
    --cosmic-primary-dark: #4f46e5;
    --cosmic-primary-light: #818cf8;
    --cosmic-secondary: #8b5cf6;
    --cosmic-success: #10b981;
    --cosmic-warning: #f59e0b;
    --cosmic-error: #ef4444;
    --cosmic-info: #3b82f6;
    --cosmic-teal: #14b8a6;
    --cosmic-purple: #a855f7;
    --cosmic-pink: #ec4899;
    
    --cosmic-bg: #0a0f1c;
    --cosmic-card: #111827;
    --cosmic-card-hover: #1f2937;
    --cosmic-border: #374151;
    --cosmic-text: #f9fafb;
    --cosmic-text-secondary: #d1d5db;
    
    --glow-primary: rgba(99, 102, 241, 0.4);
    --glow-success: rgba(16, 185, 129, 0.4);
    --glow-warning: rgba(245, 158, 11, 0.4);
    --glow-purple: rgba(139, 92, 246, 0.4);
    
    --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
    --shadow-glow-hover: 0 0 30px rgba(99, 102, 241, 0.5);
}

/* Animated Background */
.animated-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.bg-particle {
    position: absolute;
    background: linear-gradient(135deg, var(--cosmic-primary), transparent);
    border-radius: 50%;
    animation: float 20s infinite linear;
}

.bg-particle:nth-child(1) {
    width: 300px;
    height: 300px;
    top: 10%;
    left: 5%;
    animation-delay: 0s;
    opacity: 0.1;
}

.bg-particle:nth-child(2) {
    width: 200px;
    height: 200px;
    top: 60%;
    right: 10%;
    animation-delay: -5s;
    opacity: 0.08;
}

.bg-particle:nth-child(3) {
    width: 150px;
    height: 150px;
    bottom: 20%;
    left: 15%;
    animation-delay: -10s;
    opacity: 0.06;
}

.bg-particle:nth-child(4) {
    width: 250px;
    height: 250px;
    top: 30%;
    right: 20%;
    animation-delay: -15s;
    opacity: 0.07;
}

.bg-particle:nth-child(5) {
    width: 180px;
    height: 180px;
    bottom: 40%;
    left: 60%;
    animation-delay: -7s;
    opacity: 0.09;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    25% {
        transform: translateY(-20px) rotate(90deg);
    }
    50% {
        transform: translateY(0px) rotate(180deg);
    }
    75% {
        transform: translateY(20px) rotate(270deg);
    }
}

/* Enhanced Header */
.admin-header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.7));
    padding: 40px;
    border-radius: 24px;
    border: 1px solid var(--cosmic-border);
    box-shadow: var(--shadow-glow);
    backdrop-filter: blur(20px);
    overflow: hidden;
}

.admin-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, 
        var(--cosmic-primary), 
        var(--cosmic-secondary), 
        var(--cosmic-success), 
        var(--cosmic-primary));
    background-size: 200% 100%;
    animation: shimmer 3s infinite linear;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.header-content {
    display: flex;
    align-items: center;
    gap: 24px;
}

.header-icon {
    position: relative;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-secondary));
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
    box-shadow: 0 8px 32px var(--glow-primary);
    transition: all 0.3s ease;
}

.icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, var(--glow-primary) 0%, transparent 70%);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
}

.header-icon:hover {
    transform: rotate(5deg) scale(1.05);
    box-shadow: 0 12px 40px var(--glow-primary);
}

.header-text h1 {
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--cosmic-text), var(--cosmic-primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-text p {
    font-size: 18px;
    color: var(--cosmic-text-secondary);
    font-weight: 400;
}

/* Enhanced Stats Cards */
.header-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.stat-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(30, 41, 59, 0.6));
    border: 1px solid var(--cosmic-border);
    border-radius: 16px;
    min-width: 180px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.stat-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, var(--glow-primary) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--cosmic-primary);
    box-shadow: var(--shadow-glow-hover);
}

.stat-card:hover .stat-glow {
    opacity: 1;
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-primary-dark));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-number {
    font-size: 24px;
    font-weight: 800;
    color: var(--cosmic-text);
    transition: all 0.3s ease;
}

.stat-label {
    font-size: 12px;
    color: var(--cosmic-text-secondary);
    font-weight: 500;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.stat-trend.up {
    background: rgba(16, 185, 129, 0.2);
    color: var(--cosmic-success);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

/* Period Selector */
.period-selector-section {
    margin-bottom: 40px;
}

.period-card {
    position: relative;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.7));
    border: 1px solid var(--cosmic-border);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-glow);
    backdrop-filter: blur(20px);
    overflow: hidden;
}

.period-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: linear-gradient(135deg, transparent, var(--glow-primary), transparent);
    opacity: 0.1;
}

.period-card h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--cosmic-text);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.period-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.period-btn {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(30, 41, 59, 0.6));
    border: 1px solid var(--cosmic-border);
    border-radius: 16px;
    color: var(--cosmic-text-secondary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    overflow: hidden;
}

.period-btn-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, var(--glow-primary) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.period-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 0;
    height: 0;
    background: var(--glow-primary);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.period-btn:hover {
    transform: translateY(-5px);
    border-color: var(--cosmic-primary);
    color: var(--cosmic-text);
    box-shadow: var(--shadow-glow);
}

.period-btn:hover .period-btn-glow {
    opacity: 1;
}

.period-btn:hover .period-pulse {
    width: 100px;
    height: 100px;
}

.period-btn.active {
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-secondary));
    border-color: var(--cosmic-primary);
    color: white;
    box-shadow: var(--shadow-glow);
}

.period-btn i {
    font-size: 24px;
    transition: all 0.3s ease;
}

.period-btn:hover i {
    transform: scale(1.2);
}

/* Enhanced Action Cards */
.quick-actions-section {
    margin-bottom: 40px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.action-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 32px;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.7));
    border: 1px solid var(--cosmic-border);
    border-radius: 24px;
    text-decoration: none;
    color: var(--cosmic-text);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-glow);
    backdrop-filter: blur(20px);
    overflow: hidden;
}

.card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, var(--glow-primary) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.card-sparkles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 2px, transparent 2px),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 2px, transparent 2px);
    background-size: 50px 50px;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.card-hover-effect {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.6s ease;
}

.action-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-glow-hover);
    border-color: var(--cosmic-primary);
}

.action-card:hover .card-glow {
    opacity: 1;
}

.action-card:hover .card-sparkles {
    opacity: 1;
}

.action-card:hover .card-hover-effect {
    left: 100%;
}

.action-icon {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.icon-orb {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: conic-gradient(from 0deg, var(--cosmic-primary), var(--cosmic-secondary), var(--cosmic-primary));
    border-radius: 20px;
    animation: rotate 3s linear infinite;
    opacity: 0.8;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.action-card:hover .action-icon {
    transform: scale(1.1) rotate(5deg);
}

.back-card .action-icon {
    background: linear-gradient(135deg, var(--cosmic-secondary), var(--cosmic-purple));
}

.counterparty-card .action-icon {
    background: linear-gradient(135deg, var(--cosmic-success), var(--cosmic-teal));
}

.action-content {
    flex: 1;
}

.action-content h4 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--cosmic-text), var(--cosmic-primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.action-content p {
    font-size: 16px;
    color: var(--cosmic-text-secondary);
    line-height: 1.5;
}

.action-arrow {
    color: var(--cosmic-text-secondary);
    font-size: 20px;
    transition: all 0.3s ease;
}

.action-card:hover .action-arrow {
    transform: translateX(8px);
    color: var(--cosmic-primary);
}

/* Enhanced Charts */
.charts-section {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.chart-card {
    position: relative;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.7));
    border: 1px solid var(--cosmic-border);
    border-radius: 24px;
    padding: 32px;
    box-shadow: var(--shadow-glow);
    backdrop-filter: blur(20px);
    overflow: hidden;
}

.chart-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, var(--glow-primary) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.chart-card:hover .chart-glow {
    opacity: 0.3;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.chart-title {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.title-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-secondary));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.title-content h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--cosmic-text);
    margin-bottom: 4px;
}

.title-content p {
    font-size: 14px;
    color: var(--cosmic-text-secondary);
}

.chart-stats {
    display: flex;
    gap: 24px;
}

.chart-stat {
    text-align: right;
}

.stat-value {
    display: block;
    font-size: 28px;
    font-weight: 800;
    color: var(--cosmic-text);
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    display: block;
    font-size: 12px;
    color: var(--cosmic-text-secondary);
    margin-top: 4px;
}

.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 20px;
}

.chart-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-legend {
    display: flex;
    gap: 16px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.chart-actions {
    display: flex;
    gap: 8px;
}

.chart-btn {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid var(--cosmic-border);
    border-radius: 8px;
    color: var(--cosmic-text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-btn:hover {
    background: var(--cosmic-primary);
    border-color: var(--cosmic-primary);
    color: white;
    transform: scale(1.1);
}

/* Enhanced Large Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.stat-card-large {
    position: relative;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.7));
    border: 1px solid var(--cosmic-border);
    border-radius: 24px;
    padding: 32px;
    display: flex;
    align-items: center;
    gap: 24px;
    box-shadow: var(--shadow-glow);
    backdrop-filter: blur(20px);
    overflow: hidden;
}

.stat-large-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, var(--glow-primary) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card-large:hover .stat-large-glow {
    opacity: 0.3;
}

.stat-large-icon {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
    flex-shrink: 0;
}

.icon-orb-large {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: conic-gradient(from 0deg, var(--cosmic-primary), var(--cosmic-secondary), var(--cosmic-success), var(--cosmic-primary));
    border-radius: 20px;
    animation: rotate 4s linear infinite;
    opacity: 0.9;
}

.stat-large-content {
    flex: 1;
}

.stat-large-content h4 {
    font-size: 18px;
    font-weight: 600;
    color: var(--cosmic-text-secondary);
    margin-bottom: 12px;
}

.stat-large-number {
    font-size: 48px;
    font-weight: 800;
    color: var(--cosmic-text);
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-success));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px;
}

.stat-large-label {
    font-size: 14px;
    color: var(--cosmic-text-secondary);
    margin-bottom: 16px;
}

.stat-large-trend {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    color: var(--cosmic-success);
    font-size: 14px;
    font-weight: 600;
}

.trend-icon {
    width: 20px;
    height: 20px;
    background: var(--cosmic-success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
}

.stat-large-types {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.type-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--cosmic-border);
}

.type-item:last-child {
    border-bottom: none;
}

.type-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 8px;
}

.type-name {
    font-size: 14px;
    color: var(--cosmic-text);
    flex: 1;
}

.type-count {
    font-size: 14px;
    font-weight: 600;
    color: var(--cosmic-primary);
}

.stat-large-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--cosmic-primary), var(--cosmic-secondary), var(--cosmic-primary));
    background-size: 200% 100%;
    animation: shimmer 2s infinite linear;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .header-stats {
        grid-template-columns: 1fr;
    }
    
    .period-buttons {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        gap: 24px;
        text-align: center;
        padding: 24px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .chart-stats {
        width: 100%;
        justify-content: space-between;
    }
    
    .stat-card-large {
        flex-direction: column;
        text-align: center;
    }
    
    .period-buttons {
        grid-template-columns: 1fr;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: var(--cosmic-card);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--cosmic-primary), var(--cosmic-secondary));
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, var(--cosmic-primary-light), var(--cosmic-purple));
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-gradient"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animated number counting
    const statNumbers = document.querySelectorAll('.stat-number[data-count]');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = Math.round(current);
        }, 16);
    });

    // Chart data
    const documentData = @json($documentStats);
    const userData = @json($userStats);
    
    // Enhanced chart colors
    const chartColors = {
        primary: '#6366f1',
        primaryLight: '#818cf8',
        secondary: '#8b5cf6',
        success: '#10b981',
        successLight: '#34d399',
        gradient: {
            documents: {
                start: 'rgba(99, 102, 241, 0.8)',
                end: 'rgba(139, 92, 246, 0.4)'
            },
            users: {
                start: 'rgba(16, 185, 129, 0.8)',
                end: 'rgba(52, 211, 153, 0.4)'
            }
        }
    };

    // Documents Chart with enhanced styling
    const documentsCtx = document.getElementById('documentsChart').getContext('2d');
    const documentsGradient = documentsCtx.createLinearGradient(0, 0, 0, 400);
    documentsGradient.addColorStop(0, chartColors.gradient.documents.start);
    documentsGradient.addColorStop(1, chartColors.gradient.documents.end);

    new Chart(documentsCtx, {
        type: 'line',
        data: {
            labels: documentData.labels,
            datasets: [{
                label: 'Документы',
                data: documentData.data,
                borderColor: chartColors.primary,
                backgroundColor: documentsGradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: chartColors.primary,
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: chartColors.secondary,
                pointHoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#f9fafb',
                    bodyColor: '#f9fafb',
                    borderColor: '#374151',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `Документы: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#d1d5db',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#d1d5db',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Users Chart with enhanced styling
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersGradient = usersCtx.createLinearGradient(0, 0, 0, 400);
    usersGradient.addColorStop(0, chartColors.gradient.users.start);
    usersGradient.addColorStop(1, chartColors.gradient.users.end);

    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: userData.labels,
            datasets: [{
                label: 'Пользователи',
                data: userData.data,
                backgroundColor: usersGradient,
                borderColor: chartColors.success,
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#f9fafb',
                    bodyColor: '#f9fafb',
                    borderColor: '#374151',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `Пользователи: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#d1d5db',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#d1d5db',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Add hover effects to all interactive elements
    const interactiveElements = document.querySelectorAll('.stat-card, .period-btn, .action-card, .chart-card');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = this.style.transform || '';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Add loading animation
    const adminContainer = document.querySelector('.admin-container');
    adminContainer.style.opacity = '0';
    adminContainer.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        adminContainer.style.transition = 'all 0.8s ease';
        adminContainer.style.opacity = '1';
        adminContainer.style.transform = 'translateY(0)';
    }, 200);
});
</script>
@endsection