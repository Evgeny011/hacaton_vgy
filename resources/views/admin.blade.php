@extends('layouts.base')

@section('content')
<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="header-text">
                <h1>Админ панель</h1>
                <p>Управление пользователями и системой</p>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ \App\Models\User::count() }}</span>
                    <span class="stat-label">Всего пользователей</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ \App\Models\User::count() }}</span>
                    <span class="stat-label">Активных</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ \App\Models\User::where('is_blocked', true)->count() }}</span>
                    <span class="stat-label">Заблокированных</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="quick-actions-section">
        <div class="actions-grid">
            <!-- Контрагенты -->
            <a href="{{ route('admin.counterparties.index') }}" class="action-card counterparty-card">
                <div class="card-glow"></div>
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <div class="action-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="action-badge">
                            {{ \App\Models\Counterparty::count() }}
                        </div>
                    </div>
                    <div class="action-content">
                        <h4>Контрагенты</h4>
                        <p>Управление контрагентами и организациями</p>
                    </div>
                    <div class="action-footer">
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>
            
            <!-- Создать контрагента -->
            <a href="{{ route('admin.counterparties.create') }}" class="action-card create-card">
                <div class="card-glow"></div>
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <div class="action-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                    <div class="action-content">
                        <h4>Создать контрагента</h4>
                        <p>Добавить нового контрагента в систему</p>
                    </div>
                    <div class="action-footer">
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>
            
            <!-- Статистика -->
            <a href="{{ route('admin.statistics') }}" class="action-card statistics-card">
                <div class="card-glow"></div>
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <div class="action-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                    <div class="action-content">
                        <h4>Статистика</h4>
                        <p>Просмотр аналитики и графиков по документам</p>
                    </div>
                    <div class="action-footer">
                        <div class="action-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search-section">
        <form method="GET" action="{{ route('admin') }}" class="search-form">
            <div class="search-fields">
                <!-- Search Input -->
                <div class="search-field">
                    <label for="search">Поиск пользователей</label>
                    <div class="search-input-group">
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ $search ?? '' }}" 
                               placeholder="Введите логин, email или имя пользователя..."
                               class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                            Найти
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="search-actions">
                    @if($search)
                        <input type="hidden" name="show_all" value="true">
                        <button type="submit" class="action-btn primary">
                            <i class="fas fa-list"></i>
                            Показать всех
                        </button>
                    @endif
                    <a href="{{ route('admin') }}" class="reset-btn">
                        <i class="fas fa-times"></i>
                        Сбросить
                    </a>
                </div>
            </div>
        </form>

        <!-- Search Results Info -->
        @if(isset($search) && $users->count() > 0)
        <div class="search-results-info">
            <div class="results-count">
                <i class="fas fa-filter"></i>
                Найдено пользователей: {{ $users->count() }}
                @if($search)
                    | По запросу: "{{ $search }}"
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            {{ session('info') }}
        </div>
    @endif

    <!-- Users List -->
    @if($users->count() > 0)
        <div class="users-grid">
            @foreach($users as $user)
                <div class="user-card {{ $user->is_blocked ? 'blocked' : '' }}">
                    <div class="user-header">
                        <div class="user-avatar">
                            <div class="avatar-circle">
                                {{ strtoupper(substr($user->login, 0, 1)) }}
                            </div>
                            <div class="user-status {{ $user->is_blocked ? 'offline' : 'online' }}"></div>
                            @if($user->is_blocked)
                                <div class="user-blocked-indicator">
                                    <i class="fas fa-ban"></i>
                                </div>
                            @endif
                        </div>
                        <div class="user-info">
                            <h4 class="user-name">{{ $user->login }}</h4>
                            <span class="user-email">{{ $user->email }}</span>
                            @if($user->name)
                                <span class="user-fullname">{{ $user->name }}</span>
                            @endif
                        </div>
                        <div class="user-actions">
                            <a href="{{ route('admin.user.view', $user->id) }}" 
                               class="action-btn view-btn" 
                               data-bs-toggle="tooltip" 
                               title="Просмотр профиля">
                                <i class="fas fa-eye"></i>
                                <span class="action-tooltip">Просмотр</span>
                            </a>
                            <a href="{{ route('admin.user.edit', $user->id) }}" 
                               class="action-btn edit-btn" 
                               data-bs-toggle="tooltip" 
                               title="Редактировать">
                                <i class="fas fa-edit"></i>
                                <span class="action-tooltip">Редактировать</span>
                            </a>
                            <form action="{{ route('admin.user.toggle-block', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="action-btn {{ $user->is_blocked ? 'unblock-btn' : 'block-btn' }}" 
                                        data-bs-toggle="tooltip" 
                                        title="{{ $user->is_blocked ? 'Разблокировать' : 'Заблокировать' }}"
                                        onclick="return confirm('{{ $user->is_blocked ? 'Разблокировать' : 'Заблокировать' }} пользователя {{ $user->login }}?')">
                                    <i class="fas {{ $user->is_blocked ? 'fa-lock-open' : 'fa-ban' }}"></i>
                                    <span class="action-tooltip">{{ $user->is_blocked ? 'Разблокировать' : 'Заблокировать' }}</span>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="action-btn delete-btn" 
                                            data-bs-toggle="tooltip" 
                                            title="Удалить"
                                            onclick="return confirm('ВНИМАНИЕ! Вы уверены, что хотите удалить пользователя {{ $user->login }}? Это действие нельзя отменить.')">
                                        <i class="fas fa-trash"></i>
                                        <span class="action-tooltip">Удалить</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    <div class="user-body">
                        <div class="user-meta">
                            <div class="meta-item">
                                <i class="fas fa-id-card"></i>
                                <span>ID: {{ $user->id }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $user->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="user-status-badges">
                            @if($user->is_blocked)
                                <span class="status-badge blocked">
                                    <i class="fas fa-ban"></i>
                                    Заблокирован
                                </span>
                            @else
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i>
                                    Активен
                                </span>
                            @endif
                            
                            @if($user->isAdmin())
                                <span class="status-badge admin">
                                    <i class="fas fa-shield-alt"></i>
                                    Администратор
                                </span>
                            @endif
                        </div>

                        @if($user->last_login_at)
                        <div class="last-login">
                            <i class="fas fa-sign-in-alt"></i>
                            Последний вход: {{ $user->last_login_at->format('d.m.Y H:i') }}
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($users, 'links'))
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Показано {{ $users->firstItem() }} - {{ $users->lastItem() }} из {{ $users->total() }} пользователей
            </div>
            <div class="pagination-container">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    @elseif(isset($search))
        <!-- Empty Search Results -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3>Пользователи не найдены</h3>
            <p>Попробуйте изменить параметры поиска</p>
            <a href="{{ route('admin') }}" class="action-btn primary">
                <i class="fas fa-times"></i>
                Сбросить поиск
            </a>
        </div>
    @else
        <!-- Welcome State -->
        <div class="welcome-state">
            <div class="welcome-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Управление пользователями</h3>
            <p>Используйте поисковую форму выше для управления пользователями системы</p>
            <div class="welcome-features">
                <div class="feature-item">
                    <i class="fas fa-search"></i>
                    <span>Поиск по логину, email и имени</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-edit"></i>
                    <span>Полное управление профилями</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Блокировка и управление доступом</span>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Enhanced Variables */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --primary-glow: rgba(99, 102, 241, 0.3);
    --secondary: #6b7280;
    --success: #10b981;
    --success-dark: #059669;
    --warning: #f59e0b;
    --warning-dark: #d97706;
    --error: #ef4444;
    --error-dark: #dc2626;
    --info: #3b82f6;
    --purple: #8b5cf6;
    --teal: #14b8a6;
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
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.3), 0 8px 10px -6px rgb(0 0 0 / 0.3);
    --radius: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
}

