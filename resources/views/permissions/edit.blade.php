@extends(backpack_view('blank'))
@section('content')
    <h1 class="heading">{{$heading}}</h1>

    <div class="row">
        <div class="col-md-8 bold-labels">
            <form action="{{route('permissions.update', $permission->id)}}" method="post">
                @csrf
                @method('PATCH')
                <div class="card">
                    <div class="card-body row">
                        <!-- text input -->
                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.name_ar')}}</label>
                            <input type="text" name="name_ar" value="{{$permission->name_ar}}" class="form-control @error('name_ar') is-invalid @enderror">
                            @error('name_ar')
                            <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.name_en')}}</label>
                            <input type="text" name="name_en" value="{{$permission->name_en}}" class="form-control @error('name_en') is-invalid @enderror">
                            @error('name_en')
                            <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{trans('projectlang.save_data')}}</button>
                        <a href="/" class="btn btn-default">{{trans('projectlang.cancel')}}</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('after_scripts')
    <!--Display noty alerts-->
    <script>
        @if(session()->has('success'))
        new Noty({
            type: "success",
            layout: @if($lang === 'ar') 'topLeft' @else 'topRight' @endif ,
            text: '{{session()->get('success')}}'
        }).show();
        @endif

        @if ($errors->any())
        @foreach($errors->all() as $error)
        new Noty({
            type: "error",
            layout: @if($lang === 'ar') 'topLeft' @else 'topRight' @endif ,
            text: '{{$error}}'
        }).show();
        @endforeach
        @endif
    </script>
@endpush
