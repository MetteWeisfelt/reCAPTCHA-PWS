@if(session('success', isset($success) ? $success : ''))
    <div class="alert alert-success">
        {{session('success', isset($success) ? $success : '')}}
    </div>
@endif

@if(session('error', isset($error) ? $error : ''))
    <div class="alert alert-danger">
        {{session('error', isset($error) ? $error : '')}}
    </div>
@endif

@if(session('warning', isset($warning) ? $warning : ''))
    <div class="alert alert-warning">
        {{session('warning', isset($warning) ? $warning : '')}}
    </div>
@endif

@if(!$errors->isEmpty())
    <div class="alert alert-danger">
        Er zijn een of meerdere validatie fouten opgetreden. Loop het formulier zorgvuldig na.
    </div>
@endif