<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DiningTable;
use App\Models\SubCategory;
use App\Models\Menu;
use App\Models\Kitchen;
use App\Models\Material;
use App\Models\MenuStock;
use App\Models\OfflineTransaction;
use App\Models\RawMatrial;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorySubcategoryItemController extends Controller
{
    public function categories()
    {
        $categories = (new HomeController())->query(Category::class)->get();
        return response($categories);
    }

    public function subCategories()
    {
        $subCategories = (new HomeController())->query(SubCategory::class)->get();
        return response($subCategories);
    }

    public function earningExpense()
    {
        $categories = (new HomeController())->query(OfflineTransaction::class)->get();
        return response($categories);
    }


    public function menus()
    {
        $menus = (new HomeController())->query(Menu::class)->get();
        return response($menus);
    }
    public function rawMaterials()
    {
        $menus = (new HomeController())->query(Material::class)->get();
        return response($menus);
    }
    public function rawMaterialStocks()
    {
        $menus = (new HomeController())->query(RawMatrial::class)->get();
        return response($menus);
    }

    public function createCategory(Request $request)
    {
        $category = Category::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name, 'business_id' => auth()->user()->business_id,
                'admin_id' => auth()->user()->id
            ]
        );
        $this->saveImageFile($request, $category);
        return response($category);
    }
    public function createRawMaterial(Request $request)
    {
        $category = Material::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'business_id' => auth()->user()->business_id,
                'admin_id' => auth()->user()->id

            ]
        );
        return response($category);
    }
    public function createRawMaterialStock(Request $request)
    {
        $category = RawMatrial::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'business_id' => auth()->user()->business_id,
                'unit_id' => $request->unit_id,
                'qty' => $request->qty,
                'datetime' => $request->datetime,
                'type' => $request->type,
                'amount' => $request->amount,
                'material_id' => $request->material_id,
                'admin_id' => auth()->user()->id
            ]
        );
        $category->load('material');
        return response($category);
    }

    public function createKitchen(Request $request)
    {
        $kitchen = Kitchen::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'business_id' => auth()->user()->business_id,
                'admin_id' => auth()->user()->id
            ]
        );
        return response($kitchen);
    }
    public function createEarningExpense(Request $request)
    {
        $kitchen = OfflineTransaction::updateOrCreate(
            ['id' => $request->id],
            [
                'amount' => (int) $request->amount,
                'type' => $request->type,
                'cause' => $request->cause,
                'business_id' => auth()->user()->business_id,
                'admin_id' => auth()->user()->id
            ]
        );
        return response($kitchen);
    }

    public function createSubCategory(Request $request)
    {
        $category = SubCategory::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'business_id' => auth()->user()->business_id,
                'admin_id' => auth()->user()->id,
                'category_id' => $request->category_id,
                'kitchen_id' => $request->kitchen_id,
            ]
        );
        $this->saveImageFile($request, $category);
        return response($category);
    }

    public function createMenu(Request $request)
    {
        $data = $request->all();
        $data['admin_id'] = auth()->user()->id;
        $data['business_id'] = auth()->user()->business_id;


        if (isset($data['active'])) {
            $data['active'] = (($data['active'] == 1) || ($data['active'] == true) || ($data['active'] == 'true')) ? 1 : 0;
        } else {
            $data['active'] = 0;
        }


        $menu = Menu::updateOrCreate(['id' => $request->id], $data);
        $this->saveImageFile($request, $menu);
        return response(Menu::find($menu->id));
    }

    public function createUnit(Request $request)
    {
        $unit = Unit::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'business_id' => auth()->user()->business_id,
                'admin_id' => auth()->user()->id,
            ]
        );
        return response($unit);
    }
    public function adjustStock(Request $request)
    {
        $qty = (int)(($request->type == 'add') ? $request->qty : (-(int)$request->qty));
        $stock = $qty;
        $menu = Menu::find($request->menu_id);

        if ($menu) {
            $stock = $menu->stocks ?? 0;
            $stock = $stock + $qty;
            $menu->stocks = $stock;
            $menu->save();
            $model = MenuStock::create(
                [
                    'note' => $request->note,
                    'qty' => $qty,
                    'type' => $request->type,
                    'menu_id' => $request->menu_id,
                    'datetime' => now(),
                    'stock'=>$stock,
                    'business_id' => auth()->user()->business_id,
                    'admin_id' => auth()->user()->id,
                ]
            );
            $model->load('admin');
            return response([
                'stock' => $model,
                'menu' => $menu
            ]);
        }
        return response([
            'menssage' => 'menu does not exist',
        ], 401);
    }
    public function stocks($menuId)
    {
        $list = MenuStock::with('admin')->where('business_id', auth()->user()->business_id)->where('menu_id', $menuId)->latest()->get();
        return response($list);
    }



    public function createDinTable(Request $request)
    {
        $unit = DiningTable::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'business_id' => auth()->user()->business_id,
                'number' => $request->number,
                'capacity' => $request->capacity,
                'status' => 'blank',
                'admin_id' => auth()->user()->id,
            ]
        );
        return response($unit);
    }

    protected function saveImageFile(Request $request, $model)
    {
        if ($request->hasFile('file')) {
            // $image = $request->file('file');
            // $dist='menus/photos';
            // $image->move($dist, $fileName);

            // $model->image = $dist.'/'.$fileName;
            // $model->save();

            (new BusinessController())->saveFilePath($request, 'images', $model, 'image');
        } else
        if (!isset($request->image)) {
            (new BusinessController())->deleteFilePath($model, 'image');

            $model->image = null;
            $model->save();
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response(['message' => 'Category deleted successfully']);
    }

    public function deleteKitchen($id)
    {
        $kitchen = Kitchen::findOrFail($id);
        $kitchen->delete();
        return response(['message' => 'Kitchen deleted successfully']);
    }

    public function deleteSubCategory($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();
        return response(['message' => 'Subcategory deleted successfully']);
    }

    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return response(['message' => 'Menu item deleted successfully']);
    }

    public function deleteUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response(['message' => 'Unit deleted successfully']);
    }

    public function deleteDinTable($id)
    {
        $unit = DiningTable::findOrFail($id);
        $unit->delete();
        return response(['message' => 'dining table deleted successfully']);
    }
    public function deleteRawMaterial($id)
    {
        $unit = Material::findOrFail($id);
        $unit->delete();
        return response(['message' => 'Material deleted successfully']);
    }
    public function deleteRawMaterialStock($id)
    {
        $unit = RawMatrial::findOrFail($id);

        $unit->delete();
        return response(['message' => 'Material stock deleted successfully']);
    }
    public function deleteEarningExpense($id)
    {
        $unit = OfflineTransaction::findOrFail($id);

        $unit->delete();
        return response(['message' => 'Transaction deleted successfully']);
    }
    public function updateStock($id)
    {
        $unit = Menu::findOrFail($id);
        if ($unit) {
            $active = $unit->active;
            $unit->active = $active == 1 ? 0 : 1;
            $unit->save();
            return response(['message' => 'Stock Status changed', 'active' => $unit->active]);
        }
        return response(['message' => 'Stock Status not changed', 'active' => null]);
    }

    public function importDefaultData()
    {
        $adminId = auth()->user()->id;
        $businessId = auth()->user()->business_id;
        // We will not change admin id, so we can delete default data easily.
        // Also, to avoid image related errors, we can make duplicate images.

        // 03/06/24
        // Menu is linked to category and subcategory
        // Subcategory is linked to kitchen and category

        // Models to duplicate
        $models = [
            Category::class,
            Kitchen::class,
            SubCategory::class,
            DiningTable::class,
            Menu::class,
        ];

        $menus = Menu::with('category', 'subcategory.kitchen', 'subcategory.category')
            ->whereNull('business_id')
            ->get();

        $addedCategories = [];
        $addedSubcategories = [];
        $addedKitchens = [];

        foreach ($menus as $menu) {
            // Duplicate the menu
            $newMenu = $menu->replicate();
            $newMenu->business_id = $businessId;

            $subcategory = $menu->subcategory;
            $category = $menu->category;

            if ($category) {
                $categoryFound = collect($addedCategories)->firstWhere('old_id', $category->id);
                if (!$categoryFound) {
                    $newCategory = $category->replicate();
                    $newCategory->business_id = $businessId;
                    if ($newCategory->image) {
                        $newImageName = $this->duplicateImage($newCategory->image);
                        $newCategory->image = $newImageName;
                    }
                    $newCategory->save();
                    $addedCategories[] = [
                        'old_id' => $category->id,
                        'new_id' => $newCategory->id
                    ];
                    $categoryId = $newCategory->id;
                } else {
                    $categoryId = $categoryFound['new_id'];
                }
            }

            if ($subcategory) {
                $subcategoryFound = collect($addedSubcategories)->firstWhere('old_id', $subcategory->id);
                if (!$subcategoryFound) {
                    $newSubcategory = $subcategory->replicate();
                    $newSubcategory->business_id = $businessId;
                    if ($newSubcategory->image) {
                        $newImageName = $this->duplicateImage($newSubcategory->image);
                        $newSubcategory->image = $newImageName;
                    }
                    $kitchen = $subcategory->kitchen;
                    if ($kitchen) {
                        $kitchenFound = collect($addedKitchens)->firstWhere('old_id', $kitchen->id);
                        if (!$kitchenFound) {
                            $newKitchen = $kitchen->replicate();
                            $newKitchen->business_id = $businessId;
                            $newKitchen->save();
                            $addedKitchens[] = [
                                'old_id' => $kitchen->id,
                                'new_id' => $newKitchen->id
                            ];
                            $kitchenId = $newKitchen->id;
                        } else {
                            $kitchenId = $kitchenFound['new_id'];
                        }
                    }
                    if (isset($kitchenId)) {
                        $newSubcategory->kitchen_id = $kitchenId;
                    }

                    if (isset($categoryId)) {
                        $newSubcategory->category_id = $categoryId;
                    }

                    $newSubcategory->save();
                    $addedSubcategories[] = [
                        'old_id' => $subcategory->id,
                        'new_id' => $newSubcategory->id
                    ];
                    $subcategoryId = $newSubcategory->id;
                } else {
                    $subcategoryId = $subcategoryFound['new_id'];
                }
            }

            // Copy image if it exists
            if ($menu->image) {
                $newImageName = $this->duplicateImage($menu->image);
                $newMenu->image = $newImageName;
            }

            // Update category and subcategory IDs
            if (isset($categoryId)) {
                $newMenu->category_id = $categoryId;
            }
            if (isset($subcategoryId)) {
                $newMenu->subcategory_id = $subcategoryId;
            }

            $newMenu->save();
        }
        return (new HomeController())->home();
    }

    private function duplicateImage($originalImagePath)
    {
        $disk = Storage::disk('s3');

        $pathInfo = pathinfo($originalImagePath);
        $newImageName = $pathInfo['filename'] . '-' . Str::uuid() . '.' . $pathInfo['extension'];
        $newImagePath = $pathInfo['dirname'] . '/' . $newImageName;

        // Copy the file in S3
        $disk->copy($originalImagePath, $newImagePath);

        return $newImagePath;
    }
}
