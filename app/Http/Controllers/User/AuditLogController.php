<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuditService;

class AuditLogController extends Controller
{

    protected $auditService;

    public function __construct(AuditService $auditService){
        $this->auditService  = $auditService;
    }

    public function index(){

       return view('users.admin.auditlog');

    }

    public function list(Request $request){

        return $this->auditService->listService($request);

    }

}
