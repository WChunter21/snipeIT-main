<!-- Component -->
<div id="component-select" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!!  (isset($style)) ? ' style="'.e($style).'"' : ''  !!}>
    {{ Form::label($fieldname, $translated_name, array('class' => 'col-md-3 control-label')) }}
    <div class="col-md-7{{  ((isset($required) && ($required =='true'))) ?  ' required' : '' }}">
        <select class="js-data-ajax select2" data-endpoint="accessories" data-placeholder="{{ trans('general.select_component') }}" name="{{ $fieldname }}" style="width: 100%" id="{{ (isset($select_id)) ? $select_id : 'assigned_component_select' }}"{{ (isset($multiple)) ? ' multiple' : '' }}>

            @if ((!isset($unselect)) && ($component_id = Input::old($fieldname, (isset($component) ? $component->id  : (isset($item) ? $item->{$fieldname} : '')))))
                <option value="{{ $component_id }}" selected="selected">
                    {{ (\App\Models\Component::find($component_id)) ? \App\Models\Component::find($component_id)->present()->fullName : '' }}
                </option>
            @else
                @if(!isset($multiple))
                    <option value="">{{ trans('general.select_component') }}</option>
                @endif
            @endif
        </select>
    </div>
    {!! $errors->first($fieldname, '<div class="col-md-8 col-md-offset-3"><span class="alert-msg"><i class="fa fa-times"></i> :message</span></div>') !!}

</div>
