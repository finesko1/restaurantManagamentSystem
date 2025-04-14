<?php

namespace App\Http\Controllers;

use App\Services\OrderItemService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderItemController extends Controller
{
    protected $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    public function index()
    {
        try
        {
            $orders = $this->orderItemService->getAllOrderItems();
            return view('order.items.index', compact('orders'));
        }
        catch (ValidationException|Exception $e)
        {
            return redirect()->route('home')->with('error', 'An unexpected error occurred while retrieving orders.');
        }
    }

    public function show(Request $request)
    {
        try
        {
            $orderItem = $this->orderItemService->getOrderItemByItemId($request);

            return view('order.show', compact('orderItem'));
        }
        catch (ValidationException $e)
        {
            return redirect()->route('home')->with('error', 'An unexpected error occurred while retrieving order item.');
        }
        catch (ModelNotFoundException $e)
        {
            return redirect()->route('home')->with('error', 'Order item not found.');
        }
        catch (Exception $e)
        {
            return redirect()->route('home')->with('error', 'An unexpected error occurred.');
        }
    }

    public function showByOrderId($orderId)
    {
        try {
            $orderItems = $this->orderItemService->getOrderItemsByOrderId($orderId);
            return response()->json($orderItems);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Order items not found'
            ], 404);
        }
        catch (Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try
        {
            $this->orderItemService->createOrderItem($request);

            return response()->json('Order item created', 201);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        try
        {
            $this->orderItemService->updateOrderItem($request);

            return response()->json('Order item created', 201);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json(['error' => 'Order item not found.'], 404);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try
        {
            $this->orderItemService->deleteOrderItem($id);

            return response()->json('Order item deleted successfully');
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json(['error' => 'Order item not found.'], 404);
        }
        catch (ValidationException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
