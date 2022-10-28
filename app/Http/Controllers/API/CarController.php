<?php

namespace App\Http\Controllers\API;

use App\Models\Car;
use App\Models\CarRentHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class CarController
{
    private function addCarToHistory($car)
    {
        $history = new CarRentHistory();
        $history->user_id = auth()->user()->id;
        $history->car_id = $car->id;
        $history->rented_at = now();
        $history->save();
    }

    /**
     * Display all cars
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allCars(): JsonResponse
    {
        // Get all cars from database
        $cars = Car::with('users')->get();

        // Return cars as JSON
        return response()->json([
            'success' => true,
            'cars' => $cars,
        ]);
    }

    /**
     * Display all available cars
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableCars(): JsonResponse
    {
        // Get all cars from relation database
        $cars = Car::with('users')->get();

        // Filter cars with no owners
        $cars = $cars->filter(function ($car) {
            return $car->users->isEmpty();
        });

        // Return cars as JSON
        return response()->json([
            'success' => true,
            'cars' => $cars,
        ]);
    }

    /**
     * Display all cars owned by the user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userCars(): JsonResponse
    {
        // Get user's cars from relation database
        $cars = auth()->user()->cars;

        // Return cars as JSON
        return response()->json([
            'success' => true,
            'cars' => $cars,
        ]);
    }

    /**
     * Rent a car
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentCar(int $id): JsonResponse
    {
        // Get car from database
        $car = Car::find($id);

        // Check if car exists
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found',
            ]);
        }

        // Check if user already owns any cars
        if (auth()->user()->cars->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'User already owns a car',
            ]);
        }

        // Check if car is already rented
        if (!$car->users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Car is already rented',
            ]);
        }

        // Add car to history
        $this->addCarToHistory($car);

        // Attach user to car
        $car->users()->attach(auth()->user()->id);

        // Return success as JSON
        return response()->json([
            'success' => true,
            'message' => 'Car rented successfully',
        ]);
    }

    /**
     * Return a car
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnOwnedCar(): JsonResponse
    {
        // Get user's cars from relation database
        $cars = auth()->user()->cars;

        // Check if user owns any cars
        if ($cars->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'User does not own any cars',
            ]);
        }

        // Get the latest history entry
        $history = CarRentHistory::where('user_id', auth()->user()->id)->latest()->first();
        $history->end();

        // Detach user from car
        $cars->first()->users()->detach(auth()->user()->id);

        // Return success as JSON
        return response()->json([
            'success' => true,
            'message' => 'Car returned successfully',
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function car($id): JsonResponse
    {
        // Get car from database
        $car = Car::find($id);

        // Check if car exists
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found',
            ]);
        }

        // Return car as JSON
        return response()->json([
            'success' => true,
            'car' => $car->load('users'),
        ]);
    }

    /**
     * Rental history
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentalHistory(): JsonResponse
    {
        // Get user's rent history from relation database
        $history = auth()->user()->rentalHistory()->get();

        // Prob. not the best way to do this, but it works
        $history->map(function ($item) {
            $item->car = $item->car;
            $item->user = $item->user;
            $item->rented_at = $item->rented_at;
            $item->returned_at = $item->returned_at;
            $item->rent_duration = $item->rent_duration;
            $item->rent_price = $item->rent_price;
        });

        // Return history as JSON
        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }
}
