@extends('layouts.base')
@section('content')
<div class="document-upload-container">
    <div class="upload-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="header-text">
                <h1>Загрузка документов</h1>
                <p>Загрузите договоры, счета, приказы и другие документы</p>
            </div>
        </div>
    </div>

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

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Ошибки валидации:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Отладка: проверка данных -->
    <div style="display: none;">
        Document Types Count: {{ $documentTypes->count() }}<br>
        @foreach($documentTypes as $type)
            Type: {{ $type->name }}, Category: {{ $type->category ?? 'none' }}<br>
        @endforeach
    </div>

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        
        <div class="upload-card">
            <!-- Upload Area -->
            <div class="upload-section">
                <div class="upload-area" id="dropZone">
                    <div class="upload-content">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h3>Перетащите файлы сюда</h3>
                        <p>или</p>
                        <button type="button" class="browse-btn" id="browseBtn">
                            <i class="fas fa-folder-open"></i>
                            Выберите файлы
                        </button>
                        <input type="file" id="fileInput" name="documents[]" multiple style="display: none;">
                    </div>
                    <div class="upload-footer">
                        <p class="file-types">
                            <i class="fas fa-info-circle"></i>
                            Поддерживаются все типы файлов (макс. 10MB)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Grid -->
            <div class="form-grid">
                <!-- Document Type Selection -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-file-alt"></i>
                        <h3>Тип документа</h3>
                        <span class="required">*</span>
                    </div>
                    
                    <div class="checkbox-group">
                        @php
                            $mainTypes = $documentTypes->where('category', 'main');
                            $additionalTypes = $documentTypes->where('category', 'additional');
                            $otherTypes = $documentTypes->whereNotIn('category', ['main', 'additional']);
                        @endphp

                        @if($mainTypes->count() > 0)
                        <div class="checkbox-category">
                            <h4 class="category-title">
                                <i class="fas fa-star"></i>
                                Основные документы
                            </h4>
                            <div class="checkbox-grid">
                                @foreach($mainTypes as $type)
                                <label class="checkbox-item document-type-item">
                                    <input type="radio" name="document_type" value="{{ $type->id }}" 
                                           {{ old('document_type') == $type->id ? 'checked' : '' }}
                                           class="document-type-radio" 
                                           data-description="{{ $type->description }}"
                                           data-icon="{{ $type->icon ?? 'file' }}"
                                           required>
                                    <span class="custom-radio"></span>
                                    <div class="checkbox-content">
                                        <i class="fas fa-{{ $type->icon ?? 'file' }}"></i>
                                        <span class="checkbox-label">{{ $type->name }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($additionalTypes->count() > 0)
                        <div class="checkbox-category">
                            <h4 class="category-title">
                                <i class="fas fa-folder"></i>
                                Дополнительные документы
                            </h4>
                            <div class="checkbox-grid">
                                @foreach($additionalTypes as $type)
                                <label class="checkbox-item document-type-item">
                                    <input type="radio" name="document_type" value="{{ $type->id }}" 
                                           {{ old('document_type') == $type->id ? 'checked' : '' }}
                                           class="document-type-radio" 
                                           data-description="{{ $type->description }}"
                                           data-icon="{{ $type->icon ?? 'file' }}"
                                           required>
                                    <span class="custom-radio"></span>
                                    <div class="checkbox-content">
                                        <i class="fas fa-{{ $type->icon ?? 'file' }}"></i>
                                        <span class="checkbox-label">{{ $type->name }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($otherTypes->count() > 0)
                        <div class="checkbox-category">
                            <h4 class="category-title">
                                <i class="fas fa-file"></i>
                                Другие документы
                            </h4>
                            <div class="checkbox-grid">
                                @foreach($otherTypes as $type)
                                <label class="checkbox-item document-type-item">
                                    <input type="radio" name="document_type" value="{{ $type->id }}" 
                                           {{ old('document_type') == $type->id ? 'checked' : '' }}
                                           class="document-type-radio" 
                                           data-description="{{ $type->description }}"
                                           data-icon="{{ $type->icon ?? 'file' }}"
                                           required>
                                    <span class="custom-radio"></span>
                                    <div class="checkbox-content">
                                        <i class="fas fa-{{ $type->icon ?? 'file' }}"></i>
                                        <span class="checkbox-label">{{ $type->name }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($documentTypes->count() === 0)
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            Нет доступных типов документов. Обратитесь к администратору.
                        </div>
                        @endif
                    </div>
                    
                    <div class="form-hint" id="type-description">
                        <div class="type-description-default">
                            <i class="fas fa-lightbulb"></i>
                            Выберите тип документа для просмотра описания
                        </div>
                        @foreach($documentTypes as $type)
                        <div class="type-description-item" data-type="{{ $type->id }}" style="display: none;">
                            <i class="fas fa-{{ $type->icon ?? 'file' }}"></i>
                            <div class="description-content">
                                <strong>{{ $type->name }}:</strong> {{ $type->description }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('document_type')
                        <span class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Document Details -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Основная информация</h3>
                    </div>
                    
                    <div class="input-group">
                        <label for="document_name" class="input-label">
                            Название документа
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" id="document_name" name="document_name" 
                                   value="{{ old('document_name') }}" 
                                   placeholder="Введите название документа" 
                                   class="modern-input"
                                   required>
                            <i class="fas fa-heading input-icon"></i>
                        </div>
                        @error('document_name')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="document_date" class="input-label">
                            Дата документа
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="date" id="document_date" name="document_date" 
                                   value="{{ old('document_date', date('Y-m-d')) }}" 
                                   class="modern-input"
                                   required>
                            <i class="fas fa-calendar-alt input-icon"></i>
                        </div>
                        @error('document_date')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Importance Selection -->
                    <div class="input-group">
                        <label class="input-label">Важность документа</label>
                        <div class="importance-group">
                            @foreach($importanceLevels as $importance)
                            <label class="importance-item">
                                <input type="radio" name="importance" value="{{ $importance->id }}" 
                                       {{ old('importance') == $importance->id ? 'checked' : '' }}
                                       class="importance-radio" 
                                       data-color="{{ $importance->color }}"
                                       data-description="{{ $importance->description }}"
                                       data-icon="{{ $importance->icon }}">
                                <div class="importance-card" style="border-color: {{ $importance->color }}20; background: {{ $importance->color }}08;">
                                    <div class="importance-icon" style="color: {{ $importance->color }};">
                                        <i class="fas fa-{{ $importance->icon }}"></i>
                                    </div>
                                    <span class="importance-label">{{ $importance->name }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Counterparty Selection -->
                    <div class="input-group">
                        <label class="input-label">Контрагент</label>
                        <div class="counterparty-search-wrapper">
                            <div class="search-input-wrapper">
                                <input type="text" id="counterpartySearch" placeholder="Поиск контрагента..." class="modern-input search-input">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </div>
                        <div class="counterparty-list">
                            @foreach($counterparties as $counterparty)
                            <label class="counterparty-item">
                                <input type="radio" name="counterparty" value="{{ $counterparty->id }}" 
                                       {{ old('counterparty') == $counterparty->id ? 'checked' : '' }}
                                       class="counterparty-radio">
                                <div class="counterparty-card">
                                    <div class="counterparty-info">
                                        <div class="counterparty-name">{{ $counterparty->name }}</div>
                                        <div class="counterparty-details">
                                            <span class="counterparty-inn">ИНН: {{ $counterparty->inn }}</span>
                                            <span class="counterparty-type-badge badge-{{ $counterparty->type }}">
                                                {{ $counterparty->type == 'individual' ? 'Физ. лицо' : 'Юр. лицо' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="radio-indicator"></div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-align-left"></i>
                    <h3>Описание документа</h3>
                </div>
                <div class="input-group">
                    <div class="textarea-wrapper">
                        <textarea id="description" name="description" 
                                  placeholder="Добавьте описание документа..." 
                                  rows="3"
                                  class="modern-textarea">{{ old('description') }}</textarea>
                        <div class="textarea-footer">
                            <div class="char-counter">
                                <span id="charCount">0</span>/500 символов
                            </div>
                        </div>
                    </div>
                    @error('description')
                        <span class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Supported Formats -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-file-code"></i>
                    <h3>Поддерживаемые форматы</h3>
                </div>
                <div class="formats-showcase">
                    @foreach([
                        ['icon' => 'file-pdf', 'name' => 'PDF', 'color' => '#e74c3c'],
                        ['icon' => 'file-word', 'name' => 'DOC/DOCX', 'color' => '#2b579a'],
                        ['icon' => 'file-excel', 'name' => 'XLS/XLSX', 'color' => '#217346'],
                        ['icon' => 'file-image', 'name' => 'JPG/PNG', 'color' => '#e67e22'],
                        ['icon' => 'file-alt', 'name' => 'TXT', 'color' => '#7f8c8d'],
                        ['icon' => 'file-archive', 'name' => 'ZIP/RAR', 'color' => '#f59e0b'],
                        ['icon' => 'file-video', 'name' => 'MP4/AVI', 'color' => '#8b5cf6'],
                        ['icon' => 'file-audio', 'name' => 'MP3/WAV', 'color' => '#06b6d4']
                    ] as $format)
                    <div class="format-card">
                        <div class="format-icon" style="color: {{ $format['color'] }};">
                            <i class="fas fa-{{ $format['icon'] }}"></i>
                        </div>
                        <span class="format-name">{{ $format['name'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- File Preview -->
            <div class="file-preview" id="filePreview">
                <div class="section-header">
                    <i class="fas fa-file"></i>
                    <h3>Выбранные файлы</h3>
                </div>
                <div class="file-list" id="fileList">
                    <!-- Files will be displayed here -->
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('documents.index') }}" class="cancel-btn">
                    <i class="fas fa-arrow-left"></i>
                    Назад к списку
                </a>
                <button type="submit" class="upload-btn" id="uploadBtn" disabled>
                    <i class="fas fa-upload"></i>
                    <span class="btn-text">Загрузить документы</span>
                    <div class="btn-loader" style="display: none;">
                        <div class="loader"></div>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const browseBtn = document.getElementById('browseBtn');
    const fileList = document.getElementById('fileList');
    const uploadBtn = document.getElementById('uploadBtn');
    const descriptionTextarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const counterpartySearch = document.getElementById('counterpartySearch');
    let files = [];

    browseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.click();
    });

    // Обработка выбора типа документа
    document.querySelectorAll('.document-type-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const typeId = this.value;
                const description = this.getAttribute('data-description');
                const icon = this.getAttribute('data-icon');
                const typeName = this.closest('.checkbox-item').querySelector('.checkbox-label').textContent;
                
                updateDocumentTypeDisplay(typeId, typeName, description, icon);
                
                // Автозаполнение названия документа, если оно пустое
                const docNameInput = document.getElementById('document_name');
                if (!docNameInput.value.trim()) {
                    const currentDate = new Date().toLocaleDateString('ru-RU');
                    docNameInput.value = `${typeName} от ${currentDate}`;
                }
            }
        });
    });

    // Функция обновления отображения типа документа
    function updateDocumentTypeDisplay(typeId, typeName, description, icon) {
        // Скрываем все описания
        document.querySelectorAll('.type-description-item').forEach(item => {
            item.style.display = 'none';
        });
        document.querySelector('.type-description-default').style.display = 'none';
        
        // Показываем описание выбранного типа
        if (typeId) {
            const descriptionItem = document.querySelector(`.type-description-item[data-type="${typeId}"]`);
            if (descriptionItem) {
                descriptionItem.style.display = 'flex';
            }
        }
    }

    // Обработка выбора важности
    document.querySelectorAll('.importance-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const importanceId = this.value;
                const color = this.getAttribute('data-color');
                const description = this.getAttribute('data-description');
                const icon = this.getAttribute('data-icon');
                
                // Можно добавить визуализацию выбранной важности
                console.log('Выбрана важность:', importanceId, color, description);
            }
        });
    });

    // Поиск контрагентов
    counterpartySearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const counterpartyItems = document.querySelectorAll('.counterparty-item');
        
        counterpartyItems.forEach(item => {
            const counterpartyName = item.querySelector('.counterparty-name').textContent.toLowerCase();
            const counterpartyInn = item.querySelector('.counterparty-inn').textContent.toLowerCase();
            
            if (counterpartyName.includes(searchTerm) || counterpartyInn.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Счетчик символов для описания
    descriptionTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 500) {
            charCount.style.color = '#ef4444';
        } else if (length > 400) {
            charCount.style.color = '#f59e0b';
        } else {
            charCount.style.color = '#10b981';
        }
    });

    // Инициализация при загрузке
    const checkedDocumentType = document.querySelector('.document-type-radio:checked');
    if (checkedDocumentType) {
        checkedDocumentType.dispatchEvent(new Event('change'));
    } else {
        // Автовыбор первого типа документа
        const firstDocumentType = document.querySelector('.document-type-radio');
        if (firstDocumentType) {
            firstDocumentType.checked = true;
            firstDocumentType.dispatchEvent(new Event('change'));
        }
    }
    
    descriptionTextarea.dispatchEvent(new Event('input'));

    // Обработка выбора файлов через диалог
    fileInput.addEventListener('change', handleFileSelect);
    
    // Обработка перетаскивания файлов
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('highlight');
    }
    
    function unhighlight() {
        dropZone.classList.remove('highlight');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const droppedFiles = dt.files;
        handleFiles(droppedFiles);
    }
    
    function handleFileSelect(e) {
        const selectedFiles = e.target.files;
        handleFiles(selectedFiles);
    }
    
    function handleFiles(newFiles) {
        for (let i = 0; i < newFiles.length; i++) {
            // Проверяем размер файла (10MB)
            if (newFiles[i].size > 10 * 1024 * 1024) {
                alert('Файл ' + newFiles[i].name + ' слишком большой. Максимальный размер: 10MB');
                continue;
            }
            files.push(newFiles[i]);
        }
        updateFileInput();
        updateFileList();
        updateUploadButton();
    }
    
    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }
    
    function updateFileList() {
        fileList.innerHTML = '';
        
        if (files.length === 0) {
            document.getElementById('filePreview').style.display = 'none';
            return;
        }
        
        document.getElementById('filePreview').style.display = 'block';
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            
            const fileIcon = getFileIcon(file.type, file.name);
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon">${fileIcon}</div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize}</div>
                    </div>
                </div>
                <button type="button" class="remove-file" data-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
        });
        
        document.querySelectorAll('.remove-file').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                files.splice(index, 1);
                updateFileInput();
                updateFileList();
                updateUploadButton();
            });
        });
    }
    
    function getFileIcon(fileType, fileName) {
        const extension = fileName.split('.').pop().toLowerCase();
        
        // PDF
        if (fileType.includes('pdf') || extension === 'pdf') 
            return '<i class="fas fa-file-pdf"></i>';
        
        // Word documents
        if (fileType.includes('word') || fileType.includes('document') || 
            ['doc', 'docx', 'odt'].includes(extension)) 
            return '<i class="fas fa-file-word"></i>';
        
        // Excel
        if (fileType.includes('excel') || fileType.includes('sheet') || 
            ['xls', 'xlsx', 'csv', 'ods'].includes(extension)) 
            return '<i class="fas fa-file-excel"></i>';
        
        // Images
        if (fileType.includes('image') || 
            ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'].includes(extension)) 
            return '<i class="fas fa-file-image"></i>';
        
        // Archives
        if (['zip', 'rar', '7z', 'tar', 'gz'].includes(extension)) 
            return '<i class="fas fa-file-archive"></i>';
        
        // Video
        if (fileType.includes('video') || 
            ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'].includes(extension)) 
            return '<i class="fas fa-file-video"></i>';
        
        // Audio
        if (fileType.includes('audio') || 
            ['mp3', 'wav', 'ogg', 'flac', 'aac'].includes(extension)) 
            return '<i class="fas fa-file-audio"></i>';
        
        // Text
        if (fileType.includes('text') || extension === 'txt') 
            return '<i class="fas fa-file-alt"></i>';
        
        // PowerPoint
        if (['ppt', 'pptx', 'odp'].includes(extension)) 
            return '<i class="fas fa-file-powerpoint"></i>';
        
        // Default file icon
        return '<i class="fas fa-file"></i>';
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function updateUploadButton() {
        uploadBtn.disabled = files.length === 0;
    }

    // Валидация формы перед отправкой
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const documentType = document.querySelector('input[name="document_type"]:checked');
        const documentName = document.getElementById('document_name').value.trim();
        const documentDate = document.getElementById('document_date').value;
        
        if (!documentType) {
            e.preventDefault();
            alert('Пожалуйста, выберите тип документа');
            return;
        }
        
        if (!documentName) {
            e.preventDefault();
            alert('Пожалуйста, введите название документа');
            return;
        }
        
        if (!documentDate) {
            e.preventDefault();
            alert('Пожалуйста, выберите дату документа');
            return;
        }
        
        if (files.length === 0) {
            e.preventDefault();
            alert('Пожалуйста, выберите файлы для загрузки');
            return;
        }
        
        // Показываем лоадер
        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.querySelector('.btn-text').style.display = 'none';
        uploadBtn.querySelector('.btn-loader').style.display = 'block';
        uploadBtn.disabled = true;
    });
});
</script>

