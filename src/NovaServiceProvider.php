<?php

namespace iVirtual\LaravelDevelopment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->withUserMenu();

        $this->withFooter();
    }

    /**
     * Add "My Profile" to nova user menu.
     */
    private function withUserMenu(): void
    {
        Nova::userMenu(function (Request $request, Menu $menu) {
            $user = Nova::user($request);
            $resourceClass = Nova::resourceForModel($user);

            return $menu->prepend(
                MenuItem::externalLink(
                    trans('My Profile'),
                    route('nova.pages.detail', [
                        'resource' => $resourceClass::uriKey(),
                        'resourceId' => $request->user()->getKey(),
                    ])
                )->canSee(function ($request) use ($user, $resourceClass) {
                    return $resourceClass::availableForNavigation($request)
                        && Gate::forUser($user)->check('view', $user);
                })
            );
        });
    }

    /**
     * Resolve the footer used for Nova.
     */
    private function withFooter(): void
    {
        Nova::footer(fn () => View::make('ivirtual::nova.footer', [
            'version' => Nova::version(),
            'year' => date('Y'),
        ])->render());
    }
}
