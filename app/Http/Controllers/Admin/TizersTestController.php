<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTizerTestRequest;
use App\Template;
use App\Tizer;
use App\TizerTest;

use Spatie\MediaLibrary\Models\Media;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TizersTestController extends Controller
{
    use MediaUploadingTrait;
    

    public function index()
    {
        abort_if(Gate::denies('tizer_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');          
        $tests = TizerTest::get();

        return view('admin.tizers-test.index', compact('tests'));
    }

    public function create()
    {
        $tizers = Tizer::get();
        abort_if(Gate::denies('tizer_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.tizers-test.create', compact('tizers'));
    }

    public function store(StoreTizerTestRequest $request)
    {
        $tizerTest = TizerTest::create($request->all());
       
        if ($request->input('image', false)) {
            $tizerTest->color = $tizerTest->getColor(storage_path('tmp/uploads/' . $request->input('image')));
            $tizerTest->save();
            $tizerTest->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $tizerTest->id]);
        }

        return redirect()->route('admin.tizers-test.index');
    }

    public function destroy($id)
    {                
        abort_if(Gate::denies('tizer_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');        
        TizerTest::find($id)->delete();

        return back();
    }

    public function active(Request $request)
    {
       
        $item = TizerTest::find($request->item_id);
        $item->is_active = $request->is_active;
        $item->save();
  
        return response()->json(['success'=>'User status change successfully.']);
    }

    public function applyb()
    {  
        $item = TizerTest::find($_GET['id']);
        
        if ($item && $item->image ) {
            $item->tizer->image->delete();       
            $item->tizer->addMedia($item->image->getPath())->toMediaCollection('image');            
        }

        if ($item && $item->title && strlen(trim($item->title)) > 0 ) {
            $item->tizer->title = $item->title;
            $item->tizer->save();
        }
        return back();

    }
}
