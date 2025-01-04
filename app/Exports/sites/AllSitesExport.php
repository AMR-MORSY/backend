<?php

namespace App\Exports\sites;

use App\Models\Sites\Site;
use Maatwebsite\Excel\Excel;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class AllSitesExport implements FromCollection, WithHeadings, Responsable
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection

     */
    private $fileName = 'AllSites.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];
    public function collection()
    {
        // $sites = Site::with(["instrument"=>function($query){
        //     $query->select(
        //         "on_air_date",
        //         "topology",
        //         "ntra_cluster",
        //         "care_ceo",
        //         "axsees",
        //         "serve_compound",
        //         "no_ldn_accounts",
        //         "no_tp_accounts",
        //         "power_source",
        //         "power_meter_type",
        //         "power_cable_cross_sec",
        //         "power_cable_length",
        //         "gen_capacity",
        //         "no_bts",
        //         "mrfu_2G",
        //         "mrfu_3G",
        //         "mrfu_4G",
        //         "tdd",
        //         "no_mw",
        //         "mw_type",
        //         "eband",
        //         "ac1_type",
        //         "ac1_hp",
        //         "ac2_type",
        //         "ac2_hp",
        //         "network_type",
        //         "rec_brand",
        //         "module_capacity",
        //         "no_module",
        //         "pld_value",
        //         "net_eco",
        //         "net_eco_activation",
        //         "battery_brand",
        //         "battery_volt",
        //         "battery_amp_hr",
        //         "no_strings",
        //         "no_batteries",
        //         "batteries_status",
        //         "last_pm_date",
        //         "overhaul_power_consumption",
        //         "need_access_permission",
        //         "permission_type"
        //     );
        // }])->select(
        //    "site_code",
        //    "site_name",
        //    "BSC",
        //    "RNC",
        //    'office',
        //    'type',
        //    'category',
        //    'severity',
        //    'sharing',
        //    'host',
        //    'gest',
        //    'oz',
        //    'zone',
        //    "2G_cells",
        //    "3G_cells",
        //    "4G_cells",
        //     "status",
        // )->get();
        $sites = DB::table('sites')->join("instruments", "sites.site_code", "=", "instruments.site_code")->select(
            "sites.site_code",
            "sites.site_name",
            "sites.BSC",
            "sites.RNC",
            'sites.office',
            'sites.type',
            'sites.category',
            'sites.severity',
            'sites.sharing',
            'sites.host',
            'sites.gest',
            'sites.vf_code',
            'sites.we_code',
            'sites.et_code',
            'sites.oz',
            'sites.zone',
            "sites.2G_cells",
            "sites.3G_cells",
            "sites.4G_cells",
            "sites.status",
            "instruments.on_air_date",
            "instruments.topology",
            "instruments.structure",
            "instruments.equip_room",
            "instruments.ntra_cluster",
            "instruments.care_ceo",
            "instruments.axsees",
            "instruments.serve_compound",
            "instruments.universities",
            "instruments.hot_spot",
            "instruments.power_source",
            "instruments.power_meter_type",
            "instruments.power_cable_cross_sec",
            "instruments.power_cable_length",
            "instruments.gen_capacity",
            "instruments.no_bts",
            "instruments.mrfu_2G",
            "instruments.mrfu_3G",
            "instruments.mrfu_4G",
            "instruments.tdd",
            "instruments.no_mw",
            "instruments.mw_type",
            "instruments.eband",
            "instruments.ac1_type",
            "instruments.ac1_hp",
            "instruments.ac2_type",
            "instruments.ac2_hp",
            "instruments.network_type",
            "instruments.rec_brand",
            "instruments.module_capacity",
            "instruments.no_module",
            "instruments.pld_value",
            "instruments.net_eco",
            "instruments.net_eco_activation",
            "instruments.battery_brand",
            "instruments.battery_volt",
            "instruments.battery_amp_hr",
            "instruments.no_strings",
            "instruments.no_batteries",
            "instruments.batteries_status",
            "instruments.last_pm_date",
            "instruments.overhaul_power_consumption",
            "instruments.need_access_permission",
            "instruments.permission_type"
        )->get();

        // $sites = Site::with("instrument")->get();
        return $sites;
    }
    public function headings(): array
    {
        return [

            // '#',
            'Site Code',
            'Site Name',
            "BSC",
            "RNC",
            "Office",
            "Type",
            "Category",
            "Severity",
            "Sharing",
            "Host",
            "Gest",
            "VF Code",
            "WE Code",
            "ET Code",
            "OZ",
            "zone",
            "2G",
            "3G",
            "4G",
            "status",
            "on_air_date",
            "topology",
            "Structure",
            "Equip-Room",
            "ntra_cluster",
            "care_ceo",
            "axsees",
            "serve_compound",
            "Universities",
            "Hot Spot",
            "power_source",
            "power_meter_type",
            "power_cable_cross_sec",
            "power_cable_length",
            "gen_capacity",
            "no_bts",
            "mrfu_2G",
            "mrfu_3G",
            "mrfu_4G",
            "tdd",
            "no_mw",
            "mw_type",
            "eband",
            "ac1_type",
            "ac1_hp",
            "ac2_type",
            "ac2_hp",
            "network_type",
            "rec_brand",
            "module_capacity",
            "no_module",
            "pld_value",
            "net_eco",
            "net_eco_activation",
            "battery_brand",
            "battery_volt",
            "battery_amp_hr",
            "no_strings",
            "no_batteries",
            "batteries_status",
            "last_pm_date",
            "overhaul_power_consumption",
            "need_access_permission",
            "permission_type"

        ];
    }
}
