<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GoalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GoalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GoalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Goal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/goal');
        CRUD::setEntityNameStrings('цель', 'Цели');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'goal_name',
          'label' => 'Название цели',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'goal_description',
          'label' => 'Описание цели',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'goal_sum',
          'label' => 'Сумма цели',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'goal_balance',
          'label' => 'Баланс цели',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['RUB' => 'RUB', 'USD' => 'USD', 'EUR' => 'EUR'],
          'name' => 'goal_currency',
          'label' => 'Валюта',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'goal_reminder',
          'label' => 'Напоминание',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'user_id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'bill_id',
          'label' => 'ИД счета',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'goal_finish',
          'label' => 'Дата завершения',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'important',
          'label' => 'Важность',
        ]);
        $this->crud->addColumn([
          'type' => 'check',
          'name' => 'goal_active',
          'label' => 'Активность цели',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(GoalRequest::class);

        $this->crud->addField([
          'type' => 'text',
          'name' => 'goal_name',
          'label' => 'Название цели',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'goal_description',
          'label' => 'Описание цели',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'goal_sum',
          'label' => 'Сумма цели',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'goal_balance',
          'label' => 'Баланс цели',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['RUB' => 'RUB', 'USD' => 'USD', 'EUR' => 'EUR'],
          'name' => 'goal_currency',
          'label' => 'Валюта',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'goal_reminder',
          'label' => 'Напоминание',
        ]);
        $this->crud->addField([
          'type' => 'select2',
          'name' => 'user_id',
          'entity' => 'user',
          'attribute' => 'id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addField([
          'type' => 'select2',
          'name' => 'bill_id',
          'entity' => 'bill',
          'attribute' => 'id',
          'label' => 'ИД счета',
        ]);
        $this->crud->addField([
          'type' => 'date',
          'name' => 'goal_finish',
          'label' => 'Дата завершения',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'goal_important',
          'label' => 'Важность',
        ]);
        $this->crud->addField([
          'type' => 'checkbox',
          'name' => 'goal_active',
          'label' => 'Активность цели',
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
