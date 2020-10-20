@php
$plugins=[
        [
            'name' => 'Datatables',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css',
                ],
            ],
        ],
        [
            'name' => 'Select2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => url('bower_components/select2/dist/js/select2.min.js'),
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' =>url('bower_components/select2/dist/css/select2.min.css'),
                ],
            ],
        ],
       
        [
            'name' => 'Sweetalert2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => url('bower_components/PACE/themes/black/pace-theme-loading-bar.css'),
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => url('bower_components/PACE/pace.min.js'),
                ],
            ],
        ],
        [
            'name' => 'axios',
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => url('bower_components/PACE/themes/black/pace-theme-loading-bar.css'),
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => url('bower_components/PACE/pace.min.js'),
                ],
            ],
        ],
    ];

@endphp


@foreach($plugins as $pluginName => $plugin)

    @if($plugin['active'] || View::getSection('plugins.' . ($plugin['name'] ?? $pluginName)))
  
        @foreach($plugin['files'] as $file)

            {{-- Check requested file type --}}
            @if($file['type'] == $type && $type == 'css')
                <link rel="stylesheet" href="{{ $file['asset'] ? asset($file['location']) : $file['location'] }}">
            @elseif($file['type'] == $type && $type == 'js')
                <script src="{{ $file['asset'] ? asset($file['location']) : $file['location'] }}"></script>
            @endif

        @endforeach
    @endif
@endforeach
