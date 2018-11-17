@extends('user.index')
@section('content')
    <section class="content-header" style="margin-top: 50px;">
        <h1 style="font-weight: bold;font-family: Times New Roman;">
            {{ $header }}
            <small>{{ $description }}</small>
        </h1>

        <!-- breadcrumb start -->
        @if ($breadcrumb)
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{ url('user/sinh-vien') }}"><i class="fa fa-home"></i> Trang chủ</a></li>
                @foreach($breadcrumb as $item)
                    @if($loop->last)
                        <li class="active">
                            @if (array_has($item, 'icon'))
                                <i class="fa fa-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['text'] }}
                        </li>
                    @else
                        <li>
                            <a href="{{ admin_url(array_get($item, 'url')) }}">
                                @if (array_has($item, 'icon'))
                                    <i class="fa fa-{{ $item['icon'] }}"></i>
                                @endif
                                {{ $item['text'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ol>
    @endif
    <!-- breadcrumb end -->

    </section>

    <section class="content">

        @include('user.partials.error')
        @include('user.partials.success')
        @include('user.partials.exception')
        @include('user.partials.toastr')

        {!! $content !!}

    </section>
@endsection
