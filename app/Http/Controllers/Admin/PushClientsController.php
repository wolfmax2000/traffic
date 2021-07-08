<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPushClientRequest;

use App\PushClient;
use App\Domain;

use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PushClientsController extends Controller
{
    //use MediaUploadingTrait;
    
    public function index()
    {        
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $clients = PushClient::where('id', '>', 0);

        $domains = array_merge(['all_domains'], PushClient::select('domen')->distinct()->get()->pluck('domen')->toArray());
        $current_domain = isset($_GET['current_domain']) ? $_GET['current_domain'] : false;
        if ( $current_domain && $current_domain !== 'all_domains' ) {            
            $clients = $clients->where('domen', $current_domain);
        }

        $sid8s = array_merge(['all_sid8s'], PushClient::select('sid8')->distinct()->get()->pluck('sid8')->toArray());
        
        $current_sid8 = isset($_GET['current_sid8']) ? $_GET['current_sid8'] : false;
        if ( $current_sid8 && $current_sid8 !== 'all_sid8s' ) {            
            $clients = $clients->where('sid8', $current_sid8);
            
        }
        
        $clients = $clients->get();

        return view('admin.push-clients.index', compact('clients', 'domains', 'current_domain', 'sid8s', 'current_sid8'));
    }


    public function destroy(PushClient $push_client)
    {
        abort_if(Gate::denies('push_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $push_client->delete();

        return back();
    }

    public function massDestroy(MassDestroyPushClientRequest $request)
    {
        PushTemplate::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

}
