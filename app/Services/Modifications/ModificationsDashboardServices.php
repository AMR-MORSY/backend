<?php

namespace App\Services\Modifications;

use App\Models\Modifications\ModificationStatus;
use App\Models\Modifications\Project;
use App\Models\Modifications\Subcontractor;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Collection;

class ModificationsDashboardServices
{
    public function modificationStatus(Collection $modifications)
    {
        $statusKeys = $modifications->groupBy('status')->keys();
        $data = [];
        foreach ($statusKeys as $key) {
            $status = ModificationStatus::find($key);
            $status_name = $status->name;
            $status_mod_count = $modifications->where('status', $key)->count();
            if ($status_name == "Done") {
                $status_mod['cost'] = $modifications->where('status', $key)->sum('final_cost');
            } elseif ($status_name == 'In Progress') {
                $status_mod['cost'] = $modifications->where('status', $key)->sum('est_cost');
            } elseif ($status_name == 'Waiting D6') {
                $status_mod['cost'] = $modifications->where('status', $key)->sum('est_cost');
            } elseif ($status_name == 'Cancelled') {
                $status_mod['cost'] = $modifications->where('status', $key)->sum('est_cost');
            }

            $status_mod['name'] = $status_name;
            $status_mod['count'] = $status_mod_count;
            array_push($data, $status_mod);
        }
        return $data;
    }

    private function modificationsAnalysis($cancelled_modifications, $waiting_D6_modifications, $in_progress_modifications, $Done_modifications)
    {
        $mod['Done'] = 0;
        $mod['count_Done'] = 0;
        $mod['in_progress'] = 0;
        $mod['count_in_progress'] = 0;
        $mod['waiting_D6'] = 0;
        $mod['count_waiting_D6'] = 0;
        $mod['cancelled'] = 0;
        $mod['count_cancelled'] = 0;
        if (count($cancelled_modifications) > 0) {
            $mod["count_cancelled"] = count($cancelled_modifications);
            $mod['cancelled'] = $cancelled_modifications->sum('est_cost');
        }
        if (count($waiting_D6_modifications) > 0) {
            $mod["count_waiting_D6"] = count($waiting_D6_modifications);
            $mod['waiting_D6'] = $waiting_D6_modifications->sum('est_cost');
        }
        if (count($in_progress_modifications) > 0) {
            $mod["count_in_progress"] = count($in_progress_modifications);
            $mod['in_progress'] = $in_progress_modifications->sum('est_cost');
        }
        if (count($Done_modifications) > 0) {
            $mod["count_Done"] = count($Done_modifications);
            $mod['Done'] = $Done_modifications->sum('final_cost');
        }

        return $mod;
    }
    public function subcontractors(Collection $modifications)
    {
        $subcontractors = $modifications->groupBy('subcontractor')->keys(); //////return collection of subcontractor's is as a key and each key has collection of modifications that belongs to that key
        $data = [];

        foreach ($subcontractors as $id) {
            $subcontractor = Subcontractor::find($id);

            if ($subcontractor) {
            
                $subcontractor_name = $subcontractor->name;
                $subcontractor_modifications = $modifications->where('subcontractor', $id);
                $work_orders = $subcontractor_modifications->count();
                $subcontractor_cancelled_modifications = $subcontractor_modifications->where('status', 4);
                $subcontractor_waiting_D6_modifications = $subcontractor_modifications->where('status', 3);
                $subcontractor_in_progress_modifications = $subcontractor_modifications->where('status', 2);
                $subcontractor_Done_modifications = $subcontractor_modifications->where('status', 1);

                $mod = $this->modificationsAnalysis($subcontractor_cancelled_modifications, $subcontractor_waiting_D6_modifications, $subcontractor_in_progress_modifications, $subcontractor_Done_modifications);
                $mod["subcontractor"] = $subcontractor_name;
                $mod["work_orders"] = $work_orders;
            }

            array_push($data, $mod);
        }


        return $data;
    }

    public function actionOwners(Collection $modifications)
    {
        $owners = $modifications->groupBy('action_owner')->keys();
        $data = [];

        foreach ($owners as $id) {
            $owner = User::find($id);

            if ($owner) {
                $owner_name = $owner->name;
                $owner_modifications = $modifications->where('action_owner', $id);
                $work_orders = $owner_modifications->count();
                $owner_cancelled_modifications = $owner_modifications->where('status', 4);
                $owner_waiting_D6_modifications = $owner_modifications->where('status', 3);
                $owner_in_progress_modifications = $owner_modifications->where('status', 2);
                $owner_Done_modifications = $owner_modifications->where('status', 1);

                $mod = $this->modificationsAnalysis($owner_cancelled_modifications, $owner_waiting_D6_modifications, $owner_in_progress_modifications, $owner_Done_modifications);
                $mod["owner"] = $owner_name;
                $mod["work_orders"] = $work_orders;
            }
            array_push($data, $mod);
        }
        return $data;
    }
    public function projects(Collection $modifications)
    {
        $projects = $modifications->groupBy('project')->keys();
        $data = [];

        foreach ($projects as $id) {
            $project = Project::find($id);

            if ($project) {
                $project_name = $project->name;
                $project_modifications = $modifications->where('project', $id);
                $work_orders = $project_modifications->count();
                $project_cancelled_modifications = $project_modifications->where('status', 4);
                $project_waiting_D6_modifications = $project_modifications->where('status', 3);
                $project_in_progress_modifications = $project_modifications->where('status', 2);
                $project_Done_modifications = $project_modifications->where('status', 1);

                $mod = $this->modificationsAnalysis($project_cancelled_modifications, $project_waiting_D6_modifications, $project_in_progress_modifications, $project_Done_modifications);
                $mod["project"] = $project_name;
                $mod["work_orders"] = $work_orders;
            }
            array_push($data, $mod);
        }
        return $data;
    }

    public function usedItems(Collection $modifications)
    {
        $modifications = $modifications->whereNotNull('quotation');
        $data = [];
        foreach ($modifications as $modification) {

            array_push($data, $modification->quotation->prices);
        }
        $data = collect($data)->lazy();

        $items = $data->flatten()->groupBy('item');
        $newItems = [];
        foreach ($items as $key => $values) {
            $mod['item'] = $key;
            $mod['description'] = $values->first()->description;
            $mod['installation'] = $values->first()->installation;
            $mod['supply'] = $values->first()->supply;
            $mod['S&I'] = $values->first()->sup_inst;
            $mod['Unit'] = $values->first()->unit;
            $mod['quantity'] = floor($values->sum('pivot.quantity')); 
            $mod['cost'] = floor($values->sum('pivot.item_price')); 
            array_push($newItems, $mod);
        }
        return $newItems;
        
    }
}
