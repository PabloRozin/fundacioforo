<div class="item multiItem {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
    @endif

    <input 
        id="multiItem-{{ $name }}" 
        type="text" 
        autocomplete="off"
        data-min-length="{{ (isset($config['minLength'])) ? $config['minLength'] : 3 }}"
    >
    <div class="small">{!! $config['help'] !!}</div>

    <table id="multiItem-{{ $name }}-selection" class="table multiItem-table">
        @if (isset($model))
            @foreach ($model->$name as $itemKey => $item)
                <tr id="multiItem-{{ $name }}-item-{{ $item->id }}">
                    <td>
                        <div class="multiItem-table-item row pb-0 mb-0"><!--
                            @foreach ($config['data'] as $dataKey => $data)
                                --><div class="col col-1-2 pt-0 mt-0">
                                    <div class="multiItem-table-item-title">{{ $data['label'] }}</div>
                                    <input 
                                        class="multiItem-{{ $name }}-item"
                                        type="text"
                                        name="{{ $name }}[{{ $itemKey }}][{{ $data['name'] }}]"
                                        value="{{ $item->{$data['name']} }}"
                                    >
                                </div><!--
                            @endforeach
                            --><div class="lnr lnr-cross multiItem-table-item-close" data-id="#multiItem-{{ $name }}-item-{{ $item->id }}"></div><!--
                        --></div>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div>

<style>
    .multiItem-table{
        margin-top:15px;
        margin-bottom:0;
    }
    .multiItem-table td{
        padding-left:0;
    }
    .multiItem-table-item{
        padding-right: 45px;
        position: relative;
    }
    .multiItem-table-item-close{
        position: absolute;
        right: 0;
        top: 50%;
        margin-top: -8px;
        cursor: pointer;
    }
    .multiItem-table-item-title{
        padding-bottom:5px;
        font-size:13px;
    }
</style>

<script>
    $(function () {
        $('.multiItem-table-item-close').each(function() {
            $(this).bind('click', function() {
                $($(this).data('id')).remove();
            });
        });
        
        var multiItem{{ $name }}awaitingTimeout = false;

        $('#multiItem-{{ $name }}').autoComplete({
			resolver: 'custom',
            formatResult: function (item) {
                return {
                    value: item.id,
                    text: item.name + ' - ' + item.modality,
                };
            },
            events: {
                search: function (qry, callback) {
                    window.clearTimeout(multiItem{{ $name }}awaitingTimeout);

                    multiItem{{ $name }}awaitingTimeout = window.setTimeout(function() {
                        $.ajax(
                            '{{ (isset($config['url'])) ? $config['url'] : '' }}',
                            {
                                data: { 
                                    'qry': qry,
                                    '_token': '{{ csrf_token() }}'
                                }
                            }
                        ).done(function (res) {
                            callback(res.options)
                        });
                    }, 500);
                }
            }
        }).on('autocomplete.select', function (evt, item) {
            $('#multiItem-{{ $name }}').autoComplete('clear');

            var id = generateUniqueString();

            var date = Date.now();

            $('#multiItem-{{ $name }}-selection').append(
                '<tr id="multiItem-{{ $name }}-item-' + id + '">' +
                    '<td>' +
                        '<div class="multiItem-table-item row pb-0 mb-0"><!--' +
                            @foreach ($config['data'] as $key => $data)
                                '--><div class="col col-1-2 pt-0 mt-0">' +
                                    '{{ $data['label'] }}' +
                                    '<input ' +
                                        'class="multiItem-{{ $name }}-item"' +
                                        'type="text"' +
                                        'name="{{ $name }}[' + date + '][{{ $data['name'] }}]"' +
                                        'value="' + item.{{ $data['name'] }} + '"' +
                                    '>' +
                                '</div><!--' +
                            @endforeach
                            '--><div class="lnr lnr-cross multiItem-table-item-close"></div><!--' +
                        '--></div>' +
                    '</td>' +
                '</tr>'
            );

            $('#multiItem-{{ $name }}-item-' + id + ' .multiItem-table-item-close').bind('click', function() {
                $('#multiItem-{{ $name }}-item-' + id).remove();
            })
        });
    });

    function generateUniqueString() {
        var ts = String(new Date().getTime()),
            i = 0,
            out = '';

        for (i = 0; i < ts.length; i += 2) {
            out += Number(ts.substr(i, 2)).toString(36);
        }

        return ('prefix' + out);
    }
</script>