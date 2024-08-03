<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use App\Helper\ResponseHelper;
use App\Http\Requests\CreateTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Http\Requests\DeleteTimesheetRequest;
use App\Interfaces\TimesheetRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimesheetController extends Controller
{
    private TimesheetRepositoryInterface $timesheetRepository;

    public function __construct(TimesheetRepositoryInterface $timesheetRepository) {
        $this->timesheetRepository = $timesheetRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Add filters
        $timesheet = $this->timesheetRepository->index($request);
        return ResponseHelper::success(message: 'List of timesheet.', data: $timesheet, statusCode: 200);
    }

    /**
     * Create new timesheet
     */
    public function create(CreateTimesheetRequest $request)
    {
        $data = [
            'task_name' => $request->task_name,
            'date' => $request->date,
            'hours' => $request->hours,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id
        ];
        DB::beginTransaction();
        try{
             $timesheet = $this->timesheetRepository->store($data);

             DB::commit();
            return ResponseHelper::success(message: 'A new timesheet has been created', data: $timesheet, statusCode: 200);
        } catch(Exception $e){
            DB::rollBack();
            \Log::error('Unable to create the timesheet. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to create the timesheet.', statusCode: 500);
        }
    }

    /**
     * Display the specified timesheet.
     */
    public function show(string $id)
    {
        try {
            $data = $this->timesheetRepository->getById($id);
            if(!$data) {
                return ResponseHelper::error(message: 'Timesheet not found.', statusCode: 404);
            }
            return ResponseHelper::success(message: 'Specified timesheet.', data: $data, statusCode: 200);
        } catch (Exception $e) {
            return ResponseHelper::error(message: 'Unable to find the timesheet.', statusCode: 500);
        }
    }

    /**
     * Update the specified timesheet.
     */
    public function update(UpdateTimesheetRequest $request)
    {
        $data = [
            'task_name' => $request->task_name,
            'date' => $request->date,
            'hours' => $request->hours,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id
        ];
        DB::beginTransaction();
        try {
            $affectedRows = $this->timesheetRepository->update($data, $request->id);
            DB::commit();
            if($affectedRows > 0)
                return ResponseHelper::success(message: 'The timesheet has been updated.', data: null, statusCode: 200);
            else
                return ResponseHelper::error(message: 'The timesheet not found to be updated.', statusCode: 404);
        } catch(Exception $e) {
            DB::rollBack();
            \Log::error('Unable to update the timesheet. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to update the timesheet.', statusCode: 500);
        }
    }

    /**
     * Remove the specified timesheet.
     */
    public function delete(DeleteTimesheetRequest $request)
    {
        $affectedRows = $this->timesheetRepository->delete($request->id);
        if($affectedRows > 0)
            return ResponseHelper::success(message: 'The timesheet has been deleted.', data: null, statusCode: 200);
        else
            return ResponseHelper::error(message: 'The timesheet not found to be deleted.', statusCode: 404);
    }
}
