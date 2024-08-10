<?php

namespace App\Http\Livewire\Backend\Store;

use Livewire\Component;
use App\Models\Finance;
use Carbon\Carbon;

class FinanceChart extends Component
{
    public $chartData;

    public function mount()
    {
        // Obtener la fecha de hace 12 meses desde ahora
        $startDate = Carbon::now()->subMonths(12)->startOfMonth();

        // Obtener los registros de los últimos 12 meses
        $query = Finance::query()
            ->where('date_entered', '>=', $startDate)
            ->orderBy('created_at')
            ->get();

        $transactions = $query->toArray();

        $incomeData = [];
        $expenseData = [];

        foreach ($transactions as $transaction) {
            $month = Carbon::parse($transaction['date_entered'])->format('Y-m');

            if ($transaction['type'] === 'income') {
                if (!isset($incomeData[$month])) {
                    $incomeData[$month] = 0;
                }
                $incomeData[$month] += (float)$transaction['amount'];

            } elseif ($transaction['type'] === 'expense') {
                if (!isset($expenseData[$month])) {
                    $expenseData[$month] = 0;
                }
                $expenseData[$month] += -(float)$transaction['amount'];
            }
        }

        // dd($incomeData);

        $months = array_unique(array_merge(array_keys($incomeData), array_keys($expenseData)));
        sort($months);

        $this->chartData = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => array_map(fn($month) => $incomeData[$month] ?? 0, $months),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Egresos',
                    'data' => array_map(fn($month) => $expenseData[$month] ?? 0, $months),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }    

    public function render()
    {
        return view('backend.store.livewire.finance-chart');
    }
}
