<?php

namespace App\Imports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $stock = isset($row['stock']) ? $row['stock'] : null;
        $part_name = isset($row['part_name']) ? $row['part_name'] : null;
        $brand = isset($row['brand']) ? $row['brand'] : null;
        $model = isset($row['model']) ? $row['model'] : null;
        
        return new Products([
           
            'stock' => $stock,
            'part_name' =>$part_name,
            'brand' => $brand,
            'model' => $model,
         
            'prod_type_ID' => $row['prod_type_ID'] ?? null,
            'supplier_ID' => $row['supplier_ID'] ?? null,
            'part_num' => $row['part_num'] ?? null,
            'price_code' => $row['price_code'] ?? null,
        ]);
    }
}
