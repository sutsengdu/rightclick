<?php

namespace App\Admin\Controllers;

use App\Models\Outcome;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OutcomeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Outcome';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Outcome());
        $grid->column('id');
        $grid->column('description');
        $grid->column('price')->display(function ($value) {
            return intval($value);
        });
        $grid->column('created_at',__('Date'))->display(function ($value) {
            return date('d-m-y', strtotime($value));
        })->filter('date');


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
        $show = new Show(Outcome::findOrFail($id));
        $show->field('id',__('ID'));
        $show->field('description',__('description'));
        $show->field('price',__('price'))->display(function ($value) {
            return intval($value);
        });
        $show->field('created_at',__('Date'))->display(function ($value) {
            return date('d-m-y', strtotime($value));
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Outcome());

        $form->textarea('description',__('description'));
        $form->number('price',__('price'))->default(0);

        return $form;
    }
}