/* Quick Actions Section - Enhanced */
.quick-actions-section {
    margin-bottom: 40px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.action-card {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 28px;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    text-decoration: none;
    color: var(--dark-text);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(20px);
    overflow: hidden;
    min-height: 200px;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.action-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl), 0 20px 40px rgba(0, 0, 0, 0.4);
    border-color: var(--primary);
    text-decoration: none;
    color: var(--dark-text);
}

.action-card:hover::before {
    opacity: 1;
}

.action-card:hover .card-glow {
    opacity: 1;
}

.action-card:hover .action-arrow {
    transform: translateX(4px);
}

.action-card:hover .action-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Card Content Layout */
.action-card-content {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    position: relative;
    z-index: 2;
}

.action-icon-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 100%;
    margin-bottom: 20px;
}

.action-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    transition: all 0.3s ease;
    flex-shrink: 0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.action-badge {
    background: linear-gradient(135deg, var(--purple), #7c3aed);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.action-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.action-content h4 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--dark-text);
    line-height: 1.3;
}

.action-content p {
    font-size: 14px;
    color: var(--dark-text-secondary);
    margin-bottom: 20px;
    line-height: 1.5;
    flex: 1;
}

.action-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.action-arrow {
    color: var(--dark-text-secondary);
    font-size: 18px;
    transition: transform 0.3s ease;
}

/* Counterparty Card Specific */
.counterparty-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(30, 41, 59, 0.7));
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.counterparty-card:hover {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(30, 41, 59, 0.8));
    border-color: var(--purple);
}

