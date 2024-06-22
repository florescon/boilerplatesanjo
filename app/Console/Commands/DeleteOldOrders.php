<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class DeleteOldOrders extends Command
{
    protected $signature = 'orders:delete-old';
    protected $description = 'Delete orders that meet specific conditions and are older than 30 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $orders = Order::where('type', 6)
            ->where('branch_id', '<>', 0)
            ->whereNotNull('quotation')
            ->where('created_at', '<', $now->subDays(30))
            ->get();

        $deletedCount = $orders->count();
        $orders->each->delete();

        $this->info("Deleted {$deletedCount} old orders.");
    }
}
