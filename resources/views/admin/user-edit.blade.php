@extends('layouts.base')

@section('content')
<div class="admin-container">
    <!-- Enhanced Header -->
    <div class="admin-header enhanced">
        <div class="header-background"></div>
        <div class="header-content">
            <div class="header-icon-wrapper">
                <div class="icon-circle">
                    <i class="fas fa-user-edit"></i>
                </div>
            </div>
            <div class="header-text">
                <h1 class="gradient-text">Редактирование профиля</h1>
                <p class="header-subtitle">Изменение данных пользователя <span class="user-highlight">{{ $user->login }}</span></p>
                <div class="header-meta">
                    <span class="meta-item">
                        <i class="fas fa-id-card"></i>
                        ID: #{{ $user->id }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        {{ $user->created_at->format('d.m.Y') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.user.view', $user->id) }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Назад к профилю
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
        <div class="alert alert-error">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Ошибка валидации</div>
                <div class="alert-message">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            <div class="alert-close">
                <i class="fas fa-times"></i>
            </div>
        </div>
    @endif

    @php
        $displayRole = $user->login === 'copp' ? 'Администратор' : ($user->role === 'admin' ? 'Администратор' : 'Пользователь');
        $isCoppUser = $user->login === 'copp';
        $isProtectedUser = $isCoppUser;
    @endphp

    <div class="edit-user-layout">
        <!-- Main Form -->
        <div class="form-container">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="user-form">
                @csrf
                @method('PUT')
                
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="card-title">
                            <h3>Основные данные</h3>
                            <p>Личная информация пользователя</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Login Field -->
                            <div class="form-group">
                                <div class="form-label-wrapper">
                                    <label for="login" class="form-label">
                                        <i class="fas fa-user"></i>
                                        Логин пользователя
                                        <span class="required">*</span>
                                    </label>
                                    @if($isCoppUser)
                                    <div class="protected-badge">
                                        <i class="fas fa-shield-alt"></i>
                                        Защищено
                                    </div>
                                    @endif
                                </div>
                                <div class="input-container {{ $isCoppUser ? 'protected' : '' }}">
                                    <input type="text" 
                                           id="login" 
                                           name="login" 
                                           value="{{ old('login', $user->login) }}" 
                                           class="form-input"
                                           placeholder="Введите уникальный логин"
                                           {{ $isCoppUser ? 'readonly' : 'required' }}
                                           data-protected="{{ $isCoppUser ? 'true' : 'false' }}">
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                @if($isCoppUser)
                                <div class="form-hint protected">
                                    <i class="fas fa-shield-check"></i>
                                    Системный администратор - логин защищен от изменений
                                </div>
                                @else
                                <div class="form-hint">Идентификатор для входа в систему</div>
                                @endif
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Электронная почта
                                    <span class="required">*</span>
                                </label>
                                <div class="input-container">
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           class="form-input"
                                           placeholder="user@example.com"
                                           required>
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <div class="form-hint">Для уведомлений и восстановления доступа</div>
                            </div>

                            <!-- Name Field -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Полное имя
                                </label>
                                <div class="input-container">
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           class="form-input"
                                           placeholder="Иван Иванов">
                                    <div class="input-icon">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                </div>
                                <div class="form-hint">Настоящее имя пользователя (опционально)</div>
                            </div>

                            <!-- Phone Field -->
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-mobile-alt"></i>
                                    Номер телефона
                                </label>
                                <div class="input-container">
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           class="form-input"
                                           placeholder="+7 (999) 999-99-99">
                                    <div class="input-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </div>
                                <div class="form-hint">Контактный номер (опционально)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large {{ $isCoppUser ? 'btn-disabled' : '' }}" 
                            {{ $isCoppUser ? 'disabled' : '' }}>
                        <i class="fas fa-save"></i>
                        {{ $isCoppUser ? 'Изменения запрещены' : 'Обновить данные' }}
                    </button>
                    <a href="{{ route('admin.user.view', $user->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Отмена
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- User Summary Card -->
            <div class="sidebar-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="card-title">
                        <h4>Сводка профиля</h4>
                        <p>Общая информация</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="user-summary">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">ID пользователя</div>
                                <div class="summary-value">#{{ $user->id }}</div>
                            </div>
                        </div>
                        
                        <div class="summary-item">
                            <div class="summary-icon role">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Уровень доступа</div>
                                <div class="summary-value">
                                    <span class="status-badge role {{ $isCoppUser ? 'admin protected' : $user->role }}">
                                        <i class="fas {{ $isCoppUser ? 'fa-crown' : ($user->isAdmin() ? 'fa-crown' : 'fa-user') }}"></i>
                                        {{ $displayRole }}
                                        @if($isCoppUser)
                                        <i class="fas fa-shield-check"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-icon status">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Статус аккаунта</div>
                                <div class="summary-value">
                                    <span class="status-badge {{ $user->is_blocked ? 'blocked' : 'active' }} {{ $isCoppUser ? 'protected' : '' }}">
                                        <i class="fas {{ $user->is_blocked ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                        {{ $user->is_blocked ? 'Заблокирован' : 'Активен' }}
                                        @if($isCoppUser && $user->is_blocked)
                                        <i class="fas fa-shield-check"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Документы</div>
                                <div class="summary-value">{{ $user->documents_count ?? 0 }} файлов</div>
                            </div>
                        </div>

                        @if($isCoppUser)
                        <div class="summary-item protected">
                            <div class="summary-icon protected">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Тип аккаунта</div>
                                <div class="summary-value">
                                    <span class="status-badge protected">
                                        <i class="fas fa-star"></i>
                                        Системный администратор
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="sidebar-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="card-title">
                        <h4>История активности</h4>
                        <p>Хронология событий</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker primary"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="timeline-details">
                                    <div class="timeline-title">Создание аккаунта</div>
                                    <div class="timeline-date">{{ $user->created_at->format('d.m.Y в H:i') }}</div>
                                    <div class="timeline-desc">Пользователь зарегистрирован в системе</div>
                                </div>
                            </div>
                        </div>

                        @if($user->last_login_at)
                        <div class="timeline-item">
                            <div class="timeline-marker success"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="timeline-details">
                                    <div class="timeline-title">Последний вход</div>
                                    <div class="timeline-date">{{ $user->last_login_at->format('d.m.Y в H:i') }}</div>
                                    <div class="timeline-desc">{{ $user->last_login_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($user->is_blocked && $user->blocked_at)
                        <div class="timeline-item">
                            <div class="timeline-marker danger"></div>
                            <div class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <div class="timeline-details">
                                    <div class="timeline-title">Блокировка аккаунта</div>
                                    <div class="timeline-date">{{ $user->blocked_at->format('d.m.Y в H:i') }}</div>
                                    @if($isCoppUser)
                                    <div class="timeline-desc protected">
                                        <i class="fas fa-shield-alt"></i>
                                        Системный администратор не может быть заблокирован
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="sidebar-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="card-title">
                        <h4>Управление доступом</h4>
                        <p>Быстрые действия</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        @if(!$isCoppUser)
                        <form action="{{ route('admin.user.toggle-block', $user->id) }}" method="POST" class="action-form">
                            @csrf
                            <button type="submit" class="btn {{ $user->is_blocked ? 'btn-success' : 'btn-warning' }} btn-block"
                                    data-tooltip="{{ $user->is_blocked ? 'Активировать аккаунт' : 'Ограничить доступ' }}">
                                <i class="fas {{ $user->is_blocked ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                {{ $user->is_blocked ? 'Активировать' : 'Заблокировать' }}
                            </button>
                        </form>

                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="action-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block"
                                    data-tooltip="Безвозвратно удалить аккаунт"
                                    onclick="return confirm('ВНИМАНИЕ! Вы уверены, что хотите удалить пользователя {{ $user->login }}? Это действие нельзя отменить.')">
                                <i class="fas fa-trash-alt"></i>
                                Удалить аккаунт
                            </button>
                        </form>
                        @endif
                        @else
                        <div class="protected-actions">
                            <div class="protected-notice">
                                <div class="notice-icon">
                                    <i class="fas fa-shield-check"></i>
                                </div>
                                <div class="notice-content">
                                    <div class="notice-title">Защищенный аккаунт</div>
                                    <div class="notice-message">Системный администратор защищен от изменений</div>
                                </div>
                            </div>
                            <div class="action-disabled">
                                <button class="btn btn-disabled" disabled>
                                    <i class="fas fa-lock"></i>
                                    Блокировка запрещена
                                </button>
                                <button class="btn btn-disabled" disabled>
                                    <i class="fas fa-trash-alt"></i>
                                    Удаление запрещено
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Variables */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --primary-50: #eef2ff;
    --primary-100: #e0e7ff;
    --primary-200: #c7d2fe;
    --secondary: #6b7280;
    --success: #10b981;
    --success-dark: #059669;
    --warning: #f59e0b;
    --warning-dark: #d97706;
    --error: #ef4444;
    --error-dark: #dc2626;
    --info: #3b82f6;
    --purple: #8b5cf6;
    --dark-bg: #0f172a;
    --dark-card: #1e293b;
    --dark-border: #334155;
    --dark-text: #f1f5f9;
    --dark-text-secondary: #94a3b8;
    --dark-hover: #2d3748;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --radius: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
}

/* Base Styles */
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Enhanced Header */
.admin-header.enhanced {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    border-radius: var(--radius-xl);
    margin-bottom: 30px;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    position: relative;
}

.header-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(99, 102, 241, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.2) 0%, transparent 50%);
    opacity: 0.6;
}

