<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditRecord;

class AuditTrailController extends Controller
{
    public function index()
    {
        return view('audit-trail.index', [
            'audits' => AuditRecord::all()
        ]);
    }

    public function show(AuditRecord $audit)
    {
        return view('audit-trail.show', [
            'audit' => $audit
        ]);
    }

}
