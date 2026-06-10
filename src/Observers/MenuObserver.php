<?php

namespace PhpCollective\MenuMaker\Observers;

use Illuminate\Support\Facades\Artisan;
use PhpCollective\MenuMaker\Storage\Menu;

class MenuObserver
{

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Menu  $menu
     * @return void
     */
    public function created(Menu $menu)
    {
        $this->removeCache();
    }

    /**
     * Handle the Menu "updated" event.
     *
     * @param  \App\Menu  $menu
     * @return void
     */
    public function updated(Menu $menu)
    {
        $this->removeCache();
    }

    /**
     * Handle the Menu "deleting" event.
     *
     * @param  Menu  $menu
     * @return void
     */
    public function deleting(Menu $menu)
    {
        $this->removeCache();
    }

    /**
     * Handle Cache Clear.
     *
     * @return void
     */
    private function removeCache()
    {
        Artisan::call('menu:clear');
    }
}
