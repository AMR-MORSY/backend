<?php

namespace App\Imports\Quotation;

use App\Models\Price;
use App\Models\UnpricedItem;
use App\Models\PriceQuotation;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Rules\UnpricedItemCheckRule;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


HeadingRowFormatter::extend('custom', function ($value, $key) {


    return strtolower(trim($value));

    // And you can use heading column index.
    // return 'column-' . $key; 
});
HeadingRowFormatter::default('custom');

class QuotationImport implements ToModel, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    use Importable;
    /**
     *
     */

    private $quotation_id;

    public function headingRow(): int
    {
        return 1; // Specify the row number of the heading row
    }

    public function __construct($quotation)
    {
        $this->quotation_id = $quotation;
    }

    public function prepareForValidation($data, $index)
    {
         $data["scope"] = strtolower(trim($data['scope']));

        $unpriced_item = UnpricedItem::where('item', $data['item'])->exists();

        $newItemValue = [];

        if ($unpriced_item) {
            $newItemValue['value'] = $data['item'];
            $newItemValue['type'] = 'un_priced';
         
            $data['item'] = $newItemValue;
        } else {
            $newItemValue['value'] = $data['item'];
            $newItemValue['type'] = 'priced';
          
            $data['item'] = $newItemValue;
        }


        return $data;
    }






    public function rules(): array
    {

        return  [
            'item' => ['array'],
            "item.value" => ['required', 'exists:prices,item'],
            "quantity" => ["required_unless:item.type,un_priced",'numeric','nullable'],
            'scope' => ['required', "regex:/^supply|install|s&i$/"]
        ];
    }
    public function customValidationMessages()
    {
        return [
            'quantity.required_unless' => 'quantity is required',
        ];
    }

    protected function getItemPrice($quantity, string $scope, float $install_price, float $supply_price)
    {
        if ($scope == 'supply') {
            if ($supply_price != null) {
                return $quantity * $supply_price;
            } else {
                return 0;
            }
        } elseif ($scope == 'install') {
            if ($install_price != null) {
                return $quantity * $install_price;
            } else {
                return 0;
            }
        } elseif ($scope == 's&i') {
            return $quantity * ($supply_price + $install_price);
            // if ($supply_price != null && $install_price != null) {
            //     return $quantity * ($supply_price + $install_price);
            // } elseif ($supply_price == null && $install_price != null) {
            //     return $quantity * $install_price;
            // } elseif ($supply_price != null && $install_price == null) {
            //     return $quantity * $supply_price;
            // } else {
            //     return 0;
            // }
        }
    }
    protected function returnPriceValue(string $attribute, string $attribute_value)
    {
        if ($attribute == 'id') {
            $price = Price::where("item", $attribute_value)->get();
            return $price->first()->id;
        }
    }
    public function model(array $row)
    {



        return new PriceQuotation([

            "quotation_id" => $this->quotation_id,
            "price_id" => $this->returnPriceValue('id', $row['item']['value']),
            "quantity" => $row['quantity'],
            "scope" => $row['scope'],
            "install_price" => Price::where("item", $row['item'])->first()->installation,
            "supply_price" => Price::where("item", $row['item'])->first()->supply,
            "item_price" => $this->getItemPrice($row['quantity'], $row['scope'], Price::where("item", $row['item']['value'])->first()->installation, Price::where("item", $row['item']['value'])->first()->supply)



        ]);
    }
}
