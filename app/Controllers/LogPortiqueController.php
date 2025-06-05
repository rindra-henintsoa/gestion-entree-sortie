<?php

namespace App\Controllers;

use App\Models\LogPortiqueModel;

class LogPortiqueController extends BaseController
{
    public function index()
    {
        $model = new LogPortiqueModel();

        $perPage = 40;
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
        $model = new LogPortiqueModel();
        $date = $this->request->getVar('date');

        $logs = [];

        if ($date) {
            $logs = $model->getLogsParDate($date);
        }

        return view('logs', [
            'logs' => $logs,
            'date' => $date,
        ]);
    }
}
