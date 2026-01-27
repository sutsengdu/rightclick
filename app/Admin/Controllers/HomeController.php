<?php

namespace App\Admin\Controllers;

use App\Models\Record;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
            // ->description('Description...')
            ->row(Dashboard::customdashboard())
            ->row(Dashboard::chart())
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
    public function online(Content $content)
    {
        return $content
        ->title('Online')
        ->description('Seats')
        ->row(Dashboard::online());
    }
    public function debt(Content $content)
    {
        return $content
        ->title('Debt')
        ->description('list')
        ->row(Dashboard::debt());
    }
    public function unpaid(Content $content)
    {
        return $content
        ->title('Unpaid')
        ->description('list')
        ->row(Dashboard::unpaid());
    }
    public function stock(Content $content)
    {
        return $content
        ->title('Stock')
        ->description('list')
        ->row(Dashboard::stock());
    }

}