<style>
/* Стили остаются без изменений, они уже правильные */
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

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    min-height: 100vh;
    color: var(--dark-text);
}

.document-upload-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.upload-header {
    margin-bottom: 40px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
    background: var(--dark-card);
    padding: 30px;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--dark-border);
    backdrop-filter: blur(10px);
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

/* Upload Card */
.upload-card {
    background: var(--dark-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid var(--dark-border);
    backdrop-filter: blur(10px);
}

/* Upload Section */
.upload-section {
    padding: 40px;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
    border-bottom: 1px solid var(--dark-border);
}

.upload-area {
    border: 2px dashed var(--primary);
    border-radius: var(--radius-lg);
    padding: 60px 40px;
    text-align: center;
    transition: all 0.3s ease;
    background: rgba(30, 41, 59, 0.5);
    position: relative;
    backdrop-filter: blur(10px);
}

.upload-area.highlight {
    background: rgba(99, 102, 241, 0.1);
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.upload-content .upload-icon {
    font-size: 64px;
    color: var(--primary);
    margin-bottom: 20px;
    opacity: 0.8;
}

.upload-content h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 8px;
}

.upload-content p {
    font-size: 16px;
    color: var(--dark-text-secondary);
    margin-bottom: 20px;
}

.browse-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: var(--radius);
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--shadow);
    position: relative;
    z-index: 2;
}

