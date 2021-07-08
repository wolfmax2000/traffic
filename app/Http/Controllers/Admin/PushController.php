<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPushRequest;
use App\Http\Requests\StorePushRequest;
use App\Http\Requests\UpdatePushRequest;
use App\PushTemplate;
use App\Push;
use App\PushClient;

use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PushController extends Controller
{
    use MediaUploadingTrait;

    public function subscriber() {
        return response()->json([
            'test' => "done"            
        ]);
    }
    
    public function index(PushTemplate $push_template)
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pushes = Push::where('template_id', $push_template->id)->get();
    
        return view('admin.push.index', compact('pushes' ,'push_template'));
    }

    public function create(PushTemplate $template)
    {
        
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $devices = Push::$devices;
        $top_types = Push::$top_types;
        $push_types = Push::$push_types;
        $countries = $this->countries;

        $domains = array_merge(['all_domains'], PushClient::select('domen')->distinct()->get()->pluck('domen')->toArray());

        return view('admin.push.create', compact('devices', 'countries', 'top_types', 'push_types', 'template', 'domains'));
    }

    public function store(StorePushRequest $request)
    {        
        $data = $request->all();
        $push = Push::create($data);

        if ($request->input('image', false)) {           
            $push->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        if ($request->input('icon', false)) {           
            $push->addMedia(storage_path('tmp/uploads/' . $request->input('icon')))->toMediaCollection('icon');
        }

        return redirect()->route('admin.pushes.index', $push->template_id);
    }

    public function edit(Push $push)
    {       
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $devices = Push::$devices;
        $top_types = Push::$top_types;
        $push_types = Push::$push_types;
        $countries = $this->countries;
        
        $domains = array_merge(['all_domains'], PushClient::select('domen')->distinct()->get()->pluck('domen')->toArray());

        return view('admin.push.edit', compact('push', 'devices', 'countries', 'top_types', 'push_types', 'domains'));
    }

    public function update(UpdatePushRequest $request, Push $push)
    {
        
        $data = $request->all();        
        $push->update($data);

        if ($request->input('image', false)) {      
            $push->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        if ($request->input('icon', false)) {           
            $push->addMedia(storage_path('tmp/uploads/' . $request->input('icon')))->toMediaCollection('icon');
        }

        return redirect()->route('admin.pushes.index', $push->template_id);
    }

    public function destroy(Push $push)
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $push->delete();

        return back();
    }

    public function status(Push $push, $status)
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $push->status = $status;
        if ( $status === 'process') {
            $push->send();
        }
        $push->save();

        return back();
    }

    public function massDestroy(MassDestroyPushRequest $request)
    {
        Push::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

}
