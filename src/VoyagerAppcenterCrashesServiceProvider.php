<?php

namespace VoyagerAppcenterCrashes;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class VoyagerAppcenterCrashesServiceProvider extends ServiceProvider
{    
    public function register(){
        define('VOYAGER_APPCENTER_ERROR_PATH', __DIR__.'/..');
        app(Dispatcher::class)->listen('voyager.admin.routing', [$this, 'addAppcenterErrorRoutes']);
		app(Dispatcher::class)->listen('voyager.menu.display', [$this, 'addAppcenterErrorMenuItem']);
    }

    public function boot(){
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'crashes');
    }

    public function addAppcenterErrorRoutes($router){
        $namespacePrefix = '\\VoyagerAppcenterCrashes\\Http\\Controllers\\';
        $router->get('crashes', ['uses' => $namespacePrefix.'AppcenterErrorController@browse', 'as' => 'crashes']);
    }

    public function addAppcenterErrorMenuItem(Menu $menu){
	    if ($menu->name == 'admin') {
	        $url = route('voyager.crashes', [], false);
	        $menuItem = $menu->items->where('url', $url)->first();
	        if (is_null($menuItem)) {
	            $menu->items->add(MenuItem::create([
	                'menu_id'    => $menu->id,
	                'url'        => $url,
	                'title'      => 'Crashes',
	                'target'     => '_self',
	                'icon_class' => 'voyager-x',
	                'color'      => null,
	                'parent_id'  => null,
	                'order'      => 99,
	            ]));
	            $this->ensurePermissionExist();	            
	        }
	    }
    }
    
    protected function ensurePermissionExist(){
        $permissions = [
        	Permission::firstOrNew(['key' => 'browse_polls', 'table_name' => 'crashes']),
        	Permission::firstOrNew(['key' => 'read_polls', 'table_name' => 'crashes']),
        ];

        foreach($permissions as $permission){
	        if (!$permission->exists) {
	            $permission->save();
	            $role = Role::where('name', 'admin')->first();
	            if (!is_null($role)) {
	                $role->permissions()->attach($permission);
	            }
	        }
	    }
    }
}
