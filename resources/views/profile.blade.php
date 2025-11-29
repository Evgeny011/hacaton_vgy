@extends('layouts.base')
@section('content')
<div class="profile-container">
    <!-- Заголовок профиля -->
    <div class="profile-header">
        <div class="profile-avatar">
            <div class="avatar-circle">
                <i class="fas fa-user"></i>
            </div>
        </div>
        <div class="profile-info">
            <h1 class="profile-name">{{ Auth::user()->name }}</h1>
            <p class="profile-email">{{ Auth::user()->email }}</p>
            </p>
        </div>
    </div>

    <!-- Статистика пользователя -->
    <div class="profile-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-info">
                <h3>{{ Auth::user()->documents()->count() }}</h3>
                <p>Всего документов</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="stat-info">
                <h3>{{ Auth::user()->documents()->whereDate('created_at', today())->count() }}</h3>
                <p>Документов сегодня</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-hdd"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format(Auth::user()->documents()->sum('size') / 1048576, 2) }} MB</h3>
                <p>Общий объем</p>
            </div>
        </div>
    </div>

    <!-- Основной контент профиля -->
    <div class="profile-content">
        <!-- Информация об аккаунте -->
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Информация об аккаунте
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Имя пользователя:</label>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span>{{ Auth::user()->email }}</span>
                </div>
                <div class="info-item">
                    <label>Дата регистрации:</label>
                    <span>{{ Auth::user()->created_at->format('d.m.Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Быстрые действия с документами -->
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Быстрые действия
            </h2>
            <div class="action-grid">
                <a href="{{ route('documents.create') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="action-content">
                        <h3>Загрузить документ</h3>
                        <p>Добавьте новый документ в систему</p>
                    </div>
                </a>

                <a href="{{ route('documents.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="action-content">
                        <h3>Мои документы</h3>
                        <p>Просмотр всех ваших документов</p>
                    </div>
                </a>

                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="action-content">
                        <h3>Панель администратора</h3>
                        <p>Управление системой</p>
                    </div>
                </a>
                @endif
            </div>
        </div>

        <!-- Последние документы -->
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-clock"></i>
                Последние документы
            </h2>
            @php
                $recentDocuments = Auth::user()->documents()->with('documentType')->latest()->take(5)->get();
            @endphp
            
            @if($recentDocuments->count() > 0)
                <div class="recent-documents">
                    @foreach($recentDocuments as $document)
                    <div class="document-item">
                        <div class="doc-icon">
                            <i class="fas fa-file-{{ $document->documentType->icon ?? 'alt' }}"></i>
                        </div>
                        <div class="doc-info">
                            <h4>{{ $document->original_name }}</h4>
                            <p class="doc-meta">
                                {{ $document->documentType->name }} • 
                                {{ $document->getFileSizeFormatted() }} • 
                                {{ $document->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="doc-actions">
                            <a href="{{ route('documents.download', $document) }}" class="action-btn" title="Скачать">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-file-import"></i>
                    <p>У вас пока нет документов</p>
                    <a href="{{ route('documents.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Загрузить первый документ
                    </a>
                </div>
            @endif
        </div>

        <!-- Управление аккаунтом -->
        <div class="profile-section account-management">
            <h2 class="section-title">
                <i class="fas fa-cog"></i>
                Управление аккаунтом
            </h2>
            <div class="account-actions">
                <div class="account-action-group">
                    <h3 class="action-group-title">Сессия</h3>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="account-action-btn logout">
                        <div class="account-action-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="account-action-content">
                            <h4>Выйти из системы</h4>
                            <p>Завершить текущую сессию</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Форма для выхода -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
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
    --radius-xl: 20px;
    --radius-2xl: 24px;
    --radius-full: 50%;
}

/* Основной контейнер с закругленными углами */
body {
    background: var(--dark-bg);
    margin: 0;
    padding: 0;
}

.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px;
    background: var(--dark-bg);
    min-height: 100vh;
    border-radius: var(--radius-2xl);
    position: relative;
}

/* Создаем эффект закругленного фона для всего контента */
.profile-container::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    z-index: -1;
    border-radius: var(--radius-2xl);
}

/* Заголовок профиля */
.profile-header {
    display: flex;
    align-items: center;
    gap: 24px;
    background: var(--dark-card);
    padding: 30px;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    margin-bottom: 30px;
    border: 1px solid var(--dark-border);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

.avatar-circle {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
    box-shadow: var(--shadow-lg);
    border: 3px solid var(--dark-border);
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 5px;
    background: linear-gradient(135deg, var(--dark-text), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.profile-email {
    font-size: 16px;
    color: var(--dark-text-secondary);
    margin-bottom: 10px;
}

.role-badge {
    padding: 8px 16px;
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    border: 1px solid;
}

.role-badge.admin {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border-color: var(--success);
}

.role-badge.user {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    border-color: var(--primary);
}

/* Статистика */
.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--dark-card);
    padding: 25px;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow);
    border: 1px solid var(--dark-border);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    box-shadow: var(--shadow);
}

.stat-info h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 5px;
}

.stat-info p {
    font-size: 14px;
    color: var(--dark-text-secondary);
    margin: 0;
}

/* Секции профиля */
.profile-content {
    background: rgba(30, 41, 59, 0.3);
    border-radius: var(--radius-xl);
    padding: 25px;
    border: 1px solid var(--dark-border);
    backdrop-filter: blur(10px);
}

.profile-section {
    background: var(--dark-card);
    padding: 30px;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow);
    border: 1px solid var(--dark-border);
    margin-bottom: 25px;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.profile-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

.account-management::before {
    background: linear-gradient(90deg, var(--warning), var(--error));
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: var(--primary);
}

/* Информационная сетка */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--dark-border);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 500;
    color: var(--dark-text);
}

