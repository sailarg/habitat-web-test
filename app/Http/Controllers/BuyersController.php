<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buyers\GetDetailRequest;
use App\Http\Requests\Buyers\ListRequest;
use App\Models\Buyer;
use App\Traits\ApiResponse;

class BuyersController extends Controller
{
    use ApiResponse;

    /**
     * @api {get} /buyers/list List
     * @apiVersion 1.0.0
     * @apiGroup Buyers
     * @apiName List
     * @apiDescription Get list Buyers.
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
        $buyers = Buyer::get()->toArray();
        return $this->showAll($buyers);
    }

    /**
     * @api {get} /buyers/detail Detail
     * @apiVersion 1.0.0
     * @apiGroup Buyers
     * @apiName Detail
     * @apiDescription Get detail of buyer id.
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
     *           "transactions": [
     *               {
     *                   "id": 7,
     *                   "quantity": 1,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1,
     *                   "product_id": 4
     *               },
     *               {
     *                   "id": 8,
     *                   "quantity": 6,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1,
     *                   "product_id": 2
     *               },
     *               {
     *                   "id": 9,
     *                   "quantity": 19,
     *                   "created_at": "2020-12-01T01:13:27.000000Z",
     *                   "updated_at": "2020-12-01T01:13:27.000000Z",
     *                   "user_id": 1,
     *                   "product_id": 5
     *               }
     *           ]
     *       }
     *   }
     */
    public function getDetail(GetDetailRequest $request)
    {
        $buyer = Buyer::whereId($request->input(['id']))->with(['transactions'])->firstOrFail();
        return $this->showOne($buyer);
    }

}
