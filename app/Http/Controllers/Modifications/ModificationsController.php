<?php

namespace App\Http\Controllers\Modifications;

use Carbon\Carbon;
use App\Models\Sites\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Policies\ModificationPolicy;
use Illuminate\Support\Facades\Validator;
use App\Models\Modifications\Modification;
use App\Http\Requests\StoreNewModificationRequest;
use App\Exports\Modifications\AllModificationsExport;
use App\Http\Requests\FilterModificationsByDateRequest;
use App\Http\Requests\UpdateModificationRequest;
use App\Http\Resources\ModificationResource;
use App\Services\Modifications\ModificationsServices;

class ModificationsController extends Controller
{
    protected $modificationServices;
    public function __construct(ModificationsServices $modificationServices)
    {
        $this->modificationServices = $modificationServices;
    }

    private function get_column_values($column_name)
    {

        $keys = Modification::all()->groupBy($column_name)->keys();


        return $keys;
    }

    public function analysis()
    {
        $analysis = [];
        $status = $this->get_column_values('status');
        $subcontractor = $this->get_column_values('subcontractor');
        $project = $this->get_column_values('project');
        $requester = $this->get_column_values('requester');
        $actions = $this->get_column_values('actions');
        $reported = $this->get_column_values('reported');
        $analysis["status"] = $status;
        $analysis["subcontractor"] =  $subcontractor;
        $analysis["project"] = $project;
        $analysis["requester"] = $requester;
        $analysis["reported"] = $reported;
        $analysis["actions"] = $actions;

        return response()->json([
            'status' => '200',
            'message' => 'success',
            'index' => $analysis

        ]);
    }

    public function modificationsFilteredByDate(FilterModificationsByDateRequest $request, $date_type, $from_date = null, $to_date = null)
    {

        $validated = $request->validated();
        $modifications = [];

        if ($request->user()->can('viewAllModifications', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate(null, $validated,null);
        } elseif ($request->user()->can('viewCairoEastModificationsAdmin', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate(null, $validated, "Cairo East");
        } elseif ($request->user()->can('viewCairoEastModificationsUser', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate($request->user()->id, $validated, null);
        } elseif ($request->user()->can('viewCairoNorthModificationsAdmin', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate(null, $validated, "Cairo North");
        } elseif ($request->user()->can('viewCairoNorthModificationsUser', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate($request->user()->id, $validated, null);
        } elseif ($request->user()->can('viewCairoSouthModificationsAdmin', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate(null, $validated, "Cairo South");
        } elseif ($request->user()->can('viewCairoSouthModificationsUser', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate($request->user()->id, $validated, null);
        } elseif ($request->user()->can('viewGizaModificationsAdmin', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate(null, $validated, "Giza");
        } elseif ($request->user()->can('viewGizaModificationsUser', Modification::class)) {
            $modifications = $this->modificationServices->filterModificationsByDate($request->user()->id, $validated, null);
        } else {
            abort(403);
        }



        return response()->json([

            'modifications' => $modifications

        ], 200);
    }

    public function index(Request $request, $colmnName, $colmnValue)
    {
        // $this->authorize("viewAny", Modification::class);
        $data = [
            "columnName" => $colmnName,
            "columnValue" => $colmnValue
        ];
        $validator = Validator::make($data, [
            "columnName" => ['required', "regex:/^status|requester|subcontractor|project|actions|reported$/"],
            "columnValue" => ['required', 'string']
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
            $modifications = [];
            if ($request->user()->can('viewAllModifications', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory(null, $validated,null);
            } elseif ($request->user()->can('viewCairoEastModificationsAdmin', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory(null, $validated, "Cairo East");
            } elseif ($request->user()->can('viewCairoEastModificationsUser', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory($request->user()->id, $validated, null);
            } elseif ($request->user()->can('viewCairoNorthModificationsAdmin', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory(null, $validated, "Cairo North");
            } elseif ($request->user()->can('viewCairoNorthModificationsUser', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory($request->user()->id, $validated, null);
            } elseif ($request->user()->can('viewCairoSouthModificationsAdmin', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory(null, $validated, "Cairo South");
            } elseif ($request->user()->can('viewCairoSouthModificationsUser', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory($request->user()->id, $validated, null);
            } elseif ($request->user()->can('viewGizaModificationsAdmin', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory(null, $validated, "Giza");
            } elseif ($request->user()->can('viewGizaModificationsUser', Modification::class)) {
                $modifications = $this->modificationServices->filterModificationsByCategory($request->user()->id, $validated, null);
            } else {
                abort(403);
            }



            return response()->json([

                'modifications' => $modifications

            ], 200);
        }
    }

    public function modificationDetails(Request $request, Modification $modification)
    {


        if ($modification->oz == "Cairo South" && $request->user()->can('viewCairoSouthSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification),


            ], 200);
        } else if ($modification->oz == "Giza" && $request->user()->can('viewGizaSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification),


            ], 200);
        } else if ($modification->oz == "Cairo North" && $request->user()->can('viewCairoNorthSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification),


            ], 200);
        } else if ($modification->oz == "Cairo East" && $request->user()->can('viewCairoEastSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification),


            ], 200);
        } else {
            abort(403);
        }


        // }
    }

    public function reportModifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "modifications" => ['required', "array"],
            "modifications.*.id" => ['required', "exists:modifications,id"],
            "modifications.*.est_cost"=>['required','numeric','min:200']
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

            foreach($validated['modifications'] as $id)
            {
                $modification=Modification::find($id);
                if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                    $this->modificationServices->reportModification($modification);
                } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $this->modificationServices->reportModification($modification);
                } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $this->modificationServices->reportModification($modification);
                } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                    $this->modificationServices->reportModification($modification);
                } else {
                    abort(403);
                }


            }

           

            return response()->json([
                "message"=>"reported Successfully"
            ]);
        }

    }
    public function modificationUpdate(UpdateModificationRequest $request, Modification $modification)
    {


        $validated = $request->validated();

        if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated);
        } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated);
        } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated);
        } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated);
        } else {
            abort(403);
        }

