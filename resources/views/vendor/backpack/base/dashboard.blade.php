@extends('backpack::layout')

@section('after_styles')
{!! Charts::assets() !!}
@endsection

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    {!! $chartRoutes->render() !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    {!! $chartPlatform->render() !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-body">
                    {!! $chartMobileOS->render() !!}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    {!! $chartReferer->render() !!}
                </div>
            </div>
        </div>

       
    </div>
@endsection
