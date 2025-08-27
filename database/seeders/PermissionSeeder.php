<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceType;
use App\Models\ResourceActionType;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	$resource = new ResourceType();
    	$resource->name = 'events';
    	$resource->save();
    	$resource = new ResourceType();
    	$resource->name = 'funds';
    	$resource->save();
    	$resource = new ResourceType();
    	$resource->name = 'meetings';
    	$resource->save();
    	$resource = new ResourceType();
    	$resource->name = 'partnerships';
    	$resource->save();
    	$resource = new ResourceType();
    	$resource->name = 'platforms';
    	$resource->save();
    	$resource = new ResourceType();
    	$resource->name = 'central-body';
    	$resource->save();
    	$resource = new ResourceType();
    	$resource->name = 'students';
    	$resource->save();
        $resource = new ResourceType();
        $resource->name = 'accomplishment-reports';
        $resource->save();
        $resource = new ResourceType();
        $resource->name = 'general-plan-of-activities';
        $resource->save();
        $resource = new ResourceType();
        $resource->name = 'attendance';
        $resource->save();

    	$action = new ResourceActionType();
    	$action->name = 'view';
    	$action->save();
    	$action = new ResourceActionType();
    	$action->name = 'edit';
    	$action->save();

    	foreach (ResourceType::all() as $resource) {
    		$resource->actions()->sync(ResourceActionType::all());
    		$resource->save();
    	}
    }
}