.browse-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
}

.upload-footer {
    margin-top: 20px;
}

.file-types {
    font-size: 14px;
    color: var(--dark-text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border-bottom: 1px solid var(--dark-border);
}

.form-section {
    padding: 32px;
    border-right: 1px solid var(--dark-border);
}

.form-section:last-child {
    border-right: none;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--dark-border);
}

.section-header i {
    color: var(--primary);
    font-size: 20px;
}

.section-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-text);
}

.required {
    color: var(--error);
    font-weight: 600;
}

/* Checkbox Styles */
.checkbox-category {
    margin-bottom: 24px;
}

.category-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 12px;
}

.category-title i {
    color: var(--warning);
}

.checkbox-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.2s ease;
    background: rgba(30, 41, 59, 0.5);
    position: relative;
}

.checkbox-item:hover {
    border-color: var(--primary);
    background: var(--dark-hover);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.document-type-item input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.custom-radio {
    position: relative;
    height: 18px;
    width: 18px;
    background-color: var(--dark-card);
    border: 2px solid var(--dark-border);
    border-radius: 50%;
    margin-right: 12px;
    transition: all 0.2s ease;
}

.checkbox-item input[type="radio"]:checked ~ .custom-radio {
    background-color: var(--primary);
    border-color: var(--primary);
}

.checkbox-item input[type="radio"]:checked ~ .custom-radio::after {
    content: "";
    position: absolute;
    top: 4px;
    left: 4px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: white;
}

.checkbox-content {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
}

.checkbox-content i {
    color: var(--dark-text-secondary);
    font-size: 14px;
    width: 16px;
}

.checkbox-label {
    font-size: 14px;
    color: var(--dark-text);
    font-weight: 500;
}

.checkbox-item input[type="radio"]:checked ~ .checkbox-content .checkbox-label {
    color: var(--primary);
    font-weight: 600;
}

.checkbox-item input[type="radio"]:checked ~ .checkbox-content i {
    color: var(--primary);
}

/* Form Hint */
.form-hint {
    margin-top: 16px;
}

.type-description-default,
.type-description-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: rgba(30, 41, 59, 0.5);
    border-radius: var(--radius);
    border-left: 4px solid var(--primary);
    font-size: 14px;
    line-height: 1.5;
    color: var(--dark-text);
}

