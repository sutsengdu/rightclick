<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Inventory;
use App\Models\Seat;
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

            $seatOptions = Seat::query()
                ->orderByRaw('LEFT(code, 1), CAST(SUBSTRING(code, 2) AS UNSIGNED), code')
                ->pluck('code', 'code')
                ->toArray();
            $form->select('seat', __('Seat'))->options($seatOptions);
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
            $drinkOptions = '<option value="">-- Choose Drink --</option>';
            foreach($inventoryDrinks as $id => $name) {
                $drinkOptions .= '<option value="'.$id.'">'.$name.'</option>';
            }

            $inventoryFoods = Inventory::where('type','Food')->pluck('item_name', 'id')->toArray();
            $foodOptions = '<option value="">-- Choose Food --</option>';
            foreach($inventoryFoods as $id => $name) {
                $foodOptions .= '<option value="'.$id.'">'.$name.'</option>';
            }

            $form->html('
                <div class="box box-solid box-primary" style="margin-top: 15px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Manage Order Items</h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-sm-6">
                                <label>Select Drink</label>
                                <select class="form-control custom-item-select" name="custom_drink">
                                    '.$drinkOptions.'
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Select Food</label>
                                <select class="form-control custom-item-select" name="custom_food">
                                    '.$foodOptions.'
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-sm-6">
                                <label>Item Quantity</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                    <input type="number" id="itemQty" class="form-control" value="1" min="1">
                                </div>
                            </div>
                            <div class="col-sm-6" style="padding-top: 25px;">
                                <button type="button" id="addToOrderBtn" class="btn btn-primary btn-block"><i class="fa fa-plus-circle"></i> Add to Order</button>
                            </div>
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <label><i class="fa fa-list"></i> Current Order Items</label>
                            <div class="well well-sm" style="background-color: #f9fafc; min-height: 50px;">
                                <ul id="order-list" class="list-group" style="margin-bottom: 0;">
                                    <!-- Items will be appended here dynamically -->
                                    <li class="list-group-item text-muted text-center" id="empty-state-li" style="border: none; background: transparent;">No items added yet</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <style>
                    #order-list .list-group-item { margin-bottom: 5px; border-radius: 4px; border-left: 4px solid #3c8dbc; }
                    .remove-item-btn { padding: 2px 8px; font-size: 12px; }
                </style>
                
                <script>
                $(document).ready(function() {
                    const csrfToken = $(\'meta[name="csrf-token"]\').attr(\'content\');
                    const manageStockUrl = "'.route('admin.manage-inventory-stock').'";
                    
                    function updateOrderTextarea() {
                        let orderText = "";
                        $("#order-list li:not(#empty-state-li)").each(function() {
                            let iName = $(this).data("item-name");
                            if (iName) {
                                orderText += iName + " ";
                            }
                        });
                        $("textarea[name=\'order\']").val(orderText.trim());
                    }

                    // Auto-clear opposite dropdown
                    $(".custom-item-select").change(function() {
                        if($(this).attr("name") === "custom_drink" && $(this).val() !== "") {
                            $("select[name=\'custom_food\']").val("");
                        } else if($(this).attr("name") === "custom_food" && $(this).val() !== "") {
                            $("select[name=\'custom_drink\']").val("");
                        }
                    });

                    $("#addToOrderBtn").off("click").on("click", function() {
                        let selectedDrinkId = $("select[name=\'custom_drink\']").val();
                        let selectedDrinkName = selectedDrinkId ? $("select[name=\'custom_drink\'] option:selected").text() : "";
                        
                        let selectedFoodId = $("select[name=\'custom_food\']").val();
                        let selectedFoodName = selectedFoodId ? $("select[name=\'custom_food\'] option:selected").text() : "";
                        
                        let qty = parseInt($("#itemQty").val()) || 1;
                        
                        // Default to drink if both selected, or prioritize whichever is available
                        let itemId = selectedDrinkId || selectedFoodId;
                        let itemName = selectedDrinkId ? selectedDrinkName : selectedFoodName;
                        
                        if (!itemId || !itemName) {
                            alert("Please select a drink or food item first.");
                            return;
                        }

                        // Call ajax to reduce stock
                        $.ajax({
                            url: manageStockUrl,
                            type: "POST",
                            data: { 
                                itemId: itemId, 
                                qty: qty, 
                                action: "subtract" 
                            },
                            headers: {
                                \'X-CSRF-TOKEN\': csrfToken
                            },
                            success: function(response) {
                                if(response.success) {
                                  // Update order amount with the price
                                  let currentOrderAmount = parseInt($("input[name=\'order_amount\']").val()) || 0;
                                  let itemPrice = parseInt(response.price) || 0;
                                  
                                  // Append to visual list individually based on quantity
                                  for (let i = 0; i < qty; i++) {
                                      let listItemId = "order-item-" + Date.now() + "-" + i;
                                      let li = \'<li id="\' + listItemId + \'" class="list-group-item d-flex justify-content-between align-items-center" data-item-id="\' + itemId + \'" data-item-name="\' + itemName + \'" data-qty="1" data-price="\' + itemPrice + \'">\';
                                      li += \'<strong>\' + itemName + \'</strong> <span class="label label-info">\' + itemPrice + \' MMK</span>\';
                                      li += \' <button type="button" class="btn btn-sm btn-danger remove-item-btn" style="float:right;" data-target="\' + listItemId + \'"><i class="fa fa-trash"></i></button>\';
                                      li += \'</li>\';
                                      
                                      $("#order-list").append(li);
                                      
                                      // Accumulate amount for each item
                                      currentOrderAmount += itemPrice;
                                  }
                                  
                                  // Hide empty state if present
                                  $("#empty-state-li").hide();
                                  
                                  // Set new order amount and auto-sum total
                                  $("input[name=\'order_amount\']").val(currentOrderAmount);
                                  $("#addSumBtn").click();
                                  
                                  // Re-calculate textarea
                                  updateOrderTextarea();
                                  
                                  // Reset quantity helper
                                  $("#itemQty").val("1");
                                }
                            },
                            error: function(xhr) {
                                let msg = "Failed to add item.";
                                if(xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                alert(msg);
                            }
                        });
                    });

                    // Remove item logic
                    $(document).off("click", ".remove-item-btn").on("click", ".remove-item-btn", function() {
                        let targetId = $(this).data("target");
                        let $li = $("#" + targetId);
                        
                        let itemId = $li.data("item-id");
                        let qtyToRemove = parseInt($li.data("qty")) || 1;
                        
                        // Call ajax to restore stock
                        $.ajax({
                            url: manageStockUrl,
                            type: "POST",
                            data: { 
                                itemId: itemId, 
                                qty: qtyToRemove, 
                                action: "add" 
                            },
                            headers: {
                                \'X-CSRF-TOKEN\': csrfToken
                            },
                            success: function(response) {
                                if(response.success) {
                                    // Deduct price from order amount
                                    let priceToRemove = parseInt($li.data("price")) || 0;
                                    let currentOrderAmount = parseInt($("input[name=\'order_amount\']").val()) || 0;
                                    let newOrderAmount = currentOrderAmount - priceToRemove;
                                    
                                    // Make sure it doesn\'t go below zero
                                    if(newOrderAmount < 0) newOrderAmount = 0;
                                    
                                    $("input[name=\'order_amount\']").val(newOrderAmount);
                                    $("#addSumBtn").click();

                                    // Remove from visual list
                                    $li.remove();
                                    
                                    // Check if list is empty
                                    if ($("#order-list .list-group-item:not(#empty-state-li)").length === 0) {
                                        $("#empty-state-li").show();
                                    }
                                    
                                    // Re-calculate textarea
                                    updateOrderTextarea();
                                }
                            },
                            error: function(xhr) {
                                alert("Failed to remove item and restore stock.");
                            }
                        });
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
    public function manageInventoryStock(Request $request)
    {
        $itemId = $request->input('itemId');
        $qty = (int) $request->input('qty', 1);
        $action = $request->input('action', 'subtract');

        // Get the selected item from the database
        $item = Inventory::find($itemId);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        if ($action === 'subtract') {
            if ($item->qty < $qty) {
                return response()->json(['success' => false, 'message' => 'Not enough stock'], 400);
            }
            $item->qty -= $qty;
        } elseif ($action === 'add') {
            $item->qty += $qty;
        }

        // Save the updated quantity
        $item->save();

        return response()->json([
            'success' => true,
            'new_qty' => $item->qty,
            'price' => $item->price
        ]);
    }
}
