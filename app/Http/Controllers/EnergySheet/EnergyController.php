<?php

namespace App\Http\Controllers\EnergySheet;

use App\Models\EnergySheet\GenAlarm;
use App\Models\EnergySheet\DownAlarm;
use App\Models\EnergySheet\PowerAlarm;
use Illuminate\Http\Request;
use App\Models\EnergySheet\HighTempAlarm;
use App\Imports\EnergySheet\DownAlarmsImport;
use App\Imports\EnergySheet\EnergySheetImport;
use App\Imports\EnergySheet\PowerAlarmsImport;
use App\Http\Controllers\Controller;
use App\Imports\EnergySheet\GenDownAlarmsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EnergySheet\HighTempAlarmsImport;
use Illuminate\Support\Facades\Validator;

class EnergyController extends Controller
{
    public function index()
    {
        $weeks = [];
        $years = [];
        for ($i = 1; $i <= 48; $i++) {
            array_push($weeks, $i);
        }
        for ($i = 2022; $i <= 2050; $i++) {
            array_push($years, $i);
        }


        return response()->json([
            "weeks" => $weeks,
            "years" => $years


        ], 200);
    }

   


    public function store_alarms(Request $request)
    {
        $this->authorize("create",PowerAlarm::class);

        $validator = Validator::make($request->all(), ["week" => ['required', 'regex:/^(?:[1-9]|[1-3][0-9]|4[0-8])$/'], "year" => ['required', 'regex:/^2[0-9]{3}$/'], "energy_sheet" => 'required|mimes:csv,xlsx']);
        $validated = $validator->validated();
        if ($validated) {
            $power_alarm=PowerAlarm::where('week',$validated['week'])->where('year',$validated['year'])->first();
            $gen_alarm=GenAlarm::where('week',$validated['week'])->where('year',$validated['year'])->first();
            $high_temp_alarm=HighTempAlarm::where('week',$validated['week'])->where('year',$validated['year'])->first();
            $Down_alarm=DownAlarm::where('week',$validated['week'])->where('year',$validated['year'])->first();
            if($power_alarm)
            {
                return response()->json([
                    "week_year" => "Week$validated'[week]'for year$validated'[year]'",
                ], 422);
            }

            if($gen_alarm)
            {
                return response()->json([
                    "week_year" => "Week$validated'[week]'for year$validated'[year]'",
                ], 422);
            }
            if($high_temp_alarm)
            {
                return response()->json([
                    "week_year" => "Week$validated'[week]'for year$validated'[year]'",
                ], 422);
            }
            if( $Down_alarm)
            {
                return response()->json([
                    "week_year" => "Week$validated'[week]'for year$validated'[year]'",
                ], 422);
            }
            $import = new EnergySheetImport($validated['week'], $validated['year']);
            try {
                $import->onlySheets("Power", "Down", "HT without power", "Power with gen");
                Excel::import($import, $request->file("energy_sheet"));
                return response()->json([
                    "message" => "inserted Succesfully",
                ]);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
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
        } else {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(

                $request,
                $validator

            );
        }
    }
}
