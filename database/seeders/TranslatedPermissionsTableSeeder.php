<?php

namespace Database\Seeders;

use App\Models\TranslatedPermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class TranslatedPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return int
     */
    public function run()
    {
        \DB::table('translated_permissions')->delete();

        $permissionsID = Permission::all()->pluck('id');

        $permissions_en = [
            'Invoices',
            'Invoices list',
            'Paid invoices',
            'Partially paid invoices',
            'Unpaid invoices',
            'Invoices archive',
            'Reports',
            'Invoices report',
            'Customers report',
            'Users',
            'Users list',
            'Users roles',
            'Users permissions',
            'settings',
            'Products',
            'Departments',

            'Add an invoice',
            'Delete an invoice',
            'Export Excel',
            'Change payment status',
            'Edit an invoice',
            'Archive an invoice',
            'Print an invoice',
            'Add an attachment',
            'Delete an attachment',

            'Add a user',
            'Edit a user',
            'Delete a user',

            'Show role',
            'Add role',
            'Edit role',
            'Delete role',

            'Show permission',
            'Add permission',
            'Edit permission',
            'Delete permission',

            'Add a product',
            'Edit a product',
            'Delete a product',

            'Add a department',
            'Edit a department',
            'Delete a department',
            'Notifications',
        ];
        $permissions_ar = [
            'الفواتير',
            'قائمة الفواتير',
            'الفواتير المدفوعة',
            'الفواتير المدفوعة جزئيا',
            'الفواتير الغير مدفوعة',
            'ارشيف الفواتير',
            'التقارير',
            'تقرير الفواتير',
            'تقرير العملاء',
            'المستخدمين',
            'قائمة المستخدمين',
            'أدوار المستخدمين',
            'صلاحيات المستخدمين',
            'الاعدادات',
            'المنتجات',
            'الاقسام',

            'اضافة فاتورة',
            'حذف الفاتورة',
            'تصدير (Excel)',
            'تغير حالة الدفع',
            'تعديل الفاتورة',
            'ارشفة الفاتورة',
            'طباعةالفاتورة',
            'اضافة مرفق',
            'حذف المرفق',

            'اضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',

            'عرض دور',
            'اضافة دور',
            'تعديل دور',
            'حذف دور',

            'عرض صلاحية',
            'اضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',

            'اضافة منتج',
            'تعديل منتج',
            'حذف منتج',

            'اضافة قسم',
            'تعديل قسم',
            'حذف قسم',
            'الإشعارات',
        ];

        foreach ($permissionsID as $permission_id){
            TranslatedPermission::create([
                'permission_id' =>  $permission_id,
            ]);
        }

        $permissions = TranslatedPermission::all()->pluck('permission_id')->toArray();
        for($i=0, $iMax = count($permissions_en); $i < $iMax; $i++){
            TranslatedPermission::where('permission_id', $permissions[$i])->update([
                'name_en' =>  $permissions_en[$i],
                'name_ar' =>  $permissions_ar[$i],
                'permission_id' => $permissions[$i],
            ]);
        }












    }
}
