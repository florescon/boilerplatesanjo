<?php

namespace App\Http\Livewire\Backend\Store;

use Livewire\Component;
use App\Models\Finance;
use Carbon\Carbon;

class FinanceChartIncome extends Component
{
    public $chartData;

    public function mount()
    {
        $startDate = Carbon::now()->subYear()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        $transactions = Finance::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereType('income')
            ->orderBy('created_at')
            ->get()
            ->toArray();

        $dataFirst = array_fill(0, 12, 0); // Ajustar para comenzar desde 0
        $dataSecond = array_fill(0, 12, 0); // Ajustar para comenzar desde 0

        foreach ($transactions as $transaction) {
            $createdAt = Carbon::parse($transaction['created_at']);
            $year = $createdAt->year;
            $monthIndex = $createdAt->month - 1; // Ajustar índice del mes

            if ($year === $startDate->year) {
                $dataFirst[$monthIndex] += (float)$transaction['amount'];
            } elseif ($year === $endDate->year) {
                $dataSecond[$monthIndex] += (float)$transaction['amount'];
            }
        }

        // Convertir los datos a dos decimales
        $dataFirst = array_map(fn($amount) => round($amount, 2), $dataFirst);
        $dataSecond = array_map(fn($amount) => round($amount, 2), $dataSecond);

        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $this->chartData = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => $startDate->year,
                    'data' => $dataFirst,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => $endDate->year,
                    'data' => $dataSecond,
                    'backgroundColor' => 'rgba(3, 126, 255, 0.8)',
                    'borderColor' => 'rgba(0, 77, 158, 0.8)',
                    'borderWidth' => 1,
                ],
            ],
        ];        
    }

    public function render()
    {
        return view('backend.store.livewire.finance-chart-income');
    }
}
