@extends('layouts.base')

@section('content')
<div class="admin-container">
    <!-- Enhanced Header -->
    <div class="admin-header enhanced">
        <div class="header-background"></div>
        <div class="header-content">
            <div class="header-icon-wrapper">
                <div class="icon-circle">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
            <div class="header-text">
                <h1 class="gradient-text">Редактирование контрагента</h1>
                <p class="header-subtitle">Изменение данных контрагента в системе</p>
                <div class="header-meta">
                    <span class="meta-item">
                        <i class="fas fa-user"></i>
                        {{ $counterparty->name }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Создан: {{ $counterparty->created_at->format('d.m.Y') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.counterparties.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>

    <div class="form-container enhanced">
        <form action="{{ route('admin.counterparties.update', $counterparty->id) }}" method="POST" class="user-form enhanced">
            @csrf
            @method('PUT')
            
            <!-- Basic Information Card -->
            <div class="form-card enhanced">
                <div class="card-glow"></div>
                <div class="card-header enhanced">
                    <div class="card-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="card-title">
                        <h3>Основная информация</h3>
                        <p>Основные данные контрагента</p>
                    </div>
                </div>
                <div class="card-body enhanced">
                    <div class="form-grid enhanced">
                        <div class="form-group enhanced">
                            <label for="name" class="form-label enhanced">
                                <i class="fas fa-signature"></i>
                                Название / ФИО
                                <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $counterparty->name) }}" 
                                       class="form-input enhanced"
                                       placeholder="Введите название организации или ФИО"
                                       required>
                                <div class="input-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <div class="form-hint">
                                Полное наименование организации или ФИО физического лица
                            </div>
                        </div>
                        
                        <div class="form-group enhanced">
                            <label for="type" class="form-label enhanced">
                                <i class="fas fa-tag"></i>
                                Тип контрагента
                            </label>
                            <div class="input-wrapper">
                                <select id="type" name="type" class="form-input enhanced">
                                    <option value="">Выберите тип контрагента</option>
                                    <option value="legal" {{ old('type', $counterparty->type) == 'legal' ? 'selected' : '' }}>Юридическое лицо</option>
                                    <option value="individual" {{ old('type', $counterparty->type) == 'individual' ? 'selected' : '' }}>Физическое лицо</option>
                                </select>
                                <div class="input-icon">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                            <div class="form-hint">
                                Определяет тип контрагента для системного учета
                            </div>
                        </div>
                        
                        <div class="form-group enhanced">
                            <label for="contact_person" class="form-label enhanced">
                                <i class="fas fa-user-tie"></i>
                                Контактное лицо
                            </label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       id="contact_person" 
                                       name="contact_person" 
                                       value="{{ old('contact_person', $counterparty->contact_person) }}" 
                                       class="form-input enhanced"
                                       placeholder="ФИО контактного лица">
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="form-hint">
                                Лицо, уполномоченное представлять организацию
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information Card -->
            <div class="form-card enhanced">
                <div class="card-glow"></div>
                <div class="card-header enhanced">
                    <div class="card-icon">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <div class="card-title">
                        <h3>Контактная информация</h3>
                        <p>Способы связи с контрагентом</p>
                    </div>
                </div>
                <div class="card-body enhanced">
                    <div class="form-grid enhanced">
                        <div class="form-group enhanced">
                            <label for="phone" class="form-label enhanced">
                                <i class="fas fa-phone"></i>
                                Телефон
                            </label>
                            <div class="input-wrapper">
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $counterparty->phone) }}" 
                                       class="form-input enhanced"
                                       placeholder="+7 (999) 999-99-99">
                                <div class="input-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                            </div>
                            <div class="form-hint">
                                Контактный номер телефона с кодом страны
                            </div>
                        </div>
                        
                        <div class="form-group enhanced">
                            <label for="email" class="form-label enhanced">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <div class="input-wrapper">
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $counterparty->email) }}" 
                                       class="form-input enhanced"
                                       placeholder="email@example.com">
                                <div class="input-icon">
                                    <i class="fas fa-at"></i>
                                </div>
                            </div>
                            <div class="form-hint">
                                Электронная почта для официальной переписки
                            </div>
                        </div>
                        
                        <div class="form-group enhanced full-width">
                            <label for="address" class="form-label enhanced">
                                <i class="fas fa-map-marker-alt"></i>
                                Адрес
                            </label>
                            <div class="input-wrapper">
                                <textarea id="address" 
                                          name="address" 
                                          class="form-input enhanced"
                                          placeholder="Введите полный юридический или фактический адрес"
                                          rows="4">{{ old('address', $counterparty->address) }}</textarea>
                                <div class="input-icon textarea-icon">
                                    <i class="fas fa-location-dot"></i>
                                </div>
                            </div>
                            <div class="form-hint">
                                Полный почтовый адрес для корреспонденции и документов
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions enhanced">
                <button type="submit" class="btn btn-primary btn-large enhanced">
                    <i class="fas fa-save"></i>
                    <span>Обновить контрагента</span>
                    <div class="btn-shine"></div>
                </button>
                <a href="{{ route('admin.counterparties.index') }}" class="btn btn-secondary enhanced">
                    <i class="fas fa-times"></i>
                    Отмена
                </a>
            </div>
        </form>
    </div>
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

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s;
}

.btn:hover .btn-shine {
    left: 100%;
}

/* Enhanced Form Container */
.form-container.enhanced {
    max-width: 900px;
    margin: 0 auto;
}

