<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function list($id = null)
    {
        $products = $id ? Product::find($id) : Product::all();

        if (is_object($products)){
            foreach ($products as $item) {
                if (!empty($item->image)) {
                    $item->image = 'http://172.20.16.9:8000/admin/img/'. $item->image;
                }
            }
        } else{
            $products->image = 'http://172.20.16.9:8000/admin/img/'. $products->image;
        }
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }
}
