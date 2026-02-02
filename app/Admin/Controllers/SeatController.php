<?php

namespace App\Admin\Controllers;

use App\Models\Seat;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SeatController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Seat';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Seat());
        $grid->column('id', __('ID'))->sortable();
        $grid->column('code', __('Seat'))->sortable();
        $grid->column('created_at', __('Created at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Seat::findOrFail($id));
        $show->field('id', __('ID'));
        $show->field('code', __('Seat'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Seat());
        $form->display('id', __('ID'));
        $form->text('code', __('Seat'))->rules('required|max:50|unique:seats,code,{{id}}');

        return $form;
    }
}