.counterparty-card .action-icon {
    background: linear-gradient(135deg, var(--purple), #7c3aed);
}

/* Create Card Specific */
.create-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(30, 41, 59, 0.7));
    border: 1px solid rgba(20, 184, 166, 0.3);
}

.create-card:hover {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(30, 41, 59, 0.8));
    border-color: var(--teal);
}

.create-card .action-icon {
    background: linear-gradient(135deg, var(--teal), #0d9488);
}

/* Statistics Card Specific */
.statistics-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(30, 41, 59, 0.7));
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.statistics-card:hover {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(30, 41, 59, 0.8));
    border-color: var(--warning);
}

.statistics-card .action-icon {
    background: linear-gradient(135deg, var(--warning), #d97706);
}

/* Responsive Design for 3-column layout */
@media (max-width: 1200px) {
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .action-card {
        padding: 24px;
        min-height: 180px;
    }
    
    .action-icon {
        width: 60px;
        height: 60px;
        font-size: 24px;
    }
    
    .action-content h4 {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .action-card {
        padding: 20px;
        min-height: 160px;
    }
    
    .action-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .action-content h4 {
        font-size: 16px;
    }
    
    .action-content p {
        font-size: 13px;
    }
}

/* Enhanced existing styles */
.user-card.blocked {
    opacity: 0.7;
    border-color: var(--error);
}

.user-card.blocked::before {
    background: linear-gradient(90deg, var(--error), #dc2626);
    opacity: 1;
}

.user-blocked-indicator {
    position: absolute;
    top: -5px;
    left: -5px;
    width: 20px;
    height: 20px;
    background: var(--error);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
    box-shadow: var(--shadow);
}

.user-fullname {
    font-size: 12px;
    color: var(--dark-text-secondary);
    font-style: italic;
}

.user-status-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-badge.blocked {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.status-badge.admin {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.3);
}

.block-btn {
    background: linear-gradient(135deg, var(--error), #dc2626);
    color: white;
}

.block-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
}

.unblock-btn {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
}

.unblock-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
}

.alert-info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-light);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.pagination-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    margin-top: 40px;
}

.pagination-info {
    font-size: 14px;
    color: var(--dark-text-secondary);
    font-weight: 500;
}

.pagination-container {
    display: flex;
    justify-content: center;
}

.pagination-container .pagination {
    margin: 0;
}

.pagination-container .page-item .page-link {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    color: var(--dark-text);
    padding: 8px 12px;
    border-radius: var(--radius);
    transition: all 0.2s ease;
}

.pagination-container .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.pagination-container .page-item:not(.active) .page-link:hover {
    background: var(--dark-hover);
    border-color: var(--primary);
    color: var(--primary);
}

.d-inline {
    display: inline;
}

.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: var(--dark-card);
    padding: 30px;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--dark-border);
    backdrop-filter: blur(10px);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: var(--shadow-lg);
}

.header-text h1 {
    font-size: 32px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 8px;
    background: linear-gradient(135deg, var(--dark-text), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-text p {
    font-size: 16px;
    color: var(--dark-text-secondary);
    font-weight: 400;
}

.header-stats {
    display: flex;
    gap: 20px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: var(--dark-bg);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    min-width: 160px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-number {
    font-size: 20px;
    font-weight: 700;
    color: var(--dark-text);
}

.stat-label {
    font-size: 12px;
    color: var(--dark-text-secondary);
    font-weight: 500;
}

.search-section {
    margin-bottom: 30px;
}

.search-form {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
}

.search-fields {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 16px;
    align-items: end;
}

.search-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.search-field label {
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-text);
}

.search-input-group {
    display: flex;
    gap: 12px;
}

.search-input {
    flex: 1;
    padding: 12px 16px;
    background: var(--dark-bg);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 14px;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-input::placeholder {
    color: var(--dark-text-secondary);
}

.search-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    border-radius: var(--radius);
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    cursor: pointer;
    white-space: nowrap;
}

.search-btn:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.search-actions {
    display: flex;
    align-items: end;
    gap: 12px;
}

.reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: transparent;
    border: 1px solid var(--dark-border);
    color: var(--dark-text-secondary);
    border-radius: var(--radius);
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    white-space: nowrap;
}

.reset-btn:hover {
    background: var(--dark-hover);
    border-color: var(--error);
    color: var(--error);
    text-decoration: none;
}

.search-results-info {
    margin-top: 16px;
    padding: 12px 16px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: var(--radius);
    color: var(--primary-light);
    font-size: 14px;
}

.results-count {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.alert {
    padding: 16px 20px;
    border-radius: var(--radius);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 24px;
}

.user-card {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    padding: 24px;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.user-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.user-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.user-card:hover::before {
    opacity: 1;
}

.user-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}

.user-avatar {
    position: relative;
}

.avatar-circle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    font-weight: 600;
    box-shadow: var(--shadow);
}

.user-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    border: 2px solid var(--dark-card);
    border-radius: 50%;
}

