<?php

namespace App\Controllers;

use App\Models\LogPortiqueModel;

class LogPortiqueController extends BaseController
{
    public function index()
    {
        $model = new LogPortiqueModel();

        $perPage = 20;
        $page = (int) $this->request->getVar('page') ?: 1;
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $perPage;

        $collaborateurs = $model->getCollaborateursAvecCartes($perPage, $offset);
        $total = $model->countCollaborateurs();
        $totalPages = ceil($total / $perPage);

        return view('collaborateurs', [
            'collaborateurs' => $collaborateurs,
            'currentPage' => $page,
            'total' => $total,
            'totalPages' => $totalPages,
        ]);
    }

    public function logs()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $model = new LogPortiqueModel();

        $perPage = 20;
        $currentPage = (int) $this->request->getVar('page') ?: 1;
        if ($currentPage < 1) $currentPage = 1;

        $offset = ($currentPage - 1) * $perPage;

        $logs = $model->getLogsParDate($date, $perPage, $offset);
        $total = $model->countLogsParDate($date);
        $totalPages = ceil($total / $perPage);

        $volumesPause = $model->getVolumePauseParCollaborateur($date);

        return view('logs', [
            'logs' => $logs,
            'volumesPause' => $volumesPause,
            'date' => $date,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }
}
