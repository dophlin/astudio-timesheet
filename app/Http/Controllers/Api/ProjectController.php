<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Helper\ResponseHelper;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\DeleteProjectRequest;
use App\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository) {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Add filters
        $projects = $this->projectRepository->index($request);
        return ResponseHelper::success(message: 'List of projects.', data: $projects, statusCode: 200);
    }

    /**
     * Create new project
     */
    public function create(CreateProjectRequest $request)
    {
        $data = [
            'name' => $request->name,
            'department' => $request->department,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ];
        DB::beginTransaction();
        try{
             $project = $this->projectRepository->store($data);

             DB::commit();
            return ResponseHelper::success(message: 'A new project has been created', data: $project, statusCode: 200);
        } catch(Exception $e){
            DB::rollBack();
            \Log::error('Unable to create the project. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to create the project.', statusCode: 500);
        }
    }

    /**
     * Display the specified project.
     */
    public function show(string $id)
    {
        try {
            $project = $this->projectRepository->getById($id);
            if(!$project) {
                return ResponseHelper::error(message: 'Project not found.', statusCode: 404);
            }
            return ResponseHelper::success(message: 'Specified project.', data: $project, statusCode: 200);
        } catch (Exception $e) {
            return ResponseHelper::error(message: 'Unable to find the project.', statusCode: 500);
        }
    }

    /**
     * Update the specified project.
     */
    public function update(UpdateProjectRequest $request)
    {
        $data = [
            'name' => $request->name,
            'department' => $request->department,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ];
        DB::beginTransaction();
        try {
            $affectedRows = $this->projectRepository->update($data, $request->id);
            DB::commit();
            if($affectedRows > 0)
                return ResponseHelper::success(message: 'The project has been updated.', data: null, statusCode: 200);
            else
                return ResponseHelper::error(message: 'The project not found to be updated.', statusCode: 404);
        } catch(Exception $e) {
            DB::rollBack();
            \Log::error('Unable to update the project. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to update the project.', statusCode: 500);
        }
    }

    /**
     * Remove the specified project.
     */
    public function delete(DeleteProjectRequest $request)
    {
        // TODO: It should delete the related Timesheet?!
        $affectedRows = $this->projectRepository->delete($request->id);
        if($affectedRows > 0)
            return ResponseHelper::success(message: 'The project has been deleted.', data: null, statusCode: 200);
        else
            return ResponseHelper::error(message: 'The project not found to be deleted.', statusCode: 404);
    }
}
