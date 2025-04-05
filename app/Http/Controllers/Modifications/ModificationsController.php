<?php

namespace App\Http\Controllers\Modifications;

use Illuminate\Http\Response;
use auth;
use Carbon\Carbon;
use App\Models\Sites\Site;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Rules\checkIfIdExists;
use Illuminate\Validation\Rule;
use App\Models\Users\Notification;
use App\Events\ModificationCreated;
use App\Http\Controllers\Controller;
use App\Policies\ModificationPolicy;
use Illuminate\Support\Facades\Validator;
use App\Models\Modifications\Modification;
use App\Http\Resources\ModificationResource;
use App\Http\Requests\UpdateModificationRequest;
use App\Http\Requests\StoreNewModificationRequest;
use App\Exports\Modifications\AllModificationsExport;
use App\Services\Modifications\ModificationsServices;
use App\Http\Requests\FilterModificationsByDateRequest;

class ModificationsController extends Controller
{
    protected $modificationServices;
    public function __construct(ModificationsServices $modificationServices)
    {
        $this->modificationServices = $modificationServices;
    }



    public function modificationsWithoutQuotation()
    {
        $modificationsOfActionOwner = Modification::with('quotation')->where('action_owner', auth()->user()->id)->get();
        $modifications = [];
        if (count($modificationsOfActionOwner) > 0) {
            $modificationsWithoutPQ =  $modificationsOfActionOwner->filter(function ($item) { ///////////////return object of modifications
                return $item['quotation'] == null;
            });

            if (count($modificationsWithoutPQ) > 0) {
              
                $modifications = ModificationResource::collection($modificationsWithoutPQ->load(['subcontract', 'actions', "proj", "state", "request", 'report']));
            }
           
        }
        return response()->json([
            "message" => 'success',
            "modifications" => $modifications

        ], 200);
    }
    // public function checkModificationQuotation()
    // {
    //     $modifications = Modification::with('quotation')->get();
    //     $actionOwners = $modifications->groupBy('action_owner');

    //     $withoutQuotationsArray = [];
    //     foreach ($actionOwners as $key => $ownerModifications) {
    //         $withoutQuotations = $ownerModifications->filter(function ($item) {
    //             return $item['quotation'] == null;
    //         });

    //         $countOfNoQuotations = count($withoutQuotations);
    //         if ($countOfNoQuotations > 0) {
    //             $user = User::find($key);

    //             if ($user) {
    //                 $without['owner'] = $user->name;
    //                 $notification =    Notification::create([
    //                     "user_id" => $key,
    //                     "message" => "You have $countOfNoQuotations modification work orders without pre quotation "

    //                 ]);
    //             } else {
    //                 $without['owner'] = "anonymous";
    //             }

    //             $without['count_mod'] = $withoutQuotations;
    //             array_push($withoutQuotationsArray, $without);
    //         }
    //     }
    //     $withQuotations = $modifications->filter(function ($item) {
    //         return $item['quotation'] == null;
    //     });
    //     return response()->json([
    //         "modification" => Notification::all(),
    //     ]);
    // }
    public function analysis()
    {
        $analysis = [];
        $status = $this->modificationServices->getModificationStatus();
        $subcontractor = $this->modificationServices->getSubcontractors();
        $project =  $this->modificationServices->getProjects();
        $requester =  $this->modificationServices->getRequesters();
        $actions =  $this->modificationServices->getActions();
        $reported = $this->modificationServices->getReportedModification();
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
            $modifications = $this->modificationServices->filterModificationsByDate(null, $validated, null);
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
            "columnValue" => ['required', new checkIfIdExists()]
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
                $modifications = $this->modificationServices->filterModificationsByCategory(null, $validated, null);
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
                "details" => new ModificationResource($modification->load(['subcontract', 'actions', "proj", "state", "request", 'report', 'invoice'])),


            ], 200);
        } else if ($modification->oz == "Giza" && $request->user()->can('viewGizaSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification->load(['subcontract', 'actions', "proj", "state", "request", 'report', 'invoice'])),


            ], 200);
        } else if ($modification->oz == "Cairo North" && $request->user()->can('viewCairoNorthSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification->load(['subcontract', 'actions', "proj", "state", "request", 'report', 'invoice'])),


            ], 200);
        } else if ($modification->oz == "Cairo East" && $request->user()->can('viewCairoEastSiteModifications', Modification::class)) {
            return response()->json([
                "message" => "success",
                "details" => new ModificationResource($modification->load(['subcontract', 'actions', "proj", "state", "request", 'report', 'invoice'])),


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
            "modifications.*.est_cost" => ['required', 'numeric', 'min:200']
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
            $validated = $validator->validated();

            foreach ($validated['modifications'] as $mod) {
                $modification = Modification::find($mod["id"]);
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
                "message" => "reported Successfully"
            ]);
        }
    }
    public function modificationUpdate(UpdateModificationRequest $request, Modification $modification)
    {


        $validated = $request->safe()->except('actions');
        $actions = $request->safe()->only('actions');

        if (($modification->oz == "Cairo South" && $request->user()->can('updateCairoSouthSiteModification', Modification::class)) || ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated, $actions['actions']);
        } elseif (($modification->oz == "Cairo North" && $request->user()->can('updateCairoNorthSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated, $actions['actions']);
        } elseif (($modification->oz == "Cairo East" && $request->user()->can('updateCairoEastSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated, $actions['actions']);
        } elseif (($modification->oz == "Giza" && $request->user()->can('updateGizaSiteModification', Modification::class)) or ($request->user()->id == $modification->action_owner)) {
            $this->modificationServices->updateModification($modification, $validated, $actions['actions']);
        } else {
            abort(403);
        }

        return response()->json([
            "message" => "Updated Successfully",



        ], 200);
        // }
    }

    public function searchModificationsByWO($wo_code)
    {
        $data = [
            "wo_code" => $wo_code
        ];

        $validator = Validator::make($data, [
            "wo_code" => ['required', 'regex:/^(cn|CN|gz|GZ|CE|ce|cs|CS)[-]\d{3,8}$/']

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
            $validated = $validator->validated();

            $modification = Modification::where('wo_code', $validated['wo_code'])->get();

            if (count($modification) > 0) {
                return response()->json([
                    "message" => "success",
                    "modification" =>  ModificationResource::collection($modification->load(['subcontract', 'actions', "proj", "state", "request", 'report', 'invoice'])),


                ], 200);
            } else {
                return response()->json([
                    "message" => "No modification",



                ], 200);
            }
        }
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


        $validated = $request->safe()->except('actions');
        $actions = $request->safe()->only('actions');
        $validated['action_owner'] = $request->user()->id;
        $site = Site::where("site_code", $validated['site_code'])->first();

        $modification = [];
        if ($site->oz == "Cairo South" && $request->user()->can('createCairoSouthSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site, $actions['actions']);
        } else if ($site->oz == "Giza" && $request->user()->can('createGizaSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site, $actions['actions']);
        } else if ($site->oz == "Cairo North" && $request->user()->can('createCairoNorthSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site, $actions['actions']);
        } else if ($site->oz == "Cairo East" && $request->user()->can('createCairoEastSiteModification', Modification::class)) {
            $modification = $this->modificationServices->createSiteModification($validated, $site, $actions['actions']);
        } else {
            abort(403);
        }

        ModificationCreated::dispatch($modification);

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

   
}
