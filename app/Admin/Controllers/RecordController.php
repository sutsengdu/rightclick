<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Inventory;
class RecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Record';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Record());

        // $grid->quickSearch('id','seat','member_ID','member_amount','order','order_amount','total','paid','online','debt');
        $grid->column('id',__('ID'))->sortable();
        $grid->column('seat',__('Seat'));
        $grid->column('member_ID',__('Member ID'))->filter('like');
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
        $show = new Show(Record::findOrFail($id));
        $show->field('id',__('ID'));
        $show->field('seat',__('Seat'));
        $show->field('member_ID',__('Member ID'));
        $show->field('member_amount',__('Amount'));
        $show->field('order',__('Order'));
        $show->field('order_amount',__('Amount'));
        $show->field('total',__('Total'));
        $show->field('paid', __('Paid'));
        $show->field('debt',__('Debt'));
        $show->field('created_date',__('Date'))->display(function ($value) {
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
        $form = new Form(new Record());
        $form->display('id',__('ID'));
        $form->column(1/2, function($form){

            $form->select('seat',__('Seat'))->options([
                'A1' => 'A1',
                'A2' => 'A2',
                'A3' => 'A3',
                'A4' => 'A4',
                'A5' => 'A5',
                'A6' => 'A6',
                'A7' => 'A7',
                'A8' => 'A8',
                'A9' => 'A9',
                'A10' => 'A10',
                'A11' => 'A11',
                'A12' => 'A12',
                'A13' => 'A13',
                'A14' => 'A14',
                'A15' => 'A15',
                'A16' => 'A16',
                'V1' => 'V1',
                'V2' => 'V2',
            ]);
            $form->text('member_ID',__('Member ID'))->default('Time');
            $form->number('member_amount',__('Member Amount'))->default(0);
            $form->number('order_amount',__('Order Amount'))->default(0);
            $form->html('
                <button type="button" id="addSumBtn" class="btn btn-success btn-block">Sum</button>
                <script>
                    $(document).ready(function() {
                        $("#addSumBtn").click(function() {
                            var memberAmount = parseInt($("input[name=\"member_amount\"]").val()) || 0;
                            var orderAmount = parseInt($("input[name=\"order_amount\"]").val()) || 0;
                            var total = memberAmount + orderAmount;
                            $("input[name=\"total\"]").val(total);
                        });
                    });
                </script>
            ');
            $form->number('total', __('Total'))->default(0);

        });
        $form->column(1/2, function($form){

            $inventoryDrinks = Inventory::where('type','Drink')->pluck('item_name', 'id')->toArray();
            $form->select('Select Drink')->options($inventoryDrinks);
            $inventoryFoods = Inventory::where('type','Food')->pluck('item_name', 'id')->toArray();
            $form->select('Select Food')->options($inventoryFoods);
            $form->html('
                <button type="button" id="addToOrderBtn" class="btn btn-primary">Add to Order</button>
                <script>
                $(document).ready(function() {
                    $("#addToOrderBtn").click(function() {
                        var selectedDrink = $("select[name=\'Select Drink\'] option:selected").text();
                        var selectedFood = $("select[name=\'Select Food\'] option:selected").text();
                        var orderField = $("textarea[name=\'order\']");
                        var orderText = selectedDrink;

                        var selectedDrinkId = $("select[name=\'Select Drink\']").val();
                        if (selectedDrinkId) {
                            $.ajax({
                                url: "subtract-drink-qty",
                                type: "POST",
                                data: { drinkId: selectedDrinkId },
                                headers: {
                                    \'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
                                },
                                success: function(response) {
                                    console.log(response);
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        }

                        if(selectedFood !== null) {
                            orderText += " " + selectedFood;
                        }
                        orderField.val(orderField.val() + orderText + " ");
                    });
                });
            </script>
            ');
            $form->textarea('order',__('Order'));

            $form->switch('paid', __('Paid'))->states([
                'on' => ['value' => '1', 'text' => 'Yes'],
                'off' => ['value' => '0', 'text' => 'No']
            ])->default(0);
            $form->switch('online', __('Online'))->states([
                'on' => ['value' => '1', 'text' => 'Online'],
                'off' => ['value' => '0', 'text' => 'End']
            ])->default(0);
            $form->text('debt',__('Debt'))->default(0);
        });
        return $form;

    }
    public function subtractDrinkQty(Request $request)
    {
        $drinkId = $request->input('drinkId');

        // Get the selected drink from the database
        $drink = Inventory::findOrFail($drinkId);

        // Subtract 1 from the quantity
        $drink->qty -= 1;

        // Save the updated quantity
        $drink->save();

        // You can return a response if needed
        return response()->json(['success' => true]);
    }

}
