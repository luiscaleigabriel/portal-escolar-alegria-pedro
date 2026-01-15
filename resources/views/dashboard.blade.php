@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
                <div class="icon purple">
                    <i class="lni lni-users"></i>
                </div>
                <div class="content">
                    <h6 class="mb-10">Alunos</h6>
                    <h3>{{ $students }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
                <div class="icon success">
                    <i class="lni lni-graduation"></i>
                </div>
                <div class="content">
                    <h6 class="mb-10">Professores</h6>
                    <h3>{{ $teachers }}</h3>
                </div>
            </div>
        </div>

    </div>
@endsection
