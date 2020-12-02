<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sellers\AddProductRequest;
use App\Http\Requests\Sellers\GetDetailRequest;
use App\Http\Requests\Sellers\ListRequest;
use App\Models\Product;
use App\Models\Seller;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SellersController extends Controller
{
    use ApiResponse;

    /**
     * @api {get} /sellers/list List
     * @apiVersion 1.0.0
     * @apiGroup Sellers
     * @apiName List
     * @apiDescription Get list Sellers.
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *  {
     *      "data": [
     *          {
     *              "id": 1,
     *              "name": "Mr. Jamel Lang IV",
     *              "email": "west.ophelia@example.net",
     *              "email_verified_at": null,
     *              "created_at": "2020-12-01T01:13:27.000000Z",
     *              "updated_at": "2020-12-01T01:13:27.000000Z"
     *          },
     *          {
     *              "id": 2,
     *              "name": "Quinton Mitchell",
     *              "email": "bogisich.estevan@example.org",
     *              "email_verified_at": null,
     *              "created_at": "2020-12-01T01:13:27.000000Z",
     *              "updated_at": "2020-12-01T01:13:27.000000Z"
     *          },
     *          {
     *              "id": 3,
     *              "name": "Maureen Prohaska MD",
     *              "email": "elton49@example.net",
     *              "email_verified_at": null,
     *              "created_at": "2020-12-01T01:13:27.000000Z",
     *              "updated_at": "2020-12-01T01:13:27.000000Z"
     *          }
     *      ]
     *  }
     */
    public function getList(ListRequest $request)
    {
        $Sellers = Seller::get()->toArray();
        return $this->showAll($Sellers);
    }

    /**
     * @api {get} /sellers/detail Detail
     * @apiVersion 1.0.0
     * @apiGroup Sellers
     * @apiName Detail
     * @apiDescription Get detail of seller id.
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *       "data": {
     *           "id": 1,
     *           "name": "Mr. Jamel Lang IV",
     *           "email": "west.ophelia@example.net",
     *           "email_verified_at": null,
     *           "created_at": "2020-12-01T01:13:27.000000Z",
     *           "updated_at": "2020-12-01T01:13:27.000000Z",
     *           "products": [
     *               {
     *                   "id": 3,
     *                   "name": "vero sequi accusamus",
     *                   "description": "dolor ullam quibusdam eveniet similique magni",
     *                   "quantity": 12,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1
     *               },
     *               {
     *                   "id": 10,
     *                   "name": "illum minus nemo",
     *                   "description": "excepturi ex sapiente est velit odit",
     *                   "quantity": 11,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1
     *               },
     *               {
     *                   "id": 11,
     *                   "name": "perferendis qui consectetur",
     *                   "description": "aut nam recusandae et est repellat",
     *                   "quantity": 4,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1
     *               },
     *               {
     *                   "id": 16,
     *                   "name": "non omnis et",
     *                   "description": "suscipit voluptatum et alias nihil ipsam",
     *                   "quantity": 60,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1
     *               },
     *               {
     *                   "id": 19,
     *                   "name": "aperiam ut suscipit",
     *                   "description": "fugiat iusto rerum nihil voluptatem enim",
     *                   "quantity": 3,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1
     *               }
     *           ]
     *       }
     *   }
     */
    public function getDetail(GetDetailRequest $request)
    {
        $buyer = Seller::whereId($request->input(['id']))->with(['products'])->firstOrFail();
        return $this->showOne($buyer);
    }

    /**
     * @api {get} /sellers/product Product
     * @apiVersion 1.0.0
     * @apiGroup Sellers
     * @apiName Product
     * @apiDescription Add product of seller id.
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
    public function addProduct(AddProductRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
            $product = Product::create($data);
            DB::commit();
            return $this->showOne($product);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('SellersController@addProduct: ' . $e->getMessage());
            return $this->showError($e->getMessage(), [] ,404);
        }

    }
}
