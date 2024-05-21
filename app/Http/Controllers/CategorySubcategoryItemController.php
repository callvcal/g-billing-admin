<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DiningTable;
use App\Models\SubCategory;
use App\Models\Menu;
use App\Models\Kitchen;
use App\Models\Material;
use App\Models\RawMatrial;
use App\Models\Unit;
use Illuminate\Http\Request;

class CategorySubcategoryItemController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return response($categories);
    }

    public function subCategories()
    {
        $subCategories = SubCategory::all();
        return response($subCategories);
    }

    public function menus()
    {
        $menus = Menu::all();
        return response($menus);
    }
    public function rawMaterials()
    {
        $menus = Material::all();
        return response($menus);
    }
    public function rawMaterialStocks()
    {
        $menus = RawMatrial::all();
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
                'name' => $request->name, 'business_id' => auth()->user()->business_id,

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
                'name' => $request->name, 'business_id' => auth()->user()->business_id,
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
                'name' => $request->name, 'business_id' => auth()->user()->business_id,
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
                'name' => $request->name, 'business_id' => auth()->user()->business_id,
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
        $data['active'] = (($data['active']==1)||($data['active']==true)||($data['active']=='true'))?1:0;

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
}