.header-content {
    position: relative;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 30px;
    z-index: 2;
}

.header-icon-wrapper .icon-circle {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.icon-circle i {
    font-size: 28px;
    color: white;
}

.header-text {
    flex: 1;
}

.gradient-text {
    background: linear-gradient(135deg, #fff, var(--primary-200));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.header-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin-bottom: 12px;
}

.user-highlight {
    color: #fff;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: var(--radius);
}

.header-meta {
    display: flex;
    gap: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.header-actions {
    position: relative;
    z-index: 2;
    padding-right: 30px;
}

/* Buttons - Complete Redesign */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    font-size: 14px;
    font-family: inherit;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn:active {
    transform: translateY(0);
}

/* Primary Button */
.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    box-shadow: var(--shadow-lg), 0 0 20px rgba(99, 102, 241, 0.4);
}

/* Secondary Button */
.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: var(--dark-text);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
}

/* Back Button */
.btn-back {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Success Button */
.btn-success {
    background: linear-gradient(135deg, var(--success), var(--success-dark));
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-success:hover {
    background: linear-gradient(135deg, #34d399, var(--success));
    box-shadow: var(--shadow-lg), 0 0 20px rgba(16, 185, 129, 0.4);
}

/* Warning Button */
.btn-warning {
    background: linear-gradient(135deg, var(--warning), var(--warning-dark));
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #fbbf24, var(--warning));
    box-shadow: var(--shadow-lg), 0 0 20px rgba(245, 158, 11, 0.4);
}

/* Danger Button */
.btn-danger {
    background: linear-gradient(135deg, var(--error), var(--error-dark));
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #f87171, var(--error));
    box-shadow: var(--shadow-lg), 0 0 20px rgba(239, 68, 68, 0.4);
}

/* Disabled Button */
.btn-disabled {
    background: rgba(148, 163, 184, 0.2) !important;
    color: var(--dark-text-secondary) !important;
    border: 1px solid rgba(148, 163, 184, 0.3) !important;
    cursor: not-allowed !important;
    opacity: 0.6;
    transform: none !important;
    box-shadow: none !important;
}

.btn-disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}

.btn-disabled::before {
    display: none;
}

/* Button Sizes */
.btn-large {
    padding: 14px 24px;
    font-size: 15px;
}

.btn-block {
    width: 100%;
    justify-content: center;
}

/* Cards */
.form-card, .sidebar-card {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.form-card:hover, .sidebar-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(79, 70, 229, 0.05));
    border-bottom: 1px solid var(--dark-border);
}

.card-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    box-shadow: var(--shadow-md);
}

.card-title h3, .card-title h4 {
    margin: 0;
    color: var(--dark-text);
    font-weight: 600;
}

.card-title h3 {
    font-size: 1.3rem;
}

.card-title h4 {
    font-size: 1.1rem;
}

.card-title p {
    margin: 4px 0 0 0;
    color: var(--dark-text-secondary);
    font-size: 0.9rem;
}

.card-body {
    padding: 24px;
}

/* Form Styles */
.form-grid {
    display: grid;
    gap: 20px;
}

.form-group {
    position: relative;
}

.form-label-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--dark-text);
    font-size: 14px;
}

