@extends('layouts.base')
@section('content')
<div class="documents-container">
    <!-- Header -->
    <div class="documents-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="header-text">
                <h1>Мои документы</h1>
                <p>Управляйте своими документами и файлами</p>
            </div>
        </div>
        <a href="{{ route('documents.create') }}" class="upload-btn">
            <i class="fas fa-plus"></i>
            Загрузить документ
        </a>
    </div>

    <!-- Search Form -->
    <div class="search-section">
        <form method="GET" action="{{ route('documents.index') }}" class="search-form">
            <div class="search-fields">
                <!-- Search by Name -->
                <div class="search-field">
                    <label for="search">Поиск по названию</label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}" 
                           placeholder="Введите название документа..."
                           class="search-input">
                </div>

                <!-- Search by Counterparty -->
                <div class="search-field">
                    <label for="counterparty">Контрагент</label>
                    <select name="counterparty" id="counterparty" class="search-select">
                        <option value="">Все контрагенты</option>
                        @foreach($counterparties as $counterparty)
                            <option value="{{ $counterparty->id }}" 
                                    {{ request('counterparty') == $counterparty->id ? 'selected' : '' }}>
                                {{ $counterparty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search by Importance -->
                <div class="search-field">
                    <label for="importance">Важность</label>
                    <select name="importance" id="importance" class="search-select">
                        <option value="">Любая важность</option>
                        @foreach($importances as $importance)
                            <option value="{{ $importance->id }}" 
                                    {{ request('importance') == $importance->id ? 'selected' : '' }}>
                                {{ $importance->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="search-actions">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        Найти
                    </button>
                    <a href="{{ route('documents.index') }}" class="reset-btn">
                        <i class="fas fa-times"></i>
                        Сбросить
                    </a>
                </div>
            </div>
        </form>

        <!-- Search Results Info -->
        @if(request()->hasAny(['search', 'counterparty', 'importance']))
        <div class="search-results-info">
            <div class="results-count">
                <i class="fas fa-filter"></i>
                Найдено документов: {{ $documents->total() }}
                @if(request('search'))
                    | По названию: "{{ request('search') }}"
                @endif
                @if(request('counterparty'))
                    | Контрагент: {{ $counterparties->find(request('counterparty'))->name ?? '' }}
                @endif
                @if(request('importance'))
                    | Важность: {{ $importances->find(request('importance'))->name ?? '' }}
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

    <!-- Documents List -->
    @if($documents->count() > 0)
        <div class="documents-grid">
            @foreach($documents as $document)
                <div class="document-card">
                    <div class="document-header">
                        <div class="document-icon">
                            {!! $document->getFileIcon() !!}
                        </div>
                        <div class="document-actions">
                            <a href="{{ route('documents.download', $document) }}" class="action-btn download-btn" title="Скачать">
                                <i class="fas fa-download"></i>
                                <span class="action-tooltip">Скачать</span>
                            </a>
                            <a href="{{ route('documents.show', $document) }}" class="action-btn view-btn" title="Просмотреть">
                                <i class="fas fa-eye"></i>
                                <span class="action-tooltip">Просмотреть</span>
                            </a>
                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" 
                                        onclick="return confirm('Удалить документ \"{{ $document->original_name }}\"?')"
                                        title="Удалить">
                                    <i class="fas fa-trash"></i>
                                    <span class="action-tooltip">Удалить</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="document-body">
                        <h4 class="document-title">{{ $document->original_name }}</h4>
                        
                        <div class="document-meta">
                            <div class="meta-item">
                                <i class="fas fa-tag"></i>
                                <span>{{ $document->documentType->name }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-weight-hanging"></i>
                                <span>{{ $document->getFileSizeFormatted() }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $document->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            @if($document->importance)
                                <div class="meta-item importance-{{ $document->importance->level }}">
                                    <i class="fas fa-{{ $document->importance->icon }}"></i>
                                    <span>{{ $document->importance->name }}</span>
                                </div>
                            @endif
                        </div>

                        @if($document->description)
                            <p class="document-description">{{ $document->description }}</p>
                        @endif

                        @if($document->counterparty)
                            <div class="counterparty-info">
                                <i class="fas fa-building"></i>
                                <div class="counterparty-details">
                                    <span class="counterparty-name">{{ $document->counterparty->name }}</span>
                                    <span class="counterparty-inn">ИНН: {{ $document->counterparty->inn }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($documents, 'links'))
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Показано {{ $documents->firstItem() }} - {{ $documents->lastItem() }} из {{ $documents->total() }} документов
            </div>
            <div class="pagination-container">
                {{ $documents->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            @if(request()->hasAny(['search', 'counterparty', 'importance']))
                <h3>Документы не найдены</h3>
                <p>Попробуйте изменить параметры поиска</p>
                <a href="{{ route('documents.index') }}" class="upload-btn">
                    <i class="fas fa-times"></i>
                    Сбросить фильтры
                </a>
            @else
                <h3>Документов пока нет</h3>
                <p>Начните работу с системой, загрузив свой первый документ</p>
                <a href="{{ route('documents.create') }}" class="upload-btn">
                    <i class="fas fa-plus"></i>
                    Загрузить первый документ
                </a>
            @endif
        </div>
    @endif
</div>

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
    --radius: 8px;
    --radius-lg: 12px;
}

.documents-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.documents-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: var(--dark-card);
    padding: 30px;
    border-radius: var(--radius-lg);
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

.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    border: none;
    border-radius: var(--radius);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
    color: white;
}

/* Search Section */
.search-section {
    margin-bottom: 30px;
}

.search-form {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    padding: 24px;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
}

.search-fields {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

.search-input, .search-select {
    padding: 12px 16px;
    background: var(--dark-bg);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 14px;
    transition: all 0.2s ease;
}

.search-input:focus, .search-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-input::placeholder {
    color: var(--dark-text-secondary);
}

.search-actions {
    display: flex;
    gap: 12px;
    align-items: end;
}

.search-btn, .reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    border-radius: var(--radius);
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.search-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.search-btn:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    color: white;
}

.reset-btn {
    background: transparent;
    border: 1px solid var(--dark-border);
    color: var(--dark-text-secondary);
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

/* Alert Styles */
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

/* Documents Grid */
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.document-card {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    padding: 24px;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.document-card::before {
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

.document-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.document-card:hover::before {
    opacity: 1;
}

.document-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.document-icon {
    font-size: 32px;
    color: var(--primary);
}

/* Улучшенные кнопки действий */
.document-actions {
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
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
}

.action-btn:hover .action-tooltip {
    opacity: 1;
    transform: translateY(0);
}

.download-btn {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
}

.download-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.view-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.view-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), #4338ca);
    color: white;
}

.delete-btn {
    background: linear-gradient(135deg, var(--error), #dc2626);
    color: white;
}

.delete-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
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

.document-body {
    flex: 1;
}

.document-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 12px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.document-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
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
}

.importance-1 { color: #10b981; }
.importance-2 { color: #f59e0b; }
.importance-3 { color: #ef4444; }

.document-description {
    font-size: 14px;
    color: var(--dark-text);
    line-height: 1.5;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.counterparty-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    background: rgba(30, 41, 59, 0.5);
    border-radius: var(--radius);
    border: 1px solid var(--dark-border);
}

.counterparty-info i {
    color: var(--primary);
    font-size: 14px;
}

.counterparty-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.counterparty-name {
    font-size: 12px;
    font-weight: 500;
    color: var(--dark-text);
}

.counterparty-inn {
    font-size: 10px;
    color: var(--dark-text-secondary);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 40px;
    background: var(--dark-card);
    border-radius: var(--radius-lg);
    border: 1px solid var(--dark-border);
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(10px);
}

.empty-icon {
    font-size: 80px;
    color: var(--dark-border);
    margin-bottom: 24px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 16px;
    color: var(--dark-text-secondary);
    margin-bottom: 32px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Улучшенная пагинация */
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

/* Стили для стандартной Bootstrap пагинации */
.pagination-container .pagination {
    margin: 0;
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-container .page-item .page-link {
    background: transparent;
    border: 1px solid var(--dark-border);
    color: var(--dark-text);
    padding: 8px 12px;
    border-radius: var(--radius);
    transition: all 0.2s ease;
    font-size: 13px;
    min-width: 36px;
    text-align: center;
    text-decoration: none;
}

.pagination-container .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    font-weight: 600;
}

.pagination-container .page-item:not(.active) .page-link:hover {
    background: var(--dark-hover);
    border-color: var(--primary);
    color: var(--primary);
    text-decoration: none;
}

.pagination-container .page-item.disabled .page-link {
    background: transparent;
    border-color: var(--dark-border);
    color: var(--dark-text-secondary);
    cursor: not-allowed;
    opacity: 0.5;
}

/* Стили для стрелок пагинации */
.pagination-container .page-item:first-child .page-link,
.pagination-container .page-item:last-child .page-link {
    padding: 8px 10px;
    font-weight: 600;
}

.pagination-container .page-item:first-child .page-link {
    margin-right: 8px;
}

.pagination-container .page-item:last-child .page-link {
    margin-left: 8px;
}

/* Кастомные стили для Bootstrap пагинации */
.pagination-container nav[role="navigation"] {
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-lg);
    padding: 12px 16px;
    box-shadow: var(--shadow);
}

.pagination-container .flex {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-container .flex-1 {
    flex: 1;
}

.pagination-container .hidden {
    display: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .documents-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .documents-container {
        padding: 16px;
    }
    
    .documents-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 24px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .search-fields {
        grid-template-columns: 1fr;
    }
    
    .search-actions {
        justify-content: stretch;
    }
    
    .search-btn, .reset-btn {
        flex: 1;
        justify-content: center;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .empty-state {
        padding: 60px 20px;
    }
    
    .empty-icon {
        font-size: 60px;
    }
    
    .pagination-wrapper {
        gap: 12px;
    }
    
    .pagination-container nav[role="navigation"] {
        padding: 10px 12px;
    }
    
    .pagination-container .page-item .page-link {
        padding: 6px 10px;
        font-size: 12px;
        min-width: 32px;
    }
}

@media (max-width: 480px) {
    .document-card {
        padding: 20px;
    }
    
    .document-header {
        flex-direction: column;
        gap: 12px;
    }
    
    .document-actions {
        align-self: flex-end;
    }
    
    .header-text h1 {
        font-size: 24px;
    }
    
    .empty-state h3 {
        font-size: 20px;
    }
    
    .action-btn {
        width: 38px;
        height: 38px;
    }
    
    .pagination-container .flex {
        gap: 4px;
    }
    
    .pagination-container .page-item .page-link {
        padding: 5px 8px;
        font-size: 11px;
        min-width: 30px;
    }
}

/* Scrollbar */
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

.d-inline {
    display: inline;
}
</style>
@endsection