@extends('layouts.base')

@section('content')
<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="header-text">
                <h1>Профиль пользователя</h1>
                <p>Просмотр и управление данными пользователя</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Назад к списку
            </a>
            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Редактировать профиль
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Успешно!</div>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @php
        // Определяем отображаемое имя пользователя
        $displayName = $user->login === 'copp' ? 'Администратор' : $user->login;
        $displayRole = $user->login === 'copp' ? 'Администратор' : ($user->role === 'admin' ? 'Администратор' : 'Пользователь');
        $isCoppUser = $user->login === 'copp';
    @endphp

    <div class="user-profile">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="card-header">
                <div class="user-identity">
                    <div class="avatar-section">
                        <div class="avatar-wrapper">
                            <div class="avatar-circle">
                                {{ $isCoppUser ? 'A' : strtoupper(substr($user->login, 0, 1)) }}
                            </div>
                            <div class="status-indicators">
                                @if($user->is_blocked)
                                <div class="status-dot blocked" data-tooltip="Аккаунт заблокирован">
                                    <i class="fas fa-ban"></i>
                                </div>
                                @else
                                <div class="status-dot active" data-tooltip="Аккаунт активен">
                                    <i class="fas fa-check"></i>
                                </div>
                                @endif
                                @if($isCoppUser || $user->isAdmin())
                                <div class="status-dot admin" data-tooltip="Администратор">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="user-details">
                        <h2 class="user-name">{{ $displayName }}</h2>
                        <p class="user-email">{{ $user->email }}</p>
                        <div class="user-meta">
                            <span class="meta-item">
                                <i class="fas fa-id-card"></i>
                                ID: #{{ $user->id }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-calendar"></i>
                                {{ $user->created_at->format('d.m.Y') }}
                            </span>
                            @if($user->last_login_at)
                            <span class="meta-item">
                                <i class="fas fa-sign-in-alt"></i>
                                Был(а) {{ $user->last_login_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                @if(!$isCoppUser)
                <form action="{{ route('admin.user.toggle-block', $user->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="btn {{ $user->is_blocked ? 'btn-success' : 'btn-warning' }}" 
                            data-tooltip="{{ $user->is_blocked ? 'Разблокировать пользователя' : 'Заблокировать пользователя' }}">
                        <i class="fas {{ $user->is_blocked ? 'fa-lock-open' : 'fa-lock' }}"></i>
                        {{ $user->is_blocked ? 'Активировать' : 'Заблокировать' }}
                    </button>
                </form>

                @if($user->id !== auth()->id())
                <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="action-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            data-tooltip="Удалить аккаунт пользователя"
                            onclick="return confirm('ВНИМАНИЕ! Вы уверены, что хотите удалить пользователя {{ $displayName }}? Это действие нельзя отменить.')">
                        <i class="fas fa-trash-alt"></i>
                        Удалить аккаунт
                    </button>
                </form>
                @endif
                @else
                <div class="protected-actions">
                    <span class="protected-badge" data-tooltip="Это системный администратор">
                        <i class="fas fa-shield-alt"></i>
                        Защищенный аккаунт
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <!-- Personal Information -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-user-tag"></i>
                    <h3>Основная информация</h3>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user"></i>
                            Логин
                        </div>
                        <div class="info-value">{{ $user->login }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user-check"></i>
                            Отображаемое имя
                        </div>
                        <div class="info-value">{{ $displayName }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            Email
                        </div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i>
                            Полное имя
                        </div>
                        <div class="info-value">{{ $user->name ?? 'Не указано' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-phone"></i>
                            Телефон
                        </div>
                        <div class="info-value">{{ $user->phone ?? 'Не указан' }}</div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-cog"></i>
                    <h3>Учетная запись</h3>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-shield-alt"></i>
                            Роль
                        </div>
                        <div class="info-value">
                            <span class="role-badge {{ $isCoppUser ? 'admin' : $user->role }}">
                                {{ $displayRole }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-plus"></i>
                            Дата регистрации
                        </div>
                        <div class="info-value">{{ $user->created_at->format('d.m.Y в H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i>
                            В системе
                        </div>
                        <div class="info-value">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-database"></i>
                            Документов
                        </div>
                        <div class="info-value">{{ $user->documents_count ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-chart-line"></i>
                    <h3>Активность</h3>
                </div>
                <div class="card-body">
                    @if($user->last_login_at)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-sign-in-alt"></i>
                            Последний вход
                        </div>
                        <div class="info-value">{{ $user->last_login_at->format('d.m.Y в H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-history"></i>
                            Активность
                        </div>
                        <div class="info-value">Был(а) {{ $user->last_login_at->diffForHumans() }}</div>
                    </div>
                    @else
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-exclamation-triangle"></i>
                            Активность
                        </div>
                        <div class="info-value">Никогда не входил(а)</div>
                    </div>
                    @endif
                    
                    @if($user->is_blocked && $user->blocked_at)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-ban"></i>
                            Заблокирован
                        </div>
                        <div class="info-value">{{ $user->blocked_at->format('d.m.Y в H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Overview -->
            <div class="info-card status-overview">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Статус аккаунта</h3>
                </div>
                <div class="card-body">
                    <div class="status-grid">
                        <div class="status-item {{ !$user->is_blocked ? 'active' : 'inactive' }}">
                            <div class="status-icon">
                                <i class="fas {{ !$user->is_blocked ? 'fa-check-circle' : 'fa-ban' }}"></i>
                            </div>
                            <div class="status-info">
                                <div class="status-title">Статус</div>
                                <div class="status-value">{{ !$user->is_blocked ? 'Активен' : 'Заблокирован' }}</div>
                            </div>
                        </div>
                        <div class="status-item active">
                            <div class="status-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="status-info">
                                <div class="status-title">Роль</div>
                                <div class="status-value">{{ $displayRole }}</div>
                            </div>
                        </div>
                        <div class="status-item info">
                            <div class="status-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="status-info">
                                <div class="status-title">Документы</div>
                                <div class="status-value">{{ $user->documents_count ?? 0 }} файлов</div>
                            </div>
                        </div>
                        @if($isCoppUser)
                        <div class="status-item active">
                            <div class="status-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="status-info">
                                <div class="status-title">Тип аккаунта</div>
                                <div class="status-value">Системный администратор</div>
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
/* Добавляем новые стили для защищенного аккаунта */
.protected-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.protected-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 14px;
    border: 2px solid rgba(139, 92, 246, 0.3);
}

/* Обновляем существующие стили для лучшего отображения */
.role-badge.admin {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.user-name {
    font-size: 32px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--dark-text), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Остальные стили остаются без изменений */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --secondary: #6b7280;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --info: #3b82f6;
    --dark-bg: #0f172a;
    --dark-card: #1e293b;
    --dark-border: #334155;
    --dark-text: #f1f5f9;
    --dark-text-secondary: #94a3b8;
    --dark-hover: #2d3748;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.3);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.3), 0 4px 6px -4px rgb(0 0 0 / 0.3);
    --radius: 12px;
    --radius-lg: 16px;
}

.user-profile {
    max-width: 1200px;
    margin: 0 auto;
}

/* Profile Card */
.profile-card {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.card-header {
    padding: 30px;
    border-bottom: 1px solid var(--dark-border);
}

.user-identity {
    display: flex;
    align-items: center;
    gap: 24px;
}

.avatar-section {
    position: relative;
}

.avatar-wrapper {
    position: relative;
}

.avatar-circle {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 42px;
    font-weight: 700;
    box-shadow: var(--shadow-lg);
    border: 4px solid var(--dark-card);
}

.status-indicators {
    position: absolute;
    bottom: 8px;
    right: 8px;
    display: flex;
    gap: 6px;
}

.status-dot {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border: 2px solid var(--dark-card);
    position: relative;
    cursor: help;
}

.status-dot.active {
    background: var(--success);
    color: white;
}

.status-dot.blocked {
    background: var(--error);
    color: white;
}

.status-dot.admin {
    background: #8b5cf6;
    color: white;
}

.user-details {
    flex: 1;
}

.user-name {
    font-size: 32px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--dark-text), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.user-email {
    font-size: 18px;
    color: var(--dark-text-secondary);
    margin-bottom: 16px;
}

.user-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: var(--dark-text-secondary);
}

.meta-item i {
    color: var(--primary);
}

/* Quick Actions */
.quick-actions {
    padding: 24px 30px;
    background: rgba(30, 41, 59, 0.5);
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.action-form {
    display: inline;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    font-size: 14px;
    font-family: inherit;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.btn-secondary {
    background: transparent;
    border: 2px solid var(--dark-border);
    color: var(--dark-text);
}

.btn-secondary:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: rgba(99, 102, 241, 0.05);
}

.btn-success {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning), #d97706);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, var(--error), #dc2626);
    color: white;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
}

.info-card {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.info-card .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 24px;
    background: rgba(30, 41, 59, 0.5);
    border-bottom: 1px solid var(--dark-border);
}

.info-card .card-header i {
    color: var(--primary);
    font-size: 18px;
}

.info-card .card-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-text);
    margin: 0;
}

.card-body {
    padding: 24px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--dark-text);
    font-size: 14px;
}

.info-label i {
    color: var(--primary);
    width: 16px;
    text-align: center;
}

.info-value {
    color: var(--dark-text-secondary);
    font-size: 14px;
    text-align: right;
}

.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.role-badge.admin {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.role-badge.user {
    background: rgba(59, 130, 246, 0.2);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

/* Status Overview */
.status-overview .card-body {
    padding: 0;
}

.status-grid {
    display: grid;
    grid-template-columns: 1fr;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    transition: all 0.3s ease;
}

.status-item:last-child {
    border-bottom: none;
}

.status-item:hover {
    background: rgba(30, 41, 59, 0.3);
}

.status-item.active {
    border-left: 4px solid var(--success);
}

.status-item.inactive {
    border-left: 4px solid var(--error);
}

.status-item.info {
    border-left: 4px solid var(--info);
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.status-item.active .status-icon {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
}

.status-item.inactive .status-icon {
    background: rgba(239, 68, 68, 0.2);
    color: var(--error);
}

.status-item.info .status-icon {
    background: rgba(59, 130, 246, 0.2);
    color: var(--info);
}

.status-info {
    flex: 1;
}

.status-title {
    font-weight: 600;
    color: var(--dark-text);
    font-size: 14px;
    margin-bottom: 4px;
}

.status-value {
    color: var(--dark-text-secondary);
    font-size: 13px;
}

/* Alert Styles */
.alert {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-radius: var(--radius);
    margin-bottom: 24px;
    box-shadow: var(--shadow);
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: var(--success);
}

.alert-icon {
    font-size: 20px;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 2px;
}

.alert-message {
    font-size: 13px;
    opacity: 0.9;
}

/* Tooltips */
[data-tooltip] {
    position: relative;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(-8px);
    background: var(--dark-card);
    color: var(--dark-text);
    padding: 8px 12px;
    border-radius: var(--radius);
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
    border: 1px solid var(--dark-border);
    box-shadow: var(--shadow-md);
    z-index: 1000;
}

[data-tooltip]:hover:before {
    opacity: 1;
    transform: translateX(-50%) translateY(-12px);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .info-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .user-identity {
        flex-direction: column;
        text-align: center;
    }
    
    .user-meta {
        justify-content: center;
    }
    
    .quick-actions {
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .header-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .header-actions .btn {
        flex: 1;
        min-width: 200px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .avatar-circle {
        width: 80px;
        height: 80px;
        font-size: 28px;
    }
    
    .user-name {
        font-size: 24px;
    }
    
    .user-email {
        font-size: 16px;
    }
    
    .btn {
        padding: 10px 16px;
        font-size: 13px;
    }
    
    .card-header {
        padding: 20px;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .quick-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .quick-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations
    const cards = document.querySelectorAll('.info-card, .profile-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection