@extends('layouts.main')

@section("sTitleTag", $oModel->seo_title??$oModel->name." | ".config('app.name'))
@section("sDescTag", $oModel->seo_description??"")
@section("sKeywordsTag", $oModel->seo_keywords??"")

@section('content')
    @if($bIsEdited)
        <div class="editorjs" data-exidata="{{ $oModel->text }}">
        </div>
        <button class="save_edied" style="display: none;">Save</button>
    @else
    {!! \App\Classes\EditorJS::render($oModel->text) !!}
    @endif
@endsection

@section('css')
    @if($bIsEdited)
        <link rel="stylesheet" href="{{ asset('admin/libs/editorjs/styles.css') }}">
        <style>
        .editorjs
        {
            padding-top: 53.4vh;
        }
        .save_edied
        {
            position: fixed;
            bottom: 35px;
            z-index: 1;

            box-shadow: 0 1px rgb(0 0 0 / 8%), 0 0 1px rgb(255 255 255 / 20%) inset;
            background-color: #f5f5f7;
            -webkit-appearance: none;
            -webkit-user-select: none;
            border-radius: 4px;
            margin: 0;
            padding: 0 0.5em;
            border: 1px solid #bcbcca;
            display: inline-block;
            overflow: visible;
            color: rgba(0,0,0,0.7);
            font: normal normal 100%/1.75em Ubuntu, Helvetica Neue, sans-serif;
            text-decoration: none;
            text-shadow: 0 1px 0 #d9d9e0;
            white-space: normal;
            cursor: pointer;
            outline: none;
            vertical-align: middle;
        }
    </style>
    @endif
@endsection

@section('scripts')
    @if($bIsEdited)
        <script src="{{ asset('admin/libs/sortablejs/Sortable.min.js') }}"></script>
        <script type="module" src="{{ asset('admin/libs/editorjs/public.js') }}"></script>
        <script>
            var is_edited = false;
            $('.save_edied').on('click',function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('admin_static_page_edit_text',['oModel'=>$oModel]) }}',
                    data: {
                        "_token":$('[name="csrf-token"]').attr('content'),
                        text:$('.editorjs').attr('data-exidata'),
                    },
                    dataType: 'JSON',
                    type: 'POST',
                    success: function(data){
                        $('.save_edied').hide();
                        is_edited = false;
                        alert('saved');
                    },
                    error: function(data) {
                        if(data.status==422)
                        {

                        }
                    }
                });
            });
            window.addEventListener('beforeunload', (event) => {
                if(is_edited){
                    // Отмените событие, как указано в стандарте.
                    event.preventDefault();
                    // Chrome требует установки возвратного значения.
                    event.returnValue = '';
                }
            });
        </script>
    @endif
@endsection
