<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Importance;
use App\Models\Counterparty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Список документов пользователя с поиском и фильтрами
     */
    public function index(Request $request)
    {
        $query = Document::with(['documentType', 'importance', 'counterparty'])
            ->where('user_id', Auth::id());

        // Поиск по названию документа
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('original_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Фильтр по контрагенту
        if ($request->has('counterparty') && $request->counterparty) {
            $query->where('counterparty_id', $request->counterparty);
        }

        // Фильтр по важности
        if ($request->has('importance') && $request->importance) {
            $query->where('importance_id', $request->importance);
        }

        // Сортировка
        $query->orderBy('created_at', 'desc');

        // Пагинация
        $documents = $query->paginate(10);

        // Получаем данные для фильтров
        $counterparties = Counterparty::orderBy('name')->get();
        $importances = Importance::orderBy('level')->get();

        return view('index', compact('documents', 'counterparties', 'importances'));
    }

    /**
     * Показать форму создания документа
     */
    public function create()
    {
        $documentTypes = DocumentType::all();
        $importanceLevels = Importance::all();
        $counterparties = Counterparty::all();
        
        return view('create', compact(
            'documentTypes', 
            'importanceLevels', 
            'counterparties'
        ));
    }

    /**
     * Сохранить новый документ
     */
    public function store(Request $request)
    {
        // Валидация
        $validated = $request->validate([
            'document_type' => 'required|exists:document_types,id',
            'document_name' => 'required|string|max:255',
            'document_date' => 'required|date',
            'importance' => 'nullable|exists:importances,id',
            'counterparty' => 'nullable|exists:counterparties,id',
            'description' => 'nullable|string|max:1000',
            'documents' => 'required|array|min:1',
            'documents.*' => 'file|max:10240',
        ]);

        try {
            $uploadedDocuments = [];
            
            // Обрабатываем каждый загруженный файл
            foreach ($request->file('documents') as $file) {
                $document = $this->storeDocument($file, $validated);
                $uploadedDocuments[] = $document;
            }
            
            return redirect()->route('documents.index')
                ->with('success', 'Документы успешно загружены!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при загрузке документов: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Сохранить один документ в БД и файловой системе
     */
    private function storeDocument($file, $data)
    {
        // Генерируем уникальное имя файла
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;
        
        // Создаем структуру папок: год/месяц/
        $folder = date('Y/m');
        $filePath = $file->storeAs($folder, $filename, 'documents');
        
        // Подготавливаем данные для создания документа с проверкой на существование ключей
        $documentData = [
            'user_id' => Auth::id(),
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'path' => $filePath,
            'size' => $file->getSize(),
            'type_id' => $data['document_type'],
            'importance_id' => isset($data['importance']) ? $data['importance'] : null,
            'counterparty_id' => isset($data['counterparty']) ? $data['counterparty'] : null,
            'description' => $data['description'] ?? null,
        ];
        
        return Document::create($documentData);
    }

    /**
     * Скачать документ
     */
    public function download(Document $document)
    {
        // Проверка прав доступа
        if (Auth::id() !== $document->user_id) {
            abort(403, 'У вас нет прав для скачивания этого документа');
        }
        
        // Проверяем существование файла
        if (!Storage::disk('documents')->exists($document->path)) {
            abort(404, 'Файл не найден');
        }
        
        // Получаем содержимое файла
        $fileContent = Storage::disk('documents')->get($document->path);
        
        // Возвращаем ответ с файлом
        return response($fileContent, 200, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $document->original_name . '"',
            'Content-Length' => $document->size,
        ]);
    }

    /**
     * Показать документ (например, изображение в браузере)
     */
    public function show(Document $document)
    {
        // Проверка прав доступа
        if (Auth::id() !== $document->user_id) {
            abort(403, 'У вас нет прав для просмотра этого документа');
        }
        
        // Проверяем существование файла
        if (!Storage::disk('documents')->exists($document->path)) {
            abort(404, 'Файл не найден');
        }
        
        // Для изображений - показываем в браузере
        if (str_contains($document->mime_type, 'image')) {
            $fileContent = Storage::disk('documents')->get($document->path);
            return response($fileContent, 200, [
                'Content-Type' => $document->mime_type,
                'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
            ]);
        }
        
        // Для PDF - тоже можно показать в браузере
        if (str_contains($document->mime_type, 'pdf')) {
            $fileContent = Storage::disk('documents')->get($document->path);
            return response($fileContent, 200, [
                'Content-Type' => $document->mime_type,
                'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
            ]);
        }
        
        // Для остальных файлов - скачивание
        return $this->download($document);
    }

    /**
     * Удалить документ
     */
    public function destroy(Document $document)
    {
        // Проверка прав доступа
        if (Auth::id() !== $document->user_id) {
            abort(403, 'У вас нет прав для удаления этого документа');
        }
        
        try {
            // Удаляем файл
            if (Storage::disk('documents')->exists($document->path)) {
                Storage::disk('documents')->delete($document->path);
            }
            
            // Удаляем запись из БД
            $document->delete();
            
            return redirect()->route('documents.index')
                ->with('success', 'Документ удален!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при удалении документа: ' . $e->getMessage());
        }
    }

    /**
     * Получить информацию о документе (API)
     */
    public function info(Document $document)
    {
        // Проверка прав доступа
        if (Auth::id() !== $document->user_id) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        return response()->json([
            'id' => $document->id,
            'name' => $document->original_name,
            'type' => $document->mime_type,
            'size' => $document->getFileSizeFormatted(),
            'uploaded_at' => $document->created_at->format('d.m.Y H:i'),
            'exists' => Storage::disk('documents')->exists($document->path),
        ]);
    }
}