.type-description-default i,
.type-description-item i {
    color: var(--primary);
    margin-top: 2px;
    font-size: 16px;
}

.description-content {
    flex: 1;
}

.description-content strong {
    color: var(--dark-text);
}

/* Input Styles */
.input-group {
    margin-bottom: 20px;
}

.input-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-text);
    margin-bottom: 8px;
}

.input-wrapper {
    position: relative;
}

.modern-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    font-size: 14px;
    transition: all 0.2s ease;
    background: var(--dark-card);
    color: var(--dark-text);
    border: 1px solid var(--dark-border);
}

.modern-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.modern-input::placeholder {
    color: var(--dark-text-secondary);
}

.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dark-text-secondary);
    pointer-events: none;
}

/* Importance Styles */
.importance-group {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
}

.importance-item {
    cursor: pointer;
}

.importance-item input[type="radio"] {
    display: none;
}

.importance-card {
    padding: 16px;
    border: 2px solid transparent;
    border-radius: var(--radius);
    text-align: center;
    transition: all 0.2s ease;
    cursor: pointer;
    background: rgba(30, 41, 59, 0.5);
}

.importance-item input[type="radio"]:checked ~ .importance-card {
    border-color: currentColor;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    background: rgba(30, 41, 59, 0.8);
}

.importance-icon {
    font-size: 20px;
    margin-bottom: 8px;
}