.form-label i {
    color: var(--primary);
    width: 16px;
    text-align: center;
}

.required {
    color: var(--error);
    font-weight: 700;
}

.protected-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: rgba(139, 92, 246, 0.2);
    color: var(--purple);
    border: 1px solid rgba(139, 92, 246, 0.3);
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.form-input {
    width: 100%;
    padding: 14px 16px 14px 44px;
    background: var(--dark-bg);
    border: 2px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 15px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--dark-card);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input::placeholder {
    color: var(--dark-text-secondary);
    opacity: 0.7;
}

.input-container.protected .form-input {
    background: rgba(139, 92, 246, 0.1);
    border-color: rgba(139, 92, 246, 0.3);
    color: var(--purple);
}

.input-icon {
    position: absolute;
    left: 16px;
    color: var(--dark-text-secondary);
    transition: all 0.3s ease;
}

.form-input:focus + .input-icon {
    color: var(--primary);
}

.input-container.protected .input-icon {
    color: var(--purple);
}

.form-hint {
    font-size: 12px;
    color: var(--dark-text-secondary);
    margin-top: 6px;
    line-height: 1.4;
}

.form-hint.protected {
    color: var(--purple);
    font-weight: 500;
}

.form-hint.protected i {
    color: var(--purple);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    padding: 24px;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow);
}

