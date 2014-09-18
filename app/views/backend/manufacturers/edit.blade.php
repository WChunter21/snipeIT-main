@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
    @if ($manufacturer->id)
        @lang('base.manufacturer_update') ::
    @else
        @lang('base.manufacturer_create') ::
    @endif
@parent
@stop

{{-- Page content --}}
@section('content')

<form class="form-horizontal" method="post" action="" autocomplete="off">

<div class="row header">
    <div class="col-md-10">
            
        <button type="submit" class="btn btn-success pull-right"><i class="icon-ok icon-white"></i> @lang('actions.save')</button>            
        <a href="{{ URL::previous() }}" class="btn btn-default pull-right"><i class="icon-circle-arrow-left icon-white"></i> @lang('actions.cancel')</a>
            
        <h3>
        @if ($manufacturer->id)
            @lang('base.manufacturer_update')
        @elseif(isset($clone_manufacturer))
            @lang('base.manufacturer_clone')
        @else
            @lang('base.manufacturer_create')
        @endif
        </h3>
            
    </div>                            
</div>

<div class="col-md-12">


    <!-- CSRF Token -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <!-- Name -->
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-2 control-label">@lang('general.name')
                 <i class='icon-asterisk'></i></label>
                 </label>
                    <div class="col-md-5">
                        <input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $manufacturer->name) }}}" />
                        {{ $errors->first('name', '<span class="alert-msg"><i class="icon-remove-sign"></i> :message</span>') }}
                    </div>
            </div>

            <!-- Form actions -->
            <div class="form-group">
                <br>
                <label class="col-md-2 control-label"></label>
                <div class="col-md-7">
                        <a href="{{ URL::previous() }}" class="btn btn-default"><i class="icon-circle-arrow-left icon-white"></i> @lang('actions.cancel')</a>
                        <button type="submit" class="btn btn-success"><i class="icon-ok icon-white"></i> @lang('actions.save')</button>
                </div>
            </div>
</div>
</form>

@stop
