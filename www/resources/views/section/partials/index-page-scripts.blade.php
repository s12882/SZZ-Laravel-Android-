<script>
        $.ajax({
            url: '{!! route('section.treedata') !!}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function() {
               $('#info').html('<p>An error has occurred</p>');
            },
            success: responseSuccess
        });

        var _makeTree = function(options) {
            var children, e, id, o, pid, temp, _i, _len, _ref;
            id = options.id || "id";
            pid = options.parent_id || "parent_id";
            children = options.children || "children";
            temp = {};
            o = [];
            _ref = options.q;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                e = _ref[_i];
                e[children] = [];
                temp[e[id]] = e;
                if (temp[e[pid]] != null)
                    temp[e[pid]][children].push(e);
                else 
                    o.push(e);
            }
            return o;
        };



        var nodeTemplate = function(data) {
            return `
                <div class="title">${data.name}</div>
                <div class="content">
                    <div style="margin-top:6px">
                        ${data.actions}
                    </div>
                </div>
            `;
          };
          
        function responseSuccess(data){
            var tree = _makeTree({q: data['data']})[0];
            oc = $('#chart-container').orgchart({
                'data' : tree,    
                'nodeTemplate': nodeTemplate,
                'pan': true,
                'zoom': true,
                'zoomoutLimit': 0.5
            }).$chartContainer.on('touchmove', function(event) {
                event.preventDefault();
              });
            centerChart();
            $('.orgchart').css('background', '#fff');
            $('.orgchart .node .content').css('height', '35px');
            $('.btnRecenter').on('click', centerChart);
        }

        function centerChart(){
                var chart = $('.orgchart');
                var posX = chart.width() > chart.parent('div').width() ? -(chart.width() / 2 - chart.parent('div').width() /2) : '0';
                chart.css('transform', 'matrix(1, 0, 0, 1, ' + posX + ', 0)');
        }
        
</script>