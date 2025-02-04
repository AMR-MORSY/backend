<?php

namespace App\Http\Controllers\Quotations;

use App\Models\MailPrice;
use App\Models\Quotation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\UnpricedItemCheckRule;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\PriceResource;
use Maatwebsite\Excel\HeadingRowImport;
use App\Http\Resources\MailPriceResource;
use App\Http\Resources\QuotationResource;
use Illuminate\Support\Facades\Validator;
use App\Imports\Quotation\QuotationImport;
use App\Models\Modifications\Modification;
use App\Http\Resources\PriceQuotationResource;
use App\Services\Quotations\QuotationsServices;


class QuotationController extends Controller
{
    protected $quotationsServices;

    public function __construct(QuotationsServices $quotationsServices)
    {
        $this->quotationsServices = $quotationsServices;
    }

    public function mailPricesIndex()
    {
        return response(MailPriceResource::collection(MailPrice::all()), 200);
    }

    public function insertPriceListItems(Request $request, Modification $modification, $quotation_id = null)
    {

        $validator = Validator::make($request->all(), [
            "priceListItems" => ['required', "array"],
            "priceListItems.*.id" => ['required', 'exists:prices,id'],
            "priceListItems.*.quantity" => [new UnpricedItemCheckRule($request), "numeric", "nullable"], ////////we send the $request object as an argument in the rule because we do not know the index of the current item id
            "priceListItems.*.scope" => ['required', "regex:/^supply|install|s&i$/"]

        ]);
        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validated = $validator->validated();
            if (!isset($quotation_id)) { ////////////////////////////////// insertion for new quotation
                $quotation = Quotation::create([
                    "modification_id" => $modification->id,
                    "user_id" => $request->user()->id,

                ]);
                if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } else {
                    abort(403);
                }
            } else {
                $quotation = Quotation::findOrFail($quotation_id); //////////////////////insertion for quotation update
                if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addPriceListItemsToQuotation($quotation, $validated);
                } else {
                    abort(403);
                }
            }



            return response([
                "message" => "success",
                "quotation" =>  $new_quotation,


            ], 200);
        }
    }

    public function insertMailPricesItems(Request $request, Modification $modification, $quotation_id = null)
    {
        $validator = Validator::make($request->all(), [
            "mail_prices" => ['required', "array"],
            "mail_prices.*.id" => ['required', 'exists:mail_prices,id'],
            "mail_prices.*.quantity" => ["required", "numeric", "min:0.1"],
            "mail_prices.*.scope" => ['required', "regex:/^supply|install|s&i$/"]

        ]);
        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validated = $validator->validated();

            if (!isset($quotation_id)) {
                $quotation = Quotation::create([
                    "modification_id" => $modification->id,
                    "user_id" => $request->user()->id,

                ]);
                if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } else {
                    abort(403);
                }
            } else {
                $quotation = Quotation::findOrFail($quotation_id);
                if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $new_quotation = $this->quotationsServices->addMailPricesItemsToQuotation($quotation, $validated);
                } else {
                    abort(403);
                }
            }




            return response([
                "message" => "success",
                "quotation" =>  $new_quotation,


            ], 200);
        }
    }





    public function findQuotationBelongsToModification(Request $request, Modification $modification)
    {
        if ($modification->oz == "Cairo South" && $request->user()->can('viewCairoSouthSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else if ($modification->oz == "Giza" && $request->user()->can('viewGizaSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else if ($modification->oz == "Cairo North" && $request->user()->can('viewCairoNorthSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else if ($modification->oz == "Cairo East" && $request->user()->can('viewCairoEastSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else {
            abort(403);
        }

        if (isset($quotation) && $quotation != null) {
            return response()->json([
                "message" => "success",
                "quotation" => new QuotationResource($quotation),


            ], 200);
        } else {
            return response()->json([
                "message" => 'No quotation'
            ], 200);
        }
    }

    private function checkHeaderErrors(array $headings): array
    {
        $errors = [];
        // if (empty(array_filter($headings))) {   //////through error if the header is not in the first row 

        //     array_push($errors, 'header is not in the first row');
        //     return $errors;
        // } else {
        $filteredScope = array_filter($headings, function ($value) {
            return strtolower(trim($value)) == "scope";
        });
        $filteredItem = array_filter($headings, function ($value) {
            return strtolower(trim($value)) == "item";
        });
        $filteredQuantity = array_filter($headings, function ($value) {
            return strtolower(trim($value)) == "quantity";
        });
        if (!count($filteredItem) > 0) {
            array_push($errors, 'The item Header does not exist');
        }
        if (!count($filteredScope) > 0) {
            array_push($errors, 'The Scope Header does not exist');
        }
        if (!count($filteredQuantity) > 0) {
            array_push($errors, 'The Quantity Header does not exist');
        }

        // return $errors;
        // }

        return $errors;
    }

    public function uploadQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "quotation" => ['required', "file", 'mimes:xlx,xlsx,csv', 'max:1048'],
            "id" => ['required', 'exists:modifications,id']

        ]);
        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validated = $validator->validated();
            $modification = Modification::find($validated['id']);
            $isAuthorized = false;
            if (($modification->action_owner == auth()->user()->id) || ($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class))) {
                $isAuthorized = true;
            } elseif (($modification->action_owner == auth()->user()->id) || ($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class))) {
                $isAuthorized = true;
            } elseif (($modification->action_owner == auth()->user()->id) || ($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class))) {
                $isAuthorized = true;
            } elseif (($modification->action_owner == auth()->user()->id) || ($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class))) {
                $isAuthorized = true;
            } else {
                abort(403);
            }

            if ($isAuthorized) {



                $file = $request->file("quotation");

                // Read the first row of the file
                $headings = (new HeadingRowImport)->toArray($file)[0][0];

                $headingErrors = $this->checkHeaderErrors($headings);


                if (count($headingErrors) > 0) {
                    return response()->json([
                        "message" => "failed",
                        "errors" => $headingErrors

                    ], 422);
                } else {
                    $quotation = Quotation::create([
                        "modification_id" => $validated['id'],
                        "user_id" => $request->user()->id,

                    ]);

                    $import = new QuotationImport($quotation['id']);
                    try {

                        Excel::import($import, $request->file("quotation"));
                        return response()->json([
                            "message" => "inserted Successfully",
                        ]);
                    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                        $quotation->delete(); ///////////delete the created quotation in case of any upload failure
                        $failures = $e->failures();

                        $errors = [];
                        $error = [];

                        foreach ($failures as $failure) {
                            $error['row'] = $failure->row(); // row that went wrong
                            $error['attribute'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
                            $error['errors'] = $failure->errors(); // Actual error messages from Laravel validator
                            $error['values'] = $failure->values(); // The values of the row that has failed.
                            array_push($errors, $error);
                        }
                        return response()->json([
                            "sheet_errors" => $errors,
                        ], 422);
                    }
                }
            }
        }
    }





    public function deletePriceListItems(Request $request, Modification $modification, Quotation $quotation)
    {
        $validator = Validator::make($request->all(), [
            "priceListItems" => ['required', "array"],
            "priceListItems.*.id" => ['required', 'exists:prices,id'],


        ]);
        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validated = $validator->validated();

            if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deletePriceListItemsFromQuotation($quotation, $validated);
            } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deletePriceListItemsFromQuotation($quotation, $validated);
            } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deletePriceListItemsFromQuotation($quotation, $validated);
            } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deletePriceListItemsFromQuotation($quotation, $validated);
            } else {
                abort(403);
            }
        }
        if ($new_quotation) {
            return response([
                "message" => "deleted Successfully",
                "quotation" =>  $new_quotation,


            ], 200);
        }
        return response()->json([
            "message" => 'No quotation'
        ], 200);
    }

    public function deleteMailListItems(Request $request, Modification $modification, Quotation $quotation)
    {
        $validator = Validator::make($request->all(), [
            "mail_prices_items" => ['required', "array"],
            "mail_prices_items.*.id" => ['required', 'exists:mail_prices,id'],


        ]);
        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validated = $validator->validated();

            if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deleteMailListItemsFromQuotation($quotation, $validated);
            } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deleteMailListItemsFromQuotation($quotation, $validated);
            } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deleteMailListItemsFromQuotation($quotation, $validated);
            } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $new_quotation = $this->quotationsServices->deleteMailListItemsFromQuotation($quotation, $validated);
            } else {
                abort(403);
            }
        }
        if ($new_quotation) {
            return response([
                "message" => "deleted Successfully",
                "quotation" =>  $new_quotation,


            ], 200);
        }
        return response()->json([
            "message" => 'No quotation'
        ], 200);
    }
}