.importance-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--dark-text);
}

/* Counterparty Styles */
.counterparty-search-wrapper {
    margin-bottom: 12px;
}

.search-input-wrapper {
    position: relative;
}

.search-input {
    padding-left: 40px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dark-text-secondary);
}

.counterparty-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    padding: 8px;
    background: rgba(30, 41, 59, 0.5);
}

.counterparty-item {
    display: block;
    margin-bottom: 8px;
}

.counterparty-item:last-child {
    margin-bottom: 0;
}

.counterparty-item input[type="radio"] {
    display: none;
}

.counterparty-card {
    display: flex;
    align-items: center;
    justify-content: between;
    padding: 12px;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    transition: all 0.2s ease;
    cursor: pointer;
    background: rgba(30, 41, 59, 0.3);
}

.counterparty-item input[type="radio"]:checked ~ .counterparty-card {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

.counterparty-card:hover {
    border-color: var(--primary);
    background: var(--dark-hover);
}

.counterparty-info {
    flex: 1;
}

.counterparty-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.counterparty-details {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}

.counterparty-inn {
    color: var(--dark-text-secondary);
}

.counterparty-type-badge {
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 500;
}

.badge-company {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
}

.badge-individual {
    background: rgba(168, 85, 247, 0.2);
    color: #a855f7;
}

.radio-indicator {
    width: 16px;
    height: 16px;
    border: 2px solid var(--dark-border);
    border-radius: 50%;
    position: relative;
}

.counterparty-item input[type="radio"]:checked ~ .counterparty-card .radio-indicator {
    border-color: var(--primary);
    background: var(--primary);
}

.counterparty-item input[type="radio"]:checked ~ .counterparty-card .radio-indicator::after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: white;
}

