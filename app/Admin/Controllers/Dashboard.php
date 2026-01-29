<?php

namespace App\Admin\Controllers;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Record;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use Carbon\Carbon;
use App\Models\Inventory;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class Dashboard
{

    public static function customdashboard()
    {
        $onlinePlayer = DB::table('records')->where('online', 1)->count();
        $debtCount = DB::table('records')->where('debt', '>', 0)->count();
        $instockCount = intval(DB::table('inventories')->sum('qty'));
        $unpaidCount = DB::table('records')->where('paid', 0)->count();
        return view('vendor.laravel-admin.dashboard.customdashboard', compact('onlinePlayer', 'debtCount','instockCount','unpaidCount'));
    }

    public static function chart()
    {
        $inventories = DB::table('inventories')->where('type','Drink')->select('item_name', 'qty')->get();
        $inventoriesAll = DB::table('inventories')->where('type','Drink')->pluck('item_name')->toArray();
        $records = DB::table('records')->orderBy('order')->pluck('order')->toArray();
        // Retrieve the daily sums of 'total' column
        $dailyTotals = DB::table('records')
            ->selectRaw('DATE(created_at) as date, SUM(total) as sum_total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyAmounts = DB::table('records')
            ->selectRaw('DATE(created_at) as date, SUM(member_amount) as sum_amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for daily bar charts
        $dates = $dailyTotals->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('M d');
        });
        $totals = $dailyTotals->pluck('sum_total')->map(function ($total) {
            return intval($total);
        });
        $amounts = $dailyAmounts->pluck('sum_amount')->map(function ($amount) {
            return intval($amount);
        });

        // Weekly aggregates (all weeks, will paginate in frontend)
        $weeklyTotals = DB::table('records')
            ->selectRaw('YEARWEEK(created_at, 1) as yearweek, SUM(total) as sum_total')
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get();

        $weeklyAmounts = DB::table('records')
            ->selectRaw('YEARWEEK(created_at, 1) as yearweek, SUM(member_amount) as sum_amount')
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get();

        // Weekly orders (concatenated order strings per week) for weekly order count chart
        $weeklyOrders = DB::table('records')
            ->selectRaw('YEARWEEK(created_at, 1) as yearweek, GROUP_CONCAT(`order` SEPARATOR " ||| ") as orders_concat')
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get();

        // Use sequential week labels so the last one is always the latest week
        $weekLabels = $weeklyTotals->values()->map(function ($row, $index) {
            return 'W' . ($index + 1);
        });

        $weeklyTotalValues = $weeklyTotals->pluck('sum_total')->map(function ($total) {
            return intval($total);
        });

        $weeklyAmountValues = $weeklyAmounts->pluck('sum_amount')->map(function ($amount) {
            return intval($amount);
        });

        return view(
            'vendor.laravel-admin.addons.chart',
            compact(
                'inventories',
                'dates',
                'totals',
                'amounts',
                'inventoriesAll',
                'records',
                'weekLabels',
                'weeklyTotalValues',
                'weeklyAmountValues',
                'weeklyOrders'
            )
        );
    }

    public static function online()
    {
        $seats = DB::table('records')->where('online', 1)->pluck('seat')->toArray();
        $onlinememberids = DB::table('records')->where('online', 1)->pluck('member_ID')->toArray();

        // Create an associative array with seats as keys and member IDs as values
        $seatMemberIds = array_combine($seats, $onlinememberids);

        $statusArray  = ['A1', 'A2', 'A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13','A14','A15','A16','V1','V2'];

        return view('vendor.laravel-admin.addons.online')->with([
            'seats' => $seats,
            'statusArray' => $statusArray,
            'seatMemberIds' => $seatMemberIds,
        ]);

    }

    public static function debt()
    {
        $grid = new Grid(new Record());
        $grid->column('id',__('ID'));
        $grid->column('member_ID',__('Member ID'))->filter('like');;
        $grid->column('member_amount',__('Amount'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('order',__('Order'));
        $grid->column('order_amount',__('Amount'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('total',__('Total'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('paid', __('Paid'))->display(function ($value) {
            $color = $value ? 'green' : 'red';
            return "<span style='color: $color;'>".($value ? 'Yes' : 'No')."</span>";
        });
        $grid->column('debt',__('Debt'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('created_date',__('Date'))->display(function ($value) {
            return date('d-m-y', strtotime($value));
        })->filter('date');
        $grid->model()->where('debt', '>', 0);

        $grid->disableFilter();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableActions();
        $grid->disableRowSelector();
        return  $grid;
    }

    public static function stock()
    {
        $data = DB::table('inventories')->where('type','Drink')->get();
        return view('vendor.laravel-admin.addons.stock', ['inventories' => $data]);
    }

    public static function unpaid()
    {
        $grid = new Grid(new Record());
        $grid->column('id',__('ID'));
        $grid->column('seat',__('Seat'));
        $grid->column('member_ID',__('Member ID'))->filter('like');;
        $grid->column('member_amount',__('Amount'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('order',__('Order'));
        $grid->column('order_amount',__('Amount'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('total',__('Total'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('paid', __('Paid'))->display(function ($value) {
            $color = $value ? 'green' : 'red';
            return "<span style='color: $color;'>".($value ? 'Yes' : 'No')."</span>";
        });
        $grid->column('online', __('Online'))->display(function ($value) {
            $color = $value ? 'green' : 'red';
            return "<span style='color: $color;'>".($value ? 'Online' : 'End')."</span>";
        });
        $grid->column('debt',__('Debt'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('created_date',__('Date'))->display(function ($value) {
            return date('d-m-y', strtotime($value));
        })->filter('date');
        $grid->model()->where('paid', '=', 0);
        $grid->disableFilter();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableActions();
        $grid->disableRowSelector();
        return  $grid;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
     public static function title()
    {
        return view('admin::dashboard.title');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function environment()
    {
        $envs = [
            ['name' => 'PHP version',       'value' => 'PHP/'.PHP_VERSION],
            ['name' => 'Laravel version',   'value' => app()->version()],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => 'Uname',             'value' => php_uname()],
            ['name' => 'Server',            'value' => Arr::get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => 'Cache driver',      'value' => config('cache.default')],
            ['name' => 'Session driver',    'value' => config('session.driver')],
            ['name' => 'Queue driver',      'value' => config('queue.default')],

            ['name' => 'Timezone',          'value' => config('app.timezone')],
            ['name' => 'Locale',            'value' => config('app.locale')],
            ['name' => 'Env',               'value' => config('app.env')],
            ['name' => 'URL',               'value' => config('app.url')],
        ];

        return view('admin::dashboard.environment', compact('envs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function extensions()
    {
        $extensions = [
            'helpers' => [
                'name' => 'laravel-admin-ext/helpers',
                'link' => 'https://github.com/laravel-admin-extensions/helpers',
                'icon' => 'gears',
            ],
            'log-viewer' => [
                'name' => 'laravel-admin-ext/log-viewer',
                'link' => 'https://github.com/laravel-admin-extensions/log-viewer',
                'icon' => 'database',
            ],
            'backup' => [
                'name' => 'laravel-admin-ext/backup',
                'link' => 'https://github.com/laravel-admin-extensions/backup',
                'icon' => 'copy',
            ],
            'config' => [
                'name' => 'laravel-admin-ext/config',
                'link' => 'https://github.com/laravel-admin-extensions/config',
                'icon' => 'toggle-on',
            ],
            'api-tester' => [
                'name' => 'laravel-admin-ext/api-tester',
                'link' => 'https://github.com/laravel-admin-extensions/api-tester',
                'icon' => 'sliders',
            ],
            'media-manager' => [
                'name' => 'laravel-admin-ext/media-manager',
                'link' => 'https://github.com/laravel-admin-extensions/media-manager',
                'icon' => 'file',
            ],
            'scheduling' => [
                'name' => 'laravel-admin-ext/scheduling',
                'link' => 'https://github.com/laravel-admin-extensions/scheduling',
                'icon' => 'clock-o',
            ],
            'reporter' => [
                'name' => 'laravel-admin-ext/reporter',
                'link' => 'https://github.com/laravel-admin-extensions/reporter',
                'icon' => 'bug',
            ],
            'redis-manager' => [
                'name' => 'laravel-admin-ext/redis-manager',
                'link' => 'https://github.com/laravel-admin-extensions/redis-manager',
                'icon' => 'flask',
            ],
        ];

        foreach ($extensions as &$extension) {
            $name = explode('/', $extension['name']);
            $extension['installed'] = array_key_exists(end($name), Admin::$extensions);
        }

        return view('admin::dashboard.extensions', compact('extensions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function dependencies()
    {
        $json = file_get_contents(base_path('composer.json'));

        $dependencies = json_decode($json, true)['require'];

        return Admin::component('admin::dashboard.dependencies', compact('dependencies'));
    }
}
