<?php

namespace App\Http\Controllers\Admin;

use App\Models\BannedIP;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BannedIPRequest as StoreRequest;
use App\Http\Requests\BannedIPRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class BannedIPCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BannedIPCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BannedIP');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bannedip');
        $this->crud->setEntityNameStrings('bannedip', 'banned_ips');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn(['name' => 'ip', 'type' => 'text', 'label' => 'IP Address']);
        $this->crud->addColumn(['name' => 'attempts', 'type' => 'number', 'label' => 'Attempts']);
        $this->crud->addColumn(['name' => 'updated_at', 'type' => 'datetime', 'label' => 'Last Attempt']);
        // add asterisk for fields that are required in BannedIPRequest
        $this->crud->removeButton("create");
        $this->crud->removeButton("update");



    }



    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

}
