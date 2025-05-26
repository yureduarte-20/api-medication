<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Repositories\MedicationRepository;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function __construct(
        private readonly MedicationRepository $repository
    )
    {}
    public function index()
    {
        return response()->json($this->repository->getAll());
    }

    public function store(Request $request)
    {
        $data = $this->repository->create($request->all());
        return response()->json($data, 201);
    }
    public function show($id): mixed
    {
        return response()->json(
            $this->repository->findById($id)
        );
    }
    public function update(Request $request, $id)
    {
        $updated = $this->repository->updateById($id, $request->all());
        if($updated) return response()->json(null, 204);
        return response()->json([
            'message' => __('Unable to update :attribute', ['attribute' => __('medication')])
        ], 422);
    }
    public function destroy($id)
    {
        $this->repository->deleteById($id);
        return response()->json(null, 204);
    }
}