/* User Summary */
.user-summary {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius);
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.summary-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--dark-border);
}

.summary-item.protected {
    background: rgba(139, 92, 246, 0.1);
    border-color: rgba(139, 92, 246, 0.2);
}

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
}

.summary-icon.role {
    background: rgba(139, 92, 246, 0.1);
    color: var(--purple);
}

.summary-icon.status {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.summary-icon.protected {
    background: rgba(139, 92, 246, 0.2);
    color: var(--purple);
}

.summary-content {
    flex: 1;
}

.summary-label {
    font-weight: 600;
    color: var(--dark-text);
    font-size: 0.9rem;
    margin-bottom: 2px;
}

.summary-value {
    color: var(--dark-text-secondary);
    font-size: 0.9rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.role.admin {
    background: rgba(139, 92, 246, 0.2);
    color: var(--purple);
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.status-badge.role.protected {
    background: rgba(139, 92, 246, 0.3);
    border-color: rgba(139, 92, 246, 0.5);
}

.status-badge.active {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-badge.blocked {
    background: rgba(239, 68, 68, 0.2);
    color: var(--error);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.status-badge.protected {
    background: rgba(139, 92, 246, 0.2);
    color: var(--purple);
    border: 1px solid rgba(139, 92, 246, 0.3);
}

/* Timeline */
.timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.timeline-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-top: 8px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
}

.timeline-marker.primary {
    background: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
}

.timeline-marker.success {
    background: var(--success);
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
}

.timeline-marker.danger {
    background: var(--error);
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
}

.timeline-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1;
}

.timeline-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--dark-text-secondary);
    margin-top: 2px;
}

.timeline-details {
    flex: 1;
}

.timeline-title {
    font-weight: 600;
    color: var(--dark-text);
    font-size: 0.9rem;
    margin-bottom: 2px;
}

.timeline-date {
    color: var(--primary);
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 4px;
}

.timeline-desc {
    color: var(--dark-text-secondary);
    font-size: 0.8rem;
}

.timeline-desc.protected {
    color: var(--purple);
    font-weight: 500;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-form {
    margin: 0;
}

/* Protected Actions */
.protected-actions {
    text-align: center;
}

.protected-notice {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.2);
    border-radius: var(--radius);
    margin-bottom: 16px;
}

.notice-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(139, 92, 246, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--purple);
    font-size: 14px;
}

