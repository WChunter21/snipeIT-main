@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
@lang('base.category') {{ $category->name }} ::
@parent
@stop

{{-- Page content --}}
@section('content')

<div class="row header">
        <div class="col-md-12">
            {{ HTML::linkAction('update/category', Lang::get('actions.update'), array($category->id), array('class' => 'btn btn-warning pull-right')) }}
        
        <h3 class="name">@lang('base.category')     :
        {{{ $category->name }}} </h3>
    </div>
</div>


<div class="row form-wrapper">
    <div class="col-md-9">

     <h6>{{{Lang::get('base.category_use')}}} : {{$category->has_models()}}</h6>
		<br> 
		@if ($category->has_models() > 0)
		<table id="example">
                    
			<thead>
				<tr role="row">
                                    <th class="col-md-4"><span class="line"></span>@lang('base.model_shortname')</th>
                                    <th class="col-md-4"><span class="line"></span>@lang('general.count')</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($category->models as $catmodel)
				<tr>
                                    <td>{{ HTML::linkAction('view/model', $catmodel->name, array($catmodel->id)) }} </td>                                    
                                    <td>{{ $catmodel->has_assets() }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else

		<div class="col-md-10">
			<div class="alert alert-info alert-block">
				<i class="icon-info-sign"></i>
				@lang('general.no_results')
			</div>
		</div>
		@endif
                
        </div>

        <!-- side address column -->
        <div class="col-md-3 col-xs-12 address pull-right">
            <h4>@lang('base.category_about')</h4>
            <br>
            <p>@lang('admin/categories/message.about') </p>
    
        </div>
</div>

@stop