.user-status.online {
    background: var(--success);
}

.user-status.offline {
    background: var(--dark-text-secondary);
}

.user-info {
    flex: 1;
}

.user-name {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.user-email {
    font-size: 14px;
    color: var(--dark-text-secondary);
}

.user-actions {
    display: flex;
    gap: 6px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border: none;
    border-radius: var(--radius);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
    position: relative;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn:hover .action-tooltip {
    opacity: 1;
    transform: translateY(0);
}

.view-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.view-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), #4338ca);
}

.edit-btn {
    background: linear-gradient(135deg, var(--warning), #d97706);
    color: white;
}

.edit-btn:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
}

.delete-btn {
    background: linear-gradient(135deg, var(--error), #dc2626);
    color: white;
}

.delete-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
}

.action-tooltip {
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    background: var(--dark-card);
    color: var(--dark-text);
    padding: 4px 8px;
    border-radius: var(--radius);
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    transition: all 0.3s ease;
    pointer-events: none;
    border: 1px solid var(--dark-border);
    box-shadow: var(--shadow);
    z-index: 10;
}

.user-body {
    border-top: 1px solid var(--dark-border);
    padding-top: 16px;
}

.user-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--dark-text-secondary);
}

.meta-item i {
    width: 12px;
    text-align: center;
    color: var(--primary);
}

.last-login {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--dark-text-secondary);
}

.empty-state, .welcome-state {
    text-align: center;
    padding: 80px 40px;
    background: var(--dark-card);
    border-radius: var(--radius-xl);
    border: 1px solid var(--dark-border);
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(10px);
}

.empty-icon, .welcome-icon {
    font-size: 80px;
    color: var(--dark-border);
    margin-bottom: 24px;
    opacity: 0.5;
}

.empty-state h3, .welcome-state h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 12px;
}

.empty-state p, .welcome-state p {
    font-size: 16px;
    color: var(--dark-text-secondary);
    margin-bottom: 32px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.welcome-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--dark-bg);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 14px;
    font-weight: 500;
}

.feature-item i {
    color: var(--primary);
    font-size: 16px;
}

.action-btn.primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    border-radius: var(--radius);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: white;
}

@media (max-width: 1024px) {
    .users-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }
    
    .admin-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 24px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .header-stats {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .search-fields {
        grid-template-columns: 1fr;
    }
    
    .search-input-group {
        flex-direction: column;
    }
    
    .users-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .user-header {
        flex-direction: column;
        text-align: center;
    }
    
    .user-actions {
        justify-content: center;
        margin-top: 12px;
        flex-wrap: wrap;
    }
    
    .user-meta {
        grid-template-columns: 1fr;
    }
    
    .empty-state, .welcome-state {
        padding: 60px 20px;
    }
    
    .empty-icon, .welcome-icon {
        font-size: 60px;
    }
    
    .welcome-features {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .user-card {
        padding: 20px;
    }
    
    .header-text h1 {
        font-size: 24px;
    }
    
    .empty-state h3, .welcome-state h3 {
        font-size: 20px;
    }
    
    .action-btn {
        width: 38px;
        height: 38px;
    }
    
    .stat-card {
        min-width: 140px;
        padding: 12px 16px;
    }
    
    .user-actions {
        gap: 4px;
    }
}

::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: var(--dark-card);
}

::-webkit-scrollbar-thumb {
    background: var(--dark-border);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    const searchInput = document.getElementById('search');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }

    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
@endsection