.info-item span {
    color: var(--dark-text-secondary);
}

.status-badge {
    padding: 6px 12px;
    border-radius: var(--radius-full);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    border: 1px solid;
}

.status-badge.admin {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border-color: var(--success);
}

.status-badge.user {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    border-color: var(--primary);
}

/* Последние документы */
.recent-documents {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.document-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    background: rgba(30, 41, 59, 0.5);
    transition: all 0.3s ease;
}

.document-item:hover {
    border-color: var(--primary);
    background: var(--dark-hover);
    transform: translateX(4px);
}

.doc-icon {
    font-size: 20px;
    color: var(--primary);
    width: 24px;
    text-align: center;
}

.doc-info {
    flex: 1;
}

.doc-info h4 {
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.doc-meta {
    font-size: 12px;
    color: var(--dark-text-secondary);
    margin: 0;
}

.doc-actions .action-btn {
    color: var(--dark-text-secondary);
    text-decoration: none;
    padding: 6px;
    border-radius: var(--radius);
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid var(--dark-border);
    transition: all 0.2s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doc-actions .action-btn:hover {
    color: var(--primary);
    background: var(--dark-hover);
    border-color: var(--primary);
    text-decoration: none;
    transform: translateY(-1px);
}

/* Сетка быстрых действий */
.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: rgba(30, 41, 59, 0.5);
    border: 2px solid var(--dark-border);
    border-radius: var(--radius-xl);
    text-decoration: none;
    transition: all 0.3s ease;
    color: inherit;
    backdrop-filter: blur(10px);
}

.action-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
    color: inherit;
    background: var(--dark-hover);
}

.action-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    box-shadow: var(--shadow);
}

.action-content h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 5px;
}

.action-content p {
    font-size: 13px;
    color: var(--dark-text-secondary);
    margin: 0;
}

/* Управление аккаунтом */
.account-actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.account-action-group {
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    padding: 20px;
    background: rgba(30, 41, 59, 0.3);
}

.action-group-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.account-action-btn {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: transparent;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    text-decoration: none;
    transition: all 0.3s ease;
    color: inherit;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.account-action-btn:hover {
    border-color: var(--primary);
    background: var(--dark-hover);
    text-decoration: none;
    color: inherit;
}

.account-action-btn.logout:hover {
    border-color: var(--warning);
}

.account-action-icon {
    width: 40px;
    height: 40px;
    background: var(--dark-border);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dark-text);
    font-size: 16px;
}

.account-action-btn.logout .account-action-icon {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.account-action-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.account-action-content p {
    font-size: 12px;
    color: var(--dark-text-secondary);
    margin: 0;
}

/* Состояние пустоты */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--dark-text-secondary);
    background: rgba(30, 41, 59, 0.5);
    border-radius: var(--radius-xl);
    border: 1px solid var(--dark-border);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: var(--dark-border);
    opacity: 0.5;
}

.empty-state p {
    margin-bottom: 20px;
    font-size: 16px;
}

/* Кнопки */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: var(--radius-lg);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* Адаптивность */
@media (max-width: 768px) {
    .profile-container {
        padding: 20px;
        border-radius: var(--radius-lg);
    }
    
    .profile-content {
        padding: 20px;
        border-radius: var(--radius-lg);
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 20px;
        border-radius: var(--radius-lg);
    }
    
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .action-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .document-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        border-radius: var(--radius);
    }
    
    .doc-actions {
        align-self: flex-end;
    }
    
    .profile-section {
        border-radius: var(--radius-lg);
        padding: 20px;
    }
    
    .stat-card {
        border-radius: var(--radius-lg);
        padding: 20px;
    }
    
    .action-card {
        border-radius: var(--radius-lg);
    }
}

@media (max-width: 480px) {
    .profile-container {
        padding: 15px;
        border-radius: var(--radius);
    }
    
    .profile-content {
        padding: 15px;
        border-radius: var(--radius);
    }
    
    .profile-section {
        padding: 20px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .action-card {
        padding: 15px;
    }
    
    .avatar-circle {
        width: 80px;
        height: 80px;
        font-size: 32px;
    }
    
    .profile-name {
        font-size: 24px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: var(--radius);
    }
}

::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: var(--dark-card);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: var(--dark-border);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary);
    border-radius: 3px;
}

html {
    background: var(--dark-bg);
}

body {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    border-radius: var(--radius-2xl);
    margin: 0;
    padding: 0;
    min-height: 100vh;
}
</style>
@endsection