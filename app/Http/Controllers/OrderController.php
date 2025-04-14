<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $orders = $this->orderService->getAllOrders();
            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json(['error' =>'An unexpected error occurred while retrieving orders.']);
        }
    }

    public function show(Request $request)
    {
        try {
            $order = $this->orderService->getOrderById($request);
            return response()->json($order);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'Order not found.');
        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'An unexpected error occurred.');
        }
    }

    public function store(Request $request)
    {
        try
        {
            $this->orderService->createOrder($request);
            return response()->json('Order created', 201);
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
            $this->orderService->updateStatusOrder($request);

            return response()->json(['message' => 'Order updated successfully']);
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

    public function destroy(Request $request)
    {
        try
        {
            $this->orderService->deleteOrder($request);

            return response()->json(['message' => 'Order deleted successfully']);
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
