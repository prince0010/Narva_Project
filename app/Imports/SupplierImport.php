<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $supplier_id = isset($row['supplier_id']) ? $row['supplier_id'] : null;
        $supplier_name = isset($row['suppliers_name']) ? $row['suppliers_name'] : null;
        $contact_number = isset($row['contact_number']) ? $row['contact_number'] : null;
       
      
        return new Supplier([
           
            'supplier_id' => $supplier_id,
            'supplier_name' =>$supplier_name,
            'contact_number' => $contact_number,
           
            
        ]);
    }
}
