<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counterparty;
use Illuminate\Http\Request;

class CounterpartyController extends Controller
{
    public function index()
    {
        $counterparties = Counterparty::latest()->paginate(20);
        return view('admin.counterparties.index', compact('counterparties'));
    }

    public function create()
    {
        $types = [
            'supplier' => 'Поставщик',
            'client' => 'Клиент',
            'partner' => 'Партнер',
            'other' => 'Другое'
        ];
        
        return view('admin.counterparties.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string'
        ]);

        Counterparty::create($validated);

        return redirect()->route('admin.counterparties.index')
            ->with('success', 'Контрагент успешно создан.');
    }

    public function edit(Counterparty $counterparty)
    {
        $types = [
            'supplier' => 'Поставщик',
            'client' => 'Клиент',
            'partner' => 'Партнер',
            'other' => 'Другое'
        ];
        
        return view('admin.counterparties.edit', compact('counterparty', 'types'));
    }

    public function update(Request $request, Counterparty $counterparty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string'
        ]);

        $counterparty->update($validated);

        return redirect()->route('admin.counterparties.index')
            ->with('success', 'Контрагент успешно обновлен.');
    }

    public function destroy(Counterparty $counterparty)
    {
        // Проверяем, есть ли связанные документы
        if ($counterparty->documents()->exists()) {
            return redirect()->back()
                ->with('error', 'Нельзя удалить контрагента, с которым связаны документы.');
        }

        $counterparty->delete();

        return redirect()->route('admin.counterparties.index')
            ->with('success', 'Контрагент успешно удален.');
    }
}