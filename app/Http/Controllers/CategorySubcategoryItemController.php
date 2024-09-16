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
use App\Models\Sell;
use App\Models\SellItem;
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
                'name' => $request->name, 
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
                
                'admin_id' => auth()->user()->id,
            ]
        );
        return response($unit);
    }
    public function adjustStock(Request $request)
    {
        $qty = (int)(($request->type == 'add') ? abs((int)$request->qty) : (-abs((int)$request->qty)));
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
                    'stock' => $stock,
                    
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
        $list = MenuStock::with('admin')->where('menu_id', $menuId)->latest()->get();
        return response($list);
    }
    public function updateMenuStock($item, $menu, $sell, $qty, $type, $note)
    {
        $changed = ($menu->stocks ?? 0) + (($type == 'add') ? $qty : -$qty);

        // Create or update MenuStock record
        MenuStock::create([
            'sell_item_id' => $item->id,
            'qty' => $qty,
            'sell_id' => $sell->id,
            'type' => $type,
            'menu_id' => $menu->id,
            'stock' => $changed,
            'note' => $note,
            'datetime' => now(),
            'admin_id' => $sell->admin_id,
        ]);

        // Update menu stock
        $menu->stocks = $changed;
        $menu->save();
    }
    public function changeStock(Sell $sell)
    {
        $id = $sell->id;
        $status = $sell->order_status;
        $sell->load('items');
        $items = $sell->items;
        Log::channel('callvcal')->info('menus: ' . json_encode($items));

        foreach ($items as $item) {
            $menu = Menu::find($item->menu_id);
            if (!$menu) {
                continue; // Skip if menu not found
            }

            $qty = $item->qty; // Default quantity to reduce

            // Handle 'a_sent' status
            if (in_array($status, ['a_sent'])) {
                $this->updateMenuStock($item, $menu, $sell, $qty, 'reduce', 'Order is created');
            }

            // Handle 'f_rejected' and 'g_cancelled' statuses
            if (in_array($status, ['f_rejected', 'g_cancelled'])) {
                $this->updateMenuStock($item, $menu, $sell, $qty, 'add', 'Order is cancelled/rejected');
            }

            // Handle 'e_completed' status
            if (in_array($status, ['e_completed'])) {
                $stock = MenuStock::where('sell_item_id', $item->id)->where('type', 'reduce')->first();
                if (!$stock) {
                    $this->updateMenuStock($item, $menu, $sell, -$item->qty, 'reduce', 'Order is completed');
                }
            }
        }
    }
    public function changeStockSellItem(SellItem $sellItem)
    {
        $id = $sellItem->sell_id;
        $mSell = Sell::where('uuid', $id)->first();
        if (!$mSell) {
            return;
        }
        $status = $mSell->order_status;

        $menu = Menu::find($sellItem->menu_id);
        if (!$menu) {
            return; // Skip if menu not found
        }

        $qty = $sellItem->qty;

        // Handle 'a_sent' status
        if (in_array($status, ['a_sent'])) {
            $this->updateMenuStock($sellItem, $menu, $mSell, $qty, 'reduce', 'Order is created');
        }

        // Handle 'f_rejected' and 'g_cancelled' statuses
        if (in_array($status, ['f_rejected', 'g_cancelled'])) {
            $this->updateMenuStock($sellItem, $menu, $mSell, $qty, 'add', 'Order is cancelled/rejected');
        }

        // Handle 'e_completed' status
        if (in_array($status, ['e_completed'])) {
            $stock = MenuStock::where('sell_item_id', $sellItem->id)->where('type', 'reduce')->first();
            if (!$stock) {
                $this->updateMenuStock($sellItem, $menu, $mSell, -$qty, 'reduce', 'Order is completed');
            }
        }
    }



    public function createDinTable(Request $request)
    {
        $unit = DiningTable::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                
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
