<?php

use App\Models\Invoice;
use App\Models\TranslatedPermission;
use App\Models\User;

//Get all permissions related to specific role
function permissionsOfRole($id)
{
    return TranslatedPermission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "translated_permissions.permission_id")
        ->where("role_has_permissions.role_id", $id)
        ->pluck('translated_permissions.name_' . app()->getLocale())
        ->toArray();
}

//Check if auth user has specific permission
function authUserPermission($permission)
{
    return backpack_auth()->user()->can($permission);
}

//check if auth user has specific role
function authUserRole($role)
{
    return backpack_auth()->user()->hasRole($role);
}

//total invoices functions
function totalInvoicesSum()
{
    return number_format(Invoice::sum('total') , 2);
}

function allInvoicesCount()
{
    return Invoice::count();
}

//invoices functions
function invoicesSum($valueStatus)
{
    return number_format(Invoice::where('value_status' , $valueStatus)->sum('total') , 2);
}

function invoicesCount($valueStatus)
{
    return Invoice::where('value_status' , $valueStatus)->count();
}

function invoicesPercentage($valueStatus)
{
    if(Invoice::count() > 0) {
        return round(Invoice::where('value_status' , $valueStatus)->count() / Invoice::count() * 100);
    }else{
        return round(Invoice::where('value_status' , $valueStatus)->count() / 1);
    }
}

//Count each department's invoices
function invoiceDepartment($column)
{
    $departments = \Illuminate\Support\Facades\DB::table('departments')->get('*')->toArray();
    $data = [];
    foreach ($departments as $dep)
    {
        $data[] = [
            'invCount'           => Invoice::where('department_id', $dep->id)->count(),
            'paidInv'            => Invoice::where('department_id', $dep->id)->where('value_status' , 1)->count(),
            'partiallyPaidInv'   => Invoice::where('department_id', $dep->id)->where('value_status' , 2)->count(),
            'nonPaidInv'         => Invoice::where('department_id', $dep->id)->where('value_status' , 3)->count(),
            'dep_ar'             => $dep->dep_name_ar,
            'dep_en'             => $dep->dep_name_en,
        ];
    }
    return  array_column($data, $column);
}






