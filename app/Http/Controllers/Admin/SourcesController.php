<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use App\Script;
use App\Source;
use Gate;

use Symfony\Component\HttpFoundation\Response;

class SourcesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('template_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = Source::all();

        return view('admin.sources.index', compact('sources'));
    }

    public function create()
    {
        abort_if(Gate::denies('template_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $scripts = Script::all()->pluck('title', 'id');
        return view('admin.sources.create', compact('scripts'));
    }

    public function store(StoreSourceRequest $request)
    {
        $source = Source::create($request->all());
        $source->scripts()->sync($request->input('scripts', []));

        return redirect()->route('admin.sources.index');
    }

    public function edit(Source $source)
    {
        abort_if(Gate::denies('template_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scripts = Script::all()->pluck('title', 'id');

        $source->load('scripts');

        return view('admin.sources.edit', compact('scripts', 'source'));
    }

    public function update(UpdateSourceRequest $request, Source $source)
    {
        $source->update($request->all());
        $source->scripts()->sync($request->input('scripts', []));

        return redirect()->route('admin.sources.index');
    }

    public function show(Source $source)
    {
        abort_if(Gate::denies('template_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $source->load('scripts');

        return view('admin.templates.show', compact('source'));
    }

    public function destroy(Source $source)
    {
        abort_if(Gate::denies('template_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $source->delete();

        return back();
    }

    public function massDestroy(MassDestroyTemplateRequest $request)
    {
        Source::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
