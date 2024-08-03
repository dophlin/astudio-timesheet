<?php

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function index(Request $request) {
        $name = $request->query('name');
        $department = $request->query('department');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');

        $query = Project::query();

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($department) {
            $query->where('department', 'like', '%' . $department . '%');
        }

        if ($startDate) {
            $query->whereDate('start_date', $startDate);
        }

        if ($endDate) {
            $query->whereDate('end_date', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function getById($id) {
       $project = Project::with(['users', 'timesheets'])->find($id);
       if (!$project) return null;

       $project->users->each(function ($user) {
           $user->makeHidden('pivot');
       });

       return $project;
    }

    public function store(array $data) {
       return Project::create($data);
    }

    public function update(array $data, $id) {
       return Project::whereId($id)->update($data);
    }

    public function delete($id) {
       return Project::destroy($id);
    }
}
