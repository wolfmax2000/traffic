<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTemplateRequest;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Category;
use App\Template;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TemplatesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $templates = Template::all();

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        abort_if(Gate::denies('template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('title', 'id');
        //$types = Template::TEMPLATE_TYPES;

        return view('admin.templates.create', compact('categories'));
    }

    public function store(StoreTemplateRequest $request)
    {
        if(!$request->has('mix'))
        {
            $request->merge(['mix' => 0]);
        }

        $template = Template::create($request->all());
        $template->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.templates.index');
    }

    public function edit(Template $template)
    {
        abort_if(Gate::denies('template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('title', 'id');

        $template->load('categories');
        //$types = Template::TEMPLATE_TYPES;

        return view('admin.templates.edit', compact('categories', 'template'));
    }

    public function update(UpdateTemplateRequest $request, Template $template)
    {
        if(!$request->has('mix'))
        {
            $request->merge(['mix' => 0]);
        }
        
        $template->update($request->all());
        $template->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.templates.index');
    }

    public function show(Template $template)
    {
        abort_if(Gate::denies('template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $template->load('categories');

        return view('admin.templates.show', compact('template'));
    }

    public function destroy(Template $template)
    {
        abort_if(Gate::denies('template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $template->delete();

        return back();
    }

    public function massDestroy(MassDestroyTemplateRequest $request)
    {
        Template::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
