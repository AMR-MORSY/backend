<?php

namespace App\Models\Instruments;

use App\Models\Sites\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instrument extends Model
{
   
    use HasFactory;
    protected $table="instruments";
    protected $guarded=[];
    protected $hidden=["created_at","updated_at"];
    public function site()
    {
        return $this->belongsTo(Site::class,"site_code");
    }

    protected function careCeo(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value,$attributes){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }
            
            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );
    }
    protected function ntraCluster(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );
    }
    protected function axsees(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );
    }
    protected function serveCompound(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );
    }
    protected function universities(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );
    }
    protected function hotSpot(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );
    }



    protected function needAccessPermission(): Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );

    }

    protected function netEco() :Attribute
    {
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );

    }
    protected function eband():Attribute
    {
        
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );

    }

    protected function tdd():Attribute
    {
        
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );

    }



}