/* Textarea Styles */
.textarea-wrapper {
    position: relative;
}

.modern-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    transition: all 0.2s ease;
    background: var(--dark-card);
    color: var(--dark-text);
    border: 1px solid var(--dark-border);
}

.modern-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.modern-textarea::placeholder {
    color: var(--dark-text-secondary);
}

.textarea-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
}

.char-counter {
    font-size: 12px;
    color: var(--dark-text-secondary);
}

/* Formats Showcase */
.formats-showcase {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 12px;
}

.format-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 12px;
    background: rgba(30, 41, 59, 0.5);
    border-radius: var(--radius);
    border: 1px solid var(--dark-border);
    transition: all 0.2s ease;
    text-align: center;
}

.format-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
    border-color: var(--primary);
    background: var(--dark-hover);
}

.format-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.format-name {
    font-size: 12px;
    font-weight: 500;
    color: var(--dark-text);
}

/* File Preview */
.file-preview {
    padding: 32px;
    border-top: 1px solid var(--dark-border);
    display: none;
}

.file-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    transition: all 0.2s ease;
}

.file-item:hover {
    background: var(--dark-hover);
    border-color: var(--primary);
}

.file-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-icon {
    font-size: 20px;
    color: var(--primary);
}

.file-details {
    display: flex;
    flex-direction: column;
}

