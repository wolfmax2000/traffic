<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyScriptRequest;
use App\Http\Requests\UpdateScriptRequest;
use App\Script;
use App\Http\Requests\StoreScriptRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ScriptsController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scripts = Script::all();

        return view('admin.scripts.index', compact('scripts'));
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.scripts.create');
    }

    public function store(StoreScriptRequest $request)
    {
        $script = Script::create($request->all());

        return redirect()->route('admin.scripts.index');
    }

    public function edit(Script $script)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.scripts.edit', compact('script'));
    }

    public function update(UpdateScriptRequest $request, Script $script)
    {
        $script->update($request->all());

        return redirect()->route('admin.scripts.index');
    }

    public function destroy(Script $script)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $script->delete();

        return back();
    }

    public function massDestroy(MassDestroyScriptRequest $request)
    {
        Script::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
