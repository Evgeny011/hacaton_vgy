<?php

    namespace App\Http\Controllers;

    use App\Models\Counterparty;
    use Illuminate\Http\Request;

    class CounterpartyController extends Controller
    {
        public function index(Request $request)
        {
            try {
                $query = Counterparty::query();
                
                if ($request->has('search') && $request->search) {
                    $search = $request->search;
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                    });
                }
                
                if ($request->has('type') && $request->type) {
                    $query->where('type', $request->type);
                }
                
                $counterparties = $query->latest()->paginate(12);
                
                return view('counterparties.index', compact('counterparties'));
            } catch (\Exception $e) {
                return redirect()->route('admin')
                    ->with('error', 'Ошибка загрузки контрагентов: ' . $e->getMessage());
            }
        }
        
        public function create()
        {
            return view('counterparties.create');
        }
        
        public function store(Request $request)
        {
            try {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'type' => 'nullable|string|in:legal,individual',
                    'contact_person' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'address' => 'nullable|string',
                ]);
                
                Counterparty::create($validated);
                
                return redirect()->route('admin.counterparties.index')
                    ->with('success', 'Контрагент успешно создан');
            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Ошибка создания контрагента: ' . $e->getMessage());
            }
        }
        
        public function edit(Counterparty $counterparty)
        {
            try {
                return view('counterparties.edit', compact('counterparty'));
            } catch (\Exception $e) {
                return redirect()->route('admin.counterparties.index')
                    ->with('error', 'Контрагент не найден');
            }
        }
        
        public function update(Request $request, Counterparty $counterparty)
        {
            try {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'type' => 'nullable|string|in:legal,individual',
                    'contact_person' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'address' => 'nullable|string',
                ]);
                
                $counterparty->update($validated);
                
                return redirect()->route('admin.counterparties.index')
                    ->with('success', 'Контрагент успешно обновлен');
            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Ошибка обновления контрагента: ' . $e->getMessage());
            }
        }
        
        public function destroy(Counterparty $counterparty)
        {
            try {
                $counterparty->delete();
                
                return redirect()->route('admin.counterparties.index')
                    ->with('success', 'Контрагент успешно удален');
            } catch (\Exception $e) {
                return back()->with('error', 'Ошибка удаления контрагента: ' . $e->getMessage());
            }
        }
    }