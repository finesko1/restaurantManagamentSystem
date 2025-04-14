<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MenuService
{
    /**
     * Получить все блюда
     */
    public function getAllMenus()
    {
        return Menu::all();
    }

    /**
     * Получить блюдо по ID
     */
    public function getMenuById($menuId)
    {
        $id = $this->validateId($menuId);
        return Menu::findOrFail($id);
    }

    /**
     * Валидация запроса, *id*
     */
    protected function validateId($menuId)
    {
        $validator = Validator::make(['id' => $menuId], [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $menuId;
    }
}
