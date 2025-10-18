<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditRecord;
use App\Services\Format;

class AuditTrailController extends Controller
{
    public function index()
    {
        return view('audit-trail.index', [
            'audits' => AuditRecord::orderBy('created_at', 'desc')
                 ->paginate('50')
        ]);
    }

    public function show(AuditRecord $audit)
    {
        return view('audit-trail.show', [
            'backRoute' => route('audit.index'),
            'action' => $audit->action,
            'tableName' => $audit->table_name,
            'columnNames' => $audit->column_names,
            'primaryKey' => $audit->primary_key,
            'requestId' => $audit->request_id,
            'requestUrl' => $audit->request_url,
            'requestMethod' => $audit->request_method,
            'requestTime' => Format::date($audit->request_time),
            'userId' => $audit->user_id,
            'userAgent' => $audit->user_agent,
            'sessionId' => $audit->session_id,
            'createdAt' => Format::date($audit->created_at),
            'updatedAt' => Format::date($audit->updated_at),
        ]);
    }

}
