<?php

namespace App\Repositories;

use App\Interfaces\TimesheetRepositoryInterface;
use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetRepository implements TimesheetRepositoryInterface
{
    public function index(Request $request) {
        $taskName = $request->query('task_name');
        $date = $request->query('date');
        $hours = $request->query('hours');
        $userId = $request->query('user_id');
        $projectId = $request->query('project_id');

        $query = Timesheet::query();

        if ($taskName) {
            $query->where('task_name', 'like', '%' . $taskName . '%');
        }

        if ($date) {
            $query->whereDate('date', $date);
        }

        if ($hours != null) {
            $query->where('hours', $hours);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        return $query->get();
    }

    public function getById($id) {
        return Timesheet::with(['user', 'project'])->find($id);
    }

    public function store(array $data) {
       $timesheet = Timesheet::create($data);

       $user = $timesheet->user;
       $project = $timesheet->project;

       if ($user && $project) {
           if (!$project->users()->where('user_id', $user->id)->exists()) {
               $project->users()->attach($user->id);
           }
       }

       return $timesheet;
    }

    public function update(array $data, $id) {
       return Timesheet::whereId($id)->update($data);
    }

    public function delete($id) {
       return Timesheet::destroy($id);
    }
}