.notice-content {
    flex: 1;
    text-align: left;
}

.notice-title {
    font-weight: 600;
    color: var(--dark-text);
    font-size: 13px;
    margin-bottom: 2px;
}

.notice-message {
    color: var(--dark-text-secondary);
    font-size: 12px;
}

.action-disabled {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Alerts */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    border-radius: var(--radius);
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    position: relative;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: var(--error);
}

.alert-icon {
    font-size: 20px;
    margin-top: 2px;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 6px;
}

.alert-message {
    font-size: 14px;
    line-height: 1.5;
}

.alert-message div {
    margin-bottom: 4px;
}

.alert-message div:last-child {
    margin-bottom: 0;
}

.alert-close {
    position: absolute;
    top: 16px;
    right: 16px;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.alert-close:hover {
    opacity: 1;
}

/* Layout */
.edit-user-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
}

.sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .edit-user-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 16px;
        padding: 20px;
    }
    
    .header-actions {
        padding-right: 0;
        padding-bottom: 20px;
    }
    
    .gradient-text {
        font-size: 1.8rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .card-body {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .form-actions {
        padding: 20px;
    }
    
    .card-header {
        padding: 20px;
    }
    
    .form-input {
        padding: 12px 16px 12px 44px;
        font-size: 14px;
    }
    
    .btn {
        padding: 10px 16px;
        font-size: 13px;
    }
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card, .sidebar-card {
    animation: slideIn 0.6s ease-out;
}

.form-card:nth-child(1) { animation-delay: 0.1s; }
.sidebar-card:nth-child(1) { animation-delay: 0.1s; }
.sidebar-card:nth-child(2) { animation-delay: 0.2s; }
.sidebar-card:nth-child(3) { animation-delay: 0.3s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced input interactions
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Form submission loading state
    const form = document.querySelector('.user-form');
    form.addEventListener('submit', function(e) {
        const isProtected = this.querySelector('[data-protected="true"]');
        if (isProtected) {
            e.preventDefault();
            showProtectedWarning();
            return false;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
        submitBtn.disabled = true;
    });

    function showProtectedWarning() {
        const warning = document.createElement('div');
        warning.className = 'alert alert-error';
        warning.innerHTML = `
            <div class="alert-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Запрещено изменять системного администратора</div>
                <div class="alert-message">Аккаунт "copp" защищен от изменений для обеспечения безопасности системы.</div>
            </div>
            <div class="alert-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </div>
        `;
        
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.parentNode.insertBefore(warning, existingAlert.nextSibling);
        } else {
            document.querySelector('.admin-header').parentNode.insertBefore(warning, document.querySelector('.edit-user-layout'));
        }
        
        setTimeout(() => {
            if (warning.parentNode) {
                warning.remove();
            }
        }, 5000);
    }

    // Close alert functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.alert-close')) {
            e.target.closest('.alert').remove();
        }
    });

    // Add smooth hover effects to cards
    const cards = document.querySelectorAll('.form-card, .sidebar-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-2px)';
        });
    });
});
</script>
@endsection