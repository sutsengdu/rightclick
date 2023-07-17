<?php

namespace App\Admin\Controllers;

use App\Models\Inventory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InventoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Inventory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Inventory());
        $grid->column('id',__('ID'));
        $grid->column('item_name',__('Product Name'));
        $grid->column('qty',__('Quantity'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('price',__('Price'))->display(function ($value) {
            return intval($value);
        });
        $grid->column('type',__('Type'));
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
        $show = new Show(Inventory::findOrFail($id));
        $show->field('id',__('ID'));
        $show->field('item_name',__('Item Name'));
        $show->field('qty',__('Quantity'));
        $show->field('price',__('Price'));
        $show->field('Type',__('Type'));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Inventory());
        $form->display('id',__('ID'));
        $form->text('item_name',__('Item Name'));
        $form->number('qty',__('Quantity'));
        $form->number('price',__('Price'));
        $form->select('type',__('Type'))->options(['Drink' => 'Drink','Food' => 'Food']);
        return $form;
    }
}