.file-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-text);
}

.file-size {
    font-size: 12px;
    color: var(--dark-text-secondary);
}

.remove-file {
    background: none;
    border: none;
    color: var(--error);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.remove-file:hover {
    background: rgba(239, 68, 68, 0.1);
}

/* Form Actions */
.form-actions {
    padding: 24px 32px;
    background: rgba(30, 41, 59, 0.8);
    border-top: 1px solid var(--dark-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cancel-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: rgba(30, 41, 59, 0.5);
    color: var(--dark-text);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.cancel-btn:hover {
    background: var(--dark-hover);
    border-color: var(--primary);
    text-decoration: none;
    color: var(--dark-text);
}

.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.upload-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.upload-btn:disabled {
    background: var(--dark-border);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-loader {
    display: none;
}

.loader {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Error Messages */
.error-message {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--error);
    margin-top: 4px;
    font-weight: 500;
}

/* Scrollbar Styles */
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

/* Responsive Design */
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-section {
        border-right: none;
        border-bottom: 1px solid var(--dark-border);
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
}

@media (max-width: 768px) {
    .document-upload-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
        padding: 24px;
    }
    
    .upload-section {
        padding: 24px;
    }
    
    .upload-area {
        padding: 40px 20px;
    }
    
    .checkbox-grid {
        grid-template-columns: 1fr;
    }
    
    .importance-group {
        grid-template-columns: 1fr;
    }
    
    .formats-showcase {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
        gap: 12px;
    }
    
    .cancel-btn, .upload-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .formats-showcase {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .header-text h1 {
        font-size: 24px;
    }
    
    .upload-content h3 {
        font-size: 20px;
    }
}
</style>
@endsection