        return response()->json([
            "message" => "Updated Successfully",



        ], 200);
        // }
    }

    public function siteModifications(Request $request, $site_code)
    {


        $data = [
            "site_code" => $site_code
        ];
        $validator = Validator::make($data, ["site_code" => ['required', "exists:sites,site_code"]]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validator->validated();

            $site = Site::where("site_code",  $validated['site_code'])->first();
            $modifications = [];
            if ($site->oz == "Cairo South" && $request->user()->can('viewCairoSouthSiteModifications', Modification::class)) {
                $modifications = $this->modificationServices->viewSiteModifications($site);
            } else if ($site->oz == "Giza" && $request->user()->can('viewGizaSiteModifications', Modification::class)) {
                $modifications = $this->modificationServices->viewSiteModifications($site);
            } else if ($site->oz == "Cairo North" && $request->user()->can('viewCairoNorthSiteModifications', Modification::class)) {
                $modifications = $this->modificationServices->viewSiteModifications($site);
            } else if ($site->oz == "Cairo East" && $request->user()->can('viewCairoEastSiteModifications', Modification::class)) {
                $modifications = $this->modificationServices->viewSiteModifications($site);
            } else {
                abort(403);
            }



            return response([
                "message" => "success",
                "modifications" => $modifications


            ], 200);
        }
    }



    public function newModification(StoreNewModificationRequest $request)
    {


        $validated = $request->validated();
        $validated['action_owner'] = $request->user()->id;
        $site = Site::where("site_code", $validated['site_code'])->first();

        $modification = [];
        if ($site->oz == "Cairo South" && $request->user()->can('createCairoSouthSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site);
        } else if ($site->oz == "Giza" && $request->user()->can('createGizaSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site);
        } else if ($site->oz == "Cairo North" && $request->user()->can('createCairoNorthSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site);
        } else if ($site->oz == "Cairo East" && $request->user()->can('createCairoEastSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site);
        } else {
            abort(403);
        }

        return response()->json([
            "message" => "Inserted Successfully",
            "modification" => $modification

        ], 200);
        // }
    }

    public function deleteModification(Request $request)
    {

        $ruls = [
            "id" => "required|exists:modifications,id",


        ];
        $validator = Validator::make($request->all(), $ruls);

        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validator->validated();
            $modification = Modification::find($validated['id']);
            if (($modification->oz == "Cairo South" && $request->user()->can('deleteCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
                $this->modificationServices->deleteModification($modification);
            } elseif (($modification->oz == "Cairo North" && $request->user()->can('deleteCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $this->modificationServices->deleteModification($modification);
            } elseif (($modification->oz == "Cairo East" && $request->user()->can('deleteCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $this->modificationServices->deleteModification($modification);
            } elseif (($modification->oz == "Giza" && $request->user()->can('deleteGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
                $this->modificationServices->deleteModification($modification);
            } else {
                abort(403);
            }



            return response()->json([
                "message" => "Deleted Successfully"

            ], 200);
        }
    }

    public function download(Request $request)
    {
        $this->authorize("viewAny", Modification::class);
        $ruls = [
            "column_name" => ["required"],
            "column_value" => ["required"],

        ];
        $validator = Validator::make($request->all(), $ruls);

        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
        } else {
            $validated = $validator->validated();

            return new AllModificationsExport($validated['column_name'], $validated["column_value"]);
        }
    }
}