/* Enhanced Form Cards */
.form-card.enhanced {
    position: relative;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
    backdrop-filter: blur(10px);
    overflow: hidden;
    transition: all 0.4s ease;
}

.form-card.enhanced:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary);
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

.form-card.enhanced:hover .card-glow {
    opacity: 1;
}

.card-header.enhanced {
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
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    box-shadow: var(--shadow-md);
}

.card-title h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--dark-text);
}

.card-title p {
    margin: 4px 0 0 0;
    font-size: 0.9rem;
    color: var(--dark-text-secondary);
}

.card-body.enhanced {
    padding: 24px;
}

/* Enhanced Form Grid */
.form-grid.enhanced {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.form-group.enhanced.full-width {
    grid-column: 1 / -1;
}

/* Enhanced Form Elements */
.form-group.enhanced {
    position: relative;
}

.form-label.enhanced {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 8px;
    font-size: 14px;
}

.form-label.enhanced i {
    color: var(--primary);
    width: 16px;
    text-align: center;
}

.required {
    color: var(--error);
    font-weight: 700;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.form-input.enhanced {
    width: 100%;
    padding: 14px 16px 14px 48px;
    background: var(--dark-bg);
    border: 2px solid var(--dark-border);
    border-radius: var(--radius);
    color: var(--dark-text);
    font-size: 15px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-input.enhanced:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--dark-card);
    box-shadow: 0 0 0 4px var(--primary-glow);
    transform: translateY(-1px);
}

.form-input.enhanced::placeholder {
    color: var(--dark-text-secondary);
    opacity: 0.7;
}

/* Textarea specific styles */
.form-input.enhanced[rows] {
    padding: 14px 16px 14px 48px;
    resize: vertical;
    min-height: 100px;
}

.textarea-icon {
    align-items: flex-start;
    margin-top: 14px;
}

/* Select specific styles */
.form-input.enhanced[multiple] {
    padding: 12px 16px;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: var(--dark-text-secondary);
    transition: all 0.3s ease;
    pointer-events: none;
}

.form-input.enhanced:focus + .input-icon {
    color: var(--primary);
}

/* Select dropdown icon */
.form-input.enhanced[type="select"] + .input-icon {
    pointer-events: all;
}

.form-hint {
    font-size: 12px;
    color: var(--dark-text-secondary);
    margin-top: 6px;
    line-height: 1.4;
}

/* Enhanced Form Actions */
.form-actions.enhanced {
    display: flex;
    gap: 16px;
    padding: 24px;
    background: var(--dark-card);
    border: 1px solid var(--dark-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow);
    margin-top: 24px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .form-grid.enhanced {
        grid-template-columns: 1fr;
        gap: 20px;
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
        padding: 20px;
    }
    
    .header-actions {
        padding-right: 0;
        padding-bottom: 20px;
    }
    
    .gradient-text {
        font-size: 1.8rem;
    }
    
    .form-actions.enhanced {
        flex-direction: column;
    }
    
    .form-actions.enhanced .btn {
        width: 100%;
        justify-content: center;
    }
    
    .card-header.enhanced {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .card-body.enhanced {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .header-text {
        text-align: center;
    }
    
    .header-meta {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .form-container.enhanced {
        margin: 0 -16px;
    }
    
    .form-card.enhanced {
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .form-actions.enhanced {
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
}

/* Animation */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card.enhanced {
    animation: slideInUp 0.6s ease-out;
}

.form-card.enhanced:nth-child(1) { animation-delay: 0.1s; }
.form-card.enhanced:nth-child(2) { animation-delay: 0.2s; }

/* Custom scrollbar for textarea */
.form-input.enhanced::-webkit-scrollbar {
    width: 6px;
}

.form-input.enhanced::-webkit-scrollbar-track {
    background: var(--dark-bg);
    border-radius: 3px;
}

.form-input.enhanced::-webkit-scrollbar-thumb {
    background: var(--dark-border);
    border-radius: 3px;
}

.form-input.enhanced::-webkit-scrollbar-thumb:hover {
    background: var(--primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced input interactions
    const inputs = document.querySelectorAll('.form-input.enhanced');
    inputs.forEach(input => {
        const wrapper = input.closest('.input-wrapper');
        
        input.addEventListener('focus', function() {
            wrapper.classList.add('focused');
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            wrapper.classList.remove('focused');
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Add floating label effect
        if (input.value) {
            input.parentElement.classList.add('has-value');
        }
        
        input.addEventListener('input', function() {
            if (this.value) {
                this.parentElement.classList.add('has-value');
            } else {
                this.parentElement.classList.remove('has-value');
            }
        });
    });

    // Form submission loading state
    const form = document.querySelector('.user-form.enhanced');
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
        submitBtn.disabled = true;
        
        // Revert after 3 seconds if still processing (fallback)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }
        }, 3000);
    });

    // Add character counter for textarea
    const textarea = document.getElementById('address');
    if (textarea) {
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.fontSize = '12px';
        counter.style.color = 'var(--dark-text-secondary)';
        counter.style.textAlign = 'right';
        counter.style.marginTop = '4px';
        
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = textarea.value.length;
            counter.textContent = `${length} символов`;
            
            if (length > 200) {
                counter.style.color = 'var(--warning)';
            } else if (length > 500) {
                counter.style.color = 'var(--error)';
            } else {
                counter.style.color = 'var(--dark-text-secondary)';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Add smooth animations to form cards
    const formCards = document.querySelectorAll('.form-card.enhanced');
    formCards.forEach((card, index) => {
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