<?php

namespace App\Services;

use App\Models\TaskHistory;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;

class TaskHistoryService {

    public function getAll($id) {
        return TaskHistory::where('task_id', $task_id);
    }

    public function store($input) {
        return TaskHistory::create($input->except('_token'));
    }
}
