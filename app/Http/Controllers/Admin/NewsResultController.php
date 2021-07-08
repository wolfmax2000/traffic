<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTizerRequest;
use App\Http\Requests\StoreTizerRequest;
use App\Http\Requests\UpdateTizerRequest;
use App\Template;
use App\News;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class NewsResultController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('tizer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       

        $templates = Template::all();
        $current_template = isset($_GET['current_template']) ? intval($_GET['current_template']) : 1;
        $params = [];

        $params['template_id'] = $current_template;

        $countries = $this->countries;
        $current_country = isset($_GET['current_country']) ? $_GET['current_country'] : "Россия";

        $params['country'] = $current_country;

        $params['cats'] = [];
        $templateFound = Template::find($current_template);
        $templateFound->load('categories');
        foreach ($templateFound->categories as $c) {
            $params['cats'][] = $c['id'];
        } 
    
        $tizers = News::algo($params)->get();

        return view('admin.news-result.index', compact('tizers', 'countries', 'templates', 'current_template', 'current_country'));
    }

    
}
