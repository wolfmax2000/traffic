<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNewsRequest;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\News;
use App\Category;
use App\TemplateNewsClick;
use App\TemplateNewsViews;
use App\TemplateNews;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    use MediaUploadingTrait;
    
    public function index()
    {
        abort_if(Gate::denies('news_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $news = News::all();
        $countries = $this->countries;

        $current_country = '';
        if ( isset($_GET['current_country']) && $_GET['current_country'] ) {
            $current_country = $_GET['current_country'];
            $news = News::where('country', $current_country)->get();
        }
        else {
            $news = News::all();
        }

        return view('admin.news.index', compact('news', 'countries', 'current_country'));
    }

    public function create()
    {
        abort_if(Gate::denies('news_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $countries = $this->countries;
        $categories = Category::all()->pluck('title', 'id');  
        return view('admin.news.create', compact( 'countries', 'categories'));
    }

    public function store(StoreNewsRequest $request)
    {
        $data = $request->all();
        $data['cats'] = implode(',', $data['cats']);
        $news = News::create($data);

        if ($request->input('image', false)) {
            $news->color = $news->getColor(storage_path('tmp/uploads/' . $request->input('image')));
            $news->save();
            $news->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $news->id]);
        }

        return redirect()->route('admin.news.index');
    }

    public function edit(News $news)
    {
        abort_if(Gate::denies('news_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $countries = $this->countries;
        $categories = Category::all()->pluck('title', 'id');
        
        $news->cats = explode(',', $news->cats);
        return view('admin.news.edit', compact('news',  'countries', 'categories'));
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $data = $request->all();
        $data['cats'] = implode(',', $data['cats']);
        $news->update($data);

        if ($request->input('image', false)) {            
            if (!$news->image || $request->input('image') !== $news->image->file_name) {
               
                $news->color = $news->getColor(storage_path('tmp/uploads/' . $request->input('image')));
                $news->save();
                $news->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
                
            }
        } elseif ($news->image) {
            $news->image->delete();
            $news->color = null;
            $news->save();
        }

        return redirect()->route('admin.news.index');
    }

    public function destroy(News $news)
    {
        abort_if(Gate::denies('news_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $news->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewsRequest $request)
    {
        News::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('news_create') && Gate::denies('news_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new News();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media', 'public');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function null($id)
    {
        TemplateNewsClick::where("news_id", $id)->delete();
        TemplateNewsViews::where("news_id", $id)->delete();

        TemplateNews::where('news_id', $id)->update(['views'=>1,'clicks' => 0]); 
  
        return redirect()->route('admin.news.index');
    }
}
