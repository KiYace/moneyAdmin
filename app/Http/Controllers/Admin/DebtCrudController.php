<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DebtRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DebtCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DebtCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Debt::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/debt');
        CRUD::setEntityNameStrings('долг', 'Долги');
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
          'name' => 'debt_name',
          'label' => 'Название долга',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'debt_desc',
          'label' => 'Описание долга',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => 'Долг', '1' => 'Заем'],
          'name' => 'debt_type',
          'label' => 'Тип долга',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'user_id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'bill_id',
          'label' => 'Счет',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'debt_sum',
          'label' => 'Сумма долга',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['RUB' => 'RUB', 'USD' => 'USD', 'EUR' => 'EUR'],
          'name' => 'debt_currency',
          'label' => 'Валюта',
        ]);
        $this->crud->addColumn([
          'type' => 'date',
          'name' => 'debt_finish',
          'label' => 'Дата завершения',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'debt_reminder',
          'label' => 'Напоминание',
        ]);
        $this->crud->addColumn([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'debt_important',
          'label' => 'Важность',
        ]);
        $this->crud->addColumn([
          'type' => 'check',
          'name' => 'debt_active',
          'label' => 'Активность долга',
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
        CRUD::setValidation(DebtRequest::class);

        $this->crud->addField([
          'type' => 'text',
          'name' => 'debt_name',
          'label' => 'Название долга',
        ]);
        $this->crud->addField([
          'type' => 'text',
          'name' => 'debt_desc',
          'label' => 'Описание долга',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => 'Долг', '1' => 'Заем'],
          'name' => 'debt_type',
          'label' => 'Тип долга',
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
          'label' => 'Счет',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'debt_sum',
          'label' => 'Сумма долга',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['RUB' => 'RUB', 'USD' => 'USD', 'EUR' => 'EUR'],
          'name' => 'debt_currency',
          'label' => 'Валюта',
        ]);
        $this->crud->addField([
          'type' => 'date',
          'name' => 'debt_finish',
          'label' => 'Дата завершения',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => '', '1' => 'Каждый день', '2' => 'Каждую неделю', '3' => 'Каждый месяц'],
          'name' => 'debt_reminder',
          'label' => 'Напоминание',
        ]);
        $this->crud->addField([
          'type' => 'select_from_array',
          'options' => ['0' => 'Не важно', '1' => 'Важно', '2' => 'Очень важно'],
          'name' => 'debt_important',
          'label' => 'Важность',
        ]);
        $this->crud->addField([
          'type' => 'checkbox',
          'name' => 'debt_active',
          'label' => 'Активность долга',
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
