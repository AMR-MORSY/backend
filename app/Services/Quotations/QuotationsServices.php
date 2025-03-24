<?php

namespace App\Services\Quotations;

use App\Models\Price;
use App\Models\MailPrice;
use App\Models\MailQuotation;
use App\Models\PriceQuotation;
use App\Http\Resources\QuotationResource;


class QuotationsServices
{
    protected function getItemPrice(float $quantity, string $scope, float $install_price, float $supply_price) //////code repetition here as this function exists in quotation import class
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
            return $quantity * ($supply_price+$install_price);
           
            // if ($supply_price != null && $install_price != null) {
            //     return $quantity ;
            // } elseif ($supply_price == null && $install_price != null) {
            //     return $quantity * $install_price;
            // } elseif ($supply_price == null && $install_price != null) {
            //     return $quantity * $supply_price;
            // } else {
            //     return 0;
            // }
        }
    }
    public function addMailPricesItemsToQuotation(object $quotation, array $validated)
    {
        foreach ($validated["mail_prices"] as $validate) {
            MailQuotation::create([
                "quantity" => $validate["quantity"],
                "quotation_id" => $quotation->id,
                "scope" => $validate['scope'],
                "mail_price_id" => $validate['id'],
                "install_price" => MailPrice::find($validate['id'])->installation,
                "supply_price" => MailPrice::find($validate['id'])->supply,
                "item_price" => $this->getItemPrice($validate['quantity'], $validate['scope'], MailPrice::find($validate['id'])->installation, MailPrice::find($validate['id'])->supply)

            ]);
        }

        return new QuotationResource($quotation);

        // return gettype($validated['mail_prices']);





    }
    public function addPriceListItemsToQuotation(object $quotation, array $validated)
    {
        foreach ($validated["priceListItems"] as $validate) {
            PriceQuotation::create([
                "quantity" => $validate["quantity"],
                "quotation_id" => $quotation->id,
                "scope" => $validate['scope'],
                "price_id" => $validate['id'],
                "install_price" => Price::find($validate['id'])->installation,
                "supply_price" => Price::find($validate['id'])->supply,
                "item_price" => $this->getItemPrice($validate['quantity'], $validate['scope'], Price::find($validate['id'])->installation, Price::find($validate['id'])->supply)

            ]);
        }

        return new QuotationResource($quotation);

       




    }


    private function deleteQuotationAfterLastItemRemoval(object $quotation)
    {
        $newPriceQuotation=PriceQuotation::where("quotation_id",$quotation->id)->get();
        $newMailQuotation=MailQuotation::where("quotation_id",$quotation->id)->get();

        if(count($newMailQuotation)==0 && count($newPriceQuotation)==0)
        {
            $quotation->delete();
            return false;
        }
        else{
            return true;
        }



    }

    public function deletePriceListItemsFromQuotation(object $quotation,array $validated)
    {
        foreach ($validated["priceListItems"] as $validate) {
            $PriceQuotation=PriceQuotation::where("price_id",$validate['id'])->where("quotation_id",$quotation->id)->first();
            $PriceQuotation->delete();
        }
        $isThereItems=$this->deleteQuotationAfterLastItemRemoval($quotation);
        if($isThereItems)
        {
            return new QuotationResource($quotation);

        }
        return null;
    

    }
    public function deleteMailListItemsFromQuotation(object $quotation,array $validated)
    {
        foreach ($validated["mail_prices_items"] as $validate) {
            $PriceQuotation=MailQuotation::where("mail_price_id",$validate['id'])->where("quotation_id",$quotation->id)->first();
            $PriceQuotation->delete();
        }
        $isThereItems=$this->deleteQuotationAfterLastItemRemoval($quotation);
        if($isThereItems)
        {
            return new QuotationResource($quotation);

        }
        return null;
       
    }
}
