<?php

namespace App\Repositories;

use App\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Single Responsibility Principle (SRP)
 * Class ini hanya bertanggung jawab untuk operasi data Order
 */
class OrderRepository implements OrderRepositoryInterface
{
    protected Order $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->with(['user', 'items.products'])->find($id);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('id_user', $userId)
                          ->with(['items.products'])
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $order = $this->model->find($id);
        if (!$order) {
            return false;
        }

        $order->status = $status;
        return $order->save();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)
                          ->with(['user', 'items.products'])
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'items.products'])
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage);
    }
}