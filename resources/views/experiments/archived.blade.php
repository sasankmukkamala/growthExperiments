@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left">Archived Experiments</h3>
                    <div class="clearfix"></div>
                </div>

                <div class="panel-body">
                    @if (count($experiments))
                    <table class="table">
                        <thead class="thead-default">
                            <tr>
                                <th>Name</th>
                                <th>Phase</th>
                                <th>Score</th>
                                <th>Creator</th>
                                <th>Due Date</th>
                                <th class="text-center">Progress</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($experiments as $experiment)
                            <tr>
                                <td>
                                  <a href="/experiments/edit/{{ $experiment->id }}" />
                                    {{ $experiment->name }}
                                    <br/>
                                    <span class="label label-{{ strtolower($experiment->getTagName($experiment->tags)) }}">{{ $experiment->getTagName($experiment->tags) }}</span>
                                  </a>
                                </td>
                                <td>{{ $experiment->getPhase($experiment->phase) }}</td>
                                <td><span class="label label-{{ $experiment->getScoreLabel($experiment->score) }}">{{ $experiment->score }}</span></td>
                                <td>{{ $experiment->creator->name }}</td>
                                <td>{{ $experiment->due_date }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-{{ $experiment->getPhaseProgressColor($experiment->phase) }}" role="progressbar" aria-valuenow="{{ $experiment->getPhaseProgress($experiment->phase) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $experiment->getPhaseProgress($experiment->phase) }}%"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a title="Edit" href="/experiments/edit/{{ $experiment->id }}" />
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a title="Unarchive" href="/experiments/unarchive/{{ $experiment->id }}" />
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                                    </a>
                                    <a title="Delete" href="/experiments/delete/{{ $experiment->id }}" />
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No experiments found!</p>
                    @endif
                </div>
            </div>

            {{ $experiments->links() }}
        </div>
    </div>
</div>
@endsection
