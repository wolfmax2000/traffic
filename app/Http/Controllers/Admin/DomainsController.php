<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Domain;
use App\Template;
use App\Http\Requests\StoreDomainRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainsController extends Controller
{

    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('domain_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::all();

        return view('admin.domains.index', compact('domains'));
    }

    public function create()
    {        
        abort_if(Gate::denies('domain_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $templates  = Template::all()->pluck('title', 'id');   

        return view('admin.domains.create', compact('templates'));
    }

    public function store(StoreDomainRequest $request)
    {        
        $domain = Domain::create($request->all());

        return redirect()->route('admin.domains.index');
    }

    public function edit(Domain $domain)
    {
       
        abort_if(Gate::denies('domain_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $templates  = Template::all()->pluck('title', 'id');   
        $countries = array_merge(['all_country'], $this->countries);
        $devices = $this->devices;
        $types = Domain::$types;
        return view('admin.domains.edit', compact('templates', 'domain',  'countries', 'devices', 'types'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
         if ($request->input('image', false)) {                        
            if (!$domain->image || $request->input('image') !== $domain->image->file_name) {                            
                $domain->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');                
            }
        } elseif ($domain->image) {
            $domain->image->delete();                    
        }

        if ($request->input('banner', false)) {                        
            if (!$domain->banner || $request->input('banner') !== $domain->banner->file_name) {                            
                $domain->addMedia(storage_path('tmp/uploads/' . $request->input('banner')))->toMediaCollection('banner');                
            }
        } elseif ($domain->banner) {
            $domain->banner->delete();                    
        }

        if ($request->input('info1', false)) {                        
            if (!$domain->info1 || $request->input('info1') !== $domain->info1->file_name) {                            
                $domain->addMedia(storage_path('tmp/uploads/' . $request->input('info1')))->toMediaCollection('info1');                
            }
        } elseif ($domain->info1) {
            $domain->info1->delete();                    
        }
        
        if ($request->input('info2', false)) {                        
            if (!$domain->info2 || $request->input('info2') !== $domain->info2->file_name) {                            
                $domain->addMedia(storage_path('tmp/uploads/' . $request->input('info2')))->toMediaCollection('info2');                
            }
        } elseif ($domain->info2) {
            $domain->info2->delete();                    
        }

        if ($request->input('info3', false)) {                        
            if (!$domain->info3 || $request->input('info3') !== $domain->info3->file_name) {                            
                $domain->addMedia(storage_path('tmp/uploads/' . $request->input('info3')))->toMediaCollection('info3');                
            }
        } elseif ($domain->info3) {
            $domain->info3->delete();                    
        }

        if ( isset($_FILES['white_files']) ) {
            $landingDir = __DIR__ . '/../../../../landings/' . $domain->title;
            foreach ($_FILES['white_files']['name'] as $key => $fileName ) {            
                if (!is_dir($landingDir)) {
                    mkdir($landingDir);
                }
                rename($_FILES['white_files']['tmp_name'][$key], $landingDir . "/" . $fileName );
            }        
        }
        

        $domain->update($request->all());

        return redirect()->route('admin.domains.index');
    }

    public function destroy(Domain $domain)
    {
        abort_if(Gate::denies('domain_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->delete();

        return back();
    }

    public function massDestroy(MassDestroyDomainRequest $request)
    {
        Domain::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
