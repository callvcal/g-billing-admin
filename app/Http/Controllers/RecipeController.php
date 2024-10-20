<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeMaterial;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    function createRecipe(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
        ]);

        $model = Recipe::create([
            'admin_id' => auth()->user()->id,
            'business_id' => auth()->user()->business_id,
            'menu_id' => $request->menu_id,
            'subcategory_id' => $request->subcategory_id,
        ]);

        return response([
            'data' => $model,
            'message' => "Recipe Created Successfully"
        ]);
    }
    function addMaterial(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        $model = RecipeMaterial::updateOrCreate([
            'recipe_id' => $request->recipe_id,
            'material_id' => $request->material_id,
            'business_id' => auth()->user()->business_id,
        ], [
            'admin_id' => auth()->user()->id,
            'qty' => (float)$request->qty,
            'allow_dine_in' => $request->allow_dine_in ?? 0,
            'allow_parcel_delivery' => $request->allow_parcel_delivery ?? 0,
        ]);

        return response([
            'data' => $model,
            'message' => "Recipe Created Successfully"
        ]);
    }

    function deleteRecipe($id)
    {
        $recipe = Recipe::find($id);
        if (!$recipe) {
            return response(['message' => "Requested resource does not exists"], 401);
        }

        if (auth()->user()->business_id != $recipe->business_id) {
            return response(['message' => "You don't have access to this resource"], 401);
        }

        $recipe->delete();

        return response(['message' => "Recipe delete successfully"], 200);
    }
    function deleteRecipeMaterial($id)
    {
        $recipe = RecipeMaterial::find($id);
        if (!$recipe) {
            return response(['message' => "Requested resource does not exists"], 401);
        }

        if (auth()->user()->business_id != $recipe->business_id) {
            return response(['message' => "You don't have access to this resource"], 401);
        }

        $recipe->delete();

        return response(['message' => "Recipe delete successfully"], 200);
    }
}
