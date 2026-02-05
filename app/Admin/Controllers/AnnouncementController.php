<?php

namespace App\Admin\Controllers;

use App\Models\Announcement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AnnouncementController extends AdminController
{
    protected $title = 'Announcements';

    protected function grid()
    {
        $grid = new Grid(new Announcement());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('title', __('Title'))->limit(50);
        $grid->column('start_datetime', __('Start'));
        $grid->column('end_datetime', __('End'));
        $grid->column('poster_image', __('Poster'))->image('', 60, 60);
        $grid->column('active', __('Active'))->display(function ($value) {
            $color = $value ? 'green' : 'red';
            return "<span style='color: $color;'>" . ($value ? 'Yes' : 'No') . "</span>";
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Announcement::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('start_datetime', __('Start'));
        $show->field('end_datetime', __('End'));
        $show->field('poster_image', __('Poster'))->image();
        $show->field('active', __('Active'));
        $show->field('created_at', __('Created At'));
        $show->field('updated_at', __('Updated At'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Announcement());

        $form->display('id', __('ID'));
        $form->text('title', __('Title'))->required();
        $form->textarea('description', __('Description'))->required();
        $form->datetime('start_datetime', __('Start Datetime'))->required();
        $form->datetime('end_datetime', __('End Datetime'))->required();
        $form->image('poster_image', __('Poster Image'))->removable();
        $form->switch('active', __('Active'))->states([
            'on' => ['value' => '1', 'text' => 'Yes'],
            'off' => ['value' => '0', 'text' => 'No'],
        ])->default(1);

        return $form;
    }
}
