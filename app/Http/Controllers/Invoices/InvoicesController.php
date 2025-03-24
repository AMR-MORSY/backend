<?php

namespace App\Http\Controllers\Invoices;

use App\Models\File;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewInvoice;
use App\Http\Resources\InvoiceResource;
use App\Models\Modifications\Modification;

class InvoicesController extends Controller
{
    public function store(StoreNewInvoice $request)
    {
        $validated_with_wo = $request->safe()->except('invoice');
        $validated_without_wo = $request->safe()->except(['invoice', 'work_orders']);




        if ($request->user()->can('viewAllModifications', Modification::class)) {
            $modification_ids = [];
            foreach ($validated_with_wo['work_orders'] as $wo) { /////////////////////////////check if there is an invoice attached to the modification or not
                $modification =  Modification::where('wo_code', $wo)->first();
                $invoice_id = $modification->invoice_id;
                if (isset($invoice_id) & $invoice_id != null) {
                    return response()->json([
                        "message" => 'failed',
                        "wo_code" => $wo

                    ]);
                } else {
                    array_push($modification_ids, $modification->id);
                }
            }


            $invoice = Invoice::create($validated_without_wo);

            foreach ($modification_ids as $id) /////////////////////////////attach invoice to each modification in the array of modification ids
            {
                $modification = Modification::find($id);
                $modification->invoice_id = $invoice->id;
                $modification->save();
            }



            $path = $request->file('invoice')->store('uploads', 'private');
            $pdfPath = storage_path("app/private/$path");
            // $pdfData = base64_encode(file_get_contents($pdfPath));
            // $pdfUrl = 'data:application/pdf;base64,' . $pdfData;

            $file = File::create([
                'path' =>  $pdfPath,
                'original_name' => $request->file('invoice')->getClientOriginalName(),
                'mime_type' => $request->file('invoice')->getMimeType(),
                'size' => $request->file('invoice')->getSize(),
                'invoice_id' => $invoice->id,
            ]);

            return response()->json([
                'id' => $file->id,
                'name' => $file->original_name,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'created_at' => $file->created_at,

            ], 200);
        } else {
            abort(403);
        }
    }

    public function view(Invoice $invoice, Request $request)
    {
        if ($request->user()->can('viewAllModifications', Modification::class)) {

            return new InvoiceResource($invoice->load(['subcontractor','file']));
        }
        abort(403);
    }

  
}
