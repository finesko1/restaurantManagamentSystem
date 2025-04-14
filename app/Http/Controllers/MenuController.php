<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        $menus = $this->menuService->getAllMenus();
        return response()->json($menus);
    }

    public function show($menuId)
    {
        $menu = $this->menuService->getMenuById($menuId);
        return response()->json($menu);
    }
}
