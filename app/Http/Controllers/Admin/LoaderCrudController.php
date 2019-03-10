<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoaderRequest as StoreRequest;
use App\Http\Requests\LoaderRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class LoaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoaderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Loader');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loader');
        $this->crud->setEntityNameStrings('loader', 'loaders');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn(['name' => 'name', 'type' => 'text', 'label' => 'Name']);
        $this->crud->addColumn(['name' => 'version', 'type' => 'text', 'label' => 'Version Number']);
        $this->crud->addColumn(['name' => 'enabled', 'type' => 'check', 'label' => 'Enabled']);



        //$this->crud->setFromDb();
        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Loader Name']);
        $this->crud->addField(['name' => 'version', 'type' => 'text', 'label' => 'Loader Version']);
        $this->crud->addField(['name' => 'enabled', 'type' => 'checkbox', 'label' => 'Enabled']);
        $this->crud->addField([   // Upload
            'name' => 'encryption_key_public',
            'label' => 'Public Key File',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
        ]);
        $this->crud->addField([   // Upload
            'name' => 'encryption_key_private',
            'label' => 'Private Key File',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
        ]);


        // add asterisk for fields that are required in LoaderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
