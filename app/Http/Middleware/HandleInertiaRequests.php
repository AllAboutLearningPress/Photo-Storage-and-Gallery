<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Notification;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $shared = [
            'flash' => [
                'success' => fn () => $request->session()->get('success')
            ],
            'notification_count' => Notification::where([['user_id', Auth::id()], ['seen', false]])->count(),
            'user' => $request->user(),

        ];

        if (Auth::check() && !$request->header('X-Inertia')) {
            $shared['sidebarMenuItems']  =  $this->permittedSidebarMenus();
            $shared['permissions'] = request()->header('x-inertia') ? null : $request->user()->getCachedPermSlugs();
        }
        return array_merge(parent::share($request), $shared);
    }
    public function permittedSidebarMenus()
    {


        $permitted_menu_items = [];
        $menu_items = [
            [
                'name' => 'Trash',
                'route' =>  'photos.trash'
            ],
            [
                'name' => 'Invitations',
                'route' => 'invitations.index'
            ],
            [
                'name' => 'Roles',
                'route' => 'roles.index'
            ]
            // 'invitations.index',

        ];
        foreach ($menu_items as $menu_item) {
            //  dd($request->user()->hasPermission($menu_item['route']));
            if (Auth::user()->hasPermission($menu_item['route'])) {
                array_push($permitted_menu_items, $menu_item);
            }
        }
        //dd($permitted_menu_items);
        return $permitted_menu_items;
    }
}
