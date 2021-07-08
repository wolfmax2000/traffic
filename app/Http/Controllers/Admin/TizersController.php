<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTizerRequest;
use App\Http\Requests\StoreTizerRequest;
use App\Http\Requests\UpdateTizerRequest;
use App\Tizer;
use App\TemplateTizerClick;
use App\TemplateTizerViews;
use App\TemplateTizer;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class TizersController extends Controller
{
    use MediaUploadingTrait;
    

    public function index()
    {
        abort_if(Gate::denies('tizer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tizers = Tizer::all();
        
        return view('admin.tizers.index', compact('tizers'));
    }

    public function create()
    {
        abort_if(Gate::denies('tizer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('title', 'id');        
        $countries = $this->countries;
        return view('admin.tizers.create', compact('categories', 'countries'));
    }

    public function store(StoreTizerRequest $request)
    {
        $data = $request->all();
        $data['cats'] = implode(',', $data['cats']);
        $tizer = Tizer::create($data);

        if ($request->input('image', false)) {
            $tizer->color = $tizer->getColor(storage_path('tmp/uploads/' . $request->input('image')));
                $tizer->save();
            $tizer->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $tizer->id]);
        }

        return redirect()->route('admin.tizers.index');
    }

    public function edit(Tizer $tizer)
    {
        abort_if(Gate::denies('tizer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('title', 'id');
        $countries = $this->countries;

        $tizer->cats = explode(',', $tizer->cats);
        return view('admin.tizers.edit', compact('tizer', 'categories', 'countries'));
    }

    public function update(UpdateTizerRequest $request, Tizer $tizer)
    {
        $data = $request->all();
        $data['cats'] = implode(',', $data['cats']);
        $tizer->update($data);

        if ($request->input('image', false)) {
            if (!$tizer->image || $request->input('image') !== $tizer->image->file_name) {
                $tizer->color = $tizer->getColor(storage_path('tmp/uploads/' . $request->input('image')));
                $tizer->save();
                $tizer->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
            }
        } elseif ($tizer->image) {
            $tizer->image->delete();
            $tizer->color = null;
            $tizer->save();
        }

        return redirect()->route('admin.tizers.index');
    }

    public function show(Tizer $tizer)
    {
        abort_if(Gate::denies('tizer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tizers.show', compact('tizer'));
    }

    public function destroy(Tizer $tizer)
    {
        abort_if(Gate::denies('tizer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tizer->delete();

        return back();
    }

    public function active(Request $request)
    {
       
        $item = Tizer::find($request->item_id);
        $item->is_active = $request->is_active;
        $item->save();
  
        return response()->json(['success'=>'User status change successfully.']);
    }

    public function toggle(Tizer $tizer)
    {
        var_dump($tizer);die();
        abort_if(Gate::denies('tizer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tizer->is_active = !$tizer->is_active;
        $tizer->save();

        return back();
    }

    public function massDestroy(MassDestroyTizerRequest $request)
    {
        Tizer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('tizer_create') && Gate::denies('tizer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Tizer();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media', 'public');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function null($id)
    {
        TemplateTizerClick::where("tizer_id", $id)->delete();
        TemplateTizerViews::where("tizer_id", $id)->delete();
        TemplateTizer::where('tizer_id', $id)->update(['views'=>1,'clicks' => 0]);        
  
        return redirect()->route('admin.tizers.index');
    }
}
