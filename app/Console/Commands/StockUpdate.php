<?php

namespace App\Console\Commands;

use App\Models\GlobalStatus;
use DB;
use App\Models\Product;
use Illuminate\Console\Command;

class StockUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock products status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::all();
        $updates = [];

        foreach($products as $product)
        {
            $status = $product['quantity'] > 0 ? GlobalStatus::STATUS_IN_STOCK : GlobalStatus::STATUS_SOLD_OUT;
            $updates [] = "UPDATE products SET `status` = '$status' WHERE id = " . '"' . $product->id  . '"';
        }

        DB::disableQueryLog();
        foreach($updates as $update)
        {
            DB::update(DB::raw($update));
        }

        echo 'stock updated'. PHP_EOL;
        return 0;
    }
}
