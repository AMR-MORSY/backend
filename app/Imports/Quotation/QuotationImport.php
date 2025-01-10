<?php

namespace App\Imports\Quotation;

use App\Models\Price;
use App\Models\PriceQuotation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;



HeadingRowFormatter::extend('custom', function($value, $key) {
    
    
    return strtolower(trim(str_replace(" ","",$value))); 
    
    // And you can use heading column index.
    // return 'column-' . $key; 
});

class QuotationImport implements  ToModel,SkipsEmptyRows,WithHeadingRow, WithValidation
{
    use Importable;
    /**
    *
    */

    private $quotation_id;

   
    public function __construct($quotation)
    {
       $this->quotation_id=$quotation;
    }

    public static function prepareValidation($row)
    {
        $row["scope"] = strtolower(trim($row['scope']));

        return $row;
    }
    
    public function rules():array
    {
      
      return  [
        "item"=>['required','exists:prices,item'],
        "quantity"=>['required','numeric'],
        'scope'=>['required',"regex:/^supply|install|s&i$/"]
      ];
    }

    protected function getItemPrice(float $quantity,string $scope, float $install_price,float $supply_price)
    {
        if($scope=='supply')
        {
            if($supply_price!=null)
            {
                return $quantity*$supply_price;
            }
            else{
                return 0;
            }
        }
        elseif ($scope=='install')
        {
            if($install_price!=null)
            {
                return $quantity*$install_price;
            }
            else{
                return 0;
            }
        }
        elseif($scope=='s&i')
        {
            if($supply_price!=null && $install_price!=null)
            {
                return $quantity*($supply_price+$install_price);
            }
            elseif($supply_price==null && $install_price!=null)
            {
                return $quantity*$install_price;

            }
            elseif($supply_price==null && $install_price!=null)
            { return $quantity*$supply_price;
            }

            else{
                return 0;
            }
        }




    }
    protected function returnPriceValue( string $attribute, string $attribute_value)
    {
        if($attribute=='id')
        {
            $price=Price::where("item",$attribute_value)->get();
            return $price->first()->id;
        }



    }
    public function model(array $row)
    {
        return new PriceQuotation([

            "quotation_id"=>$this->quotation_id,
            "price_id"=>$this->returnPriceValue('id',$row['item']),
            "quantity"=>$row['quantity'],
            "scope"=>$row['scope'],
            "install_price"=>Price::where("item",$row['item'])->first()->installation,
            "supply_price"=>Price::where("item",$row['item'])->first()->supply,
            "item_price"=>$this->getItemPrice($row['quantity'],$row['scope'],Price::where("item",$row['item'])->first()->installation,Price::where("item",$row['item'])->first()->supply)



        ]);
    }
}
