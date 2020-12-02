<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\BuyRequest;
use App\Http\Requests\Products\GetDetailRequest;
use App\Http\Requests\Products\ListRequest;
use App\Models\GlobalStatus;
use App\Models\Product;
use App\Models\Transaction;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    use ApiResponse;

    /**
     * @api {get} /products/list List
     * @apiVersion 1.0.0
     * @apiGroup Products
     * @apiName List
     * @apiDescription Get list Products.
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *  {
     *       "data": [
     *           {
     *               "id": 1,
     *               "name": "qui sunt labore",
     *               "description": "et illo non laboriosam quos laudantium",
     *               "quantity": 16,
     *               "status": "In Stock",
     *               "created_at": "2020-12-01T01:13:27.000000Z",
     *               "updated_at": "2020-12-01T01:13:27.000000Z",
     *               "user_id": 3
     *           },
     *           {
     *               "id": 3,
     *               "name": "vero sequi accusamus",
     *               "description": "dolor ullam quibusdam eveniet similique magni",
     *               "quantity": 12,
     *               "status": "In Stock",
     *               "created_at": "2020-12-01T01:13:27.000000Z",
     *               "updated_at": "2020-12-01T01:13:27.000000Z",
     *               "user_id": 1
     *           }
     *
     *  }
     */
    public function getList(ListRequest $request)
    {
        $data = $request->all();
        $Products = (isset($data['show_products_without_stock']) && (!is_null($data['show_products_without_stock']))) ? Product::withoutGlobalScope('stock')->get() : Product::get();
        return $this->showAll($Products->toArray());
    }

    /**
     * @api {get} /products/detail Detail
     * @apiVersion 1.0.0
     * @apiGroup Products
     * @apiName Detail
     * @apiDescription Get detail of product id.
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *       "data": {
     *           "id": 1,
     *           "name": "qui sunt labore",
     *           "description": "et illo non laboriosam quos laudantium",
     *           "quantity": 16,
     *           "status": "In Stock",
     *           "created_at": "2020-12-01T01:13:27.000000Z",
     *           "updated_at": "2020-12-01T01:13:27.000000Z",
     *           "user_id": 3
     *       }
     *   }
     */
    public function getDetail(GetDetailRequest $request)
    {
        $buyer = Product::whereId($request->input(['id']))->firstOrFail();
        return $this->showOne($buyer);
    }

    /**
     * @api {get} /products/:id/Buy Buy
     * @apiVersion 1.0.0
     * @apiGroup Products
     * @apiName Buy
     * @apiDescription Store Buy of seller id.
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *       "data": {
     *           "name": "prueba",
     *           "description": "prueba",
     *           "quantity": 10,
     *           "user_id": 4,
     *           "updated_at": "2020-12-02T02:26:49.000000Z",
     *           "created_at": "2020-12-02T02:26:49.000000Z",
     *           "id": 21
     *       }
     *   }
     */
    public function buy(BuyRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $product = Product::whereId($data['id'])->first();

            if($product['quantity'] < $data['quantity'])
            {
                return $this->showError('la cantidad supera el total disponible', [], 404);
            }

            Transaction::create([
                'quantity' => $data['quantity'],
                'user_id' => Auth::user()->id,
                'product_id' => $product['id']
            ]);

            $quantity = ($product['quantity'] - $data['quantity']);
            $status = ($quantity > 0) ? GlobalStatus::STATUS_IN_STOCK : GlobalStatus::STATUS_SOLD_OUT;
            $product = $product->update(['quantity' => $quantity, 'status' => $status]);
            DB::commit();
            return $this->showOne($product);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('ProductController@buy: ' . $e->getMessage());
            return $this->showError($e->getMessage(), [], 404);
        }
    }
}
