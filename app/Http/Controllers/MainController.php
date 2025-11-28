<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\Importance;
use App\Models\Counterparty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller {
    public function viewIndex()
    {
        $user = Auth::user();
        
        $documentTypes = DocumentType::all();
        $importances = Importance::all();
        $counterparties = Counterparty::all();
        
        if ($user->isAdmin()) {
            $documents = Document::with(['user', 'documentType', 'importance', 'counterparty'])
                                ->latest()
                                ->get();
        } else {
            $documents = Document::with(['documentType', 'importance', 'counterparty'])
                                ->where('user_id', $user->id)
                                ->latest()
                                ->get();
        }
        
        return view('index')->with([
            'username' => $user ? $user->name : 'You',
            'documents' => $documents,
            'documentTypes' => $documentTypes,
            'importances' => $importances,
            'counterparties' => $counterparties
        ]);
    }

    public function viewProfile()
    {
        $user = Auth::user();
        
        $documentStats = [
            'total' => Document::where('user_id', $user->id)->count(),
            'recent' => Document::where('user_id', $user->id)
                              ->where('created_at', '>=', now()->subDays(7))
                              ->count(),
        ];
        
        return view('profile')->with([
            'username' => $user ? $user->name : 'You',
            'user' => $user,
            'documentStats' => $documentStats
        ]);
    }

    public function viewLogin()
    {
        return view('login');
    }

    public function viewDocument($id)
    {
        $user = Auth::user();
        $document = Document::with(['user', 'documentType', 'importance', 'counterparty'])
                           ->findOrFail($id);
        
        if (!$user->isAdmin() && $document->user_id !== $user->id) {
            abort(403, 'У вас нет прав для просмотра этого документа');
        }
        
        return view('document.view')->with([
            'username' => $user->name,
            'document' => $document
        ]);
    }

    public function viewCreateDocument()
    {
        $user = Auth::user();
        
        $documentTypes = DocumentType::all();
        $importances = Importance::all();
        $counterparties = Counterparty::all();
        
        return view('document.create')->with([
            'username' => $user->name,
            'documentTypes' => $documentTypes,
            'importances' => $importances,
            'counterparties' => $counterparties
        ]);
    }

    public function viewUsers()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'У вас нет прав для просмотра этой страницы');
        }
        
        $users = User::withCount('documents')->latest()->get();
        
        return view('users.index')->with([
            'username' => $user->name,
            'users' => $users
        ]);
    }

    public function viewUserDocuments($userId)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'У вас нет прав для просмотра этой страницы');
        }
        
        $targetUser = User::findOrFail($userId);
        $documents = Document::with(['documentType', 'importance', 'counterparty'])
                           ->where('user_id', $userId)
                           ->latest()
                           ->get();
        
        return view('users.documents')->with([
            'username' => $user->name,
            'targetUser' => $targetUser,
            'documents' => $documents
        ]);
    }
}
