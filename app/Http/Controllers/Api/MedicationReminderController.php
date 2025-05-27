<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicationReminder;
use App\Repositories\MedicationReminderRepository;
use Illuminate\Http\Request;

class MedicationReminderController extends Controller
{
    public function __construct(
        private readonly MedicationReminderRepository $repository
    )
    {}
    public function index()
    {
        $paginated = request()->query('paginate', true);
        return response()->json(
            $this->repository->getAll(
                $paginated
            )
        );
    }
    public function show($id)
    {
        return response()->json(
            $this->repository->findById($id)
        );
    }
    public function store(Request $request)
    {
        return response()->json(
            $this->repository->create($request->all())
        , 201);
    }
    public function update(Request $request, $id)
    {
        $result = $this->repository->updateById($id, $request->all());
        if($result) return response()->json(
            null, 204
        );
        return response()->json([
            'message' => __('Unable to update :attribute', ['attribute' => __('validation.attributes.time')])
        ],422);
    }
    public function destroy($id)
    {
        $this->repository->deleteById($id);
        return response()->json(null, 204);
    }
}

