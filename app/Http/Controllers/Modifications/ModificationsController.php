<?php

namespace App\Http\Controllers\Modifications;

use auth;
use Carbon\Carbon;
use App\Models\Sites\Site;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Rules\checkIfIdExists;
use Illuminate\Validation\Rule;
use App\Events\ModificationCreated;
use App\Http\Controllers\Controller;
use App\Policies\ModificationPolicy;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\Modifications\Modification;
use App\Http\Resources\ModificationResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\UpdateModificationRequest;
use App\Models\Modifications\ModificationReport;
use App\Http\Requests\StoreNewModificationRequest;
use App\Exports\Modifications\AllModificationsExport;
use App\Services\Modifications\ModificationsServices;
use App\Http\Requests\FilterModificationsByDateRequest;
use App\Notifications\UnreportedModificationsNotification;
use App\Notifications\ModificationsWithoutQuotationNotification;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class ModificationsController extends Controller
{
    protected $modificationServices;
    public function __construct(ModificationsServices $modificationServices)
    {
        $this->modificationServices = $modificationServices;
    }

  


    public function unreportedModifications(Request $request)
    {
        $oz = $request->query('oz');
        $action_owner = $request->query('action_owner');
        $data['oz'] = $oz;
        $data['action_owner'] = $action_owner;
        $validator = Validator::make($data, [
            "oz" => ['nullable', 'in:Cairo South,Cairo East,Cairo North,Giza'],
            'action_owner' => ['nullable', 'exists:users,id']

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
            $user = User::find(auth()->user()->id);
            $modifications = [];
            if (isset($validated['oz']) && $validated['oz'] != null & isset($validated['action_owner']) && $validated['action_owner'] != null) {

                if ($validated['oz'] == "Cairo East") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_E_Mod_Admin'])) {
                        $UnreportedModifications = Modification::where('reported', 2)->where('action_owner', $validated['action_owner'])->where('oz', "Cairo East")->get();


                        $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                } elseif ($validated['oz'] == "Cairo North") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_N_Mod_Admin'])) {
                        $UnreportedModifications = Modification::where('reported', 2)->where('action_owner', $validated['action_owner'])->where('oz', "Cairo North")->get();


                        $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                } elseif ($validated['oz'] == "Cairo South") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_S_Mod_Admin'])) {
                        $UnreportedModifications = Modification::where('reported', 2)->where('action_owner', $validated['action_owner'])->where('oz', "Cairo South")->get();


                        $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                } elseif ($validated['oz'] == "Giza") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_GZ_Mod_Admin'])) {
                        $UnreportedModifications = Modification::where('reported', 2)->where('action_owner', $validated['action_owner'])->where('oz', "Giza")->get();


                        $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                }
            } else {
                if ($user->hasAnyPermission(['read_CS_modifications', 'read_CN_modifications', 'read_CE_modifications', 'read_GZ_modifications'])) {
                    $UnreportedModifications = Modification::where('reported', 2)->where('action_owner', $user->id)->get();


                    $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));

                    return response()->json([
                        "message" => "success",
                        "modifications" => $modifications
                    ], 200);
                } else {
                    abort(403);
                }
            }
        }
        // return response()->json([
        //     "data"=>$oz
        // ],200);
    }

    public function modificationsWithoutQuotation(Request $request)
    {
        $oz = $request->query('oz');
        $action_owner = $request->query('action_owner');
        $data['oz'] = $oz;
        $data['action_owner'] = $action_owner;
        $validator = Validator::make($data, [
            "oz" => ['nullable', 'in:Cairo South,Cairo East,Cairo North,Giza'],
            'action_owner' => ['nullable', 'exists:users,id']

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
            $user = User::find(auth()->user()->id);
            $modifications = [];
            ///////if zone is set this means that the notification is directed to the area owner or the modification admin and the query comes from them 
            if (isset($validated['oz']) && $validated['oz'] != null & isset($validated['action_owner']) && $validated['action_owner'] != null) {
                if ($validated['oz'] == "Cairo East") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_E_Mod_Admin'])) {
                        $Unreported = Modification::with('quotation')->where('action_owner', $validated['action_owner'])->where('oz', "Cairo East")->get();

                        if (count($Unreported) > 0) {
                            $UnreportedModifications =    $Unreported->filter(function ($item) { ///////////////return object of modifications
                                return $item['quotation'] == null;
                            });
                            $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));
                        }




                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                } elseif ($validated['oz'] == "Cairo North") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_N_Mod_Admin'])) {
                        $Unreported = Modification::with('quotation')->where('action_owner', $validated['action_owner'])->where('oz', "Cairo North")->get();
                        if (count($Unreported) > 0) {
                            $UnreportedModifications =    $Unreported->filter(function ($item) { ///////////////return object of modifications
                                return $item['quotation'] == null;
                            });
                            $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));
                        }

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                } elseif ($validated['oz'] == "Cairo South") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_S_Mod_Admin'])) {
                        $Unreported = Modification::with('quotation')->where('action_owner', $validated['action_owner'])->where('oz', "Cairo South")->get();


                        if (count($Unreported) > 0) {
                            $UnreportedModifications =    $Unreported->filter(function ($item) { ///////////////return object of modifications
                                return $item['quotation'] == null;
                            });
                            $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));
                        }

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                } elseif ($validated['oz'] == "Giza") {
                    if ($user->hasRole(['Modification_Admin', 'Cairo_GZ_Mod_Admin'])) {
                        $Unreported = Modification::with('quotation')->where('action_owner', $validated['action_owner'])->where('oz', "Giza")->get();


                        if (count($Unreported) > 0) {
                            $UnreportedModifications =    $Unreported->filter(function ($item) { ///////////////return object of modifications
                                return $item['quotation'] == null;
                            });
                            $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));
                        }

                        return response()->json([
                            "message" => "success",
                            "modifications" => $modifications
                        ], 200);
                    } else {
                        abort(403);
                    }
                }
            }
            /////////////////////if the zone is null that means the query comes from the site engineer 
            else {
                if ($user->hasAnyPermission(['read_CS_modifications', 'read_CN_modifications', 'read_CE_modifications', 'read_GZ_modifications'])) {
                    $Unreported = Modification::with('quotation')->where('action_owner', $user->id)->get();

                    if (count($Unreported) > 0) {
                        $UnreportedModifications =    $Unreported->filter(function ($item) { ///////////////return object of modifications
                            return $item['quotation'] == null;
                        });
                        $modifications = ModificationResource::collection($UnreportedModifications->load(['subcontract', 'actions', "proj", "state", "request", 'report']));
                    }
                    return response()->json([
                        "message" => "success",
                        "modifications" => $modifications
                    ], 200);
                } else {
                    abort(403);
                }
            }
        }
    }

    // private function getAreaOwners($modificationZone)
    // {

    //     if ($modificationZone == "Cairo East") {
    //         $users = User::role(['Modification_Admin', 'Cairo_E_Mod_Admin'])->get();
    //         return $users;
    //     } elseif ($modificationZone == "Cairo North") {
    //         $users = User::role(['Modification_Admin', 'Cairo_N_Mod_Admin'])->get();
    //         return $users;
    //     } elseif ($modificationZone == "Cairo South") {
    //         $users = User::role(['Modification_Admin', 'Cairo_S_Mod_Admin'])->get();
    //         return $users;
    //     } elseif ($modificationZone == "Giza") {
    //         $users = User::role(['Modification_Admin', 'Cairo_GZ_Mod_Admin'])->get();
    //         return $users;
    //     }
    // }
    public function checkModificationQuotation()
    {
        // $UnreportedModification = ModificationReport::where('name', 'No')->first();
        // $UnreportedModifications = $UnreportedModification->modifications;
        // $actionOwners = $UnreportedModifications->groupBy('action_owner');

        // $frontendUrl = Config::get('app.frontend_url');

        // foreach ($actionOwners as $key => $ownerModifications) {


        //     $countOfModifications = count($ownerModifications);
        //     if ($countOfModifications > 0) {
        //         $user = User::find($key); /////////////////refer to the action_owner

        //         $operation_zones = $ownerModifications->groupBy('oz');
        //         foreach ($operation_zones as $zone => $operations) {//////////////because the site engineer works in different zones so we have to get the count of modifications in every zone
        //             $areaAwners = $this->getAreaOwners($zone); //// area owners and modification admin
        //             $countZoneModifications=count($operations);
        //             $data["message"] = "You have$countZoneModifications unreported modification work orders.Reporting a modification work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
        //             $data["title"] = "Unreported Modifications";
        //             $data["slug"] = "You have$countZoneModifications unreported modification work orders in $zone created by $user->name";
        //             $data["link"] = "$frontendUrl/modifications/unreported-modifications/$zone/$user->id";///////this link will returns to  area owners and modification admin the modification of that specific action owner

        //             Notification::send($areaAwners, new UnreportedModificationsNotification($data));/////////////////sending notification to modification admin and area owner
        //         }

        //         $data["message"] = "You have $countOfModifications unreported modification work orders.Reporting a modification work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
        //         $data["title"] = "Unreported Modifications";
        //         $data["slug"] = "You have $countOfModifications unreported modification work orders";
        //         $data["link"] = "$frontendUrl/modifications/unreported-modifications";
        //         $user->notify(new UnreportedModificationsNotification($data));////////////////////sending notification to site engineer with all unreported modifications regardless the zone
        //     }
        // }


        // $modifications = Modification::with('quotation')->get();
        // $actionOwners = $modifications->groupBy('action_owner');
        // $frontendUrl = Config::get('app.frontend_url');
        // foreach ($actionOwners as $key => $ownerModifications) {
        //     $withoutQuotations = $ownerModifications->filter(function ($item) {
        //         return $item['quotation'] == null;
        //     });

        //     $countOfNoQuotations = count($withoutQuotations);
        //     if ($countOfNoQuotations > 0) {
        //         $user = User::find($key);

        //         $operation_zones = $withoutQuotations->groupBy('oz');
        //         foreach ($operation_zones as $zone => $operations) {
        //             $areaAwners = $this->getAreaOwners($zone);
        //             $countZoneModifications = count($operations);
        //             $data["message"] = "You have $countZoneModifications modification work orders without pre quotation.Attaching a soft copy of the pre-quotation to the modification's work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
        //             $data["title"] = "Modifications without Quotation";
        //             $data["slug"] = "You have $countZoneModifications modification work orders without pre quotation in $zone created by $user->name";
        //             $data["link"] = "$frontendUrl/modifications/without/pq/$zone/$user->id";
        //             Notification::send($areaAwners, new ModificationsWithoutQuotationNotification($data));
        //         }




        //         $data["message"] = "You have $countOfNoQuotations modification work orders without pre quotation.Attaching a soft copy of the pre-quotation to the modification's work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
        //         $data["title"] = "Modifications without Quotation";
        //         $data["slug"] = "You have $countOfNoQuotations modification work orders without pre quotation";
        //         $data["link"] = "$frontendUrl/modifications/without/pq";

        //         $user->notify(new ModificationsWithoutQuotationNotification($data));
        //     }
        // }

        // return response()->json([
        //     "message" => "success"
        // ], 200);







    }
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
