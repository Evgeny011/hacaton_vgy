@extends('layouts.base')

@section('content')
<div class="admin-container">
    <!-- Enhanced Header -->
    <div class="admin-header enhanced">
        <div class="header-background"></div>
        <div class="header-content">
            <div class="header-icon-wrapper">
                <div class="icon-circle">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <div class="header-text">
                <h1 class="gradient-text">Управление контрагентами</h1>
                <p class="header-subtitle">Создание и редактирование контрагентов системы</p>
                <div class="header-meta">
                    <span class="meta-item">
                        <i class="fas fa-database"></i>
                        {{ $counterparties->total() }} записей
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-filter"></i>
                        {{ request()->hasAny(['search', 'type']) ? 'Фильтры активны' : 'Все записи' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Назад в админку
            </a>
            <a href="{{ route('admin.counterparties.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Создать контрагента
            </a>
        </div>
    </div>

    <!-- Enhanced Stats -->
    <div class="counterparties-stats enhanced-stats">
        <div class="stat-card enhanced">
            <div class="stat-glow"></div>
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $counterparties->total() }}</span>
                <span class="stat-label">Всего контрагентов</span>
            </div>
            <div class="stat-trend">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Enhanced Search and Filters -->
    <div class="search-section enhanced">
        <form method="GET" action="{{ route('admin.counterparties.index') }}" class="search-form enhanced">
            <div class="search-header">
                <h4>Поиск и фильтрация</h4>
                <p>Найдите нужного контрагента по различным параметрам</p>
            </div>
            <div class="search-fields enhanced">
                <div class="search-field enhanced">
                    <div class="input-wrapper">
                        <i class="fas fa-search search-field-icon"></i>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Поиск по названию, контактному лицу, email или телефону..."
                               class="search-input enhanced">
                    </div>
                </div>
                
                <div class="filter-field enhanced">
                    <div class="input-wrapper">
                        <i class="fas fa-filter filter-field-icon"></i>
                        <select name="type" class="filter-select enhanced">
                            <option value="">Все типы контрагентов</option>
                            <option value="legal" {{ request('type') == 'legal' ? 'selected' : '' }}>Юридическое лицо</option>
                            <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>Физическое лицо</option>
                        </select>
                    </div>
                </div>
                
                <div class="search-actions enhanced">
                    <button type="submit" class="search-btn enhanced">
                        <i class="fas fa-search"></i>
                        <span>Применить</span>
                    </button>
                    
                    @if(request()->hasAny(['search', 'type']))
                    <a href="{{ route('admin.counterparties.index') }}" class="reset-btn enhanced">
                        <i class="fas fa-times"></i>
                        <span>Сбросить</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Alerts -->
    @if(session('success'))
        <div class="alert alert-success enhanced">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Успешно!</div>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
            <div class="alert-close">
                <i class="fas fa-times"></i>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error enhanced">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Ошибка!</div>
                <div class="alert-message">{{ session('error') }}</div>
            </div>
            <div class="alert-close">
                <i class="fas fa-times"></i>
            </div>
        </div>
    @endif

    <!-- Enhanced Counterparties List -->
    @if($counterparties->count() > 0)
        <div class="counterparties-grid enhanced">
            @foreach($counterparties as $counterparty)
            <div class="counterparty-card enhanced">
                <div class="card-glow"></div>
                <div class="card-header enhanced">
                    <div class="counterparty-avatar enhanced">
                        <div class="avatar-circle enhanced">
                            {{ strtoupper(substr($counterparty->name, 0, 1)) }}
                        </div>
                        <div class="type-badge enhanced {{ $counterparty->type }}">
                            <i class="fas {{ $counterparty->type == 'legal' ? 'fa-building' : 'fa-user' }}"></i>
                            {{ $counterparty->type == 'legal' ? 'Юр. лицо' : 'Физ. лицо' }}
                        </div>
                    </div>
                    <div class="counterparty-info enhanced">
                        <h4 class="counterparty-name enhanced">{{ $counterparty->name }}</h4>
                        @if($counterparty->contact_person)
                        <p class="contact-person enhanced">{{ $counterparty->contact_person }}</p>
                        @endif
                    </div>
                    <div class="counterparty-actions enhanced">
                        <a href="{{ route('admin.counterparties.edit', $counterparty->id) }}" 
                           class="action-btn edit-btn enhanced" 
                           title="Редактировать"
                           data-tooltip="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.counterparties.destroy', $counterparty->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Удалить контрагента {{ $counterparty->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn enhanced" title="Удалить" data-tooltip="Удалить">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body enhanced">
                    <div class="contact-info enhanced">
                        @if($counterparty->phone)
                        <div class="contact-item enhanced">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-content">
                                <span class="contact-label">Телефон</span>
                                <span class="contact-value">{{ $counterparty->phone }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($counterparty->email)
                        <div class="contact-item enhanced">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-content">
                                <span class="contact-label">Email</span>
                                <span class="contact-value">{{ $counterparty->email }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($counterparty->address)
                        <div class="contact-item enhanced">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-content">
                                <span class="contact-label">Адрес</span>
                                <span class="contact-value">{{ Str::limit($counterparty->address, 60) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="meta-info enhanced">
                        <div class="meta-item enhanced">
                            <i class="fas fa-calendar"></i>
                            <span>Создан: {{ $counterparty->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="meta-item enhanced">
                            <i class="fas fa-clock"></i>
                            <span>{{ $counterparty->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Enhanced Pagination -->
        <div class="pagination-wrapper enhanced">
            <div class="pagination-info enhanced">
                <i class="fas fa-list"></i>
                Показано <strong>{{ $counterparties->firstItem() }} - {{ $counterparties->lastItem() }}</strong> из <strong>{{ $counterparties->total() }}</strong> контрагентов
            </div>
            <div class="pagination-container enhanced">
                {{ $counterparties->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @else
        <!-- Enhanced Empty State -->
        <div class="empty-state enhanced">
            <div class="empty-background"></div>
            <div class="empty-content">
                <div class="empty-icon enhanced">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Контрагенты не найдены</h3>
                <p>Создайте первого контрагента или измените параметры поиска</p>
                <div class="empty-actions">
                    <a href="{{ route('admin.counterparties.create') }}" class="btn btn-primary btn-large">
                        <i class="fas fa-plus"></i>
                        Создать контрагента
                    </a>
                    <a href="{{ route('admin.counterparties.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Сбросить фильтры
                    </a>
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

/* Enhanced Header */
.admin-header.enhanced {
    position: relative;
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    border-radius: var(--radius-xl);
    margin-bottom: 30px;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--dark-border);
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
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.icon-circle i {
    font-size: 32px;
    color: white;
}

.header-text {
    flex: 1;
}

.gradient-text {
    background: linear-gradient(135deg, #fff, var(--primary-light));
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
    display: flex;
    gap: 12px;
}

/* Enhanced Buttons */
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

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    box-shadow: var(--shadow-lg), 0 0 20px rgba(99, 102, 241, 0.4);
}

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

.btn-large {
    padding: 14px 24px;
    font-size: 15px;
}

/* Enhanced Stats */
.enhanced-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card.enhanced {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    transition: all 0.4s ease;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.stat-card.enhanced::before {
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

.stat-card.enhanced:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary);
}

.stat-card.enhanced:hover::before {
    opacity: 1;
}

.stat-card.enhanced.legal {
    border-color: rgba(139, 92, 246, 0.3);
}

.stat-card.enhanced.legal:hover {
    border-color: var(--purple);
}

.stat-card.enhanced.individual {
    border-color: rgba(20, 184, 166, 0.3);
}

.stat-card.enhanced.individual:hover {
    border-color: var(--teal);
}

.stat-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.stat-card.enhanced:hover .stat-glow {
    opacity: 1;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
    z-index: 2;
}

.stat-card.enhanced .stat-icon {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.stat-card.enhanced.legal .stat-icon {
    background: linear-gradient(135deg, var(--purple), #7c3aed);
}

.stat-card.enhanced.individual .stat-icon {
    background: linear-gradient(135deg, var(--teal), #0d9488);
}

.stat-info {
    flex: 1;
    z-index: 2;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark-text);
    display: block;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--dark-text-secondary);
    font-weight: 500;
}

.stat-trend {
    color: var(--dark-text-secondary);
    font-size: 16px;
    z-index: 2;
}

/* Enhanced Search Section */
.search-section.enhanced {
    margin-bottom: 30px;
}

.search-form.enhanced {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    padding: 0;
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.search-header {
    padding: 24px 24px 0;
}

.search-header h4 {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.search-header p {
    font-size: 14px;
    color: var(--dark-text-secondary);
    margin: 0;
}

.search-fields.enhanced {
    padding: 20px 24px 24px;
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 16px;
    align-items: end;
}

.search-field.enhanced {
    position: relative;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-field-icon, .filter-field-icon {
    position: absolute;
    left: 16px;
    color: var(--dark-text-secondary);
    z-index: 2;
}

.search-input.enhanced {
    width: 100%;
    padding: 14px 16px 14px 44px;
    background: var(--dark-bg);
    border: 2px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input.enhanced:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--dark-card);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.search-input.enhanced::placeholder {
    color: var(--dark-text-secondary);
}

.filter-select.enhanced {
    width: 100%;
    padding: 14px 16px 14px 44px;
    background: var(--dark-bg);
    border: 2px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 14px;
    cursor: pointer;
    appearance: none;
}

.filter-select.enhanced:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.search-actions.enhanced {
    display: flex;
    gap: 12px;
}

.search-btn.enhanced {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    border-radius: var(--radius);
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
    white-space: nowrap;
}

.search-btn.enhanced:hover {
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.reset-btn.enhanced {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 20px;
    background: transparent;
    border: 1px solid var(--dark-border);
    color: var(--dark-text-secondary);
    border-radius: var(--radius);
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    white-space: nowrap;
}

.reset-btn.enhanced:hover {
    background: var(--dark-hover);
    border-color: var(--error);
    color: var(--error);
    text-decoration: none;
}

/* Enhanced Alerts */
.alert.enhanced {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    border-radius: var(--radius-lg);
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid;
    position: relative;
    backdrop-filter: blur(10px);
}

.alert-success.enhanced {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--success);
}

.alert-error.enhanced {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
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
    margin-bottom: 4px;
}

.alert-message {
    font-size: 14px;
}

.alert-close {
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.3s ease;
    padding: 4px;
}

.alert-close:hover {
    opacity: 1;
}

/* Enhanced Counterparties Grid */
.counterparties-grid.enhanced {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

.counterparty-card.enhanced {
    position: relative;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    padding: 0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.card-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.counterparty-card.enhanced:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl), 0 20px 40px rgba(0, 0, 0, 0.4);
    border-color: var(--primary);
}

.counterparty-card.enhanced:hover .card-glow {
    opacity: 1;
}

.card-header.enhanced {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 24px;
    border-bottom: 1px solid var(--dark-border);
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), transparent);
}

.counterparty-avatar.enhanced {
    position: relative;
}

.avatar-circle.enhanced {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    font-weight: 700;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}

.counterparty-card.enhanced:hover .avatar-circle.enhanced {
    transform: scale(1.1) rotate(5deg);
}

.type-badge.enhanced {
    position: absolute;
    top: -6px;
    right: -6px;
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid;
}

.type-badge.enhanced.legal {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
    border-color: rgba(139, 92, 246, 0.3);
}

.type-badge.enhanced.individual {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
    border-color: rgba(16, 185, 129, 0.3);
}

.counterparty-info.enhanced {
    flex: 1;
}

.counterparty-name.enhanced {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 6px;
    line-height: 1.3;
}

.contact-person.enhanced {
    font-size: 14px;
    color: var(--dark-text-secondary);
    margin: 0;
    font-weight: 500;
}

.counterparty-actions.enhanced {
    display: flex;
    gap: 8px;
}

.action-btn.enhanced {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: var(--radius);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
    position: relative;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
}

.action-btn.enhanced:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn.enhanced:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--dark-card);
    color: var(--dark-text);
    padding: 6px 12px;
    border-radius: var(--radius);
    font-size: 12px;
    white-space: nowrap;
    border: 1px solid var(--dark-border);
    box-shadow: var(--shadow);
    z-index: 10;
}

.edit-btn.enhanced {
    background: linear-gradient(135deg, var(--warning), var(--warning-dark));
    color: white;
}

.edit-btn.enhanced:hover {
    background: linear-gradient(135deg, #fbbf24, var(--warning));
}

.delete-btn.enhanced {
    background: linear-gradient(135deg, var(--error), var(--error-dark));
    color: white;
}

.delete-btn.enhanced:hover {
    background: linear-gradient(135deg, #f87171, var(--error));
}

.card-body.enhanced {
    padding: 20px 24px 24px;
}

.contact-info.enhanced {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 20px;
}

.contact-item.enhanced {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius);
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.contact-item.enhanced:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--dark-border);
}

.contact-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    font-size: 14px;
    flex-shrink: 0;
}

.contact-content {
    flex: 1;
}

.contact-label {
    display: block;
    font-size: 11px;
    color: var(--dark-text-secondary);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.contact-value {
    display: block;
    font-size: 13px;
    color: var(--dark-text);
    font-weight: 500;
}

.meta-info.enhanced {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    padding-top: 16px;
    border-top: 1px solid var(--dark-border);
}

.meta-item.enhanced {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--dark-text-secondary);
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius);
}

.meta-item.enhanced i {
    color: var(--primary);
}

/* Enhanced Pagination */
.pagination-wrapper.enhanced {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    margin-top: 40px;
    padding: 24px;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow);
}

.pagination-info.enhanced {
    font-size: 14px;
    color: var(--dark-text-secondary);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pagination-info.enhanced strong {
    color: var(--dark-text);
}

.pagination-container.enhanced {
    display: flex;
    justify-content: center;
}

.pagination-container.enhanced .pagination {
    margin: 0;
}

.pagination-container.enhanced .page-item .page-link {
    background: var(--dark-bg);
    border: 1px solid var(--dark-border);
    color: var(--dark-text);
    padding: 10px 16px;
    border-radius: var(--radius);
    transition: all 0.3s ease;
    margin: 0 2px;
}

.pagination-container.enhanced .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    box-shadow: var(--shadow-md);
}

.pagination-container.enhanced .page-item:not(.active) .page-link:hover {
    background: var(--dark-hover);
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-1px);
}

/* Enhanced Empty State */
.empty-state.enhanced {
    position: relative;
    text-align: center;
    padding: 80px 40px;
    background: var(--dark-card);
    border-radius: var(--radius-xl);
    border: 1px solid var(--dark-border);
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.empty-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
}

.empty-content {
    position: relative;
    z-index: 2;
}

.empty-icon.enhanced {
    font-size: 80px;
    color: var(--primary);
    margin-bottom: 24px;
    opacity: 0.8;
}

.empty-state.enhanced h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 12px;
}

.empty-state.enhanced p {
    font-size: 16px;
    color: var(--dark-text-secondary);
    margin-bottom: 32px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.5;
}

.empty-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .counterparties-grid.enhanced {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
    
    .search-fields.enhanced {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}

@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }
    
    .admin-header.enhanced {
        text-align: center;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .header-actions {
        padding-right: 0;
        padding-bottom: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .gradient-text {
        font-size: 1.8rem;
    }
    
    .enhanced-stats {
        grid-template-columns: 1fr;
    }
    
    .counterparties-grid.enhanced {
        grid-template-columns: 1fr;
    }
    
    .card-header.enhanced {
        flex-direction: column;
        text-align: center;
    }
    
    .counterparty-actions.enhanced {
        justify-content: center;
        margin-top: 12px;
    }
    
    .empty-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .empty-actions .btn {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .header-text {
        text-align: center;
    }
    
    .header-meta {
        justify-content: center;
    }
    
    .search-fields.enhanced {
        padding: 16px;
    }
    
    .empty-state.enhanced {
        padding: 60px 20px;
    }
    
    .empty-icon.enhanced {
        font-size: 60px;
    }
    
    .empty-state.enhanced h3 {
        font-size: 20px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.counterparty-card.enhanced {
    animation: fadeInUp 0.6s ease-out;
}

.counterparty-card.enhanced:nth-child(1) { animation-delay: 0.1s; }
.counterparty-card.enhanced:nth-child(2) { animation-delay: 0.2s; }
.counterparty-card.enhanced:nth-child(3) { animation-delay: 0.3s; }
.counterparty-card.enhanced:nth-child(4) { animation-delay: 0.4s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close alert functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.alert-close')) {
            e.target.closest('.alert').remove();
        }
    });

    // Add animation to stats cards
    const statCards = document.querySelectorAll('.stat-card.enhanced');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });

    // Add input focus effects
    const inputs = document.querySelectorAll('.search-input.enhanced, .filter-select.enhanced');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>
@endsection