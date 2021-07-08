<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPushTemplateRequest;
use App\Http\Requests\StorePushTemplateRequest;
use App\Http\Requests\UpdatePushTemplateRequest;
use App\PushTemplate;
use App\Push;

use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PushTemplatesController extends Controller
{
    use MediaUploadingTrait;
    
    public function index()
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $templates = PushTemplate::all();
    
        return view('admin.push-templates.index', compact('templates'));
    }

    public function create()
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $devices = Push::$devices;
        $top_types = Push::$top_types;
        $countries = $this->countries;
        return view('admin.push-templates.create', compact('devices', 'countries', 'top_types'));
    }

    public function store(StorePushTemplateRequest $request)
    {        
        $data = $request->all();
        $template = PushTemplate::create($data);

        return redirect()->route('admin.push-templates.index');
    }

    public function edit(PushTemplate $push_template)
    {       
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $devices = Push::$devices;
        $top_types = Push::$top_types;
        $countries = $this->countries;

        return view('admin.push-templates.edit', compact('push_template', 'devices', 'countries', 'top_types'));
    }

    public function update(UpdatePushTemplateRequest $request, PushTemplate $push_template)
    {
        $data = $request->all();        
        $push_template->update($data);

        return redirect()->route('admin.push-templates.index');
    }

    public function destroy(PushTemplate $push_template)
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $push_template->delete();

        return back();
    }

    public function massDestroy(MassDestroyPushTemplateRequest $request)
    {
        PushTemplate::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

}
