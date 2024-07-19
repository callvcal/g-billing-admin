<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Menu;
use App\Models\SubCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MenuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $menu;
    protected $category;
    protected $subCategory;

    /**
     * Create a new job instance.
     */
    public function __construct($menu = null, $category = null, $subCategory = null)
    {
        $this->menu = $menu;
        $this->subCategory = $subCategory;
        $this->category = $category;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $menu = $this->menu;
        if ($menu != null) {
            $this->runForMenu($menu);
            return;
        }
        $subCategory = $this->subCategory;
        if ($subCategory != null) {
            $this->runForSubCategory($subCategory);
            return;
        }
        $category = $this->category;
        if ($category != null) {
            $this->runForCategory($category);
            return;
        }

        foreach (Category::all() as $category) {
            $this->runForCategory($category);
        }
        foreach (SubCategory::all() as $subCategory) {
            $this->runForSubCategory($subCategory);
        }
    }

    function runForMenu($menu): void
    {
        $subcategory_id = $menu->subcategory_id ?? null;
        $category_id = $menu->category_id ?? null;

        $subCategory = SubCategory::find($subcategory_id);
        $category = Category::find($category_id);

        if ($subCategory) {
            $subCategory->menus = Menu::where('subcategory_id', $subcategory_id)->count();
            $subCategory->save();
        }
        if ($category) {
            $category->menus = Menu::where('category_id', $category_id)->count();
            $category->subcategories = SubCategory::where('category_id', $category_id)->count();
            $category->save();
        }
    }

    function runForCategory($category): void
    {
        $category->menus = Menu::where('category_id', $category->id)->count();
        $category->subcategories = SubCategory::where('category_id', $category->id)->count();
        $category->save();
    }
    function runForSubCategory($subCategory): void
    {
        $subCategory->menus = Menu::where('subcategory_id', $subCategory->id)->count();
        $subCategory->save();
    }
}
