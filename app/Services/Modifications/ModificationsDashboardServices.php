<?php

namespace App\Services\Modifications;

use App\Models\Modifications\ModificationStatus;
use App\Models\Modifications\Subcontractor;
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

    public function subcontractors(Collection $modifications)
    {
        $subcontractors = $modifications->groupBy('subcontractor')->keys(); //////return collection of subcontractor's is as a key and each key has collection of modifications that belongs to that key
        $data = [];

        foreach ($subcontractors as $id) {
            $subcontractor = Subcontractor::find($id);

            if ($subcontractor) {
                $mod['Done'] = 0;
                $mod['count_Done'] = 0;
                $mod['in_progress'] = 0;
                $mod['count_in_progress'] = 0;
                $mod['waiting_D6'] = 0;
                $mod['count_waiting_D6'] = 0;
                $mod['cancelled'] = 0;
                $mod['count_cancelled'] = 0;


                $subcontractor_name = $subcontractor->name;
                $subcontractor_modifications = $modifications->where('subcontractor', $id);
                $work_orders=$subcontractor_modifications->count();
                $subcontractor_cancelled_modifications = $subcontractor_modifications->where('status', 4);
                $subcontractor_waiting_D6_modifications = $subcontractor_modifications->where('status', 3);
                $subcontractor_in_progress_modifications = $subcontractor_modifications->where('status', 2);
                $subcontractor_Done_modifications = $subcontractor_modifications->where('status', 1);

                if (count($subcontractor_cancelled_modifications) > 0) {
                    $mod["count_cancelled"] = count($subcontractor_cancelled_modifications);
                    $mod['cancelled'] = $subcontractor_cancelled_modifications->sum('est_cost');
                }
                if (count($subcontractor_waiting_D6_modifications) > 0) {
                    $mod["count_waiting_D6"] = count($subcontractor_waiting_D6_modifications);
                    $mod['waiting_D6'] = $subcontractor_waiting_D6_modifications->sum('est_cost');
                }
                if (count($subcontractor_in_progress_modifications) > 0) {
                    $mod["count_in_progress"] = count($subcontractor_in_progress_modifications);
                    $mod['in_progress'] = $subcontractor_in_progress_modifications->sum('est_cost');
                }
                if (count($subcontractor_Done_modifications) > 0) {
                    $mod["count_Done"] = count($subcontractor_Done_modifications);
                    $mod['Done'] = $subcontractor_Done_modifications->sum('final_cost');
                }
                $mod["subcontractor"] = $subcontractor_name;
                $mod["work_orders"] = $work_orders;
            }

            array_push($data, $mod);
        }
     

        return $data;
    }
}
