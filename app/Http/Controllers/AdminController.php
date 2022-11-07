<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\TranslatedPermission;
use App\Models\User;
use Illuminate\Http\Request;
use App\Charts\InvoicesCharts;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => route('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        //line chart labels
        $this->data['chart'] = new InvoicesCharts;
        $labels = [];
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            if ($days_backwards == 1) {
            }
            if(app()->getLocale() == 'en'){
                $labels[] = $days_backwards.' days ago ';
            }else {
                $labels[] = ' منذ ' . $days_backwards . ' يوم';
            }

        }
        $this->data['chart']->labels($labels);
        $this->data['chart']->minimalist(false);
        $this->data['chart']->displayLegend(true);

        //line chart datasets
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            // Could also be an array_push if using an array rather than a collection.
            $users[] = User::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->count();
            $products[] = Product::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->count();
            $departments[] = Department::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->count();
            $invoices[] = Invoice::whereDate('created_at', today()
                ->subDays($days_backwards))
                ->count();
        }

        $this->data['chart']->dataset(trans('projectlang.users'), 'line', $users)
            ->color('rgb(77, 189, 116)')
            ->backgroundColor('rgba(77, 189, 116, 0.4)');

        $this->data['chart']->dataset(trans('projectlang.products'), 'line', $products)
            ->color('rgb(96, 92, 168)')
            ->backgroundColor('rgba(96, 92, 168, 0.4)');

        $this->data['chart']->dataset(trans('projectlang.departments'), 'line', $departments)
            ->color('rgb(255, 193, 7)')
            ->backgroundColor('rgba(255, 193, 7, 0.4)');

        $this->data['chart']->dataset(trans('projectlang.invoices'), 'line', $invoices)
            ->color('rgba(70, 127, 208, 1)')
            ->backgroundColor('rgba(70, 127, 208, 0.4)');


        //Bar chart
        $this->data['chart2'] = new InvoicesCharts;
        $this->data['chart2']->dataset(trans('projectlang.invoices'), 'bar', invoiceDepartment('invCount'))
            ->color('rgb(96, 92, 168)')
            ->backgroundColor('rgba(96, 92, 168, 0.4)');
        $this->data['chart2']->dataset(trans('projectlang.paid-invoices'), 'bar', invoiceDepartment('paidInv'))
            ->color('rgb(77, 189, 116)')
            ->backgroundColor('rgba(77, 189, 116, 0.4)');
        $this->data['chart2']->dataset(trans('projectlang.partialy-paid'), 'bar', invoiceDepartment('partiallyPaidInv'))
            ->color('rgb(255, 193, 7)')
            ->backgroundColor('rgba(255, 193, 7, 0.4)');
        $this->data['chart2']->dataset(trans('projectlang.non-paid'), 'bar', invoiceDepartment('nonPaidInv'))
            ->color('rgba(235, 77, 75, 1.0)')
            ->backgroundColor('rgba(235, 77, 75, 0.4)');

        // OPTIONAL
        $this->data['chart2']->displayAxes(true);
        $this->data['chart2']->displayLegend(true);
        $this->data['chart2']->barWidth(0.6);

        // MANDATORY. Set the labels for the dataset points
        if(app()->getLocale() === 'ar'){
            $this->data['chart2']->labels(invoiceDepartment('dep_ar'));
        }else{
            $this->data['chart2']->labels(invoiceDepartment('dep_en'));
        }

        return view(backpack_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(url('/'));
    }
}
