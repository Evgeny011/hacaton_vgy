<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Counterparty;
use App\Models\Document;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function viewAdmin(Request $request)
    {
        $search = $request->get('search');
        $showAll = $request->get('show_all', false);
        
        $usersQuery = User::query();
        
        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('login', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        if (!$search || $showAll) {
            // Показываем всех пользователей, если нет поиска или явно запрошено
            $users = $usersQuery->orderBy('created_at', 'desc')->paginate(10);
        } else {
            // Только результаты поиска
            $users = $usersQuery->orderBy('created_at', 'desc')->get();
        }
        
        return view('admin', compact('users', 'search', 'showAll'));
    }
    
    public function viewUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-view', compact('user'));
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.user.view', $user->id)
            ->with('success', 'Профиль пользователя успешно обновлен');
    }
    
    public function verifyUser($id)
    {
        $user = User::findOrFail($id);
        
        if (!$user->email_verified_at) {
            $user->update([
                'email_verified_at' => now()
            ]);
            
            return redirect()->route('admin')
                ->with('success', "Email пользователя {$user->login} успешно подтвержден");
        }
        
        return redirect()->route('admin')
            ->with('info', 'Email пользователя уже подтвержден');
    }
    
    public function toggleBlockUser($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_blocked' => !$user->is_blocked,
            'blocked_at' => $user->is_blocked ? null : now()
        ]);
        
        $status = $user->is_blocked ? 'заблокирован' : 'разблокирован';
        
        return redirect()->route('admin')
            ->with('success', "Пользователь {$user->login} успешно {$status}");
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $login = $user->login;
        
        // Не позволяем удалять самих себя
        if ($user->id === auth()->id()) {
            return redirect()->route('admin')
                ->with('error', 'Вы не можете удалить свой собственный аккаунт');
        }
        
        $user->delete();
        
        return redirect()->route('admin')
            ->with('success', "Пользователь {$login} успешно удален");
    }
    
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('admin.user.view', $user->id)
            ->with('success', 'Пароль пользователя успешно обновлен');
    }

        public function statistics(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        // Получаем данные для графиков
        $documentStats = $this->getDocumentStatistics($period);
        $userStats = $this->getUserStatistics($period);
        $counterpartyStats = $this->getCounterpartyStatistics();
        
        return view('statistics', compact(
            'documentStats', 
            'userStats', 
            'counterpartyStats',
            'period'
        ));
    }
    
    private function getDocumentStatistics($period)
    {
        $endDate = Carbon::now();
        $startDate = match($period) {
            'day' => $endDate->copy()->subDay(),
            'week' => $endDate->copy()->subWeek(),
            'month' => $endDate->copy()->subMonth(),
            'year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subMonth(),
        };
        
        // Группируем документы по дате создания
        $documents = Document::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function($document) use ($period) {
                return match($period) {
                    'day' => $document->created_at->format('H:00'),
                    'week' => $document->created_at->format('Y-m-d'),
                    'month' => $document->created_at->format('Y-m-d'),
                    'year' => $document->created_at->format('Y-m'),
                    default => $document->created_at->format('Y-m-d'),
                };
            });
        
        $labels = [];
        $data = [];
        
        // Формируем данные для графика
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $key = match($period) {
                'day' => $current->format('H:00'),
                'week' => $current->format('Y-m-d'),
                'month' => $current->format('Y-m-d'),
                'year' => $current->format('Y-m'),
                default => $current->format('Y-m-d'),
            };
            
            $labels[] = $key;
            $data[] = $documents->has($key) ? $documents[$key]->count() : 0;
            
            $current->add(match($period) {
                'day' => '1 hour',
                'week' => '1 day',
                'month' => '1 day',
                'year' => '1 month',
                default => '1 day',
            });
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
            'average' => count($data) > 0 ? array_sum($data) / count($data) : 0,
        ];
    }
    
    private function getUserStatistics($period)
    {
        $endDate = Carbon::now();
        $startDate = match($period) {
            'day' => $endDate->copy()->subDay(),
            'week' => $endDate->copy()->subWeek(),
            'month' => $endDate->copy()->subMonth(),
            'year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subMonth(),
        };
        
        $users = User::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function($user) use ($period) {
                return match($period) {
                    'day' => $user->created_at->format('H:00'),
                    'week' => $user->created_at->format('Y-m-d'),
                    'month' => $user->created_at->format('Y-m-d'),
                    'year' => $user->created_at->format('Y-m'),
                    default => $user->created_at->format('Y-m-d'),
                };
            });
        
        $labels = [];
        $data = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $key = match($period) {
                'day' => $current->format('H:00'),
                'week' => $current->format('Y-m-d'),
                'month' => $current->format('Y-m-d'),
                'year' => $current->format('Y-m'),
                default => $current->format('Y-m-d'),
            };
            
            $labels[] = $key;
            $data[] = $users->has($key) ? $users[$key]->count() : 0;
            
            $current->add(match($period) {
                'day' => '1 hour',
                'week' => '1 day',
                'month' => '1 day',
                'year' => '1 month',
                default => '1 day',
            });
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
            'active_users' => User::where('last_login_at', '>=', $startDate)->count(),
        ];
    }
    
    private function getCounterpartyStatistics()
    {
        $totalCounterparties = Counterparty::count();
        $recentCounterparties = Counterparty::where('created_at', '>=', Carbon::now()->subMonth())->count();
        
        $byType = Counterparty::groupBy('type')
            ->selectRaw('type, count(*) as count')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
        
        return [
            'total' => $totalCounterparties,
            'recent' => $recentCounterparties,
            'by_type' => $byType,
        ];
    }
}
